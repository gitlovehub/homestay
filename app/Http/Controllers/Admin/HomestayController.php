<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHomestayRequest;
use App\Http\Requests\UpdateHomestayRequest;
use App\Models\Category;
use App\Models\Homestay;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomestayController extends Controller
{
    /**
     * Hiển thị danh sách Homestay.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $homestays = Homestay::with([
            'category',
            'owner',
            'amenities',
        ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('owner', function ($ownerQuery) use ($search) {
                            $ownerQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.homestays.index', compact(
            'homestays',
            'search'
        ));
    }

    /**
     * Hiển thị form thêm Homestay.
     */
    public function create()
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $owners = User::query()
            ->orderBy('name')
            ->get();

        return view('admin.homestays.create', compact(
            'categories',
            'owners'
        ));
    }

    /**
     * Lưu Homestay mới.
     */
    public function store(StoreHomestayRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = $this->createUniqueSlug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = $request
                ->file('image')
                ->store('homestays', 'public');
        }

        Homestay::create($data);

        return redirect()
            ->route('admin.homestays.index')
            ->with('success', 'Thêm Homestay thành công.');
    }

    /**
     * Hiển thị chi tiết Homestay.
     */
    public function show(Homestay $homestay)
    {
        $homestay->load([
            'category',
            'owner',
            'amenities',
        ]);

        return view(
            'admin.homestays.show',
            compact('homestay')
        );
    }

    /**
     * Hiển thị form sửa Homestay.
     */
    public function edit(Homestay $homestay)
    {
        $categories = Category::orderBy('name')->get();

        $owners = User::orderBy('name')->get();

        return view(
            'admin.homestays.edit',
            compact(
                'homestay',
                'categories',
                'owners'
            )
        );
    }

    /**
     * Cập nhật Homestay.
     */
    public function update(UpdateHomestayRequest $request, Homestay $homestay)
    {
        $data = $request->validated();

        // Cập nhật slug nếu đổi tên
        if ($data['name'] !== $homestay->name) {
            $data['slug'] = $this->createUniqueSlug($data['name']);
        }

        // Nếu người dùng bấm xóa ảnh hiện tại
        if ($request->boolean('remove_image')) {
            if (
                $homestay->image &&
                Storage::disk('public')->exists($homestay->image)
            ) {
                Storage::disk('public')->delete($homestay->image);
            }

            $data['image'] = null;
        }

        // Nếu người dùng chọn ảnh mới
        if ($request->hasFile('image')) {
            if (
                $homestay->image &&
                Storage::disk('public')->exists($homestay->image)
            ) {
                Storage::disk('public')->delete($homestay->image);
            }

            $data['image'] = $request
                ->file('image')
                ->store('homestays', 'public');
        }

        $homestay->update($data);

        return redirect()
            ->route('admin.homestays.index')
            ->with('success', 'Cập nhật Homestay thành công.');
    }

    /**
     * Xóa Homestay.
     */
    public function destroy(Homestay $homestay)
    {
        // Xóa ảnh của Homestay nếu có
        if (
            $homestay->image &&
            Storage::disk('public')->exists($homestay->image)
        ) {
            Storage::disk('public')->delete($homestay->image);
        }

        // Xóa Homestay khỏi database
        $homestay->delete();

        return redirect()
            ->route('admin.homestays.index')
            ->with('success', 'Xóa Homestay thành công.');
    }

    /**
     * Tạo slug duy nhất.
     */
    private function createUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (
            Homestay::query()
                ->when(
                    $ignoreId,
                    function ($query) use ($ignoreId) {
                        $query->where('id', '!=', $ignoreId);
                    }
                )
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}