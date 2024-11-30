<?php

namespace App\Http\Requests\Game;

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

        return $user && $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:games,id'],
            'name' => ['required', 'string', 'max:255'],
            'event' => ['required', 'integer', 'exists:events,id'],
            'team1' => ['required', 'integer', 'exists:teams,id'],
            'team2' => ['required', 'integer', 'exists:teams,id'],
            'date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'Game does not exist.',
        ];
    }
}
