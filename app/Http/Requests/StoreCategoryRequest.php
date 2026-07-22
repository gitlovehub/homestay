<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:categories,name',
            'slug' => 'nullable|max:255|unique:categories,slug',
            'description' => 'nullable|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'name.max' => 'Tên danh mục không được quá 255 ký tự.',

            'slug.unique' => 'Slug đã tồn tại.',
            'slug.max' => 'Slug không được quá 255 ký tự.',

            'description.max' => 'Mô tả không được quá 1000 ký tự.',
        ];
    }
}