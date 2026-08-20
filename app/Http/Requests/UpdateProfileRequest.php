<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'avatar' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
            'address' => 'nullable|string',
            'id_country' => 'required|integer|exists:countries,id',
            'phone' => [
                'required',
                'numeric',
                'regex:/^(0|\+84)[3|5|7|8|9][0-9]{8}$/',
                'unique:users,phone,' . $this->user?->id,
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'min' => ':attribute không được nhỏ hơn :min ký tự',
            'mimes' => ':attribute phải thuộc các dạng jpeg,jpg,png và gif',
            'avatar' => ':attribute phải là file dạng ảnh',
            'max' => ':attribute upload đã vượt quá giới hạn upload cho phép',
            'email' => ':attribute nhập vào phải thuộc dạng email'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'tên',
        ];
    }
}
