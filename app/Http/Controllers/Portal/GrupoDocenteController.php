<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrupoDocenteRequest;
use App\Http\Requests\UpdateGrupoDocenteRequest;
use App\Models\Grupo;
use App\Models\GrupoDocente;
use Illuminate\Http\RedirectResponse;

class GrupoDocenteController extends Controller
{
    public function store(
        StoreGrupoDocenteRequest $request,
        Grupo $grupo
    ): RedirectResponse {
        $grupo->docentes()->create(
            $request->validated()
        );

        return back()->with(
            'success',
            'El docente fue asignado correctamente al grupo.'
        );
    }

    public function update(
        UpdateGrupoDocenteRequest $request,
        Grupo $grupo,
        GrupoDocente $grupoDocente
    ): RedirectResponse {
        if (
            $grupoDocente->grupo_id
            !== $grupo->id
        ) {
            abort(404);
        }

        $grupoDocente->update(
            $request->validated()
        );

        return back()->with(
            'success',
            'La asignación docente fue actualizada correctamente.'
        );
    }

    public function cambiarEstado(
        Grupo $grupo,
        GrupoDocente $grupoDocente
    ): RedirectResponse {
        if (
            $grupoDocente->grupo_id
            !== $grupo->id
        ) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Reactivar asignación
        |--------------------------------------------------------------------------
        */

        if (!$grupoDocente->activo) {

            $existePrincipalActivo =
                GrupoDocente::query()
                    ->where(
                        'grupo_id',
                        $grupo->id
                    )
                    ->where(
                        'tipo_asignacion',
                        'principal'
                    )
                    ->where(
                        'activo',
                        true
                    )
                    ->where(
                        'id',
                        '!=',
                        $grupoDocente->id
                    )
                    ->exists();

            if ($existePrincipalActivo) {
                return back()->with(
                    'error',
                    'El grupo ya tiene un docente principal activo. Finalice esa asignación antes de reactivar esta.'
                );
            }

            $grupoDocente->update([
                'activo' => true,
                'fecha_fin' => null,
            ]);

            return back()->with(
                'success',
                'La asignación docente fue activada correctamente.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Finalizar asignación
        |--------------------------------------------------------------------------
        */

        $grupoDocente->update([
            'activo' => false,
            'fecha_fin' =>
                min(
                    now()->toDateString(),
                    $grupo->fecha_fin->format('Y-m-d')
                ),
        ]);

        return back()->with(
            'success',
            'La asignación docente fue finalizada correctamente.'
        );
    }
}