<?php

namespace App\Services\Matriculas;

use App\Models\Estudiante;
use App\Models\Grupo;
use App\Models\HistorialCambioGrupoMatricula;
use App\Models\Matricula;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CambiarGrupoMatriculaService
{
    public function ejecutar(
    Estudiante $estudiante,
    int $grupoNuevoId,
    int $usuarioId
): Matricula{

        return DB::transaction(
            function () use (
                $estudiante,
                $grupoNuevoId,
                $usuarioId,
                
            ): Matricula {

                $matricula = Matricula::query()
                    ->where(
                        'estudiante_id',
                        $estudiante->id
                    )
                    ->where(
                        'estado',
                        'activa'
                    )
                    ->with(
                        'grupo.periodoAcademico'
                    )
                    ->lockForUpdate()
                    ->first();

                if (!$matricula) {
                    throw new RuntimeException(
                        'No tienes una matrícula activa para realizar un cambio de grupo.'
                    );
                }

                $grupoAnterior =
                    $matricula->grupo;

                if (
                    $grupoAnterior->id
                    === $grupoNuevoId
                ) {
                    throw new RuntimeException(
                        'Ya estás matriculado en este grupo.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | El cambio solo se permite mientras el período acepte matrículas
                |--------------------------------------------------------------------------
                */

                if (
                    $grupoAnterior
                        ->periodoAcademico
                        ->estado
                    !== 'matricula_abierta'
                ) {
                    throw new RuntimeException(
                        'El período ya no permite cambios de grupo.'
                    );
                }

                $grupoNuevo = Grupo::query()
                    ->whereKey($grupoNuevoId)
                    ->lockForUpdate()
                    ->first();

                if (!$grupoNuevo) {
                    throw new RuntimeException(
                        'El grupo seleccionado ya no se encuentra disponible.'
                    );
                }

                if (
                    $grupoNuevo->estado
                    !== 'activo'
                ) {
                    throw new RuntimeException(
                        'El grupo seleccionado no se encuentra disponible.'
                    );
                }

                if (
                    $grupoNuevo->nivel_id
                    !== $grupoAnterior->nivel_id
                ) {
                    throw new RuntimeException(
                        'El grupo seleccionado no corresponde a tu nivel actual.'
                    );
                }

                if (
                    $grupoNuevo
                        ->periodo_academico_id
                    !==
                    $grupoAnterior
                        ->periodo_academico_id
                ) {
                    throw new RuntimeException(
                        'El grupo seleccionado no corresponde al período de tu matrícula.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Revisar cupo real nuevamente
                |--------------------------------------------------------------------------
                */

                $ocupados = Matricula::query()
                    ->where(
                        'grupo_id',
                        $grupoNuevo->id
                    )
                    ->where(
                        'estado',
                        'activa'
                    )
                    ->count();

                if (
                    $ocupados
                    >= $grupoNuevo->cupo_maximo
                ) {
                    throw new RuntimeException(
                        'Este grupo ya no tiene cupos disponibles. Selecciona otra opción.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Registrar historial
                |--------------------------------------------------------------------------
                */

                HistorialCambioGrupoMatricula::query()
                    ->create([
                        'matricula_id' =>
                            $matricula->id,

                        'grupo_anterior_id' =>
                            $grupoAnterior->id,

                        'grupo_nuevo_id' =>
                            $grupoNuevo->id,

                        'motivo' => null,

                        'cambiado_por' =>
                            $usuarioId,

                        'cambiado_at' =>
                            now(),
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Actualizar grupo vigente
                |--------------------------------------------------------------------------
                */

                $matricula->update([
                    'grupo_id' =>
                        $grupoNuevo->id,
                ]);

                return $matricula
                    ->fresh([
                        'grupo.nivel.programa',
                        'grupo.periodoAcademico',
                        'grupo.horarios.horario',
                        'grupo.docentes.docente.empleado.persona',
                    ]);
            },
            3
        );
    }
}