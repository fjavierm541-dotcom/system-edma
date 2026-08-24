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

            /*
            |--------------------------------------------------------------------------
            | Valores institucionales
            |--------------------------------------------------------------------------
            */

            'modalidad' => 'virtual',

            'cupo_minimo' => 3,

            'cupo_maximo' => 25,
        ]);
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Nivel
            |--------------------------------------------------------------------------
            */

            'nivel_id' => [
                'required',
                'integer',

                Rule::exists(
                    'niveles',
                    'id'
                )->where(
                    fn ($query) =>
                        $query
                            ->where(
                                'estado',
                                'activo'
                            )
                            ->whereNull(
                                'deleted_at'
                            )
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Período académico
            |--------------------------------------------------------------------------
            */

            'periodo_academico_id' => [
                'required',
                'integer',

                Rule::exists(
                    'periodos_academicos',
                    'id'
                )->where(
                    fn ($query) =>
                        $query
                            ->whereIn(
                                'estado',
                                [
                                    'planificado',
                                    'matricula_abierta',
                                    'en_curso',
                                ]
                            )
                            ->whereNull(
                                'deleted_at'
                            )
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Información del grupo
            |--------------------------------------------------------------------------
            */

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

                Rule::in([
                    3,
                ]),
            ],

            'cupo_maximo' => [
                'required',
                'integer',

                Rule::in([
                    25,
                ]),
            ],

            /*
            |--------------------------------------------------------------------------
            | Fechas
            |--------------------------------------------------------------------------
            */

            'fecha_inicio' => [
                'required',
                'date',
            ],

            'fecha_fin' => [
                'required',
                'date',
                'after_or_equal:fecha_inicio',
            ],

            /*
            |--------------------------------------------------------------------------
            | Estado
            |--------------------------------------------------------------------------
            */

            'estado' => [
                'required',

                Rule::in([
                    'planificado',
                    'activo',
                    'finalizado',
                    'cancelado',
                ]),
            ],

            /*
            |--------------------------------------------------------------------------
            | Observaciones
            |--------------------------------------------------------------------------
            */

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
            function (Validator $validator): void {

                $this->validarProgramaDelNivel(
                    $validator
                );

                $this->validarFechasDelPeriodo(
                    $validator
                );
            },
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validar programa del nivel
    |--------------------------------------------------------------------------
    */

    private function validarProgramaDelNivel(
        Validator $validator
    ): void {
        $nivel = Nivel::query()
            ->with('programa')
            ->find(
                $this->input(
                    'nivel_id'
                )
            );

        if (!$nivel) {
            return;
        }

        if (
            !$nivel->programa ||
            $nivel->programa->estado !== 'activo'
        ) {
            $validator->errors()->add(
                'nivel_id',
                'El programa correspondiente a este nivel no se encuentra disponible.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validar fechas dentro del período
    |--------------------------------------------------------------------------
    */

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

        $inicioGrupo =
            $this->date(
                'fecha_inicio'
            );

        $finGrupo =
            $this->date(
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

    /*
    |--------------------------------------------------------------------------
    | Mensajes
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [
            'nivel_id.required' =>
                'Debe seleccionar el nivel del grupo.',

            'nivel_id.exists' =>
                'El nivel seleccionado no existe o no se encuentra disponible.',

            'periodo_academico_id.required' =>
                'Debe seleccionar el período académico.',

            'periodo_academico_id.exists' =>
                'El período seleccionado no se encuentra disponible para gestionar grupos.',

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

    /*
    |--------------------------------------------------------------------------
    | Normalización
    |--------------------------------------------------------------------------
    */

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