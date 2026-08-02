<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyContactMessageRequest extends FormRequest
{
    /**
     * Chỉ admin mới gọi được route này vì route đã có middleware admin.
     */
    public function authorize(): bool
    {
        return true;
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
                'max:5000',
            ],
        ];
    }

    /**
     * Thông báo lỗi tiếng Việt.
     */
    public function messages(): array
    {
        return [
            'reply_subject.required' => 'Vui lòng nhập tiêu đề phản hồi.',
            'reply_subject.string' => 'Tiêu đề phản hồi không hợp lệ.',
            'reply_subject.max' => 'Tiêu đề phản hồi không được vượt quá 255 ký tự.',

            'reply_message.required' => 'Vui lòng nhập nội dung phản hồi.',
            'reply_message.string' => 'Nội dung phản hồi không hợp lệ.',
            'reply_message.max' => 'Nội dung phản hồi không được vượt quá 5000 ký tự.',
        ];
    }

    /**
     * Tên hiển thị của các trường.
     */
    public function attributes(): array
    {
        return [
            'reply_subject' => 'tiêu đề phản hồi',
            'reply_message' => 'nội dung phản hồi',
        ];
    }

    /**
     * Chuẩn hóa dữ liệu trước khi validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'reply_subject' => is_string($this->reply_subject)
                ? trim($this->reply_subject)
                : $this->reply_subject,

            'reply_message' => is_string($this->reply_message)
                ? trim($this->reply_message)
                : $this->reply_message,
        ]);
    }
}