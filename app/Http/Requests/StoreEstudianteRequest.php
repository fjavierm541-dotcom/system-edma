<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        /*
         * Temporalmente permitido.
         * Posteriormente será controlado mediante Policy.
         */
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'profesion_ocupacion' => $this->normalizeNullableText(
                $this->input('profesion_ocupacion')
            ),

            'observaciones' => $this->normalizeNullableText(
                $this->input('observaciones')
            ),

            'estado' => $this->normalizeNullableText(
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
                 * Una persona solo puede tener un expediente estudiantil.
                 */
                Rule::unique('estudiantes', 'persona_id')
                    ->whereNull('deleted_at'),
            ],

            /*
            |--------------------------------------------------------------------------
            | Información estudiantil
            |--------------------------------------------------------------------------
            */

            'nivel_escolaridad_id' => [
                'nullable',
                'integer',

                Rule::exists('niveles_escolaridad', 'id')
                    ->where(
                        fn ($query) => $query->where(
                            'activo',
                            true
                        )
                    ),
            ],

            'profesion_ocupacion' => [
                'nullable',
                'string',
                'max:150',
            ],

            'fecha_ingreso' => [
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
            'persona_id.required' =>
                'Debe seleccionar la persona que será registrada como estudiante.',

            'persona_id.integer' =>
                'La persona seleccionada no es válida.',

            'persona_id.exists' =>
                'La persona seleccionada no existe, está inactiva o no se encuentra disponible.',

            'persona_id.unique' =>
                'La persona seleccionada ya posee un expediente de estudiante.',

            'nivel_escolaridad_id.integer' =>
                'El nivel de escolaridad seleccionado no es válido.',

            'nivel_escolaridad_id.exists' =>
                'El nivel de escolaridad seleccionado no existe o está inactivo.',

            'profesion_ocupacion.max' =>
                'La profesión u ocupación no puede superar los 150 caracteres.',

            'fecha_ingreso.required' =>
                'La fecha de ingreso es obligatoria.',

            'fecha_ingreso.date' =>
                'La fecha de ingreso no tiene un formato válido.',

            'fecha_ingreso.before_or_equal' =>
                'La fecha de ingreso no puede ser posterior a hoy.',

            'estado.required' =>
                'Debe seleccionar el estado del estudiante.',

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
            'nivel_escolaridad_id' => 'nivel de escolaridad',
            'profesion_ocupacion' => 'profesión u ocupación',
            'fecha_ingreso' => 'fecha de ingreso',
            'estado' => 'estado del estudiante',
            'observaciones' => 'observaciones',
        ];
    }

    private function normalizeNullableText(mixed $value): mixed
    {
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