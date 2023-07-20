<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'string|max:30',
            'cost' => 'integer|max:10000|min:1',
        ];
    }
    public function messages()
    {
        return [
            'name.string' => '商品名は文字列で入力してください',
            'name.max' => '商品名の最大文字数は100文字です',
            'cost.integer' => '単価は数字で入力してください',
            'cost.max' => '単価の最大値は10000です',
            'cost.min' => '単価の最小値は1です',
        ];
    }
}
