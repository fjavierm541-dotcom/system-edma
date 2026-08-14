<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGrupoDocenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tipo_asignacion' => 'principal',

            'observaciones' =>
                $this->normalizeNullableText(
                    $this->input('observaciones')
                ),
        ]);
    }

    public function rules(): array
    {
        $grupo = $this->route('grupo');

        $grupoDocente =
            $this->route('grupoDocente');

        return [
            'docente_id' => [
            'required',
            'integer',

            Rule::exists(
                'docentes',
                'id'
            )->whereNull('deleted_at'),

            Rule::unique(
                'grupo_docentes',
                'docente_id'
            )
                ->ignore(
                    $grupoDocente?->id
                )
                ->where(
                    fn ($query) =>
                        $query->where(
                            'grupo_id',
                            $grupo?->id
                        )
                ),
        ],

            'tipo_asignacion' => [
                'required',

                Rule::in([
                    'principal',
                ]),

                Rule::unique(
                    'grupo_docentes',
                    'tipo_asignacion'
                )
                    ->ignore(
                        $grupoDocente?->id
                    )
                    ->where(
                        fn ($query) =>
                            $query
                                ->where(
                                    'grupo_id',
                                    $grupo?->id
                                )
                                ->where(
                                    'activo',
                                    true
                                )
                    ),
            ],

            'fecha_inicio' => [
                'required',
                'date',
                'after_or_equal:' .
                    $grupo?->fecha_inicio?->format('Y-m-d'),
                'before_or_equal:' .
                    $grupo?->fecha_fin?->format('Y-m-d'),
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio',
                'before_or_equal:' .
                    $grupo?->fecha_fin?->format('Y-m-d'),
            ],

            'activo' => [
                'required',
                'boolean',
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
            'docente_id.required' =>
                'Debe seleccionar un docente.',

            'docente_id.exists' =>
                'El docente seleccionado no existe.',

            'docente_id.unique' =>
            'Este docente ya tiene otra asignación registrada en este grupo.',

            'tipo_asignacion.unique' =>
                'Este grupo ya tiene otro docente principal activo.',

            'fecha_inicio.required' =>
                'Debe indicar la fecha de inicio de la asignación.',

            'fecha_inicio.after_or_equal' =>
                'La asignación docente no puede iniciar antes que el grupo.',

            'fecha_inicio.before_or_equal' =>
                'La asignación docente no puede iniciar después de finalizar el grupo.',

            'fecha_fin.after_or_equal' =>
                'La fecha de finalización no puede ser anterior a la fecha de inicio.',

            'fecha_fin.before_or_equal' =>
                'La asignación docente no puede finalizar después que el grupo.',

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