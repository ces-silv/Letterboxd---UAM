<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // El usuario autenticado puede actualizar su perfil
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->user_id;
        
        return [
            'username' => 'sometimes|string|max:50|unique:users,username,' . $userId . ',user_id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'username.string' => 'El nombre de usuario debe ser una cadena de texto.',
            'username.max' => 'El nombre de usuario no puede exceder los 50 caracteres.',
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'email.email' => 'Debe proporcionar un email válido.',
            'email.max' => 'El email no puede exceder los 100 caracteres.',
            'email.unique' => 'Este email ya está registrado.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->has('username')) {
                $validator->errors()->add('general', 'Debe proporcionar al menos un campo para actualizar (username o email).');
            }
        });
    }
}
