<?php

namespace App\Http\Requests\TeamLike;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Like extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user && $user->isUser() || $user->isActiveVoter();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'game_id' => ['required', 'integer', 'exists:games,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'team_id.exists' => 'Team does not exist.',
            'game_id.exists' => 'Game does not exist.',
        ];
    }
}
