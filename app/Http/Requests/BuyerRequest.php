<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyerRequest extends FormRequest
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
            'name' => 'required|string|max:10',
            'email' => 'required|string|max:30',
            'postalCode' => 'required|integer|size:7',
            'address1' => 'required|string|max:10',
            'address2' => 'required|string|max:10',
            'address3' => 'required|string|max:10',
            'phoneNumber' => 'required|integer|max:15',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '入力してください',
            'name.string' => '文字列で入力してください',
            'name.max' => '最大文字数は10文字です',
            'email.required' => '入力してください',
            'email.string' => '文字列で入力してください',
            'email.max' => '最大文字数は30文字です',
            'cost.required' => '入力してください',
            'cost.integer' => '数字で入力してください',
            'cost.size' => '7桁で入力してください',
            'address1.required' => '入力してください',
            'address1.string' => '文字列で入力してください',
            'address1.max' => '最大文字数は10文字です',
            'address2.required' => '入力してください',
            'address2.string' => '文字列で入力してください',
            'address2.max' => '最大文字数は10文字です',
            'address3.required' => '入力してください',
            'address3.string' => '文字列で入力してください',
            'address3.max' => '最大文字数は10文字です',
            'phoneNumber.required' => '入力してください',
            'phoneNumber.integer' => '数字で入力してください',
            'phoneNumber.max' => '最大値は15です',
        ];
    }
}
