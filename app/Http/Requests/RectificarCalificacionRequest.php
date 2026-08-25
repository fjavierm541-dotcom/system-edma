<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RectificarCalificacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()
            && $this->user()->tieneRol('Administrador');
    }

    public function rules(): array
    {
        return [
            'tipo_resultado' => [
                'required',
                'in:normal,incompleto,retirado',
            ],

            'nota_final' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],

            'motivo' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_resultado.required' =>
                'Selecciona el resultado de la rectificación.',

            'tipo_resultado.in' =>
                'El resultado seleccionado no es válido.',

            'nota_final.integer' =>
    'La nueva nota debe ser un número entero.',

            'nota_final.min' =>
                'La nueva nota no puede ser menor que 0.',

            'nota_final.max' =>
                'La nueva nota no puede ser mayor que 100.',

            'motivo.required' =>
                'Debes indicar el motivo de la rectificación.',

            'motivo.min' =>
                'Explica el motivo de la rectificación con mayor detalle.',

            'motivo.max' =>
                'El motivo no debe superar los 1000 caracteres.',
        ];
    }
}