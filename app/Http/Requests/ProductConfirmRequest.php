<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductConfirmRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => 'required|string|max:30',
            'cost' => 'required|integer|max:10000|min:1',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'name.string' => '商品名は文字列で入力してください',
            'name.max' => '商品名の最大文字数は100文字です',
            'cost.required' => '単価を入力してください',
            'cost.integer' => '単価は数字で入力してください',
            'cost.max' => '単価の最大値は10000です',
            'cost.min' => '単価の最小値は1です',
        ];
    }
}
