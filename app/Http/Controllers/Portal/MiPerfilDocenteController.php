<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\FormacionAcademica;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MiPerfilDocenteController extends Controller
{
    public function index(
        Request $request
    ): View {
        $user =
            $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Docente'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Persona asociada al usuario
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Expediente docente
        |--------------------------------------------------------------------------
        */

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
                ->with([
                    'empleado.persona',
                ])
                ->first();

        abort_unless(
            $docente,
            403,
            'No se encontró un expediente docente asociado a tu cuenta.'
        );

        /*
        |--------------------------------------------------------------------------
        | Formación académica
        |--------------------------------------------------------------------------
        */

        $formaciones =
            FormacionAcademica::query()
                ->where(
                    'persona_id',
                    $persona->id
                )
                ->where(
                    'estado',
                    'activo'
                )
                ->with([
                    'pais',
                ])
                ->orderByDesc(
                    'es_principal'
                )
                ->orderByDesc(
                    'anio_graduacion'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Formación principal
        |--------------------------------------------------------------------------
        */

        $formacionPrincipal =
            $formaciones
                ->firstWhere(
                    'es_principal',
                    true
                )
            ?? $formaciones->first();

        return view(
            'portal.docente.perfil',
            [
                'user' =>
                    $user,

                'persona' =>
                    $persona,

                'docente' =>
                    $docente,

                'empleado' =>
                    $docente->empleado,

                'formaciones' =>
                    $formaciones,

                'formacionPrincipal' =>
                    $formacionPrincipal,
            ]
        );
    }
}