<?php

namespace App\Http\Requests\Announcement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;

class Delete extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user && ($user->isSuperAdmin() || $user->isAdmin())) {
            return true;
        }

        $announcement = Announcement::find($this->id);

        return $announcement && $announcement->author_id === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:announcements,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'Announcement does not exist.',
        ];
    }
}
