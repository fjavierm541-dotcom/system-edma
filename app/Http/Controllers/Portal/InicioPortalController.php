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
        |
        | El Portal Docente todavía no está habilitado.
        |
        */

        if ($user->tieneRol('Docente')) {
            abort(
                403,
                'El acceso para docentes aún no se encuentra habilitado.'
            );
        }

        abort(
            403,
            'Tu cuenta no tiene acceso habilitado al Portal EDMA.'
        );
    }
}