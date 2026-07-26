<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHomestayRequest extends FormRequest
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
                Rule::unique('homestays', 'slug')
                    ->ignore($this->route('homestay')->id),
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
        return (new StoreHomestayRequest())->messages();
    }
}