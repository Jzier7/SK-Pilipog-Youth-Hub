<?php

namespace App\Http\Requests\Ability;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
            'role_id' => ['required', 'exists:roles,id'],
            'abilities' => ['required', 'array'],
            'abilities.*' => ['exists:routes,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'role.exists' => 'Role does not exist.',
            'abilities.*.exists' => 'Ability does not exist.'
        ];
    }
}
