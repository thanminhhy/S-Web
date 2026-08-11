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
            'images' => 'required|array|min:1|max:3',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|min:0|numeric',
            'status' => 'required|string|in:new,sale',
            'sale' => 'required_if:status,sale|numeric|min:0|max:100',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'company' => 'nullable|max:255|string',
            'detail' => 'nullable|string'
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
            'numeric' => ':attribute phải thuộc dạng số',
            'images.required' => 'Vui lòng chọn ít nhất 1 ảnh',
            'images.max' => 'Bạn chỉ được phép tải lên tối đa 3 ảnh',
            'images.*.image' => 'File tải lên phải là định dạng ảnh',
            'images.*.max' => 'Kích thước mỗi fiel ảnh không được quá 2MB',
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'Tên sản phẩm',
            'image' => 'Ảnh sản phẩm',
            'price' => 'Giá sản phẩm',
            'status' => 'Tình trạng sản phẩm',
            'category_id' => 'Danh mục sản phẩm',
            'brand_id' => 'Thương hiệu sản phẩm'
        ];
    }
}
