<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:3',
            'lastname' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|min:3|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo :attribute es requerido.',
            'lastname.required' => 'El campo :attribute es requerido.',
            'email.required' => 'El campo :attribute es requerido.',
            'email.email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'username.required' => 'El campo :attribute es requerido.',
            'username.unique' => 'El nombre de usuario ya está registrado.',
            'password.required' => 'El campo :attribute es requerido.',
            'password_confirmation.required' => 'El campo :attribute es requerido.',
            'image.image' => 'El campo :attribute debe ser una imagen.',
            'image.mimes' => 'El campo :attribute debe ser una imagen.',
            'image.max' => 'El campo :attribute debe ser una imagen.',
        ];
    }

    
}
