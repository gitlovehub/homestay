<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHomestayRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'owner_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'regex:/^(0)[0-9]{9,10}$/'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable','boolean'],
            'status' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục đã chọn không tồn tại.',

            'owner_id.required' => 'Vui lòng chọn chủ sở hữu.',
            'owner_id.exists' => 'Chủ sở hữu đã chọn không tồn tại.',

            'name.required' => 'Vui lòng nhập tên Homestay.',
            'name.string' => 'Tên Homestay không hợp lệ.',
            'name.max' => 'Tên Homestay không được vượt quá 255 ký tự.',

            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.string' => 'Địa chỉ không hợp lệ.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

            'city.required' => 'Vui lòng nhập thành phố.',
            'city.string' => 'Tên thành phố không hợp lệ.',
            'city.max' => 'Tên thành phố không được vượt quá 100 ký tự.',

            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại phải gồm 10 hoặc 11 chữ số.',

            'description.string' => 'Mô tả không hợp lệ.',

            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',

            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'danh mục',
            'owner_id' => 'chủ sở hữu',
            'name' => 'tên Homestay',
            'address' => 'địa chỉ',
            'city' => 'thành phố',
            'phone' => 'số điện thoại',
            'description' => 'mô tả',
            'image' => 'hình ảnh',
            'status' => 'trạng thái',
        ];
    }
}
