<?php

namespace App\Http\Controllers;

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

        // Lọc theo loại phòng
        if ($request->filled('room_type')) {
            $roomType = $request->input('room_type');

            $query->whereHas('rooms', function ($roomQuery) use ($roomType) {
                $roomQuery
                    ->where('room_type', $roomType)
                    ->where('status', true);
            });
        }

        $homestays = $query
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $roomTypes = Room::query()
            ->where('status', true)
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