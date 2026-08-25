<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarCalificacionesGrupoRequest;
use App\Models\Docente;
use App\Models\Grupo;
use App\Services\Calificaciones\GuardarBorradoresCalificacionesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use App\Services\Calificaciones\ConfirmarCalificacionesGrupoService;

class CalificacionesDocenteController extends Controller
{
    public function edit(
    Request $request,
    Grupo $grupo
): View {
    $docente =
        $this->obtenerDocenteAutenticado(
            $request
        );

    /*
    |--------------------------------------------------------------------------
    | Validar asignación
    |--------------------------------------------------------------------------
    */

    $asignacion =
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
            ->first();

    abort_unless(
        $asignacion,
        403,
        'No tienes acceso a este grupo.'
    );

    /*
    |--------------------------------------------------------------------------
    | Cargar información
    |--------------------------------------------------------------------------
    */

    $grupo->load([
        'nivel.programa',
        'periodoAcademico',

        'matriculas' =>
            function ($query): void {
                $query
                    ->whereIn(
                        'estado',
                        [
                            'pendiente',
                            'activa',
                        ]
                    )
                    ->with([
                        'estudiante.persona',
                        'calificacionFinal',
                    ])
                    ->orderBy('id');
            },
    ]);

    /*
    |--------------------------------------------------------------------------
    | Validar ventana
    |--------------------------------------------------------------------------
    */

    $periodo =
        $grupo
            ->periodoAcademico;

    $puedeEditar =
        $periodo->calificaciones_desde
        &&
        $periodo->calificaciones_hasta
        &&
        now()->betweenIncluded(
            $periodo->calificaciones_desde,
            $periodo->calificaciones_hasta
        );

    /*
    |--------------------------------------------------------------------------
    | Estado general de la entrega
    |--------------------------------------------------------------------------
    */

    $totalEstudiantes =
        $grupo
            ->matriculas
            ->count();

    $totalConCalificacion =
        $grupo
            ->matriculas
            ->filter(
                fn ($matricula) =>
                    $matricula
                        ->calificacionFinal
                    !== null
            )
            ->count();

    $totalBorradores =
        $grupo
            ->matriculas
            ->filter(
                fn ($matricula) =>
                    $matricula
                        ->calificacionFinal
                        ?->estado
                    === 'borrador'
            )
            ->count();

    $todasConfirmadas =
        $totalEstudiantes > 0
        &&
        $grupo
            ->matriculas
            ->every(
                fn ($matricula) =>
                    in_array(
                        $matricula
                            ->calificacionFinal
                            ?->estado,
                        [
                            'confirmada',
                            'bloqueada',
                        ],
                        true
                    )
            );

    $puedeConfirmar =
        $puedeEditar
        &&
        $totalEstudiantes > 0
        &&
        $totalConCalificacion
            ===
            $totalEstudiantes
        &&
        $totalBorradores > 0;

    return view(
        'portal.docente.calificaciones.edit',
        [
            'grupo' =>
                $grupo,

            'periodo' =>
                $periodo,

            'puedeEditar' =>
                $puedeEditar,

            'totalEstudiantes' =>
                $totalEstudiantes,

            'totalConCalificacion' =>
                $totalConCalificacion,

            'totalBorradores' =>
                $totalBorradores,

            'todasConfirmadas' =>
                $todasConfirmadas,

            'puedeConfirmar' =>
                $puedeConfirmar,
        ]
    );
}

    public function update(
        GuardarCalificacionesGrupoRequest $request,
        Grupo $grupo,
        GuardarBorradoresCalificacionesService $service
    ): RedirectResponse {
        $docente =
            $this->obtenerDocenteAutenticado(
                $request
            );

        try {
            $service->ejecutar(
                $docente,
                $grupo,
                $request->validated(),
                $request->user()->id
            );

            return redirect()
                ->route(
                    'portal.docente.calificaciones.edit',
                    $grupo
                )
                ->with(
                    'success',
                    'Las calificaciones fueron guardadas como borrador.'
                );
        } catch (
            RuntimeException $exception
        ) {
            return redirect()
                ->route(
                    'portal.docente.calificaciones.edit',
                    $grupo
                )
                ->withInput()
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }

    private function obtenerDocenteAutenticado(
        Request $request
    ): Docente {
        $user =
            $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Docente'),
            403
        );

        $user->load('persona');

        $persona =
            $user->persona;

        abort_unless(
            $persona,
            403,
            'No se encontró un expediente personal asociado a tu cuenta.'
        );

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
                ->first();

        abort_unless(
            $docente,
            403,
            'No se encontró un expediente docente asociado a tu cuenta.'
        );

        return $docente;
    }


    public function confirmar(
    Request $request,
    Grupo $grupo,
    ConfirmarCalificacionesGrupoService $service
): RedirectResponse {
    $docente =
        $this->obtenerDocenteAutenticado(
            $request
        );

    try {
        $service->ejecutar(
            $docente,
            $grupo
        );

        return redirect()
            ->route(
                'portal.docente.calificaciones.edit',
                $grupo
            )
            ->with(
                'success',
                'Las calificaciones del grupo fueron confirmadas correctamente.'
            );

    } catch (
        RuntimeException $exception
    ) {
        return redirect()
            ->route(
                'portal.docente.calificaciones.edit',
                $grupo
            )
            ->with(
                'error',
                $exception->getMessage()
            );
    }
}


}