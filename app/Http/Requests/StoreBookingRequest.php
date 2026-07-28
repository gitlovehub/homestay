<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'exists:rooms,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => [
                'required',
                'regex:/^(0|\+84)[0-9]{9,10}$/',
            ],
            'check_in' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'check_out' => [
                'required',
                'date',
                'after:check_in',
            ],
            'number_of_guests' => [
                'required',
                'integer',
                'min:1',
            ],
            'note' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Vui lòng chọn phòng.',
            'room_id.exists' => 'Phòng không tồn tại.',

            'customer_name.required' => 'Vui lòng nhập họ tên.',
            'customer_name.max' => 'Họ tên không được vượt quá 255 ký tự.',

            'customer_email.required' => 'Vui lòng nhập email.',
            'customer_email.email' => 'Email không đúng định dạng.',

            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
            'customer_phone.regex' => 'Số điện thoại không hợp lệ.',

            'check_in.required' => 'Vui lòng chọn ngày nhận phòng.',
            'check_in.date' => 'Ngày nhận phòng không hợp lệ.',
            'check_in.after_or_equal' => 'Ngày nhận phòng không được nhỏ hơn ngày hiện tại.',

            'check_out.required' => 'Vui lòng chọn ngày trả phòng.',
            'check_out.date' => 'Ngày trả phòng không hợp lệ.',
            'check_out.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',

            'number_of_guests.required' => 'Vui lòng nhập số lượng khách.',
            'number_of_guests.integer' => 'Số lượng khách phải là số nguyên.',
            'number_of_guests.min' => 'Số lượng khách phải ít nhất là 1.',

            'note.max' => 'Ghi chú không được vượt quá 2000 ký tự.',
        ];
    }
}