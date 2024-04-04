<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'name' => 'required|string|between:2,100',
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'phone' => [
                'required',
                'string',
                'regex:/[0-9]{10}/',
                'unique:users,phone'
            ],
            'password' => [
                'required',
                'string',
                'min:6',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
                'confirmed'
            ],
            'username' => [
                'required',
                'string',
                'regex:/[a-zA-Z]/',      // must contain at least one letter
                'unique:users,email'
            ],
            'password_confirmation' => [
                'string',
                'same:password'
            ]
        ];
    }
}
