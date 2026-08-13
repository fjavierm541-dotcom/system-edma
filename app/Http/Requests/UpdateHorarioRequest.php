<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHorarioRequest extends FormRequest
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

            'zona_horaria' => $this->normalizeNullableText(
                $this->input('zona_horaria')
            ),

            'activo' => $this->boolean('activo'),
        ]);
    }

    public function rules(): array
    {
        $horario = $this->route('horario');

        return [
            'nombre' => [
                'required',
                'string',
                'max:100',

                Rule::unique('horarios', 'nombre')
                    ->ignore($horario?->id),
            ],

            'hora_inicio' => [
                'required',
                'date_format:H:i',
            ],

            'hora_fin' => [
                'required',
                'date_format:H:i',
                'after:hora_inicio',
            ],

            'zona_horaria' => [
                'required',
                'string',
                'max:50',
            ],

            'activo' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' =>
                'El nombre del horario es obligatorio.',

            'nombre.max' =>
                'El nombre no puede superar los 100 caracteres.',

            'nombre.unique' =>
                'Ya existe otro horario con este nombre.',

            'hora_inicio.required' =>
                'Debe indicar la hora de inicio.',

            'hora_inicio.date_format' =>
                'La hora de inicio no tiene un formato válido.',

            'hora_fin.required' =>
                'Debe indicar la hora de finalización.',

            'hora_fin.date_format' =>
                'La hora de finalización no tiene un formato válido.',

            'hora_fin.after' =>
                'La hora de finalización debe ser posterior a la hora de inicio.',

            'zona_horaria.required' =>
                'Debe indicar la zona horaria.',

            'zona_horaria.max' =>
                'La zona horaria no puede superar los 50 caracteres.',
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