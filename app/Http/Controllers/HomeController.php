<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Homestay;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Homestay::query()
            ->with(['category', 'rooms'])
            ->where('status', true);

        // Tìm theo tên Homestay
        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));

            $query->where('name', 'like', "%{$keyword}%");
        }

        // Tìm theo địa chỉ hoặc thành phố
        if ($request->filled('location')) {
            $location = trim($request->input('location'));

            $query->where(function ($locationQuery) use ($location) {
                $locationQuery
                    ->where('address', 'like', "%{$location}%")
                    ->orWhere('city', 'like', "%{$location}%");
            });
        }

        // Lọc theo khoảng giá Homestay
        switch ($request->input('price_range')) {
            case 'under_500':
                $query->where('price', '<', 500000);
                break;

            case '500_1000':
                $query->whereBetween('price', [500000, 1000000]);
                break;

            case '1000_2000':
                $query->whereBetween('price', [1000000, 2000000]);
                break;

            case 'over_2000':
                $query->where('price', '>', 2000000);
                break;
        }

        // Lọc theo loại phòng
        if ($request->filled('room_type')) {
            $roomType = $request->input('room_type');

            $query->whereHas('rooms', function ($roomQuery) use ($roomType) {
                $roomQuery
                    ->where('room_type', $roomType)
                    ->where('status', true);
            });
        }

        // Sắp xếp theo giá
        $sortPrice = $request->input('sort_price');

        if ($sortPrice === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sortPrice === 'desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $homestays = $query
            ->paginate(6)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $roomTypes = Room::query()
            ->where('status', true)
            ->select('room_type')
            ->distinct()
            ->orderBy('room_type')
            ->pluck('room_type');

        return view('home.index', compact(
            'homestays',
            'categories',
            'roomTypes'
        ));
    }
}