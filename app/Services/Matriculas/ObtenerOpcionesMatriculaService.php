<?php

namespace App\Services\Matriculas;

use App\Models\Estudiante;
use App\Models\Grupo;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\PeriodoAcademico;
use RuntimeException;

class ObtenerOpcionesMatriculaService
{
    public function ejecutar(
        Estudiante $estudiante
    ): array {
        /*
        |--------------------------------------------------------------------------
        | 1. Validar estado del estudiante
        |--------------------------------------------------------------------------
        */

        if ($estudiante->estado !== 'activo') {
            throw new RuntimeException(
                'Tu expediente de estudiante no se encuentra habilitado para realizar una matrícula.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Validar nivel autorizado
        |--------------------------------------------------------------------------
        */

        if (!$estudiante->nivel_autorizado_id) {
            throw new RuntimeException(
                'Aún no tienes un nivel académico autorizado para continuar con la matrícula.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Obtener período con matrícula abierta
        |--------------------------------------------------------------------------
        */

        $periodos = PeriodoAcademico::query()
            ->where(
                'estado',
                'matricula_abierta'
            )
            ->whereDate(
                'fecha_inicio_matricula',
                '<=',
                now()->toDateString()
            )
            ->whereDate(
                'fecha_fin_matricula',
                '>=',
                now()->toDateString()
            )
            ->get();

        if ($periodos->isEmpty()) {
            throw new RuntimeException(
                'Actualmente no hay un período disponible para realizar matrículas.'
            );
        }

        if ($periodos->count() > 1) {
            throw new RuntimeException(
                'Actualmente existe más de un período habilitado para matrícula. Comunícate con administración para continuar.'
            );
        }

        /** @var PeriodoAcademico $periodo */
        $periodo = $periodos->first();

        /*
        |--------------------------------------------------------------------------
        | 4. Buscar matrícula activa del estudiante
        |--------------------------------------------------------------------------
        */

        $matriculaActiva = Matricula::query()
            ->where(
                'estudiante_id',
                $estudiante->id
            )
            ->where(
                'estado',
                'activa'
            )
            ->whereHas(
                'grupo',
                function ($query) use ($periodo): void {
                    $query->where(
                        'periodo_academico_id',
                        $periodo->id
                    );
                }
            )
            ->with([
                'grupo.nivel.programa',
                'grupo.periodoAcademico',
                'grupo.horarios.horario',
                'grupo.docentes.docente.empleado.persona',
            ])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | 5. Si ya tiene matrícula, obtener grupos compatibles para cambio
        |--------------------------------------------------------------------------
        */

        if ($matriculaActiva) {
            $gruposCambio = Grupo::query()
                ->where(
                    'nivel_id',
                    $matriculaActiva
                        ->grupo
                        ->nivel_id
                )
                ->where(
                    'periodo_academico_id',
                    $matriculaActiva
                        ->grupo
                        ->periodo_academico_id
                )
                ->where(
                    'estado',
                    'activo'
                )
                ->with([
                    'nivel.programa',
                    'periodoAcademico',
                    'horarios.horario',
                    'docentes.docente.empleado.persona',
                ])
                ->withCount([
                    'matriculas as matriculas_activas_count' =>
                        function ($query): void {
                            $query->where(
                                'estado',
                                'activa'
                            );
                        },
                ])
                ->get()
                ->filter(
                    function (Grupo $grupo) use (
                        $matriculaActiva
                    ): bool {
                        /*
                         * El grupo actual siempre debe mostrarse,
                         * aunque esté lleno, porque queremos marcarlo
                         * como "Clase matriculada".
                         */
                        if (
                            $grupo->id
                            ===
                            $matriculaActiva->grupo_id
                        ) {
                            return true;
                        }

                        return
                            $grupo->matriculas_activas_count
                            < $grupo->cupo_maximo;
                    }
                )
                ->values();

            return [
                'periodo' =>
                    $periodo,

                'pago' =>
                    null,

                'grupos' =>
                    collect(),

                'matriculaActiva' =>
                    $matriculaActiva,

                'gruposCambio' =>
                    $gruposCambio,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Validar pago aprobado
        |--------------------------------------------------------------------------
        |
        | Para la primera matrícula se utiliza el pago que fue
        | aprobado durante la Solicitud de Inscripción.
        |
        */

        $pago = Pago::query()
            ->where(
                'estudiante_id',
                $estudiante->id
            )
            ->where(
                'periodo_academico_id',
                $periodo->id
            )
            ->where(
                'estado',
                'aprobado'
            )
            ->whereNull(
                'matricula_id'
            )
            ->orderBy('id')
            ->first();

        if (!$pago) {
            throw new RuntimeException(
                'No encontramos un pago aprobado disponible para completar tu matrícula.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Obtener grupos compatibles para primera matrícula
        |--------------------------------------------------------------------------
        */

        $grupos = Grupo::query()
            ->where(
                'nivel_id',
                $estudiante->nivel_autorizado_id
            )
            ->where(
                'periodo_academico_id',
                $periodo->id
            )
            ->where(
                'estado',
                'activo'
            )
            ->with([
                'nivel.programa',
                'periodoAcademico',
                'horarios.horario',
                'docentes.docente.empleado.persona',
            ])
            ->withCount([
                'matriculas as matriculas_activas_count' =>
                    function ($query): void {
                        $query->where(
                            'estado',
                            'activa'
                        );
                    },
            ])
            ->get()
            ->filter(
                fn (Grupo $grupo): bool =>
                    $grupo->matriculas_activas_count
                    < $grupo->cupo_maximo
            )
            ->values();

        if ($grupos->isEmpty()) {
            throw new RuntimeException(
                'Actualmente no hay grupos con cupo disponible para tu nivel autorizado.'
            );
        }

        return [
            'periodo' =>
                $periodo,

            'pago' =>
                $pago,

            'grupos' =>
                $grupos,

            'matriculaActiva' =>
                null,

            'gruposCambio' =>
                collect(),
        ];
    }
}