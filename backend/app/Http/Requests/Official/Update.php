<?php

namespace App\Http\Requests\Official;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Update extends FormRequest
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
            'id' => ['required', 'integer', 'exists:officials,id'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'integer', 'exists:positions,id'],
            'term' => ['required', 'integer', 'exists:terms,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'Official does not exist.',
            'position.exists' => 'Position does not exist.',
            'term.exists' => 'Term does not exist.',
        ];
    }
}
