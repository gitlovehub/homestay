<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHomestayRequest;
use App\Models\Category;
use App\Models\Homestay;
use App\Models\User;
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
        //
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
    public function update(Request $request, Homestay $homestay)
    {
        //
    }

    /**
     * Xóa Homestay.
     */
    public function destroy(Homestay $homestay)
    {
        //
    }

    /**
     * Tạo slug duy nhất.
     */
    private function createUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'homestay';
        }

        $slug = $baseSlug;
        $number = 1;

        while (Homestay::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $number;
            $number++;
        }

        return $slug;
    }
}