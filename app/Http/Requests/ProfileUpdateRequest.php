<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)
                    ->ignore($this->user()->id),
            ],

            'phone' => [
                'nullable',
                'regex:/^[0-9]{10,11}$/',
            ],

            'address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'remove_avatar' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.string' => 'Họ và tên không hợp lệ.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',

            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.string' => 'Địa chỉ email không hợp lệ.',
            'email.lowercase' => 'Email phải được viết bằng chữ thường.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',

            'phone.regex' => 'Số điện thoại phải gồm 10 đến 11 chữ số.',

            'address.string' => 'Địa chỉ không hợp lệ.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

            'avatar.image' => 'Tệp tải lên phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh đại diện chỉ chấp nhận JPG, JPEG, PNG hoặc WEBP.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',

            'remove_avatar.boolean' => 'Giá trị xóa ảnh đại diện không hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'họ và tên',
            'email' => 'email',
            'phone' => 'số điện thoại',
            'address' => 'địa chỉ',
            'avatar' => 'ảnh đại diện',
        ];
    }
}
