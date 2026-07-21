<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\View\View;

class HomestayController extends Controller
{
    /**
     * Hiển thị chi tiết Homestay ở phía khách hàng.
     */
    public function show(string $slug): View
    {
        $homestay = Homestay::query()
            ->with([
                'category',
                'owner',
                'amenities',
            ])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        return view(
            'homestays.show',
            compact('homestay')
        );
    }
}