<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDirectorRequest extends FormRequest
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
        $directorId = $this->route('id');

        return [
            'director_name' => 'required|string|max:50|unique:directors,director_name,' . $directorId . ',director_id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'director_name.required' => 'El nombre del director es obligatorio.',
            'director_name.string' => 'El nombre del director debe ser una cadena de texto.',
            'director_name.max' => 'El nombre del director no puede exceder los 50 caracteres.',
            'director_name.unique' => 'Este director ya existe.',
        ];
    }
}


