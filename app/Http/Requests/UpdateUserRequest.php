<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('id');
        
        return [
            'name' => ['sometimes', 'string', 'max:30', 'min:3'],
            'lastname' => ['sometimes', 'string', 'max:30', 'min:3'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password' => 'sometimes|string|min:8|confirmed',
            'username' => [
                'sometimes',
                'string',
                'max:255',
                'min:3',
                Rule::unique('users', 'username')->ignore($userId)
            ],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function messages(){
        return [
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre debe tener menos de :max caracteres.',
            'name.string' => 'El nombre es requerido.',
            'lastname.string' => 'El apellido es requerido.',
            'lastname.max' => 'El apellido debe tener menos de :max caracteres.',
            'lastname.min' => 'El apellido debe tener al menos :min caracteres.',
            'email.email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'username.string' => 'El nombre de usuario es requerido.',
            'username.unique' => 'El nombre de usuario ya está registrado.',
            'password.min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'image.image' => 'El campo :attribute debe ser una imagen.',
            'image.mimes' => 'El campo :attribute debe ser una imagen.',
            'image.max' => 'El campo :attribute debe ser una imagen.',
        ];
    }
}
