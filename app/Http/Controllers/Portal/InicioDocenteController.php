<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\GrupoDocente;
use App\Models\PeriodoAcademico;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InicioDocenteController extends Controller
{
    public function index(
        Request $request
    ): View {
        $user =
            $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Docente'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Persona
        |--------------------------------------------------------------------------
        */

        $user->load([
            'persona',
        ]);

        $persona =
            $user->persona;

        abort_unless(
            $persona,
            403,
            'No se encontró un expediente personal asociado a tu cuenta.'
        );

        /*
        |--------------------------------------------------------------------------
        | Docente
        |--------------------------------------------------------------------------
        */

        $docente =
            Docente::query()
                ->whereHas(
                    'empleado',
                    function ($query) use (
                        $persona
                    ): void {
                        $query->where(
                            'persona_id',
                            $persona->id
                        );
                    }
                )
                ->with([
                    'empleado.persona',
                ])
                ->first();

        abort_unless(
            $docente,
            403,
            'No se encontró un expediente docente asociado a tu cuenta.'
        );

        /*
        |--------------------------------------------------------------------------
        | Grupos asignados actualmente
        |--------------------------------------------------------------------------
        */

        $asignaciones =
            GrupoDocente::query()
                ->where(
                    'docente_id',
                    $docente->id
                )
                ->where(
                    'activo',
                    true
                )
                ->whereHas(
                    'grupo',
                    function ($query): void {
                        $query->whereIn(
                            'estado',
                            [
                                'planificado',
                                'activo',
                            ]
                        );
                    }
                )
                ->with([
                    'grupo.nivel.programa',
                    'grupo.periodoAcademico',

                    'grupo.matriculas' =>
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
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Indicadores
        |--------------------------------------------------------------------------
        */

        $cantidadGrupos =
            $asignaciones
                ->count();

        $cantidadEstudiantes =
            $asignaciones
                ->sum(
                    fn ($asignacion) =>
                        $asignacion
                            ->grupo
                            ->matriculas
                            ->count()
                );

        /*
        |--------------------------------------------------------------------------
        | Período académico actual
        |--------------------------------------------------------------------------
        |
        | Prioridad:
        |
        | 1. En curso
        | 2. Matrícula abierta
        |
        */

        $periodoActual =
            PeriodoAcademico::query()
                ->where(
                    'estado',
                    'en_curso'
                )
                ->orderByDesc(
                    'fecha_inicio'
                )
                ->first();

        if (!$periodoActual) {
            $periodoActual =
                PeriodoAcademico::query()
                    ->where(
                        'estado',
                        'matricula_abierta'
                    )
                    ->orderByDesc(
                        'fecha_inicio'
                    )
                    ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Estado de las fechas para registrar calificaciones
        |--------------------------------------------------------------------------
        */

        $estadoCalificaciones =
            'sin_periodo';

        if ($periodoActual) {

            if (
                !$periodoActual
                    ->calificaciones_desde
                ||
                !$periodoActual
                    ->calificaciones_hasta
            ) {

                $estadoCalificaciones =
                    'no_configuradas';

            } elseif (
                now()->lt(
                    $periodoActual
                        ->calificaciones_desde
                )
            ) {

                $estadoCalificaciones =
                    'programadas';

            } elseif (
                now()->betweenIncluded(
                    $periodoActual
                        ->calificaciones_desde,
                    $periodoActual
                        ->calificaciones_hasta
                )
            ) {

                $estadoCalificaciones =
                    'habilitadas';

            } else {

                $estadoCalificaciones =
                    'finalizadas';
            }
        }

        return view(
            'portal.docente.inicio',
            [
                'user' =>
                    $user,

                'persona' =>
                    $persona,

                'docente' =>
                    $docente,

                'asignaciones' =>
                    $asignaciones,

                'cantidadGrupos' =>
                    $cantidadGrupos,

                'cantidadEstudiantes' =>
                    $cantidadEstudiantes,

                'periodoActual' =>
                    $periodoActual,

                'estadoCalificaciones' =>
                    $estadoCalificaciones,
            ]
        );
    }
}