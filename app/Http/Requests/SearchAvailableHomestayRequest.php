<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchAvailableHomestayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'location' => trim(
                (string) $this->input('location', '')
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            'location' => [
                'nullable',
                'string',
                'max:100',
            ],

            'check_in' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],

            'check_out' => [
                'required',
                'date_format:Y-m-d',
                'after:check_in',
            ],

            /*
             * Các bộ lọc phụ của trang kết quả.
             */
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],

            'min_price' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'max_price' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'guests' => [
                'nullable',
                'integer',
                'min:1',
                'max:50',
            ],

            'room_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'rating' => [
                'nullable',
                'integer',
                'between:1,5',
            ],

            'amenities' => [
                'nullable',
                'array',
            ],

            'amenities.*' => [
                'integer',
                'exists:amenities,id',
            ],

            'sort' => [
                'nullable',
                'in:popular,bookings_desc,rating_desc,price_asc,price_desc,latest',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'location.string' => 'Địa điểm không hợp lệ.',
            'location.max' => 'Tên địa điểm không được vượt quá 100 ký tự.',

            'check_in.required' => 'Vui lòng chọn ngày nhận phòng.',
            'check_in.date_format' => 'Ngày nhận phòng không hợp lệ.',
            'check_in.after_or_equal' => 'Ngày nhận phòng không được trước ngày hôm nay.',

            'check_out.required' => 'Vui lòng chọn ngày trả phòng.',
            'check_out.date_format' => 'Ngày trả phòng không hợp lệ.',
            'check_out.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',

            'min_price.integer' => 'Giá tối thiểu không hợp lệ.',
            'min_price.min' => 'Giá tối thiểu không được nhỏ hơn 0.',

            'max_price.integer' => 'Giá tối đa không hợp lệ.',
            'max_price.min' => 'Giá tối đa không được nhỏ hơn 0.',

            'guests.integer' => 'Số khách không hợp lệ.',
            'guests.min' => 'Số khách phải từ 1 người.',
            'guests.max' => 'Số khách không được vượt quá 50 người.',

            'rating.between' => 'Mức đánh giá phải từ 1 đến 5 sao.',

            'amenities.array' => 'Danh sách tiện ích không hợp lệ.',
            'amenities.*.exists' => 'Tiện ích được chọn không tồn tại.',

            'sort.in' => 'Kiểu sắp xếp không hợp lệ.',
        ];
    }
}