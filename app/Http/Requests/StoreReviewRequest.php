<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'title' => [
                'nullable',
                'string',
                'max:150',
            ],

            'content' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' =>
                'Vui lòng chọn số sao đánh giá.',

            'rating.integer' =>
                'Số sao đánh giá không hợp lệ.',

            'rating.between' =>
                'Số sao phải từ 1 đến 5.',

            'title.string' =>
                'Tiêu đề đánh giá không hợp lệ.',

            'title.max' =>
                'Tiêu đề không được vượt quá 150 ký tự.',

            'content.required' =>
                'Vui lòng nhập nội dung đánh giá.',

            'content.string' =>
                'Nội dung đánh giá không hợp lệ.',

            'content.min' =>
                'Nội dung đánh giá phải có ít nhất 10 ký tự.',

            'content.max' =>
                'Nội dung đánh giá không được vượt quá 1000 ký tự.',
        ];
    }
}