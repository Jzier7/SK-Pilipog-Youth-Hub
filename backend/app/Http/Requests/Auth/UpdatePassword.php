<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
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
            'userId' => ['nullable', 'integer', 'exists:users,id'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
        ];
    }

    /**
     * Custom messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'confirm_password.same' => 'Password does not match.',
        ];
    }
}
