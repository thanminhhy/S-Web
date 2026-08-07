<?php

namespace App\Http\Requests\ProductRequest;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|min:2|max:255|string',
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'required|min:0|numeric'
        ];
    }
    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'min' => ':attribute không được nhỏ hơn :min ký tự',
            'string' => ':attribute phải là một chuỗi ký tự',
            'image' => ':attribute phải là dạng ảnh',
            'mimes' => ':attribute phải thuộc các dạng file ảnh jpeg,jpg và png',
            'numeric' => ':attribute phải thuộc dạng số'
        ];
    }
}
