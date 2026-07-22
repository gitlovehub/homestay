<?php

namespace App\Http\Controllers;

use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Homestay::query()
            ->with('category')
            ->where('status', true);

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('location')) {
            $location = $request->location;

            $query->where(function ($subQuery) use ($location) {
                $subQuery
                    ->where('city', 'like', '%' . $location . '%')
                    ->orWhere('address', 'like', '%' . $location . '%');
            });
        }

        $homestays = $query
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('home.index', compact('homestays'));
    }
}
