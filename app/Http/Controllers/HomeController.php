<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Homestay::with('category');

        // Tìm theo tên Homestay
        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));

            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($request->filled('location')) {
            $location = trim($request->input('location'));

            $query->where(function ($q) use ($location) {
                $q->where('address', 'like', "%{$location}%")
                    ->orWhere('city', 'like', "%{$location}%");
            });
        }

        // Lọc theo khoảng giá
        $priceRange = $request->input('price_range');

        switch ($priceRange) {
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
        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->integer('category_id')
            );
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
            ->latest()
            ->paginate(6);
       
        $categories = Category::orderBy('name')->get();

        return view('home.index', compact('homestays', 'categories'));
    }
}
