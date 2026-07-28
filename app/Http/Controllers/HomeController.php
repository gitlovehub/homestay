<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Category;
use App\Models\Homestay;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ và xử lý tìm kiếm, lọc Homestay.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Truy vấn Homestay
        |--------------------------------------------------------------------------
        | Chỉ hiển thị Homestay đang hoạt động và tải sẵn các quan hệ cần dùng.
        */

        $query = Homestay::query()
            ->with([
                'category',
                'amenities',
                'rooms' => function ($roomQuery) {
                    $roomQuery->where('status', true);
                },
            ])
            ->where('status', true);

        /*
        |--------------------------------------------------------------------------
        | Tìm theo tên Homestay
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->input('keyword'));

            $query->where('name', 'like', '%' . $keyword . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | Tìm theo địa điểm
        |--------------------------------------------------------------------------
        | Tìm trong cả địa chỉ và thành phố.
        */

        if ($request->filled('location')) {
            $location = trim((string) $request->input('location'));

            $query->where(function (Builder $locationQuery) use ($location) {
                $locationQuery
                    ->where('address', 'like', '%' . $location . '%')
                    ->orWhere('city', 'like', '%' . $location . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Lọc theo khoảng giá
        |--------------------------------------------------------------------------
        | Sử dụng cột base_price trong bảng homestays.
        */

        switch ($request->input('price_range')) {
            case 'under_500':
                $query->where('base_price', '<', 500000);
                break;

            case '500_1000':
                $query->whereBetween('base_price', [500000, 1000000]);
                break;

            case '1000_2000':
                $query->whereBetween('base_price', [1000000, 2000000]);
                break;

            case 'over_2000':
                $query->where('base_price', '>', 2000000);
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Lọc theo loại phòng
        |--------------------------------------------------------------------------
        | Chỉ kiểm tra những phòng đang hoạt động.
        */

        if ($request->filled('room_type')) {
            $roomType = trim((string) $request->input('room_type'));

            $query->whereHas('rooms', function (Builder $roomQuery) use ($roomType) {
                $roomQuery
                    ->where('room_type', $roomType)
                    ->where('status', true);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Lọc theo số người
        |--------------------------------------------------------------------------
        | Homestay phải có ít nhất một phòng đang hoạt động đủ sức chứa.
        */

        if ($request->filled('guests')) {
            $guests = max(1, $request->integer('guests'));

            $query->whereHas('rooms', function (Builder $roomQuery) use ($guests) {
                $roomQuery
                    ->where('capacity', '>=', $guests)
                    ->where('status', true);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Lọc theo nhiều tiện ích
        |--------------------------------------------------------------------------
        | Homestay phải có đầy đủ tất cả tiện ích người dùng đã chọn.
        */

        $amenityIds = collect($request->input('amenities', []))
            ->map(fn ($amenityId) => (int) $amenityId)
            ->filter(fn ($amenityId) => $amenityId > 0)
            ->unique()
            ->values();

        foreach ($amenityIds as $amenityId) {
            $query->whereHas('amenities', function (Builder $amenityQuery) use ($amenityId) {
                $amenityQuery
                    ->where('amenities.id', $amenityId)
                    ->where('amenities.status', true);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Sắp xếp
        |--------------------------------------------------------------------------
        */

        switch ($request->input('sort_price')) {
            case 'asc':
                $query->orderBy('base_price', 'asc');
                break;

            case 'desc':
                $query->orderBy('base_price', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Phân trang
        |--------------------------------------------------------------------------
        | withQueryString() giúp giữ lại điều kiện lọc khi chuyển trang.
        */

        $homestays = $query
            ->paginate(6)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Dữ liệu cho giao diện bộ lọc
        |--------------------------------------------------------------------------
        */

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $roomTypes = Room::query()
            ->where('status', true)
            ->whereNotNull('room_type')
            ->where('room_type', '!=', '')
            ->select('room_type')
            ->distinct()
            ->orderBy('room_type')
            ->pluck('room_type');

        $amenities = Amenity::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('home.index', compact(
            'homestays',
            'categories',
            'roomTypes',
            'amenities'
        ));
    }
}