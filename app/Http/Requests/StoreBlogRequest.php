<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
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
            'title' => 'required|max:255|string',
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'string',
            'content' => 'required'

        ];
    }
    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'max' => ':attribute không được vượt quá giới 2Mb',
            'image' => ':attribute phải là dạng ảnh',
            'mimes' => ':attribute phải thuộc các dạng file jpg, jpeg, png',
            'string' => ':attribute phải được nhập ký tự'
        ];
    }
    public function attributes()
    {
        return [
            'title' => 'Tiêu đề',
            'image' => 'Ảnh',
            'description' => 'Mô tả',
            'content' => 'Nội dung'
        ];
    }
}
