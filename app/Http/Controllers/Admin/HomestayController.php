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
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');
        $city = trim((string) $request->input('city'));
        $sort = $request->input('sort');

        $allowedStatuses = ['active', 'inactive'];
        $allowedSorts = ['price_desc', 'price_asc', 'name_asc', 'name_desc', 'oldest'];

        if (! in_array($status, $allowedStatuses, true)) {
            $status = null;
        }

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = null;
        }

        $statistics = [
            'total' => Homestay::count(),
            'active' => Homestay::where('status', true)->count(),
            'inactive' => Homestay::where('status', false)->count(),
            'cities' => Homestay::query()
                ->whereNotNull('city')
                ->where('city', '!=', '')
                ->distinct()
                ->count('city'),
        ];

        $cities = Homestay::query()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $homestaysQuery = Homestay::query()
            ->with([
                'category',
                'owner',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
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
            ->when($status === 'active', function ($query) {
                $query->where('status', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('status', false);
            })
            ->when($city !== '', function ($query) use ($city) {
                $query->where('city', $city);
            });

        switch ($sort) {
            case 'price_desc':
                $homestaysQuery
                    ->orderByDesc('homestays.base_price')
                    ->orderByDesc('homestays.id');
                break;

            case 'price_asc':
                $homestaysQuery
                    ->orderBy('homestays.base_price')
                    ->orderByDesc('homestays.id');
                break;

            case 'name_asc':
                $homestaysQuery
                    ->orderBy('homestays.name')
                    ->orderBy('homestays.id');
                break;

            case 'name_desc':
                $homestaysQuery
                    ->orderByDesc('homestays.name')
                    ->orderByDesc('homestays.id');
                break;

            case 'oldest':
                $homestaysQuery
                    ->orderBy('homestays.created_at')
                    ->orderBy('homestays.id');
                break;

            default:
                $homestaysQuery
                    ->orderByDesc('homestays.created_at')
                    ->orderByDesc('homestays.id');
                break;
        }

        $homestays = $homestaysQuery
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.homestays.index',
            compact('homestays', 'statistics', 'cities')
        );
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

        if ($request->boolean('remove_thumbnail')) {
            if (
                $homestay->thumbnail &&
                Storage::disk('public')->exists($homestay->thumbnail)
            ) {
                Storage::disk('public')->delete($homestay->thumbnail);
            }

            $data['thumbnail'] = null;
        } elseif ($request->hasFile('thumbnail')) {
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
        if (
            $homestay->thumbnail &&
            Storage::disk('public')->exists($homestay->thumbnail)
        ) {
            Storage::disk('public')->delete($homestay->thumbnail);
        }

        $homestay->amenities()->detach();

        $homestay->delete();

        return redirect()
            ->route('admin.homestays.index')
            ->with('success', 'Xóa Homestay thành công.');
    }
}