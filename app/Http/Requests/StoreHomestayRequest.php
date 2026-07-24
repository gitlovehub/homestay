<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomestayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'owner_id' => [
                'required',
                'exists:users,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:homestays,slug',
            ],

            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'phone' => [
                'nullable',
                'regex:/^[0-9]{10,11}$/',
            ],

            'description' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'base_price' => [
                'required',
                'integer',
                'min:0',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'check_in_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'check_out_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'policy' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'amenities' => [
                'nullable',
                'array',
            ],

            'amenities.*' => [
                'integer',
                'exists:amenities,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục được chọn không tồn tại.',

            'owner_id.required' => 'Vui lòng chọn chủ sở hữu.',
            'owner_id.exists' => 'Chủ sở hữu được chọn không tồn tại.',

            'name.required' => 'Vui lòng nhập tên Homestay.',
            'name.max' => 'Tên Homestay không được quá 255 ký tự.',

            'slug.unique' => 'Slug này đã được sử dụng.',
            'slug.max' => 'Slug không được quá 255 ký tự.',

            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.max' => 'Địa chỉ không được quá 255 ký tự.',

            'city.required' => 'Vui lòng nhập thành phố.',
            'city.max' => 'Tên thành phố không được quá 100 ký tự.',

            'phone.regex' => 'Số điện thoại phải gồm 10 đến 11 chữ số.',

            'description.max' => 'Mô tả không được quá 3000 ký tự.',

            'base_price.required' => 'Vui lòng nhập giá cơ bản.',
            'base_price.integer' => 'Giá cơ bản phải là số nguyên.',
            'base_price.min' => 'Giá cơ bản không được nhỏ hơn 0.',

            'latitude.numeric' => 'Vĩ độ phải là một số.',
            'latitude.between' => 'Vĩ độ phải nằm trong khoảng -90 đến 90.',

            'longitude.numeric' => 'Kinh độ phải là một số.',
            'longitude.between' => 'Kinh độ phải nằm trong khoảng -180 đến 180.',

            'check_in_time.date_format' => 'Giờ nhận phòng không đúng định dạng.',
            'check_out_time.date_format' => 'Giờ trả phòng không đúng định dạng.',

            'policy.max' => 'Chính sách không được quá 3000 ký tự.',

            'thumbnail.image' => 'Ảnh đại diện phải là tệp hình ảnh.',
            'thumbnail.mimes' => 'Ảnh đại diện chỉ chấp nhận JPG, JPEG, PNG hoặc WEBP.',
            'thumbnail.max' => 'Ảnh đại diện không được lớn hơn 3MB.',

            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}