<?php

namespace App\Http\Requests\ForumComment;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ForumComment;

class Delete extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $comment = ForumComment::find($this->input('id'));

        if (!$comment) {
            return false;
        }

        return $comment->author_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:forum_comments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'User does not exist.',
        ];
    }
}
