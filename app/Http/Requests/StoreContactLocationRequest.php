<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => [
                'required',
                'string',
                'max:50',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'map_query' => [
                'required',
                'string',
                'max:255',
            ],

            'sort_order' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'label.required' => 'Vui lòng nhập tên ngắn của vị trí.',
            'label.max' => 'Tên ngắn không được vượt quá 50 ký tự.',

            'name.required' => 'Vui lòng nhập tên địa điểm.',
            'name.max' => 'Tên địa điểm không được vượt quá 150 ký tự.',

            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

            'map_query.required' => 'Vui lòng nhập địa chỉ tìm kiếm trên Google Maps.',
            'map_query.max' => 'Địa chỉ Google Maps không được vượt quá 255 ký tự.',

            'sort_order.required' => 'Vui lòng chọn thứ tự hiển thị.',
            'sort_order.integer' => 'Thứ tự hiển thị không hợp lệ.',
            'sort_order.between' => 'Thứ tự hiển thị phải từ 1 đến 5.',

            'is_active.required' => 'Trạng thái hiển thị không hợp lệ.',
            'is_active.boolean' => 'Trạng thái hiển thị không hợp lệ.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'label' => is_string($this->label)
                ? trim($this->label)
                : $this->label,

            'name' => is_string($this->name)
                ? trim($this->name)
                : $this->name,

            'address' => is_string($this->address)
                ? trim($this->address)
                : $this->address,

            'map_query' => is_string($this->map_query)
                ? trim($this->map_query)
                : $this->map_query,

            'is_active' => $this->boolean('is_active'),
        ]);
    }
}