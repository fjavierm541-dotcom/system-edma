<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePeriodoAcademicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' =>
                $this->normalizeNullableText(
                    $this->input('nombre')
                ),

            'estado' =>
                $this->normalizeNullableText(
                    $this->input('estado')
                ),

            'observaciones' =>
                $this->normalizeNullableText(
                    $this->input('observaciones')
                ),
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'fecha_inicio' => [
                'required',
                'date',
            ],

            'fecha_fin' => [
                'required',
                'date',
                'after_or_equal:fecha_inicio',
            ],

            'fecha_inicio_matricula' => [
                'required',
                'date',
            ],

            'fecha_fin_matricula' => [
                'required',
                'date',
                'after_or_equal:fecha_inicio_matricula',
                'before_or_equal:fecha_fin',
            ],

            /*
            |--------------------------------------------------------------------------
            | Ventana de carga de calificaciones
            |--------------------------------------------------------------------------
            */

            'calificaciones_desde' => [
                'nullable',
                'date',
                'required_with:calificaciones_hasta',
            ],

            'calificaciones_hasta' => [
                'nullable',
                'date',
                'required_with:calificaciones_desde',
                'after_or_equal:calificaciones_desde',
            ],

            'estado' => [
                'required',

                Rule::in([
                    'planificado',
                    'matricula_abierta',
                    'en_curso',
                    'finalizado',
                    'cancelado',
                ]),
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' =>
                'El nombre del período es obligatorio.',

            'nombre.max' =>
                'El nombre no puede superar los 100 caracteres.',

            'fecha_inicio.required' =>
                'Debe indicar la fecha de inicio del período.',

            'fecha_fin.required' =>
                'Debe indicar la fecha de finalización del período.',

            'fecha_fin.after_or_equal' =>
                'La fecha de finalización no puede ser anterior a la fecha de inicio.',

            'fecha_inicio_matricula.required' =>
                'Debe indicar cuándo inicia la matrícula.',

            'fecha_fin_matricula.required' =>
                'Debe indicar cuándo finaliza la matrícula.',

            'fecha_fin_matricula.after_or_equal' =>
                'La fecha de cierre de matrícula no puede ser anterior a su fecha de inicio.',

            'fecha_fin_matricula.before_or_equal' =>
                'La matrícula no puede cerrar después de finalizar el período académico.',

            'calificaciones_desde.required_with' =>
                'Debe indicar la fecha y hora de inicio de la carga de calificaciones.',

            'calificaciones_desde.date' =>
                'La fecha y hora de inicio de la carga de calificaciones no es válida.',

            'calificaciones_hasta.required_with' =>
                'Debe indicar la fecha y hora límite de la carga de calificaciones.',

            'calificaciones_hasta.date' =>
                'La fecha y hora límite de la carga de calificaciones no es válida.',

            'calificaciones_hasta.after_or_equal' =>
                'La fecha y hora límite de carga de calificaciones no puede ser anterior a la fecha y hora de inicio.',

            'estado.required' =>
                'Debe seleccionar el estado del período.',

            'estado.in' =>
                'El estado seleccionado no es válido.',

            'observaciones.max' =>
                'Las observaciones no pueden superar los 2,000 caracteres.',
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

        return $value === ''
            ? null
            : $value;
    }
}