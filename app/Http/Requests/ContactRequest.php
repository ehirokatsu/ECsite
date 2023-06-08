<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'email' => 'required|email',
            'title' => 'required',
            'body' => 'required',
                ];
    }
    public function messages()
    {
        return [
            'email.required' => 'メールを入力してください',
            'email.email' => 'メール形式で入力してください',
            'title.required' => 'タイトルを入力してください',
            'body.required' => '本文を入力してください',
        ];
    }
}
