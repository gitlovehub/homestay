<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
     * Lưu yêu cầu hỗ trợ của người dùng đã đăng nhập.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'phone' => [
                    'nullable',
                    'string',
                    'max:20',
                    'regex:/^(0|\+84)[0-9]{9}$/',
                ],

                'subject' => [
                    'required',
                    'string',
                    Rule::in([
                        'Hỗ trợ đặt phòng',
                        'Hỗ trợ tài khoản',
                        'Thanh toán và hoàn tiền',
                        'Khiếu nại dịch vụ',
                        'Góp ý cho HomeStayGo',
                        'Vấn đề khác',
                    ]),
                ],

                'message' => [
                    'required',
                    'string',
                    'min:10',
                    'max:5000',
                ],
            ],
            [
                'phone.regex' => 'Số điện thoại không đúng định dạng.',

                'subject.required' => 'Vui lòng chọn chủ đề cần hỗ trợ.',
                'subject.in' => 'Chủ đề cần hỗ trợ không hợp lệ.',

                'message.required' => 'Vui lòng nhập nội dung liên hệ.',
                'message.min' => 'Nội dung liên hệ phải có ít nhất 10 ký tự.',
                'message.max' => 'Nội dung liên hệ không được vượt quá 5000 ký tự.',
            ]
        );

        $user = $request->user();

        ContactMessage::create([
            'user_id' => $user->id,

            // Luôn lấy từ tài khoản đăng nhập,
            // không lấy name và email do trình duyệt gửi lên.
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