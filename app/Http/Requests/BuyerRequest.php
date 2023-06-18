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
            'postalCode' => 'required|digits:7',
            'address1' => 'required|string|max:10',
            'address2' => 'required|string|max:10',
            'address3' => 'required|string|max:10',
            'phoneNumber' => 'required|digits_between:1,15',
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
            'postalCode.required' => '入力してください',
            'postalCode.digits' => '半角数字７桁で入力してください',
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
            'phoneNumber.digits_between' => '数字15桁以内で入力してください',
        ];
    }
}
