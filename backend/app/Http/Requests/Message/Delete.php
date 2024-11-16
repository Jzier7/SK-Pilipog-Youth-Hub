<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Message;

class Delete extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = Message::find($this->input('id'));

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
            'id' => ['required', 'integer', 'exists:messages,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'Message does not exist.',
        ];
    }
}
