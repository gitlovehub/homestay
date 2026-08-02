<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactMessageRequest extends FormRequest
{
    /**
     * Chỉ người dùng đã đăng nhập mới được gửi liên hệ.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Quy tắc kiểm tra dữ liệu.
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'nullable',
                'string',
                'max:11',
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
        ];
    }

    public function messages(): array
    {
        return [
            'phone.string' => 'Số điện thoại không hợp lệ.',
            'phone.max' => 'Số điện thoại không được vượt quá 11 ký tự.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',

            'subject.required' => 'Vui lòng chọn chủ đề cần hỗ trợ.',
            'subject.string' => 'Chủ đề cần hỗ trợ không hợp lệ.',
            'subject.in' => 'Chủ đề cần hỗ trợ không hợp lệ.',

            'message.required' => 'Vui lòng nhập nội dung liên hệ.',
            'message.string' => 'Nội dung liên hệ không hợp lệ.',
            'message.min' => 'Nội dung liên hệ phải có ít nhất 10 ký tự.',
            'message.max' => 'Nội dung liên hệ không được vượt quá 5000 ký tự.',
        ];
    }
}