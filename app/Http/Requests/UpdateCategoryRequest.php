<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
                'max:100',
                Rule::unique('categories')->ignore($this->category),
            ],

            'slug' => [
                'nullable',
                'max:100',
                Rule::unique('categories')->ignore($this->category),
            ],

            'description' => [
                'nullable',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại.',

            'slug.max' => 'Slug không được vượt quá 100 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',

            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'tên danh mục',
            'slug' => 'slug',
            'description' => 'mô tả',
        ];
    }
}