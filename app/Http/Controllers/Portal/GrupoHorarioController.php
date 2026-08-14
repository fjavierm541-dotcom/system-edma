<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrupoHorarioRequest;
use App\Http\Requests\UpdateGrupoHorarioRequest;
use App\Models\Grupo;
use App\Models\GrupoHorario;
use Illuminate\Http\RedirectResponse;

class GrupoHorarioController extends Controller
{
    public function store(
        StoreGrupoHorarioRequest $request,
        Grupo $grupo
    ): RedirectResponse {
        $grupo->horarios()->create(
            $request->validated()
        );

        return back()->with(
            'success',
            'El día y horario fueron asignados correctamente.'
        );
    }

    public function update(
        UpdateGrupoHorarioRequest $request,
        Grupo $grupo,
        GrupoHorario $grupoHorario
    ): RedirectResponse {
        if (
            $grupoHorario->grupo_id
            !== $grupo->id
        ) {
            abort(404);
        }

        $grupoHorario->update(
            $request->validated()
        );

        return back()->with(
            'success',
            'La asignación de horario fue actualizada correctamente.'
        );
    }

    public function destroy(
        Grupo $grupo,
        GrupoHorario $grupoHorario
    ): RedirectResponse {
        if (
            $grupoHorario->grupo_id
            !== $grupo->id
        ) {
            abort(404);
        }

        $grupoHorario->delete();

        return back()->with(
            'success',
            'El horario fue retirado del grupo correctamente.'
        );
    }
}