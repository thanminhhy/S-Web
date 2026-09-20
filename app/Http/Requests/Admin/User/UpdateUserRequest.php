<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateUserRequest extends FormRequest
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
            'phone' => [
                'numeric',
                'regex:/^(0|\+84)[3|5|7|8|9][0-9]{8}$/',
                'unique:users,phone,' . $this->user?->id,
            ],
            'address' => 'nullable|string',
            'id_country' => 'required|integer|exists:countries,id'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'min' => ':attribute không được nhỏ hơn :min ký tự',
            'max' => ':attribute upload đã vượt quá giới hạn upload cho phép',
            'email' => ':attribute nhập vào phải thuộc dạng email'
        ];
    }

    //check lỗi
    // protected function failedValidation(Validator $validator)
    // {
    //     dd($validator->errors()->toArray());
    // }
}
