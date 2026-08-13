<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeriodoAcademicoRequest;
use App\Http\Requests\UpdatePeriodoAcademicoRequest;
use App\Models\PeriodoAcademico;
use App\Services\Periodos\CrearPeriodoAcademicoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PeriodoAcademicoController extends Controller
{
    public function __construct(
        private readonly CrearPeriodoAcademicoService $crearPeriodoService
    ) {
    }

    public function index(Request $request): View
    {
        $termino = trim(
            (string) $request->query('buscar', '')
        );

        $estado = $request->query('estado');

        $periodos = PeriodoAcademico::query()
            ->withCount('grupos')
            ->buscar($termino)
            ->when(
                in_array(
                    $estado,
                    ['activo', 'inactivo'],
                    true
                ),
                fn (Builder $query) =>
                    $query->where('estado', $estado)
            )
            ->orderByDesc('fecha_inicio')
            ->paginate(15)
            ->withQueryString();

        $resumen = [
            'total' => PeriodoAcademico::query()->count(),

            'activos' => PeriodoAcademico::query()
                ->activos()
                ->count(),

            'inactivos' => PeriodoAcademico::query()
                ->inactivos()
                ->count(),
        ];

        return view('portal.periodos.index', [
            'periodos' => $periodos,
            'resumen' => $resumen,
            'termino' => $termino,
            'estadoSeleccionado' => $estado,
        ]);
    }

    public function create(): View
    {
        return view('portal.periodos.create');
    }

    public function store(
        StorePeriodoAcademicoRequest $request
    ): RedirectResponse {
        try {
            $datos = $request->validated();

            $periodo = $this->crearPeriodoService->ejecutar(
                $datos,
                $datos['fecha_inicio'] ?? null
            );

            return redirect()
                ->route(
                    'portal.periodos.show',
                    $periodo
                )
                ->with(
                    'success',
                    'El período académico fue registrado correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al registrar período académico.',
                [
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible registrar el período académico.'
                );
        }
    }

    public function show(
        PeriodoAcademico $periodo
    ): View {
        $periodo->load([
            'grupos' => fn ($query) =>
                $query
                    ->with('nivel.programa')
                    ->orderBy('fecha_inicio')
                    ->orderBy('nombre'),
        ]);

        return view('portal.periodos.show', [
            'periodo' => $periodo,
        ]);
    }

    public function edit(
        PeriodoAcademico $periodo
    ): View {
        return view('portal.periodos.edit', [
            'periodo' => $periodo,
        ]);
    }

    public function update(
        UpdatePeriodoAcademicoRequest $request,
        PeriodoAcademico $periodo
    ): RedirectResponse {
        try {
            $datos = $request->validated();

            unset($datos['codigo']);

            $periodo->update($datos);

            return redirect()
                ->route(
                    'portal.periodos.show',
                    $periodo
                )
                ->with(
                    'success',
                    'El período académico fue actualizado correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al actualizar período académico.',
                [
                    'periodo_id' => $periodo->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible actualizar el período académico.'
                );
        }
    }

    public function cambiarEstado(
        PeriodoAcademico $periodo
    ): RedirectResponse {
        try {
            $nuevoEstado =
                $periodo->estado === 'activo'
                    ? 'inactivo'
                    : 'activo';

            $periodo->update([
                'estado' => $nuevoEstado,
            ]);

            return back()->with(
                'success',
                $nuevoEstado === 'activo'
                    ? 'El período académico fue activado correctamente.'
                    : 'El período académico fue desactivado correctamente.'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error al cambiar estado del período académico.',
                [
                    'periodo_id' => $periodo->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()->with(
                'error',
                'No fue posible cambiar el estado del período académico.'
            );
        }
    }
}