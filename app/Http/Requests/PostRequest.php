<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:102400',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El título es requerido.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'description.required' => 'La descripción es requerida.',
            'description.max' => 'La descripción no puede tener más de 255 caracteres.',
            'file.required' => 'El archivo es requerido.',
            'file.file' => 'El archivo no es válido.',
            'file.mimes' => 'El archivo no tiene un formato válido.',
            'file.max' => 'El archivo no puede tener más de 100MB.',
            'user_id.required' => 'El usuario es requerido.',
            'user_id.exists' => 'El usuario no existe.',
        ];
    }
}
