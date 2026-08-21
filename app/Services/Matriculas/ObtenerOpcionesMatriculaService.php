<?php

namespace App\Services\Matriculas;

use App\Models\Estudiante;
use App\Models\Grupo;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\PeriodoAcademico;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

class ObtenerOpcionesMatriculaService
{
    public function ejecutar(Estudiante $estudiante): array
    {
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
        | 4. Impedir matrícula duplicada
        |--------------------------------------------------------------------------
        |
        | El nivel y período se obtienen mediante el grupo.
        |
        */

        $yaMatriculado = Matricula::query()
            ->where(
                'estudiante_id',
                $estudiante->id
            )
            ->whereHas(
                'grupo',
                function ($query) use (
                    $estudiante,
                    $periodo
                ): void {
                    $query
                        ->where(
                            'nivel_id',
                            $estudiante->nivel_autorizado_id
                        )
                        ->where(
                            'periodo_academico_id',
                            $periodo->id
                        );
                }
            )
            ->whereIn(
                'estado',
                [
                    'pendiente',
                    'activa',
                ]
            )
            ->exists();

        if ($yaMatriculado) {
            throw new RuntimeException(
                'Ya tienes una matrícula registrada para este nivel y período académico.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Validar pago aprobado
        |--------------------------------------------------------------------------
        |
        | En la primera matrícula este será el pago aprobado durante
        | la Solicitud de Inscripción.
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
        | 6. Obtener grupos compatibles
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
                'horarios',
                'docentes.docente.persona',
            ])
            ->withCount([
                'matriculas as matriculas_activas_count' =>
                    function ($query): void {
                        $query->whereIn(
                            'estado',
                            [
                                'pendiente',
                                'activa',
                            ]
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
            'periodo' => $periodo,
            'pago' => $pago,
            'grupos' => $grupos,
        ];
    }
}