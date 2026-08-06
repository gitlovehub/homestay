<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AmenityController extends Controller
{
    /**
     * Hiển thị danh sách tiện ích.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');
        $sort = $request->input('sort', 'newest');

        $allowedStatuses = ['0', '1'];
        $allowedSorts = [
            'newest',
            'oldest',
            'name_asc',
            'name_desc',
            'most_used',
            'least_used',
        ];

        if (! in_array((string) $status, $allowedStatuses, true)) {
            $status = null;
        }

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'newest';
        }

        $statistics = [
            'total' => Amenity::count(),
            'active' => Amenity::where('status', true)->count(),
            'inactive' => Amenity::where('status', false)->count(),
            'in_use' => Amenity::whereHas('homestays')->count(),
        ];

        $amenitiesQuery = Amenity::query()
            ->withCount('homestays')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('status', (int) $status);
            });

        match ($sort) {
            'oldest' => $amenitiesQuery
                ->orderBy('amenities.created_at')
                ->orderBy('amenities.id'),

            'name_asc' => $amenitiesQuery
                ->orderBy('amenities.name')
                ->orderBy('amenities.id'),

            'name_desc' => $amenitiesQuery
                ->orderByDesc('amenities.name')
                ->orderByDesc('amenities.id'),

            'most_used' => $amenitiesQuery
                ->orderByDesc('homestays_count')
                ->orderByDesc('amenities.id'),

            'least_used' => $amenitiesQuery
                ->orderBy('homestays_count')
                ->orderBy('amenities.id'),

            default => $amenitiesQuery
                ->orderByDesc('amenities.created_at')
                ->orderByDesc('amenities.id'),
        };

        $amenities = $amenitiesQuery
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.amenities.index',
            compact('amenities', 'statistics')
        );
    }

    /**
     * Hiển thị form thêm tiện ích.
     */
    public function create()
    {
        return view('admin.amenities.create');
    }

    /**
     * Lưu tiện ích mới.
     */
    public function store(StoreAmenityRequest $request)
    {
        $data = $request->validated();

        $slugSource = !empty($data['slug'])
            ? $data['slug']
            : $data['name'];

        $data['slug'] = $this->generateUniqueSlug($slugSource);

        $data['status'] = $request->boolean('status');

        Amenity::create($data);

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Thêm tiện ích thành công.');
    }

    /**
     * Hiển thị chi tiết tiện ích.
     */
    public function show(Amenity $amenity)
    {
        $amenity->loadCount('homestays');

        return view(
            'admin.amenities.show',
            compact('amenity')
        );
    }

    /**
     * Hiển thị form chỉnh sửa tiện ích.
     */
    public function edit(Amenity $amenity)
    {
        return view(
            'admin.amenities.edit',
            compact('amenity')
        );
    }

    /**
     * Cập nhật tiện ích.
     */
    public function update(
        UpdateAmenityRequest $request,
        Amenity $amenity
    ) {
        $data = $request->validated();

        $slugSource = !empty($data['slug'])
            ? $data['slug']
            : $data['name'];

        $data['slug'] = $this->generateUniqueSlug(
            $slugSource,
            $amenity->id
        );

        $data['status'] = $request->boolean('status');

        $amenity->update($data);

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Cập nhật tiện ích thành công.');
    }

    /**
     * Xóa tiện ích.
     */
    public function destroy(Amenity $amenity)
    {
        /*
         * Không cho xóa nếu tiện ích đang được
         * ít nhất một Homestay sử dụng.
         */
        if ($amenity->homestays()->exists()) {
            return redirect()
                ->route('admin.amenities.index')
                ->with(
                    'error',
                    'Không thể xóa vì tiện ích đang được Homestay sử dụng.'
                );
        }

        $amenityName = $amenity->name;

        $amenity->delete();

        return redirect()
            ->route('admin.amenities.index')
            ->with(
                'success',
                "Đã xóa tiện ích \"{$amenityName}\" thành công."
            );
    }

    /**
     * Tạo slug không bị trùng.
     */
    private function generateUniqueSlug(
        string $value,
        ?int $ignoreAmenityId = null
    ): string {
        $baseSlug = Str::slug($value);

        if ($baseSlug === '') {
            $baseSlug = 'tien-ich';
        }

        $slug = $baseSlug;
        $number = 2;

        while (
            $this->slugExists(
                $slug,
                $ignoreAmenityId
            )
        ) {
            $slug = "{$baseSlug}-{$number}";
            $number++;
        }

        return $slug;
    }

    /**
     * Kiểm tra slug đã tồn tại hay chưa.
     */
    private function slugExists(
        string $slug,
        ?int $ignoreAmenityId = null
    ): bool {
        $query = Amenity::query()
            ->where('slug', $slug);

        if ($ignoreAmenityId !== null) {
            $query->where(
                'id',
                '!=',
                $ignoreAmenityId
            );
        }

        return $query->exists();
    }
}