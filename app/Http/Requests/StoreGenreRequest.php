<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGenreRequest extends FormRequest
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
            'genre_name' => 'required|string|max:50|unique:genres,genre_name',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'genre_name.required' => 'El nombre del género es obligatorio.',
            'genre_name.string' => 'El nombre del género debe ser una cadena de texto.',
            'genre_name.max' => 'El nombre del género no puede exceder los 50 caracteres.',
            'genre_name.unique' => 'Este género ya existe.',
        ];
    }
}
