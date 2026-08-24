<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatriculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol('Estudiante') ?? false;
    }

    public function rules(): array
    {
        return [
            'grupo_id' => [
                'required',
                'integer',
                'exists:grupos,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'grupo_id.required' =>
                'Debes seleccionar un grupo para continuar.',
            'grupo_id.exists' =>
                'El grupo seleccionado ya no se encuentra disponible.',
        ];
    }
}