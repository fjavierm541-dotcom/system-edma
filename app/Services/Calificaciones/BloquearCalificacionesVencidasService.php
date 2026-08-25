<?php

namespace App\Services\Calificaciones;

use App\Models\CalificacionFinal;
use Illuminate\Support\Facades\DB;

class BloquearCalificacionesVencidasService
{
    public function ejecutar(): int
    {
        /*
        |--------------------------------------------------------------------------
        | Bloquear calificaciones confirmadas cuya ventana ya terminó
        |--------------------------------------------------------------------------
        |
        | Solo se bloquean:
        |
        | - calificaciones en estado confirmada;
        | - pertenecientes a una matrícula;
        | - cuyo grupo pertenece a un período;
        | - donde calificaciones_hasta ya venció.
        |
        */

        return DB::transaction(
            function (): int {

                $calificaciones =
                    CalificacionFinal::query()
                        ->where(
                            'estado',
                            'confirmada'
                        )
                        ->whereHas(
                            'matricula.grupo.periodoAcademico',
                            function ($query): void {
                                $query
                                    ->whereNotNull(
                                        'calificaciones_hasta'
                                    )
                                    ->where(
                                        'calificaciones_hasta',
                                        '<',
                                        now()
                                    );
                            }
                        )
                        ->lockForUpdate()
                        ->get();

                $cantidadBloqueadas = 0;

                foreach (
                    $calificaciones
                    as $calificacion
                ) {
                    $calificacion->update([
                        'estado' =>
                            'bloqueada',

                        'bloqueada_at' =>
                            now(),
                    ]);

                    $cantidadBloqueadas++;
                }

                return $cantidadBloqueadas;
            }
        );
    }
}