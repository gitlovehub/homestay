<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\Http\Request;

class HomestayController extends Controller
{
    /**
     * Hiển thị danh sách Homestay.
     */
    public function index(Request $request)
    {
        $homestays = Homestay::query()
            ->with([
                'category',
                'owner',
            ])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.homestays.index', compact('homestays'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Homestay $homestay)
    {
        //
    }

    public function edit(Homestay $homestay)
    {
        //
    }

    public function update(Request $request, Homestay $homestay)
    {
        //
    }

    public function destroy(Homestay $homestay)
    {
        //
    }
}