<?php

namespace App\Http\Requests;

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


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material' => 'required|string|max:50',
            'price' => 'required|numeric|gt:0',
            'gender' => 'required|numeric',
            'category_id' => 'required|exists:tbl_category,id',

            'sizes.*.size_name' => 'required|string|max:10',
            'sizes.*.stock_quantity' => 'required|numeric|gt:0',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc*',
            'description.required' => 'Mô tả là bắt buộc*',
            'material.required' => 'Chất liệu là bắt buộc.',
            'price.required' => 'Giá là bắt buộc*',
            'price.numeric'  => 'Giá sản phẩm phải là một số*',
            'price.gt'       => 'Giá sản phẩm phải lớn hơn 0*',
            'gender.required' => 'Giới tính là bắt buộc*',
            'category_id.required' => 'Loại là bắt buộc*',

            'sizes.*.size_name.required' => 'Tên kích cỡ là bắt buộc*',
            'sizes.*.stock_quantity.required' => 'Số lượng là bắt buộc*',
            'sizes.*.stock_quantity.numeric'  => 'Số lượng sản phẩm phải là một số*',
            'sizes.*.stock_quantity.gt'       => 'Số lượng sản phẩm phải lớn hơn 0*',
        ];
    }
}
