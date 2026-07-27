<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'homestay_id' => ['required', 'exists:homestays,id'],
            'name' => ['required', 'string', 'max:255'],
            'room_code' => ['required', 'string', 'max:100', 'unique:rooms,room_code'],
            'room_type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'price_per_night' => ['required', 'integer', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
            'number_of_beds' => ['required', 'integer', 'min:1'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:available,maintenance,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'homestay_id.required' => 'Vui lòng chọn Homestay.',
            'homestay_id.exists' => 'Homestay không tồn tại.',

            'name.required' => 'Vui lòng nhập tên phòng.',
            'name.max' => 'Tên phòng không được vượt quá 255 ký tự.',

            'room_code.required' => 'Vui lòng nhập mã phòng.',
            'room_code.unique' => 'Mã phòng đã tồn tại.',
            'room_code.max' => 'Mã phòng không được vượt quá 100 ký tự.',

            'room_type.required' => 'Vui lòng nhập loại phòng.',
            'room_type.max' => 'Loại phòng không được vượt quá 100 ký tự.',

            'description.max' => 'Mô tả không được vượt quá 3000 ký tự.',

            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',

            'price_per_night.required' => 'Vui lòng nhập giá phòng mỗi đêm.',
            'price_per_night.integer' => 'Giá phòng phải là số nguyên.',
            'price_per_night.min' => 'Giá phòng không được nhỏ hơn 0.',

            'capacity.required' => 'Vui lòng nhập sức chứa.',
            'capacity.integer' => 'Sức chứa phải là số nguyên.',
            'capacity.min' => 'Sức chứa phải ít nhất là 1 người.',

            'number_of_beds.required' => 'Vui lòng nhập số giường.',
            'number_of_beds.integer' => 'Số giường phải là số nguyên.',
            'number_of_beds.min' => 'Số giường phải ít nhất là 1.',

            'area.numeric' => 'Diện tích phải là số.',
            'area.min' => 'Diện tích không được nhỏ hơn 0.',

            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}