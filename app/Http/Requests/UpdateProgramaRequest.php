<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgramaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'codigo' => strtoupper(
                trim((string) $this->codigo)
            ),

            'nombre' => trim(
                (string) $this->nombre
            ),

            'segmento' => trim(
                (string) $this->segmento
            ),
        ]);
    }

    public function rules(): array
    {
        $programa = $this->route('programa');

        return [
            'codigo' => [
                'required',
                'string',
                'max:20',

                Rule::unique('programas', 'codigo')
                    ->ignore($programa?->id)
                    ->whereNull('deleted_at'),
            ],

            'nombre' => [
                'required',
                'string',
                'max:150',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'segmento' => [
                'required',
                'string',
                'max:30',
            ],

            'estado' => [
                'required',
                Rule::in([
                    'activo',
                    'inactivo',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' =>
                'El código del programa es obligatorio.',

            'codigo.max' =>
                'El código no puede superar los 20 caracteres.',

            'codigo.unique' =>
                'Ya existe un programa con este código.',

            'nombre.required' =>
                'El nombre del programa es obligatorio.',

            'nombre.max' =>
                'El nombre no puede superar los 150 caracteres.',

            'descripcion.max' =>
                'La descripción es demasiado extensa.',

            'segmento.required' =>
                'Debe indicar el segmento del programa.',

            'segmento.max' =>
                'El segmento no puede superar los 30 caracteres.',

            'estado.in' =>
                'El estado seleccionado no es válido.',
        ];
    }
}