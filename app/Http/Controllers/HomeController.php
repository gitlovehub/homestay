<?php

namespace App\Http\Controllers;

use App\Models\Homestay;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $homestays = Homestay::query()
            ->with('category')
            ->where('status', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home.index', compact('homestays'));
    }
}