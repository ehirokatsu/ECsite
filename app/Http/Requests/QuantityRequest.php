<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuantityRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1|max:100',
        ];
    }
    public function messages()
    {
        return [
            'quantity.required' => '個数を入力してください',
            'quantity.integer' => '数値で入力してください',
            'quantity.min' => '最小個数は1個です',
            'quantity.max' => '最大個数は100個です',
        ];
    }
}
