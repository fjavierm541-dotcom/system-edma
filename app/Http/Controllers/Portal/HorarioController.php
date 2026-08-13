<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHorarioRequest;
use App\Http\Requests\UpdateHorarioRequest;
use App\Models\Horario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HorarioController extends Controller
{
    public function index(Request $request): View
    {
        $termino = trim(
            (string) $request->query('buscar', '')
        );

        $estado = $request->query('estado');

        $horarios = Horario::query()
            ->withCount('grupoHorarios')
            ->buscar($termino)
            ->when(
                $estado === 'activo',
                fn (Builder $query) =>
                    $query->where('activo', true)
            )
            ->when(
                $estado === 'inactivo',
                fn (Builder $query) =>
                    $query->where('activo', false)
            )
            ->orderBy('hora_inicio')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        $resumen = [
            'total' => Horario::query()->count(),

            'activos' => Horario::query()
                ->activos()
                ->count(),

            'inactivos' => Horario::query()
                ->inactivos()
                ->count(),
        ];

        return view('portal.horarios.index', [
            'horarios' => $horarios,
            'resumen' => $resumen,
            'termino' => $termino,
            'estadoSeleccionado' => $estado,
        ]);
    }

    public function create(): View
    {
        return view('portal.horarios.create');
    }

    public function store(
        StoreHorarioRequest $request
    ): RedirectResponse {
        $horario = Horario::query()->create(
            $request->validated()
        );

        return redirect()
            ->route(
                'portal.horarios.show',
                $horario
            )
            ->with(
                'success',
                'El horario fue registrado correctamente.'
            );
    }

    public function show(Horario $horario): View
    {
        $horario->load([
            'grupoHorarios.grupo.nivel.programa',
            'grupoHorarios.grupo.periodoAcademico',
        ]);

        return view('portal.horarios.show', [
            'horario' => $horario,
        ]);
    }

    public function edit(Horario $horario): View
    {
        return view('portal.horarios.edit', [
            'horario' => $horario,
        ]);
    }

    public function update(
        UpdateHorarioRequest $request,
        Horario $horario
    ): RedirectResponse {
        $horario->update(
            $request->validated()
        );

        return redirect()
            ->route(
                'portal.horarios.show',
                $horario
            )
            ->with(
                'success',
                'El horario fue actualizado correctamente.'
            );
    }

    public function cambiarEstado(
        Horario $horario
    ): RedirectResponse {
        $horario->update([
            'activo' => !$horario->activo,
        ]);

        return back()->with(
            'success',
            $horario->activo
                ? 'El horario fue activado correctamente.'
                : 'El horario fue desactivado correctamente.'
        );
    }
}