<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Models\Amenity;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AmenityController extends Controller
{
    public function index(): View
    {
        $amenities = Amenity::query()
            ->when(
                request('search'),
                function ($query, $search) {
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.amenities.index',
            compact('amenities')
        );
    }

    public function create(): View
    {
        return view('admin.amenities.create');
    }

    public function store(
        StoreAmenityRequest $request
    ): RedirectResponse {
        Amenity::create($request->validated());

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Thêm tiện ích thành công.');
    }

    public function show(Amenity $amenity): View
    {
        return view(
            'admin.amenities.show',
            compact('amenity')
        );
    }

    public function edit(Amenity $amenity): View
    {
        return view(
            'admin.amenities.edit',
            compact('amenity')
        );
    }

    public function update(
        UpdateAmenityRequest $request,
        Amenity $amenity
    ): RedirectResponse {
        $amenity->update($request->validated());

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Cập nhật tiện ích thành công.');
    }

    public function destroy(
        Amenity $amenity
    ): RedirectResponse {
        if ($amenity->homestays()->exists()) {
            return redirect()
                ->route('admin.amenities.index')
                ->with(
                    'error',
                    'Không thể xóa tiện ích đang được sử dụng bởi Homestay.'
                );
        }

        $amenity->delete();

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Xóa tiện ích thành công.');
    }
}