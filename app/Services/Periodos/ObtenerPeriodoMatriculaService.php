<?php

namespace App\Services\Periodos;

use App\Models\PeriodoAcademico;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

class ObtenerPeriodoMatriculaService
{
    public function obtenerDisponibles(): Collection
    {
        $hoy = now()->toDateString();

        return PeriodoAcademico::query()
            ->where(
                'estado', 'matricula_abierta'
            )
            ->whereDate(
                'fecha_inicio_matricula',
                '<=',
                $hoy
            )
            ->whereDate(
                'fecha_fin_matricula',
                '>=',
                $hoy
            )
            ->orderBy('fecha_inicio')
            ->get();
    }

    public function obtenerUnico(): PeriodoAcademico
    {
        $periodos =
            $this->obtenerDisponibles();

        if ($periodos->isEmpty()) {
            throw new RuntimeException(
                'Actualmente no hay un período académico abierto para nuevas inscripciones.'
            );
        }

        if ($periodos->count() > 1) {
            throw new RuntimeException(
                'Hay más de un período académico disponible.'
            );
        }

        return $periodos->first();
    }
}