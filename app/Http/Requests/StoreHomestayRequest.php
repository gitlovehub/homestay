<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHomestayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Chuẩn hóa dữ liệu trước khi kiểm tra.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->name)
                ? trim($this->name)
                : $this->name,

            'slug' => is_string($this->slug)
                ? trim($this->slug)
                : $this->slug,

            'address' => is_string($this->address)
                ? trim($this->address)
                : $this->address,

            'city' => is_string($this->city)
                ? trim($this->city)
                : $this->city,

            'phone' => is_string($this->phone)
                ? trim($this->phone)
                : $this->phone,
        ]);
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'owner_id' => [
                'required',
                'integer',
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
                Rule::in(config('homestay_locations', [])),
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
                'distinct',
                'exists:amenities,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.integer' => 'Danh mục không hợp lệ.',
            'category_id.exists' => 'Danh mục được chọn không tồn tại.',

            'owner_id.required' => 'Vui lòng chọn chủ sở hữu.',
            'owner_id.integer' => 'Chủ sở hữu không hợp lệ.',
            'owner_id.exists' => 'Chủ sở hữu được chọn không tồn tại.',

            'name.required' => 'Vui lòng nhập tên Homestay.',
            'name.string' => 'Tên Homestay không hợp lệ.',
            'name.max' => 'Tên Homestay không được quá 255 ký tự.',

            'slug.string' => 'Slug không hợp lệ.',
            'slug.unique' => 'Slug này đã được sử dụng.',
            'slug.max' => 'Slug không được quá 255 ký tự.',

            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.string' => 'Địa chỉ không hợp lệ.',
            'address.max' => 'Địa chỉ không được quá 255 ký tự.',

            'city.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'city.string' => 'Tỉnh/thành phố không hợp lệ.',
            'city.max' => 'Tên tỉnh/thành phố không được quá 100 ký tự.',
            'city.in' => 'Tỉnh/thành phố được chọn không nằm trong danh sách.',

            'phone.regex' => 'Số điện thoại phải gồm 10 đến 11 chữ số.',

            'description.string' => 'Mô tả không hợp lệ.',
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

            'policy.string' => 'Chính sách không hợp lệ.',
            'policy.max' => 'Chính sách không được quá 3000 ký tự.',

            'thumbnail.image' => 'Ảnh đại diện phải là tệp hình ảnh.',
            'thumbnail.mimes' => 'Ảnh đại diện chỉ chấp nhận JPG, JPEG, PNG hoặc WEBP.',
            'thumbnail.max' => 'Ảnh đại diện không được lớn hơn 3MB.',

            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.boolean' => 'Trạng thái không hợp lệ.',

            'amenities.array' => 'Danh sách tiện ích không hợp lệ.',
            'amenities.*.integer' => 'Tiện ích không hợp lệ.',
            'amenities.*.distinct' => 'Tiện ích đang bị chọn trùng.',
            'amenities.*.exists' => 'Tiện ích được chọn không tồn tại.',
        ];
    }
}