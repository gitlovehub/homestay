<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAmenityRequest extends FormRequest
{
    /**
     * Xác định người dùng có quyền gửi request hay không.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Quy tắc validate.
     */
    public function rules(): array
    {
        $amenity = $this->route('amenity');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('amenities', 'name')
                    ->ignore($amenity?->id),
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('amenities', 'slug')
                    ->ignore($amenity?->id),
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Thông báo lỗi.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên tiện ích không được để trống.',
            'name.unique' => 'Tên tiện ích đã tồn tại.',
            'name.max' => 'Tên tiện ích tối đa 255 ký tự.',

            'slug.unique' => 'Slug đã tồn tại.',
            'slug.max' => 'Slug tối đa 255 ký tự.',

            'icon.max' => 'Tên icon tối đa 255 ký tự.',

            'description.max' => 'Mô tả tối đa 1000 ký tự.',

            'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }

    /**
     * Tên hiển thị.
     */
    public function attributes(): array
    {
        return [
            'name' => 'tên tiện ích',
            'slug' => 'slug',
            'icon' => 'icon',
            'description' => 'mô tả',
            'status' => 'trạng thái',
        ];
    }
}