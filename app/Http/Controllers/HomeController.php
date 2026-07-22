<?php

namespace App\Http\Controllers;

use App\Models\Homestay;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $roomType = $request->input('room_type');
        $priceRange = $request->input('price_range');
        $sortPrice = $request->input('sort_price');

        $query = Homestay::query()
            ->with('category')
            ->where('status', true)

            // Chỉ lấy Homestay có phòng đang hoạt động
            // và thỏa mãn loại phòng + khoảng giá đang chọn
            ->whereHas('rooms', function (Builder $roomQuery) use (
                $roomType,
                $priceRange
            ) {
                $roomQuery->where('status', true);

                // Lọc theo loại phòng
                if (!empty($roomType)) {
                    $roomQuery->where('room_type', $roomType);
                }

                // Lọc theo giá phòng
                switch ($priceRange) {
                    case 'under_500':
                        $roomQuery->where('price', '<', 500000);
                        break;

                    case '500_1000':
                        $roomQuery->whereBetween('price', [
                            500000,
                            1000000,
                        ]);
                        break;

                    case '1000_2000':
                        $roomQuery->whereBetween('price', [
                            1000000,
                            2000000,
                        ]);
                        break;

                    case 'over_2000':
                        $roomQuery->where('price', '>', 2000000);
                        break;
                }
            })

            // Lấy giá thấp nhất trong đúng nhóm phòng đang lọc
            ->withMin([
                'rooms as minimum_room_price' => function (
                    Builder $roomQuery
                ) use ($roomType, $priceRange) {
                    $roomQuery->where('status', true);

                    if (!empty($roomType)) {
                        $roomQuery->where('room_type', $roomType);
                    }

                    switch ($priceRange) {
                        case 'under_500':
                            $roomQuery->where('price', '<', 500000);
                            break;

                        case '500_1000':
                            $roomQuery->whereBetween('price', [
                                500000,
                                1000000,
                            ]);
                            break;

                        case '1000_2000':
                            $roomQuery->whereBetween('price', [
                                1000000,
                                2000000,
                            ]);
                            break;

                        case 'over_2000':
                            $roomQuery->where('price', '>', 2000000);
                            break;
                    }
                },
            ], 'price');

        // Tìm theo tên Homestay
        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));

            $query->where('name', 'like', "%{$keyword}%");
        }

        // Tìm theo địa chỉ hoặc thành phố
        if ($request->filled('location')) {
            $location = trim($request->input('location'));

            $query->where(function (Builder $locationQuery) use ($location) {
                $locationQuery
                    ->where('address', 'like', "%{$location}%")
                    ->orWhere('city', 'like', "%{$location}%");
            });
        }

        // Sắp xếp theo giá phòng đang lọc
        if ($sortPrice === 'asc') {
            $query->orderBy('minimum_room_price', 'asc');
        } elseif ($sortPrice === 'desc') {
            $query->orderBy('minimum_room_price', 'desc');
        } else {
            $query->latest();
        }

        $homestays = $query
            ->paginate(6)
            ->withQueryString();

        // Lấy danh sách loại phòng để hiển thị trong modal
        $roomTypes = Room::query()
            ->where('status', true)
            ->whereNotNull('room_type')
            ->where('room_type', '!=', '')
            ->select('room_type')
            ->distinct()
            ->orderBy('room_type')
            ->pluck('room_type');

        return view('home.index', compact(
            'homestays',
            'roomTypes'
        ));
    }
}