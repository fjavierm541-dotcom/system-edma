<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocenteRequest extends FormRequest
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
            'empleado_id' => [
                'required',
                'integer',

                Rule::exists('empleados', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('estado', 'activo')
                            ->whereNull('deleted_at')
                    ),

                Rule::unique(
                    'docentes',
                    'empleado_id'
                )->whereNull('deleted_at'),
            ],

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
            'empleado_id.required' =>
                'Debe seleccionar el empleado que será registrado como docente.',

            'empleado_id.integer' =>
                'El empleado seleccionado no es válido.',

            'empleado_id.exists' =>
                'El empleado seleccionado no existe, está inactivo o no se encuentra disponible.',

            'empleado_id.unique' =>
                'El empleado seleccionado ya posee un perfil docente.',

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