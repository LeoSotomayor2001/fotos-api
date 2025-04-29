<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'user_id.required' => 'El usuario es requerido.',
            'user_id.exists' => 'El usuario no existe.',
            'post_id.required' => 'La publicación es requerido.',
            'post_id.exists' => 'La publicación es no existe.',
            'comment.required' => 'Debe escribir un comentario.',
        ];
    }
}
