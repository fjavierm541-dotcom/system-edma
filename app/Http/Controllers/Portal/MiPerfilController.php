<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MiPerfilController extends Controller
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
            $persona
            && $estudiante,
            403,
            'No se encontró un expediente de estudiante asociado a tu cuenta.'
        );

        return view(
            'portal.estudiante.perfil',
            [
                'user' =>
                    $user,

                'persona' =>
                    $persona,

                'estudiante' =>
                    $estudiante,
            ]
        );
    }
}