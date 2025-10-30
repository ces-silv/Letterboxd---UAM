<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se maneja en el middleware 'auth:sanctum'
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $actorId = $this->route('id');

        return [
            'actor_name' => 'required|string|max:50|unique:actors,actor_name,' . $actorId . ',actor_id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'actor_name.required' => 'El nombre del actor es obligatorio.',
            'actor_name.string' => 'El nombre del actor debe ser una cadena de texto.',
            'actor_name.max' => 'El nombre del actor no puede exceder los 50 caracteres.',
            'actor_name.unique' => 'Este actor ya existe.',
        ];
    }
}


