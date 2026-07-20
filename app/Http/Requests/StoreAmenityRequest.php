<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAmenityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:amenities,name',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên tiện ích.',
            'name.string' => 'Tên tiện ích không hợp lệ.',
            'name.max' => 'Tên tiện ích không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên tiện ích đã tồn tại.',

            'icon.string' => 'Biểu tượng không hợp lệ.',
            'icon.max' => 'Biểu tượng không được vượt quá 100 ký tự.',

            'description.string' => 'Mô tả không hợp lệ.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',

            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'tên tiện ích',
            'icon' => 'biểu tượng',
            'description' => 'mô tả',
            'status' => 'trạng thái',
        ];
    }
}
