<?php

namespace App\Http\Requests;

use App\Models\Nivel;
use App\Models\PeriodoAcademico;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateGrupoRequest extends FormRequest
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

            'observaciones' =>
                $this->normalizeNullableText(
                    $this->input('observaciones')
                ),

            'estado' =>
                $this->normalizeNullableText(
                    $this->input('estado')
                ),

            'modalidad' => 'virtual',
            'cupo_minimo' => 3,
            'cupo_maximo' => 25,
        ]);
    }

    public function rules(): array
    {
        return [
            'nivel_id' => [
                'required',
                'integer',

                Rule::exists(
                    'niveles',
                    'id'
                )->whereNull('deleted_at'),
            ],

            'periodo_academico_id' => [
                'required',
                'integer',

                Rule::exists(
                    'periodos_academicos',
                    'id'
                )->whereNull('deleted_at'),
            ],

            'nombre' => [
                'required',
                'string',
                'max:120',
            ],

            'modalidad' => [
                'required',
                Rule::in([
                    'virtual',
                ]),
            ],

            'cupo_minimo' => [
                'required',
                'integer',
                Rule::in([3]),
            ],

            'cupo_maximo' => [
                'required',
                'integer',
                Rule::in([25]),
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

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $this->validarProgramaDelNivel(
                    $validator
                );

                $this->validarFechasDelPeriodo(
                    $validator
                );
            },
        ];
    }

    private function validarProgramaDelNivel(
        Validator $validator
    ): void {
        $nivel = Nivel::query()
            ->with('programa')
            ->find($this->input('nivel_id'));

        if (!$nivel) {
            return;
        }

        if (!$nivel->programa) {
            $validator->errors()->add(
                'nivel_id',
                'No fue posible identificar el programa correspondiente al nivel seleccionado.'
            );
        }
    }

    private function validarFechasDelPeriodo(
        Validator $validator
    ): void {
        $periodo = PeriodoAcademico::query()
            ->find(
                $this->input(
                    'periodo_academico_id'
                )
            );

        if (!$periodo) {
            return;
        }

        $inicioGrupo = $this->date(
            'fecha_inicio'
        );

        $finGrupo = $this->date(
            'fecha_fin'
        );

        if (
            $inicioGrupo &&
            $periodo->fecha_inicio &&
            $inicioGrupo->lt(
                $periodo->fecha_inicio
            )
        ) {
            $validator->errors()->add(
                'fecha_inicio',
                'La fecha de inicio del grupo no puede ser anterior al inicio del período académico.'
            );
        }

        if (
            $finGrupo &&
            $periodo->fecha_fin &&
            $finGrupo->gt(
                $periodo->fecha_fin
            )
        ) {
            $validator->errors()->add(
                'fecha_fin',
                'La fecha de finalización del grupo no puede superar la fecha de finalización del período académico.'
            );
        }
    }

    public function messages(): array
    {
        return [
            'nivel_id.required' =>
                'Debe seleccionar el nivel del grupo.',

            'nivel_id.exists' =>
                'El nivel seleccionado no existe.',

            'periodo_academico_id.required' =>
                'Debe seleccionar el período académico.',

            'periodo_academico_id.exists' =>
                'El período seleccionado no existe.',

            'nombre.required' =>
                'El nombre del grupo es obligatorio.',

            'nombre.max' =>
                'El nombre no puede superar los 120 caracteres.',

            'fecha_inicio.required' =>
                'Debe indicar la fecha de inicio del grupo.',

            'fecha_fin.required' =>
                'Debe indicar la fecha de finalización del grupo.',

            'fecha_fin.after_or_equal' =>
                'La fecha de finalización no puede ser anterior a la fecha de inicio.',

            'estado.required' =>
                'Debe seleccionar el estado del grupo.',

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