<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyContactMessageRequest extends FormRequest
{
    /**
     * Chỉ tài khoản Admin mới được phản hồi.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Quy tắc kiểm tra dữ liệu phản hồi.
     */
    public function rules(): array
    {
        return [
            'reply_subject' => [
                'required',
                'string',
                'max:255',
            ],

            'reply_message' => [
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
            'reply_subject.required' => 'Vui lòng nhập tiêu đề phản hồi.',
            'reply_subject.string' => 'Tiêu đề phản hồi không hợp lệ.',
            'reply_subject.max' => 'Tiêu đề phản hồi không được vượt quá 255 ký tự.',

            'reply_message.required' => 'Vui lòng nhập nội dung phản hồi.',
            'reply_message.string' => 'Nội dung phản hồi không hợp lệ.',
            'reply_message.min' => 'Nội dung phản hồi phải có ít nhất 10 ký tự.',
            'reply_message.max' => 'Nội dung phản hồi không được vượt quá 5000 ký tự.',
        ];
    }
}