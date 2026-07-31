<?php

namespace App\Http\Controllers;

use App\Models\Homestay;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Truy vấn Homestay hoạt động dùng chung
        |--------------------------------------------------------------------------
        */

        $activeHomestaysQuery = Homestay::query()
            ->where(
                'homestays.status',
                true
            );

        /*
        |--------------------------------------------------------------------------
        | Thành phố không trùng lặp
        |--------------------------------------------------------------------------
        */

        $locations = (clone $activeHomestaysQuery)
            ->whereNotNull('homestays.city')
            ->whereRaw(
                "TRIM(homestays.city) <> ''"
            )
            ->selectRaw(
                'TRIM(homestays.city) as city'
            )
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        /*
        |--------------------------------------------------------------------------
        | Homestay nổi bật
        |--------------------------------------------------------------------------
        */

        $homestays = (clone $activeHomestaysQuery)
            ->with([
                'category',
            ])
            ->withCount([
                'bookings as bookings_count' => function (
                    Builder $query
                ) {
                    $query->whereIn(
                        'bookings.status',
                        [
                            'confirmed',
                            'checked_in',
                            'completed',
                        ]
                    );
                },

                'reviews as approved_reviews_count' => function (
                    Builder $query
                ) {
                    $query->where(
                        'reviews.status',
                        'approved'
                    );
                },
            ])
            ->withAvg([
                'reviews as average_rating' => function (
                    Builder $query
                ) {
                    $query->where(
                        'reviews.status',
                        'approved'
                    );
                },
            ], 'rating')
            ->orderByDesc('bookings_count')
            ->orderByDesc('average_rating')
            ->orderByDesc(
                'approved_reviews_count'
            )
            ->orderByDesc('homestays.id')
            ->paginate(6);

        /*
        |--------------------------------------------------------------------------
        | Thống kê
        |--------------------------------------------------------------------------
        */

        $totalHomestays = (clone $activeHomestaysQuery)
            ->count();

        $totalLocations = $locations->count();

        $averageRating = Review::query()
            ->where(
                'reviews.status',
                'approved'
            )
            ->whereHas(
                'homestay',
                function (Builder $query) {
                    $query->where(
                        'homestays.status',
                        true
                    );
                }
            )
            ->avg('reviews.rating');

        $averageRating = round(
            (float) ($averageRating ?? 0),
            1
        );

        return view(
            'home.index',
            compact(
                'homestays',
                'locations',
                'totalHomestays',
                'totalLocations',
                'averageRating'
            )
        );
    }
}