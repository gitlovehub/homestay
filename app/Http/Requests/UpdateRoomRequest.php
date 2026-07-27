<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
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

            'room_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('rooms', 'room_code')
                    ->ignore($this->route('room')->id),
            ],

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
        return (new StoreRoomRequest())->messages();
    }
}