<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Hiển thị trang liên hệ.
     */
    public function index(): View
    {
        return view('contact.index');
    }

    /**
     * Lưu yêu cầu hỗ trợ của người dùng đã đăng nhập.
     */
    public function store(
        StoreContactMessageRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $user = $request->user();

        ContactMessage::create([
            'user_id' => $user->id,

            // Lấy từ tài khoản đăng nhập,
            // không lấy tên và email từ form.
            'name' => $user->name,
            'email' => $user->email,

            'phone' => $validated['phone'] ?? $user->phone,
            'subject' => $validated['subject'],
            'message' => $validated['message'],

            'status' => 'unread',
            'read_at' => null,
            'replied_at' => null,
        ]);

        return redirect()
            ->route('contact')
            ->with(
                'success',
                'Yêu cầu hỗ trợ đã được gửi thành công.'
            );
    }
}