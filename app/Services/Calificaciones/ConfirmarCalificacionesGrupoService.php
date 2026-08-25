<?php

namespace App\Services\Calificaciones;

use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Matricula;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ConfirmarCalificacionesGrupoService
{
    public function ejecutar(
        Docente $docente,
        Grupo $grupo
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Validar asignación docente
        |--------------------------------------------------------------------------
        */

        $asignado =
            $grupo
                ->docentes()
                ->where(
                    'docente_id',
                    $docente->id
                )
                ->where(
                    'activo',
                    true
                )
                ->exists();

        if (!$asignado) {
            throw new RuntimeException(
                'No tienes autorización para confirmar las calificaciones de este grupo.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validar ventana de carga
        |--------------------------------------------------------------------------
        */

        $grupo->loadMissing(
            'periodoAcademico'
        );

        $periodo =
            $grupo->periodoAcademico;

        if (
            !$periodo->calificaciones_desde
            ||
            !$periodo->calificaciones_hasta
        ) {
            throw new RuntimeException(
                'La carga de calificaciones no se encuentra habilitada para este período.'
            );
        }

        if (
            !now()->betweenIncluded(
                $periodo->calificaciones_desde,
                $periodo->calificaciones_hasta
            )
        ) {
            throw new RuntimeException(
                'La ventana para confirmar calificaciones no se encuentra habilitada en este momento.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmación transaccional
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $grupo
            ): void {

                $matriculas =
                    Matricula::query()
                        ->where(
                            'grupo_id',
                            $grupo->id
                        )
                        ->whereIn(
                            'estado',
                            [
                                'pendiente',
                                'activa',
                            ]
                        )
                        ->with(
                            'calificacionFinal'
                        )
                        ->lockForUpdate()
                        ->get();

                if ($matriculas->isEmpty()) {
                    throw new RuntimeException(
                        'Este grupo no tiene estudiantes matriculados para confirmar.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Todos deben tener calificación
                |--------------------------------------------------------------------------
                */

                foreach (
                    $matriculas
                    as $matricula
                ) {
                    $calificacion =
                        $matricula
                            ->calificacionFinal;

                    if (!$calificacion) {
                        throw new RuntimeException(
                            'Todos los estudiantes deben tener una calificación registrada antes de confirmar.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | No permitir confirmación si existe un registro bloqueado
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $calificacion->estado
                        === 'bloqueada'
                    ) {
                        throw new RuntimeException(
                            'El grupo contiene calificaciones que ya se encuentran bloqueadas.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Validar resultado
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !in_array(
                            $calificacion->resultado,
                            [
                                'aprobado',
                                'reprobado',
                                'incompleto',
                                'retirado',
                            ],
                            true
                        )
                    ) {
                        throw new RuntimeException(
                            'Existe una calificación con un resultado académico no válido.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | APR / REP requieren nota
                    |--------------------------------------------------------------------------
                    */

                    if (
                        in_array(
                            $calificacion->resultado,
                            [
                                'aprobado',
                                'reprobado',
                            ],
                            true
                        )
                        &&
                        is_null(
                            $calificacion
                                ->nota_final
                        )
                    ) {
                        throw new RuntimeException(
                            'Las calificaciones ordinarias deben tener una nota final antes de confirmar.'
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Confirmar
                |--------------------------------------------------------------------------
                */

                foreach (
                    $matriculas
                    as $matricula
                ) {
                    $calificacion =
                        $matricula
                            ->calificacionFinal;

                    if (
                        $calificacion->estado
                        === 'confirmada'
                    ) {
                        continue;
                    }

                    $calificacion->update([
                        'estado' =>
                            'confirmada',

                        'confirmada_at' =>
                            now(),
                    ]);
                }
            }
        );
    }
}