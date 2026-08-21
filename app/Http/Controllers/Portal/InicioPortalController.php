<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InicioPortalController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->tieneRol('Administrador')) {
            return redirect()->route('portal.dashboard');
        }

        if ($user->tieneRol('Estudiante')) {
            return redirect()->route('portal.mi-matricula.index');
        }

        if ($user->tieneRol('Docente')) {
            abort(
                403,
                'El acceso para docentes aún no se encuentra habilitado.'
            );
        }

        abort(
            403,
            'Su cuenta no tiene un rol autorizado para acceder al portal.'
        );
    }
}