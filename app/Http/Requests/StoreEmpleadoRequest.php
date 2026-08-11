<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'institucion_laboral_actual' =>
                $this->normalizeNullableText(
                    $this->input('institucion_laboral_actual')
                ),

            'horario_laboral_actual' =>
                $this->normalizeNullableText(
                    $this->input('horario_laboral_actual')
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
            /*
            |--------------------------------------------------------------------------
            | Persona
            |--------------------------------------------------------------------------
            */

            'persona_id' => [
                'required',
                'integer',

                Rule::exists('personas', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('estado', 'activo')
                            ->whereNull('deleted_at')
                    ),

                /*
                 * Una persona solo puede tener un expediente de empleado.
                 */
                Rule::unique('empleados', 'persona_id')
                    ->whereNull('deleted_at'),
            ],

            /*
            |--------------------------------------------------------------------------
            | Información laboral
            |--------------------------------------------------------------------------
            */

            'fecha_ingreso' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'fecha_salida' => [
                'nullable',
                'date',
                'after_or_equal:fecha_ingreso',
            ],

            'cantidad_hijos' => [
                'nullable',
                'integer',
                'min:0',
                'max:65535',
            ],

            'institucion_laboral_actual' => [
                'nullable',
                'string',
                'max:180',
            ],

            'horario_laboral_actual' => [
                'nullable',
                'string',
                'max:150',
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
            'persona_id.required' =>
                'Debe seleccionar la persona que será registrada como empleado.',

            'persona_id.integer' =>
                'La persona seleccionada no es válida.',

            'persona_id.exists' =>
                'La persona seleccionada no existe, está inactiva o no se encuentra disponible.',

            'persona_id.unique' =>
                'La persona seleccionada ya posee un expediente de empleado.',

            'fecha_ingreso.required' =>
                'La fecha de ingreso es obligatoria.',

            'fecha_ingreso.date' =>
                'La fecha de ingreso no tiene un formato válido.',

            'fecha_ingreso.before_or_equal' =>
                'La fecha de ingreso no puede ser posterior a hoy.',

            'fecha_salida.date' =>
                'La fecha de salida no tiene un formato válido.',

            'fecha_salida.after_or_equal' =>
                'La fecha de salida no puede ser anterior a la fecha de ingreso.',

            'cantidad_hijos.integer' =>
                'La cantidad de hijos debe ser un número entero.',

            'cantidad_hijos.min' =>
                'La cantidad de hijos no puede ser negativa.',

            'cantidad_hijos.max' =>
                'La cantidad de hijos ingresada supera el valor permitido.',

            'institucion_laboral_actual.max' =>
                'La institución laboral actual no puede superar los 180 caracteres.',

            'horario_laboral_actual.max' =>
                'El horario laboral actual no puede superar los 150 caracteres.',

            'estado.required' =>
                'Debe seleccionar el estado del empleado.',

            'estado.in' =>
                'El estado seleccionado no es válido.',

            'observaciones.max' =>
                'Las observaciones no pueden superar los 1000 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'persona_id' => 'persona',
            'fecha_ingreso' => 'fecha de ingreso',
            'fecha_salida' => 'fecha de salida',
            'cantidad_hijos' => 'cantidad de hijos',
            'institucion_laboral_actual' =>
                'institución laboral actual',
            'horario_laboral_actual' =>
                'horario laboral actual',
            'estado' => 'estado del empleado',
            'observaciones' => 'observaciones',
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