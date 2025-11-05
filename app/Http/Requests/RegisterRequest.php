<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cualquiera puede registrarse
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'username.required' => [
                'en' => 'The username is required.',
                'es' => 'El nombre de usuario es obligatorio.'
            ],
            'username.max' => [
                'en' => 'The username cannot exceed 50 characters.',
                'es' => 'El nombre de usuario no puede exceder los 50 caracteres.'
            ],
            'username.unique' => [
                'en' => 'This username is already in use.',
                'es' => 'Este nombre de usuario ya está en uso.'
            ],
            'email.required' => [
                'en' => 'The email is required.',
                'es' => 'El email es obligatorio.'
            ],
            'email.email' => [
                'en' => 'You must provide a valid email.',
                'es' => 'Debe proporcionar un email válido.'
            ],
            'email.max' => [
                'en' => 'The email cannot exceed 100 characters.',
                'es' => 'El email no puede exceder los 100 caracteres.'
            ],
            'email.unique' => [
                'en' => 'This email is already registered.',
                'es' => 'Este email ya está registrado.'
            ],
            'password.required' => [
                'en' => 'The password is required.',
                'es' => 'La contraseña es obligatoria.'
            ],
            'password.min' => [
                'en' => 'The password must be at least 6 characters.',
                'es' => 'La contraseña debe tener al menos 6 caracteres.'
            ],
            'password.confirmed' => [
                'en' => 'The password confirmation does not match.',
                'es' => 'La confirmación de contraseña no coincide.'
            ],
        ];
    }
}
