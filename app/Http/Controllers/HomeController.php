<?php

namespace App\Http\Controllers;

use App\Models\Homestay;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $homestays = Homestay::query()
            ->with([
                'category',
            ])
            ->where('homestays.status', true)
            ->withCount([
                'bookings as bookings_count' => function ($query) {
                    $query->whereIn('bookings.status', [
                        'confirmed',
                        'checked_in',
                        'completed',
                    ]);
                },

                'reviews as approved_reviews_count' => function ($query) {
                    $query->where(
                        'reviews.status',
                        'approved'
                    );
                },
            ])
            ->withAvg([
                'reviews as average_rating' => function ($query) {
                    $query->where(
                        'reviews.status',
                        'approved'
                    );
                },
            ], 'rating')
            ->orderByDesc('bookings_count')
            ->orderByDesc('average_rating')
            ->orderByDesc('approved_reviews_count')
            ->orderByDesc('homestays.id')
            ->paginate(6)
            ->withQueryString();

        return view('home.index', compact('homestays'));
    }
}