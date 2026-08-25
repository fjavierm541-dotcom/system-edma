<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InicioDocenteController extends Controller
{
    public function index(
        Request $request
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Docente'),
            403
        );

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

        return view(
            'portal.docente.inicio',
            [
                'user' =>
                    $user,

                'persona' =>
                    $persona,
            ]
        );
    }
}