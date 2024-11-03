<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateParticipationCount extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user && $user->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*.id' => ['required', 'integer', 'exists:users,id'],
            '*.count' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.id.exists' => 'User does not exist.',
            '*.id.required' => 'The user ID is required.',
            '*.count.required' => 'The participation count is required.',
            '*.count.integer' => 'The participation count must be an integer.',
            '*.count.min' => 'The participation count cannot be negative.',
        ];
    }
}

