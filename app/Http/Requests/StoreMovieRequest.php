<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // La autorización se maneja en el middleware 'admin'
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
            'release_date' => 'required|date',
            'director_id' => 'required|integer|exists:directors,director_id',
            'synopsis' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título de la película es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.max' => 'El título no puede exceder los 255 caracteres.',
            'release_date.required' => 'La fecha de lanzamiento es obligatoria.',
            'release_date.date' => 'La fecha de lanzamiento debe ser una fecha válida.',
            'director_id.required' => 'El ID del director es obligatorio.',
            'director_id.integer' => 'El ID del director debe ser un número entero.',
            'director_id.exists' => 'El director seleccionado no existe.',
            'synopsis.string' => 'La sinopsis debe ser una cadena de texto.',
            'duration.required' => 'La duración es obligatoria.',
            'duration.integer' => 'La duración debe ser un número entero.',
            'duration.min' => 'La duración debe ser al menos 1 minuto.',
            'poster.image' => 'El archivo debe ser una imagen.',
            'poster.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'poster.max' => 'La imagen no puede ser mayor a 2MB.',
        ];
    }
}