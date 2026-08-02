<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactLocationRequest;
use App\Http\Requests\UpdateContactLocationRequest;
use App\Models\ContactLocation;
use Illuminate\Http\RedirectResponse;

class ContactLocationController extends Controller
{
    public function index(): View
    {
        $contactLocations = ContactLocation::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->take(5)
            ->get();

        return view(
            'contact',
            compact('contactLocations')
        );
    }
    public function store(
        StoreContactLocationRequest $request
    ): RedirectResponse {
        if (ContactLocation::count() >= 5) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Hệ thống chỉ cho phép tối đa 5 vị trí.'
                );
        }

        ContactLocation::create(
            $request->validated()
        );

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Đã thêm vị trí bản đồ thành công.');
    }

    /**
     * Cập nhật vị trí.
     */
    public function update(
        UpdateContactLocationRequest $request,
        ContactLocation $contactLocation
    ): RedirectResponse {
        $contactLocation->update(
            $request->validated()
        );

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Đã cập nhật vị trí bản đồ.');
    }

    /**
     * Xóa vị trí.
     */
    public function destroy(
        ContactLocation $contactLocation
    ): RedirectResponse {
        $contactLocation->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Đã xóa vị trí bản đồ.');
    }
}