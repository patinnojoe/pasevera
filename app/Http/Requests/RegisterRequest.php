<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use function Laravel\Prompts\password;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:5|max:150|string',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users|min:6|max:15',
            'password' => 'required|min:7|string|confirmed|'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Pasevera requires you to enter your full name.',
            'name.min' => 'Pasevera requires your name to be not less than 5 characters',
            'name.max' => 'Pasevera requires your name to be not more than 150 characters',


            'username.required' => 'Pasevera wants to call you by your nickname!',
            'username.max' => 'Hey, Keep your nickname simple',
            'username.min' => 'Pasevera wants your username to have at least 5characters',
            'username.unique' => 'its not you, its us, we dot accept this nickname find another one',

            'email.unique' => 'Cant register you with that email, use something else!'
        ];
    }
}
