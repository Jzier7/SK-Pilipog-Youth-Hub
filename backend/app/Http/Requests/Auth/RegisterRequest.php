<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

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
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'birthdate' => ['required', 'date'],
            'gender' => 'required',
            'purok' => 'required',
            'email' => ['required', 'unique:users,email'],
            'username' => 'required',
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
            'files' => ['required', 'array', 'max:1'],
            'files.*' => ['required', 'file', 'image', 'max:' . env('MAX_FILE_UPLOAD_SIZE', '5000')]
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
