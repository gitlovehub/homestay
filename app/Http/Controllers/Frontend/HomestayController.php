<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\View\View;
use App\Models\User;

class HomestayController extends Controller
{
    public function show(string $slug): View
    {
        $homestay = Homestay::query()
            ->with([
                'category',
                'owner',
                'amenities',
                'images',
                'rooms' => function ($query) {
                    $query
                        ->where('status', 'available')
                        ->orderBy('price_per_night');
                },
            ])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $totalUsers = User::count();

        return view('homestays.show', compact('homestay', 'totalUsers'));
    }
}
