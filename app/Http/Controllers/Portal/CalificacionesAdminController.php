<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\RectificarCalificacionRequest;
use App\Models\CalificacionFinal;
use App\Models\Grupo;
use App\Models\PeriodoAcademico;
use App\Services\Calificaciones\RectificarCalificacionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class CalificacionesAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Selección de período
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Administrador'),
            403
        );

        $periodos =
            PeriodoAcademico::query()
                ->orderByDesc('fecha_inicio')
                ->get();

        return view(
            'portal.admin.calificaciones.index',
            [
                'periodos' =>
                    $periodos,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Grupos de un período
    |--------------------------------------------------------------------------
    */

    public function grupos(
        Request $request,
        PeriodoAcademico $periodo
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Administrador'),
            403
        );

        $grupos =
            Grupo::query()
                ->where(
                    'periodo_academico_id',
                    $periodo->id
                )
                ->with([
                    'nivel.programa',
                ])
                ->withCount([
                    'matriculas as estudiantes_count' =>
                        function ($query): void {
                            $query->whereIn(
                                'estado',
                                [
                                    'pendiente',
                                    'activa',
                                ]
                            );
                        },

                    'matriculas as calificaciones_count' =>
                        function ($query): void {
                            $query
                                ->whereIn(
                                    'estado',
                                    [
                                        'pendiente',
                                        'activa',
                                    ]
                                )
                                ->whereHas(
                                    'calificacionFinal',
                                    function ($subquery): void {
                                        $subquery->where(
                                            'estado',
                                            'bloqueada'
                                        );
                                    }
                                );
                        },
                ])
                ->orderBy('nombre')
                ->get();

        return view(
            'portal.admin.calificaciones.grupos',
            [
                'periodo' =>
                    $periodo,

                'grupos' =>
                    $grupos,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calificaciones de un grupo
    |--------------------------------------------------------------------------
    */

    public function grupo(
        Request $request,
        Grupo $grupo
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Administrador'),
            403
        );

        $grupo->load([
            'nivel.programa',
            'periodoAcademico',
        ]);

        $buscar =
            trim(
                (string)
                $request->query(
                    'buscar',
                    ''
                )
            );

        $resultado =
            $request->query(
                'resultado'
            );

        $rectificado =
            $request->query(
                'rectificado'
            );

        $calificaciones =
            CalificacionFinal::query()
                ->where(
                    'estado',
                    'bloqueada'
                )
                ->whereHas(
                    'matricula',
                    function ($query) use (
                        $grupo
                    ): void {
                        $query->where(
                            'grupo_id',
                            $grupo->id
                        );
                    }
                )
                ->when(
                    $buscar !== '',
                    function ($query) use (
                        $buscar
                    ): void {
                        $query->whereHas(
                            'matricula.estudiante.persona',
                            function ($personaQuery) use (
                                $buscar
                            ): void {
                                $personaQuery
                                    ->where(
                                        'primer_nombre',
                                        'like',
                                        "%{$buscar}%"
                                    )
                                    ->orWhere(
                                        'segundo_nombre',
                                        'like',
                                        "%{$buscar}%"
                                    )
                                    ->orWhere(
                                        'primer_apellido',
                                        'like',
                                        "%{$buscar}%"
                                    )
                                    ->orWhere(
                                        'segundo_apellido',
                                        'like',
                                        "%{$buscar}%"
                                    );
                            }
                        )
                        ->orWhereHas(
                            'matricula.estudiante',
                            function ($estudianteQuery) use (
                                $buscar
                            ): void {
                                $estudianteQuery->where(
                                    'codigo_estudiante',
                                    'like',
                                    "%{$buscar}%"
                                );
                            }
                        );
                    }
                )
                ->when(
                    in_array(
                        $resultado,
                        [
                            'aprobado',
                            'reprobado',
                            'incompleto',
                            'retirado',
                        ],
                        true
                    ),
                    function ($query) use (
                        $resultado
                    ): void {
                        $query->where(
                            'resultado',
                            $resultado
                        );
                    }
                )
                ->when(
                    $rectificado === 'si',
                    function ($query): void {
                        $query->whereHas(
                            'historial'
                        );
                    }
                )
                ->when(
                    $rectificado === 'no',
                    function ($query): void {
                        $query->whereDoesntHave(
                            'historial'
                        );
                    }
                )
                ->with([
                    'matricula.estudiante.persona',
                    'matricula.grupo.nivel.programa',
                    'matricula.grupo.periodoAcademico',

                    'historial' =>
                        function ($query): void {
                            $query
                                ->with(
                                    'cambiadoPor.persona'
                                )
                                ->orderByDesc(
                                    'cambiado_at'
                                );
                        },
                ])
                ->orderByDesc(
                    'bloqueada_at'
                )
                ->paginate(20)
                ->withQueryString();

        return view(
            'portal.admin.calificaciones.grupo',
            [
                'grupo' =>
                    $grupo,

                'calificaciones' =>
                    $calificaciones,

                'buscar' =>
                    $buscar,

                'resultadoFiltro' =>
                    $resultado,

                'rectificadoFiltro' =>
                    $rectificado,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Rectificar
    |--------------------------------------------------------------------------
    */

    public function rectificar(
        RectificarCalificacionRequest $request,
        CalificacionFinal $calificacion,
        RectificarCalificacionService $service
    ): RedirectResponse {
        try {
            $service->ejecutar(
                $calificacion,
                $request->validated(),
                $request->user()->id
            );

            $calificacion->loadMissing(
                'matricula'
            );

            return redirect()
                ->route(
                    'portal.admin.calificaciones.grupo',
                    $calificacion
                        ->matricula
                        ->grupo_id
                )
                ->with(
                    'success',
                    'La calificación fue rectificada correctamente y el cambio quedó registrado en el historial.'
                );

        } catch (
            RuntimeException $exception
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }
}