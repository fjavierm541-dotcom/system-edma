<?php

namespace App\Services\Calificaciones;

use App\Models\CalificacionFinal;
use App\Models\HistorialCalificacion;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RectificarCalificacionService
{
    public function ejecutar(
        CalificacionFinal $calificacion,
        array $datos,
        int $usuarioId
    ): CalificacionFinal {
        /*
        |--------------------------------------------------------------------------
        | Solo calificaciones bloqueadas
        |--------------------------------------------------------------------------
        */

        if ($calificacion->estado !== 'bloqueada') {
            throw new RuntimeException(
                'Únicamente pueden rectificarse calificaciones que ya se encuentran bloqueadas.'
            );
        }

        $calificacion->loadMissing([
            'matricula.grupo.nivel',
        ]);

        $nivel =
            $calificacion
                ->matricula
                ?->grupo
                ?->nivel;

        if (!$nivel) {
            throw new RuntimeException(
                'No fue posible determinar el nivel académico asociado a esta calificación.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Determinar nueva nota y resultado
        |--------------------------------------------------------------------------
        */

        $tipoResultado =
            $datos['tipo_resultado'];

        $notaNueva = null;
        $resultadoNuevo = null;

        if ($tipoResultado === 'normal') {

            if (
                !array_key_exists(
                    'nota_final',
                    $datos
                )
                ||
                $datos['nota_final'] === null
                ||
                $datos['nota_final'] === ''
            ) {
                throw new RuntimeException(
                    'Debes ingresar la nueva nota final.'
                );
            }

            $notaNueva =
                (float) $datos['nota_final'];

            $notaMinima =
                (float)
                $nivel
                    ->nota_minima_aprobacion;

            $resultadoNuevo =
                $notaNueva >= $notaMinima
                    ? 'aprobado'
                    : 'reprobado';

        } elseif (
            $tipoResultado === 'incompleto'
        ) {

            $notaNueva = null;

            $resultadoNuevo =
                'incompleto';

        } elseif (
            $tipoResultado === 'retirado'
        ) {

            $notaNueva = null;

            $resultadoNuevo =
                'retirado';
        }

        /*
        |--------------------------------------------------------------------------
        | Evitar rectificación sin cambio real
        |--------------------------------------------------------------------------
        */

        $notaActual =
            is_null($calificacion->nota_final)
                ? null
                : (float) $calificacion->nota_final;

        if (
            $notaActual === $notaNueva
            &&
            $calificacion->resultado
                ===
                $resultadoNuevo
        ) {
            throw new RuntimeException(
                'La rectificación no contiene ningún cambio respecto a la calificación actual.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Rectificación transaccional
        |--------------------------------------------------------------------------
        */

        return DB::transaction(
            function () use (
                $calificacion,
                $notaNueva,
                $resultadoNuevo,
                $datos,
                $usuarioId
            ): CalificacionFinal {

                /*
                |--------------------------------------------------------------------------
                | Bloqueo del registro
                |--------------------------------------------------------------------------
                */

                $calificacionBloqueada =
                    CalificacionFinal::query()
                        ->whereKey(
                            $calificacion->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();

                if (
                    $calificacionBloqueada->estado
                    !== 'bloqueada'
                ) {
                    throw new RuntimeException(
                        'La calificación ya no se encuentra disponible para rectificación.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Guardar historial ANTES de modificar
                |--------------------------------------------------------------------------
                */

                HistorialCalificacion::create([
                    'calificacion_final_id' =>
                        $calificacionBloqueada->id,

                    'nota_anterior' =>
                        $calificacionBloqueada
                            ->nota_final,

                    'nota_nueva' =>
                        $notaNueva,

                    'resultado_anterior' =>
                        $calificacionBloqueada
                            ->resultado,

                    'resultado_nuevo' =>
                        $resultadoNuevo,

                    'observacion_anterior' =>
                        $calificacionBloqueada
                            ->observacion_docente,

                    'observacion_nueva' =>
                        $calificacionBloqueada
                            ->observacion_docente,

                    'motivo' =>
                        trim(
                            $datos['motivo']
                        ),

                    'cambiado_por' =>
                        $usuarioId,

                    'cambiado_at' =>
                        now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Actualizar calificación oficial
                |--------------------------------------------------------------------------
                |
                | IMPORTANTE:
                | Permanece bloqueada.
                |
                */

                $calificacionBloqueada->update([
                    'nota_final' =>
                        $notaNueva,

                    'resultado' =>
                        $resultadoNuevo,

                    'estado' =>
                        'bloqueada',
                ]);

                return $calificacionBloqueada
                    ->fresh();
            }
        );
    }
}