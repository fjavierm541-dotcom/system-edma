<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\Matriculas\ObtenerOpcionesMatriculaService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class MiMatriculaController extends Controller
{
    public function index(
        Request $request,
        ObtenerOpcionesMatriculaService $service
    ): View {
        $user = $request->user();

        abort_unless(
            $user && $user->tieneRol('Estudiante'),
            403
        );

        $user->load(
            'persona.estudiante.nivelAutorizado.programa'
        );

        $estudiante = $user->persona?->estudiante;

        abort_unless(
            $estudiante,
            403,
            'No se encontró un expediente de estudiante asociado a tu cuenta.'
        );

        try {
            $opciones = $service->ejecutar(
                $estudiante
            );

            return view(
                'portal/matriculas/index',
                [
                    'estudiante' => $estudiante,
                    'periodo' => $opciones['periodo'],
                    'pago' => $opciones['pago'],
                    'grupos' => $opciones['grupos'],
                    'mensajeBloqueo' => null,
                ]
            );
        } catch (RuntimeException $exception) {
            return view(
                'portal/matriculas/index',
                [
                    'estudiante' => $estudiante,
                    'periodo' => null,
                    'pago' => null,
                    'grupos' => collect(),
                    'mensajeBloqueo' =>
                        $exception->getMessage(),
                ]
            );
        }
    }
}