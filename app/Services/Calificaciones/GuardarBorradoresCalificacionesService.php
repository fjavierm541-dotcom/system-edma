<?php

namespace App\Services\Calificaciones;

use App\Models\CalificacionFinal;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Matricula;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class GuardarBorradoresCalificacionesService
{
    public function ejecutar(
        Docente $docente,
        Grupo $grupo,
        array $datos,
        int $usuarioId
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Validar asignación del docente
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
                'No tienes autorización para gestionar las calificaciones de este grupo.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validar ventana de carga
        |--------------------------------------------------------------------------
        */

        $periodo =
            $grupo->periodoAcademico;

        if (
            !$periodo->calificaciones_desde
            ||
            !$periodo->calificaciones_hasta
        ) {
            throw new RuntimeException(
                'La carga de calificaciones todavía no se encuentra habilitada para este período.'
            );
        }

        $ahora =
            now();

        if (
            !$ahora->betweenIncluded(
                $periodo->calificaciones_desde,
                $periodo->calificaciones_hasta
            )
        ) {
            throw new RuntimeException(
                'La ventana para registrar calificaciones no se encuentra habilitada en este momento.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Guardar calificaciones
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $grupo,
                $datos,
                $usuarioId
            ): void {
                foreach (
                    $datos['calificaciones']
                    as $fila
                ) {
                    $matricula =
                        Matricula::query()
                            ->where(
                                'id',
                                $fila['matricula_id']
                            )
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
                                'grupo.nivel'
                            )
                            ->first();

                    if (!$matricula) {
                        throw new RuntimeException(
                            'Una de las matrículas recibidas no pertenece al grupo o ya no se encuentra habilitada.'
                        );
                    }

                    $calificacion =
                        CalificacionFinal::query()
                            ->where(
                                'matricula_id',
                                $matricula->id
                            )
                            ->first();

                    /*
                     * Una calificación confirmada o
                     * bloqueada ya no se modifica
                     * desde este flujo.
                     */
                    if (
                        $calificacion
                        &&
                        in_array(
                            $calificacion->estado,
                            [
                                'confirmada',
                                'bloqueada',
                            ],
                            true
                        )
                    ) {
                        continue;
                    }

                    $tipoResultado =
                        $fila[
                            'tipo_resultado'
                        ];

                    $notaFinal =
                        $fila[
                            'nota_final'
                        ] ?? null;

                    /*
                    |--------------------------------------------------------------------------
                    | Resultado ordinario
                    |--------------------------------------------------------------------------
                    |
                    | El mínimo se obtiene del nivel,
                    | nunca se fija en 70 dentro del código.
                    |
                    */

                    if (
                        $tipoResultado
                        === 'normal'
                    ) {
                        if (
                            $notaFinal === null
                            ||
                            $notaFinal === ''
                        ) {
                            throw new RuntimeException(
                                'Debes ingresar una nota final para los estudiantes con resultado ordinario.'
                            );
                        }

                        $notaFinal =
                            (float) $notaFinal;

                        $notaMinima =
                            (float)
                            $matricula
                                ->grupo
                                ->nivel
                                ->nota_minima_aprobacion;

                        $resultado =
                            $notaFinal
                            >=
                            $notaMinima
                                ? 'aprobado'
                                : 'reprobado';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | No se presentó
                    |--------------------------------------------------------------------------
                    */

                    elseif (
                        $tipoResultado
                        === 'incompleto'
                    ) {
                        $notaFinal =
                            null;

                        $resultado =
                            'incompleto';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Abandono
                    |--------------------------------------------------------------------------
                    */

                    else {
                        $notaFinal =
                            null;

                        $resultado =
                            'retirado';
                    }

                    CalificacionFinal::updateOrCreate(
                        [
                            'matricula_id' =>
                                $matricula->id,
                        ],
                        [
                            'nota_final' =>
                                $notaFinal,

                            'resultado' =>
                                $resultado,

                           

                            'estado' =>
                                'borrador',

                            'registrada_por' =>
                                $calificacion
                                    ?->registrada_por
                                ?? $usuarioId,

                            'registrada_at' =>
                                $calificacion
                                    ?->registrada_at
                                ?? now(),
                        ]
                    );
                }
            }
        );
    }
}