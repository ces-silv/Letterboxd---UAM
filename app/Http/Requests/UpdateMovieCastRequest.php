<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieCastRequest extends FormRequest
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
        $castId = $this->route('id');

        return [
            'movie_id' => 'required|integer|exists:movies,movie_id',
            'actor_id' => 'required|integer|exists:actors,actor_id',
            'character_name' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'movie_id.required' => 'El ID de la película es obligatorio.',
            'movie_id.integer' => 'El ID de la película debe ser un número entero.',
            'movie_id.exists' => 'La película seleccionada no existe.',
            'actor_id.required' => 'El ID del actor es obligatorio.',
            'actor_id.integer' => 'El ID del actor debe ser un número entero.',
            'actor_id.exists' => 'El actor seleccionado no existe.',
            'character_name.required' => 'El nombre del personaje es obligatorio.',
            'character_name.string' => 'El nombre del personaje debe ser una cadena de texto.',
            'character_name.max' => 'El nombre del personaje no puede exceder los 255 caracteres.',
        ];
    }
}