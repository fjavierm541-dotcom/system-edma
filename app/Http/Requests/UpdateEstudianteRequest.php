<?php

namespace App\Http\Requests;

use App\Models\Estudiante;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstudianteRequest extends FormRequest
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
        $estudiante = $this->route('estudiante');

        $estudianteId = $estudiante instanceof Estudiante
            ? $estudiante->getKey()
            : $estudiante;

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
                            ->whereNull('deleted_at')
                    ),

                Rule::unique('estudiantes', 'persona_id')
                    ->ignore($estudianteId)
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
                'El expediente debe permanecer asociado a una persona.',

            'persona_id.integer' =>
                'La persona asociada no es válida.',

            'persona_id.exists' =>
                'La persona asociada no existe o fue eliminada.',

            'persona_id.unique' =>
                'La persona seleccionada ya posee otro expediente de estudiante.',

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