<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InicioPortalController extends Controller
{
    public function __invoke(
        Request $request
    ): RedirectResponse {
        $user = $request->user();

        abort_unless(
            $user,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Administrador
        |--------------------------------------------------------------------------
        */

        if ($user->tieneRol('Administrador')) {
            return redirect()
                ->route(
                    'portal.admin.inicio'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Estudiante
        |--------------------------------------------------------------------------
        */

        if ($user->tieneRol('Estudiante')) {
            return redirect()
                ->route(
                    'portal.estudiante.inicio'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Docente
        |--------------------------------------------------------------------------
        */

        if ($user->tieneRol('Docente')) {
            return redirect()
                ->route(
                    'portal.docente.inicio'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Sin rol habilitado
        |--------------------------------------------------------------------------
        */

        abort(
            403,
            'Tu cuenta no tiene acceso habilitado al Portal EDMA.'
        );
    }
}