<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMatriculaRequest;
use App\Services\Matriculas\CambiarGrupoMatriculaService;
use App\Services\Matriculas\CrearMatriculaService;
use App\Services\Matriculas\ObtenerOpcionesMatriculaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class MiMatriculaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Vista principal
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        ObtenerOpcionesMatriculaService $service
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Estudiante'),
            403
        );

        $user->load(
            'persona.estudiante.nivelAutorizado.programa'
        );

        $estudiante =
            $user->persona?->estudiante;

        abort_unless(
            $estudiante,
            403,
            'No se encontró un expediente de estudiante asociado a tu cuenta.'
        );

        try {
            $opciones =
                $service->ejecutar(
                    $estudiante
                );

            return view(
                'portal/matriculas/index',
                [
                    'estudiante' =>
                        $estudiante,

                    'periodo' =>
                        $opciones['periodo'],

                    'pago' =>
                        $opciones['pago'],

                    'grupos' =>
                        $opciones['grupos'],

                    'matriculaActiva' =>
                        $opciones[
                            'matriculaActiva'
                        ],

                    'gruposCambio' =>
                        $opciones[
                            'gruposCambio'
                        ],

                    'mensajeBloqueo' =>
                        null,
                ]
            );
        } catch (RuntimeException $exception) {
            return view(
                'portal/matriculas/index',
                [
                    'estudiante' =>
                        $estudiante,

                    'periodo' =>
                        null,

                    'pago' =>
                        null,

                    'grupos' =>
                        collect(),

                    'matriculaActiva' =>
                        null,

                    'gruposCambio' =>
                        collect(),

                    'mensajeBloqueo' =>
                        $exception->getMessage(),
                ]
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Crear primera matrícula
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreMatriculaRequest $request,
        CrearMatriculaService $service
    ): RedirectResponse {
        $user = $request->user();

        $user->load(
            'persona.estudiante'
        );

        $estudiante =
            $user->persona?->estudiante;

        abort_unless(
            $estudiante,
            403,
            'No se encontró un expediente de estudiante asociado a tu cuenta.'
        );

        try {
            $matricula =
                $service->ejecutar(
                    $estudiante,
                    (int) $request
                        ->validated(
                            'grupo_id'
                        ),
                    $user->id
                );

            return redirect()
                ->route(
                    'portal.mi-matricula.index'
                )
                ->with(
                    'success',
                    "Tu matrícula {$matricula->codigo_matricula} fue registrada correctamente."
                );
        } catch (RuntimeException $exception) {
            return redirect()
                ->route(
                    'portal.mi-matricula.index'
                )
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cambiar grupo de matrícula activa
    |--------------------------------------------------------------------------
    */

    public function cambiarGrupo(
        Request $request,
        CambiarGrupoMatriculaService $service
    ): RedirectResponse {
        $request->validate([
            'grupo_id' => [
                'required',
                'integer',
                'exists:grupos,id',
            ],

            
        ]);

        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Estudiante'),
            403
        );

        $user->load(
            'persona.estudiante'
        );

        $estudiante =
            $user->persona?->estudiante;

        abort_unless(
            $estudiante,
            403,
            'No se encontró un expediente de estudiante asociado a tu cuenta.'
        );

        try {
            $service->ejecutar(
                $estudiante,
                (int) $request->input('grupo_id'),
                $user->id
            );

            return redirect()
                ->route(
                    'portal.mi-matricula.index'
                )
                ->with(
                    'success',
                    'Tu cambio de grupo fue realizado correctamente.'
                );
        } catch (RuntimeException $exception) {
            return redirect()
                ->route(
                    'portal.mi-matricula.index'
                )
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }
}