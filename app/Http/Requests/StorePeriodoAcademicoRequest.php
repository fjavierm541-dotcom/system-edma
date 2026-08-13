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
            'nombre' => $this->normalizeNullableText(
                $this->input('nombre')
            ),

            'estado' => $this->normalizeNullableText(
                $this->input('estado')
            ),

            'observaciones' => $this->normalizeNullableText(
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

        return $value === '' ? null : $value;
    }
}