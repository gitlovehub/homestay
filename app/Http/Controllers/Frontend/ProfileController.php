<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        $user->loadCount([
            'bookings',
            'reviews',

            'bookings as completed_bookings_count' => function ($query) {
                $query->where('status', 'completed');
            },

            'bookings as active_bookings_count' => function ($query) {
                $query->whereIn('status', [
                    'pending',
                    'confirmed',
                    'checked_in',
                ]);
            },
        ]);

        $latestBooking = $user->bookings()
            ->with([
                'room.homestay',
            ])
            ->latest()
            ->first();

        return view('profile.edit', [
            'user' => $user,
            'latestBooking' => $latestBooking,
        ]);
    }

    public function update(
        ProfileUpdateRequest $request
    ): RedirectResponse {
        $user = $request->user();
        $validated = $request->validated();

        if (
            $request->boolean('remove_avatar')
            && $user->avatar
        ) {
            Storage::disk('public')->delete(
                $user->avatar
            );

            $validated['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete(
                    $user->avatar
                );
            }

            $validated['avatar'] = $request
                ->file('avatar')
                ->store('avatars', 'public');
        }

        unset($validated['remove_avatar']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with(
                'success',
                'Cập nhật thông tin tài khoản thành công.'
            );
    }

    public function destroy(
        Request $request
    ): RedirectResponse {
        $request->validateWithBag(
            'userDeletion',
            [
                'password' => [
                    'required',
                    'current_password',
                ],
            ],
            [
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.current_password' => 'Mật khẩu không chính xác.',
            ]
        );

        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete(
                $user->avatar
            );
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('home')
            ->with(
                'success',
                'Tài khoản của bạn đã được xóa.'
            );
    }
}
