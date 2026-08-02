<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Hiển thị trang liên hệ.
     */
    public function index(): View
    {
        return view('contact');
    }

    /**
     * Lưu thư liên hệ.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                ],

                'phone' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'subject' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'message' => [
                    'required',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'name.required' => 'Vui lòng nhập họ và tên.',
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không đúng định dạng.',
                'subject.required' => 'Vui lòng nhập tiêu đề.',
                'message.required' => 'Vui lòng nhập nội dung liên hệ.',
            ]
        );

        $validated['status'] = 'unread';

        ContactMessage::create($validated);

        return redirect()
            ->route('contact')
            ->with(
                'success',
                'Gửi liên hệ thành công. Chúng tôi sẽ phản hồi sớm nhất.'
            );
    }
}