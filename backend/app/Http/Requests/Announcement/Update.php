<?php

namespace App\Http\Requests\Announcement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;

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
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'integer', 'exists:categories,id'],
            'content' => ['required', 'string'],
            'file' => ['required', 'file', 'image', 'max:' . env('MAX_FILE_UPLOAD_SIZE', '5000')]
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'Announcement does not exist.',
            'category.exists' => 'Category does not exist.',
        ];
    }
}

