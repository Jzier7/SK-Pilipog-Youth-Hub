<?php

namespace App\Http\Requests\ForumPost;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ForumPost;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = ForumPost::find($this->input('id'));

        if (!$post) {
            return false;
        }

        return $post->author_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:forum_posts,id'],
            'post' => ['required', 'string'],
        ];
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.exists' => 'Official does not exist.',
        ];
    }
}

