<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'especialidad' =>
                $this->normalizeNullableText(
                    $this->input('especialidad')
                ),

            'observaciones' =>
                $this->normalizeNullableText(
                    $this->input('observaciones')
                ),

            'estado' =>
                $this->normalizeNullableText(
                    $this->input('estado')
                ),
        ]);
    }

    public function rules(): array
    {
        return [
            'especialidad' => [
                'nullable',
                'string',
                'max:180',
            ],

            'fecha_inicio_docencia' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'estado' => [
                'required',
                Rule::in([
                    'activo',
                    'inactivo',
                ]),
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'especialidad.max' =>
                'La especialidad no puede superar los 180 caracteres.',

            'fecha_inicio_docencia.required' =>
                'La fecha de inicio de docencia es obligatoria.',

            'fecha_inicio_docencia.date' =>
                'La fecha de inicio de docencia no tiene un formato válido.',

            'fecha_inicio_docencia.before_or_equal' =>
                'La fecha de inicio de docencia no puede ser posterior a hoy.',

            'estado.required' =>
                'Debe seleccionar el estado del docente.',

            'estado.in' =>
                'El estado seleccionado no es válido.',

            'observaciones.max' =>
                'Las observaciones no pueden superar los 1000 caracteres.',
        ];
    }

    private function normalizeNullableText(
        mixed $value
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        $value = preg_replace(
            '/\s+/u',
            ' ',
            trim($value)
        );

        return $value === '' ? null : $value;
    }
}