<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InicioEstudianteController extends Controller
{
    public function index(
        Request $request
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Estudiante'),
            403
        );

        $user->load([
            'persona.estudiante.nivelAutorizado.programa',
        ]);

        $persona =
            $user->persona;

        $estudiante =
            $persona?->estudiante;

        abort_unless(
            $estudiante,
            403,
            'No se encontró un expediente de estudiante asociado a tu cuenta.'
        );

        /*
        |--------------------------------------------------------------------------
        | Avisos al ingresar
        |--------------------------------------------------------------------------
        |
        | Los mostramos únicamente la primera vez
        | que el estudiante llega a Inicio durante
        | su sesión actual.
        |
        */

        $mostrarAvisos =
            !$request->session()->has(
                'inicio_estudiante_mostrado'
            );

        $pagosPendientesRevision = 0;
        $pagosRechazados = 0;

        if ($mostrarAvisos) {

            $pagosPendientesRevision =
                Pago::query()
                    ->where(
                        'estudiante_id',
                        $estudiante->id
                    )
                    ->where(
                        'estado',
                        'pendiente_revision'
                    )
                    ->count();

            $pagosRechazados =
                Pago::query()
                    ->where(
                        'estudiante_id',
                        $estudiante->id
                    )
                    ->where(
                        'estado',
                        'rechazado'
                    )
                    ->count();

            $request
                ->session()
                ->put(
                    'inicio_estudiante_mostrado',
                    true
                );
        }

        return view(
            'portal.estudiante.inicio',
            [
                'user' =>
                    $user,

                'persona' =>
                    $persona,

                'estudiante' =>
                    $estudiante,

                'mostrarAvisos' =>
                    $mostrarAvisos,

                'pagosPendientesRevision' =>
                    $pagosPendientesRevision,

                'pagosRechazados' =>
                    $pagosRechazados,
            ]
        );
    }
}