<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $userId = $this->route('id') ?? $this->route('user');

        //Nếu route dùng route model binding thì $userID sẽ là object
        if (is_object($userId)) {
            $userId = $userId->id;
        }
        return [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'phone' => [
                'numeric',
                'regex:/^(0|\+84)[3|5|7|8|9][0-9]{8}$/',
                //Bỏ qua ID của user muốn cập nhật 
                Rule::unique('users', 'phone')->ignore($userId),
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

    public function attributes()
    {
        return [
            'id_country' => "country",
            'name' => 'user name'
        ];
    }

    //check lỗi
    // protected function failedValidation(Validator $validator)
    // {
    //     dd($validator->errors()->toArray());
    // }
}
