<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGrupoHorarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'dia_semana' => mb_strtolower(
                trim((string) $this->input('dia_semana'))
            ),
        ]);
    }

    public function rules(): array
    {
        $grupo = $this->route('grupo');

        $grupoHorario =
            $this->route('grupoHorario');

        return [
            'dia_semana' => [
                'required',
                'string',
                'max:15',
                Rule::in([
                    'lunes',
                    'martes',
                    'miércoles',
                    'jueves',
                    'viernes',
                    'sábado',
                    'domingo',
                ]),
            ],

            'horario_id' => [
                'required',
                'integer',

                Rule::exists('horarios', 'id')
                    ->where(
                        fn ($query) =>
                            $query->where(function ($subquery) use ($grupoHorario) {
                                $subquery
                                    ->where('activo', true)
                                    ->orWhere(
                                        'id',
                                        $grupoHorario?->horario_id
                                    );
                            })
                    ),

                Rule::unique(
                    'grupo_horarios',
                    'horario_id'
                )
                    ->ignore($grupoHorario?->id)
                    ->where(
                        fn ($query) =>
                            $query
                                ->where(
                                    'grupo_id',
                                    $grupo?->id
                                )
                                ->where(
                                    'dia_semana',
                                    $this->input('dia_semana')
                                )
                    ),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'dia_semana.required' =>
                'Debe seleccionar el día de clase.',

            'dia_semana.in' =>
                'El día seleccionado no es válido.',

            'horario_id.required' =>
                'Debe seleccionar un horario.',

            'horario_id.exists' =>
                'El horario seleccionado no está disponible.',

            'horario_id.unique' =>
                'Este horario ya está asignado al grupo para el día seleccionado.',
        ];
    }
}