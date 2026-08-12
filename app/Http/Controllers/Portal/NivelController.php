<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNivelRequest;
use App\Http\Requests\UpdateNivelRequest;
use App\Models\Nivel;
use App\Models\Programa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NivelController extends Controller
{
    public function index(Request $request): View
    {
        $termino = trim(
            (string) $request->query('buscar', '')
        );

        $estado = $request->query('estado');

        $programaId = $request->integer('programa');

        $niveles = Nivel::query()
            ->with('programa')
            ->withCount('grupos')
            ->buscar($termino)
            ->when(
                $programaId > 0,
                fn (Builder $query) =>
                    $query->where(
                        'programa_id',
                        $programaId
                    )
            )
            ->when(
                in_array(
                    $estado,
                    ['activo', 'inactivo'],
                    true
                ),
                fn (Builder $query) =>
                    $query->where(
                        'estado',
                        $estado
                    )
            )
            ->orderBy('programa_id')
            ->orderBy('orden')
            ->paginate(15)
            ->withQueryString();

        $programas = Programa::query()
            ->orderBy('nombre')
            ->get([
                'id',
                'nombre',
                'codigo',
                'estado',
            ]);

        $resumen = [
            'total' => Nivel::query()->count(),

            'activos' => Nivel::query()
                ->activos()
                ->count(),

            'inactivos' => Nivel::query()
                ->inactivos()
                ->count(),
        ];

        return view('portal.niveles.index', [
            'niveles' => $niveles,
            'programas' => $programas,
            'resumen' => $resumen,
            'termino' => $termino,
            'estadoSeleccionado' => $estado,
            'programaSeleccionado' => $programaId,
        ]);
    }

    public function create(Request $request): View
    {
        $programaSeleccionado = null;

        $programaId = $request->integer('programa');

        if ($programaId > 0) {
            $programaSeleccionado = Programa::query()
                ->activos()
                ->find($programaId);
        }

        $programas = Programa::query()
            ->activos()
            ->orderBy('nombre')
            ->get([
                'id',
                'codigo',
                'nombre',
                'segmento',
            ]);

        return view('portal.niveles.create', [
            'programas' => $programas,
            'programaSeleccionado' =>
                $programaSeleccionado,
        ]);
    }

    public function store(
        StoreNivelRequest $request
    ): RedirectResponse {
        $nivel = Nivel::query()->create(
            $request->validated()
        );

        return redirect()
            ->route(
                'portal.niveles.show',
                $nivel
            )
            ->with(
                'success',
                'El nivel académico fue registrado correctamente.'
            );
    }

    public function show(Nivel $nivel): View
    {
        $nivel->load([
            'programa',

            'grupos' => fn ($query) =>
                $query
                    ->orderByDesc('fecha_inicio')
                    ->orderBy('nombre'),
        ]);

        return view('portal.niveles.show', [
            'nivel' => $nivel,
        ]);
    }

    public function edit(Nivel $nivel): View
    {
        $nivel->load('programa');

        /*
         * Incluimos el programa actual aunque esté inactivo,
         * para no romper la edición de registros históricos.
         */
        $programas = Programa::query()
            ->where(function (Builder $query) use ($nivel) {
                $query
                    ->where('estado', 'activo')
                    ->orWhere(
                        'id',
                        $nivel->programa_id
                    );
            })
            ->orderBy('nombre')
            ->get([
                'id',
                'codigo',
                'nombre',
                'segmento',
                'estado',
            ]);

        return view('portal.niveles.edit', [
            'nivel' => $nivel,
            'programas' => $programas,
        ]);
    }

    public function update(
        UpdateNivelRequest $request,
        Nivel $nivel
    ): RedirectResponse {
        $nivel->update(
            $request->validated()
        );

        return redirect()
            ->route(
                'portal.niveles.show',
                $nivel
            )
            ->with(
                'success',
                'El nivel académico fue actualizado correctamente.'
            );
    }

    public function cambiarEstado(
        Nivel $nivel
    ): RedirectResponse {
        $nuevoEstado =
            $nivel->estado === 'activo'
                ? 'inactivo'
                : 'activo';

        /*
         * Para activar un nivel, el programa también
         * debe encontrarse activo.
         */
        if (
            $nuevoEstado === 'activo' &&
            $nivel->programa?->estado !== 'activo'
        ) {
            return back()->with(
                'error',
                'No puede activarse el nivel mientras su programa se encuentre inactivo.'
            );
        }

        $nivel->update([
            'estado' => $nuevoEstado,
        ]);

        return back()->with(
            'success',
            $nuevoEstado === 'activo'
                ? 'El nivel fue activado correctamente.'
                : 'El nivel fue desactivado correctamente.'
        );
    }
}