<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'in:pending,confirmed,checked_in,completed,cancelled',
            ],
            'cancellation_reason' => [
                'nullable',
                'required_if:status,cancelled',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Vui lòng chọn trạng thái Booking.',
            'status.in' => 'Trạng thái Booking không hợp lệ.',

            'cancellation_reason.required_if' =>
                'Vui lòng nhập lý do hủy Booking.',

            'cancellation_reason.max' =>
                'Lý do hủy không được vượt quá 2000 ký tự.',
        ];
    }
}