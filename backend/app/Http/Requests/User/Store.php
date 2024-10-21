<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user && $user->isSuperAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'unique:users,email'],
            'birthdate' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', 'in:Male,Female'],
            'purok' => ['required'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $this->id],
        ];
    }

    public function messages(): array
    {
        return [
            'birthdate.date' => 'Birthdate must be a valid date.',
            'gender.in' => 'Gender must be one of the following: Male, Female',
            'username.unique' => 'Username has already been taken.',
        ];
    }
}

