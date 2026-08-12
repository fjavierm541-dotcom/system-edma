<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramaRequest;
use App\Http\Requests\UpdateProgramaRequest;
use App\Models\Programa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramaController extends Controller
{
    /**
     * Listado de programas.
     */
    public function index(Request $request): View
    {
        $buscar = trim(
            (string) $request->input('buscar')
        );

        $estado = $request->input('estado');

        $programas = Programa::query()
            ->withCount('niveles')
            ->buscar($buscar)
            ->when(
                in_array(
                    $estado,
                    ['activo', 'inactivo'],
                    true
                ),
                fn ($query) =>
                    $query->where('estado', $estado)
            )
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        $totalProgramas = Programa::query()->count();

        $programasActivos = Programa::query()
            ->activos()
            ->count();

        $programasInactivos = Programa::query()
            ->inactivos()
            ->count();

        return view('portal.programas.index', [
            'programas' => $programas,
            'buscar' => $buscar,
            'estado' => $estado,
            'totalProgramas' => $totalProgramas,
            'programasActivos' => $programasActivos,
            'programasInactivos' => $programasInactivos,
        ]);
    }

    /**
     * Mostrar formulario para crear programa.
     */
    public function create(): View
    {
        return view('portal.programas.create');
    }

    /**
     * Guardar programa.
     */
    public function store(
        StoreProgramaRequest $request
    ): RedirectResponse {
        $programa = Programa::create(
            $request->validated()
        );

        return redirect()
            ->route(
                'portal.programas.show',
                $programa
            )
            ->with(
                'success',
                'El programa fue registrado correctamente.'
            );
    }

    /**
     * Mostrar programa.
     */
    public function show(Programa $programa): View
    {
        $programa->load([
            'niveles' => fn ($query) =>
                $query
                    ->orderBy('orden')
                    ->orderBy('nombre'),
        ]);

        return view('portal.programas.show', [
            'programa' => $programa,
        ]);
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Programa $programa): View
    {
        return view('portal.programas.edit', [
            'programa' => $programa,
        ]);
    }

    /**
     * Actualizar programa.
     */
    public function update(
        UpdateProgramaRequest $request,
        Programa $programa
    ): RedirectResponse {
        $programa->update(
            $request->validated()
        );

        return redirect()
            ->route(
                'portal.programas.show',
                $programa
            )
            ->with(
                'success',
                'El programa fue actualizado correctamente.'
            );
    }

    /**
     * Activar o desactivar programa.
     */
    public function cambiarEstado(
        Programa $programa
    ): RedirectResponse {
        $programa->update([
            'estado' =>
                $programa->estado === 'activo'
                    ? 'inactivo'
                    : 'activo',
        ]);

        return back()->with(
            'success',
            $programa->estado === 'activo'
                ? 'El programa fue activado correctamente.'
                : 'El programa fue desactivado correctamente.'
        );
    }
}