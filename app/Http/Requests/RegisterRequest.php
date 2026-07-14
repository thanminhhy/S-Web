<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'level' => 'required'

        ];
    }
    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'string' => ':attribute phải là dạng ký tự',
            'max' => ':attribute nhập vào không được quá :max ký tự',
            'unique' => ':attribute đã tồn tại',
            'min' => ':attribute nhập vào phải đạt ít nhất :min ký tự',
            'confirmed' => ':attribute nhập vào không trùng khớp với :attribute xác nhận'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên',
            'password' => 'Mât khẩu'
        ];
    }
}
