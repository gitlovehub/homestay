<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHomestayRequest;
use App\Http\Requests\UpdateHomestayRequest;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\Homestay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomestayController extends Controller
{
    /**
     * Hiển thị danh sách Homestay.
     */
    public function index(Request $request)
    {
        $homestays = Homestay::query()
            ->with([
                'category',
                'owner',
            ])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.homestays.index', compact('homestays'));
    }

    public function create()
    {
        $categories = Category::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $owners = User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $amenities = Amenity::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.homestays.create',
            compact('categories', 'owners', 'amenities')
        );
    }

    public function store(StoreHomestayRequest $request)
    {
        $data = $request->validated();

        $amenities = $data['amenities'] ?? [];

        unset($data['amenities']);

        $baseSlug = !empty($data['slug'])
            ? Str::slug($data['slug'])
            : Str::slug($data['name']);

        $slug = $baseSlug;
        $number = 1;

        while (Homestay::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $number;
            $number++;
        }

        $data['slug'] = $slug;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request
                ->file('thumbnail')
                ->store('homestays/thumbnails', 'public');
        }

        $homestay = Homestay::create($data);

        $homestay->amenities()->sync($amenities);

        return redirect()
            ->route('admin.homestays.index')
            ->with('success', 'Thêm Homestay thành công.');
    }

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

    public function edit(Homestay $homestay)
    {
        $categories = Category::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $owners = User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $amenities = Amenity::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $homestay->load('amenities');

        return view(
            'admin.homestays.edit',
            compact(
                'homestay',
                'categories',
                'owners',
                'amenities'
            )
        );
    }

    public function update(
        UpdateHomestayRequest $request,
        Homestay $homestay
    ) {
        $data = $request->validated();

        $amenities = $data['amenities'] ?? [];
        unset($data['amenities']);

        $data['slug'] = !empty($data['slug'])
            ? Str::slug($data['slug'])
            : Str::slug($data['name']);

        if ($request->hasFile('thumbnail')) {
            if (
                $homestay->thumbnail &&
                Storage::disk('public')->exists($homestay->thumbnail)
            ) {
                Storage::disk('public')->delete($homestay->thumbnail);
            }

            $data['thumbnail'] = $request
                ->file('thumbnail')
                ->store('homestays/thumbnails', 'public');
        } else {
            unset($data['thumbnail']);
        }

        $homestay->update($data);

        $homestay->amenities()->sync($amenities);

        return redirect()
            ->route('admin.homestays.index')
            ->with('success', 'Cập nhật Homestay thành công.');
    }

    public function destroy(Homestay $homestay)
    {
        //
    }
}