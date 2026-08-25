<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarCalificacionesGrupoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()
            && $this->user()->tieneRol('Docente');
    }

    public function rules(): array
    {
        return [
            'calificaciones' =>
                [
                    'required',
                    'array',
                ],

            'calificaciones.*.matricula_id' =>
                [
                    'required',
                    'integer',
                    'exists:matriculas,id',
                ],

            'calificaciones.*.tipo_resultado' =>
                [
                    'required',
                    'in:normal,incompleto,retirado',
                ],

            'calificaciones.*.nota_final' =>
                [
                    'nullable',
                    'numeric',
                    'min:0',
                    'max:100',
                ],
        ];
    }

    public function messages(): array
    {
        return [
            'calificaciones.required' =>
                'No se recibieron calificaciones para guardar.',

            'calificaciones.*.matricula_id.required' =>
                'No se pudo identificar una de las matrículas.',

            'calificaciones.*.tipo_resultado.required' =>
                'Selecciona el tipo de resultado.',

            'calificaciones.*.tipo_resultado.in' =>
                'El tipo de resultado seleccionado no es válido.',

            'calificaciones.*.nota_final.numeric' =>
                'La nota final debe ser un valor numérico.',

            'calificaciones.*.nota_final.min' =>
                'La nota final no puede ser menor que 0.',

            'calificaciones.*.nota_final.max' =>
                'La nota final no puede ser mayor que 100.',

        ];
    }
}