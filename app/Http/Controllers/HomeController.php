<?php

namespace App\Http\Controllers;

use App\Models\Homestay;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Lấy toàn bộ thành phố đang có Homestay hoạt động
        |--------------------------------------------------------------------------
        */

        $locations = Homestay::query()
            ->where('homestays.status', true)
            ->whereNotNull('homestays.city')
            ->where('homestays.city', '!=', '')
            ->select('homestays.city')
            ->distinct()
            ->orderBy('homestays.city')
            ->pluck('homestays.city');

        /*
        |--------------------------------------------------------------------------
        | Truy vấn danh sách Homestay
        |--------------------------------------------------------------------------
        */

        $query = Homestay::query()
            ->with([
                'category',
            ])
            ->where('homestays.status', true)

            // Đếm số lượt đặt phòng hợp lệ
            ->withCount([
                'bookings as bookings_count' => function (Builder $query) {
                    $query->whereIn('bookings.status', [
                        'confirmed',
                        'checked_in',
                        'completed',
                    ]);
                },

                // Đếm số đánh giá đã duyệt
                'reviews as approved_reviews_count' => function (Builder $query) {
                    $query->where('reviews.status', 'approved');
                },
            ])

            // Tính điểm trung bình từ đánh giá đã duyệt
            ->withAvg([
                'reviews as average_rating' => function (Builder $query) {
                    $query->where('reviews.status', 'approved');
                },
            ], 'rating');

        /*
        |--------------------------------------------------------------------------
        | Lọc theo thành phố
        |--------------------------------------------------------------------------
        */

        if ($request->filled('location')) {
            $location = trim((string) $request->input('location'));

            $query->where('homestays.city', $location);
        }

        /*
        |--------------------------------------------------------------------------
        | Sắp xếp và phân trang
        |--------------------------------------------------------------------------
        */

        $homestays = $query
            ->orderByDesc('bookings_count')
            ->orderByDesc('average_rating')
            ->orderByDesc('approved_reviews_count')
            ->orderByDesc('homestays.id')
            ->paginate(6)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Thống kê đầu trang
        |--------------------------------------------------------------------------
        */

        $totalHomestays = Homestay::query()
            ->where('homestays.status', true)
            ->count();

        // Đếm số thành phố từ chính danh sách đã lấy
        $totalLocations = $locations->count();

        $averageRating = Review::query()
            ->where('reviews.status', 'approved')
            ->whereHas('homestay', function (Builder $query) {
                $query->where('homestays.status', true);
            })
            ->avg('reviews.rating');

        $averageRating = round($averageRating ?? 0, 1);

        return view('home.index', compact(
            'homestays',
            'locations',
            'totalHomestays',
            'totalLocations',
            'averageRating'
        ));
    }
}