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
            ->paginate(6);

        return view('home.index', compact('homestays'));
    }
}