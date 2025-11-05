<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cualquiera puede intentar hacer login
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:100',
            'password' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
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
            'password.required' => [
                'en' => 'The password is required.',
                'es' => 'La contraseña es obligatoria.'
            ],
        ];
    }
}
