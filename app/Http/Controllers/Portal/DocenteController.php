<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocenteRequest;
use App\Http\Requests\UpdateDocenteRequest;
use App\Models\Docente;
use App\Models\Empleado;
use App\Services\Docentes\CrearDocenteService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DocenteController extends Controller
{
    public function __construct(
        private readonly CrearDocenteService $crearDocenteService
    ) {
    }

    public function index(Request $request): View
    {
        $termino = trim(
            (string) $request->query('buscar', '')
        );

        $estado = $request->query('estado');

        $docentes = Docente::query()
            ->with([
                'empleado.persona.paisResidencia',
            ])
            ->buscar($termino)
            ->when(
                in_array(
                    $estado,
                    ['activo', 'inactivo'],
                    true
                ),
                fn (Builder $query) => $query->where(
                    'estado',
                    $estado
                )
            )
            ->orderByDesc('fecha_inicio_docencia')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $resumen = [
            'total' => Docente::query()->count(),

            'activos' => Docente::query()
                ->where('estado', 'activo')
                ->count(),

            'inactivos' => Docente::query()
                ->where('estado', 'inactivo')
                ->count(),
        ];

        return view('portal.docentes.index', [
            'docentes' => $docentes,
            'resumen' => $resumen,
            'termino' => $termino,
            'estadoSeleccionado' => $estado,
        ]);
    }

    public function create(Request $request): View
    {
        $empleadoSeleccionado = null;

        $empleadoId = $request->integer('empleado');

        if ($empleadoId > 0) {
            $empleadoSeleccionado = Empleado::query()
                ->activos()
                ->doesntHave('docente')
                ->with('persona')
                ->find($empleadoId);
        }

        return view('portal.docentes.create', [
            'empleadosDisponibles' =>
                $this->obtenerEmpleadosDisponibles(),

            'empleadoSeleccionado' =>
                $empleadoSeleccionado,
        ]);
    }

    public function store(
        StoreDocenteRequest $request
    ): RedirectResponse {
        try {
            $datos = $request->validated();

            unset($datos['codigo_docente']);

            $docente = $this->crearDocenteService->ejecutar(
                $datos,
                $datos['fecha_inicio_docencia'] ?? null
            );

            return redirect()
                ->route(
                    'portal.docentes.show',
                    $docente
                )
                ->with(
                    'success',
                    'El perfil docente fue creado correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al registrar docente.',
                [
                    'empleado_id' =>
                        $request->input('empleado_id'),

                    'usuario_id' =>
                        auth()->id(),

                    'exception' =>
                        $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible crear el perfil docente.'
                );
        }
    }

    public function show(Docente $docente): View
    {
        $docente->load([
            'empleado.persona.paisResidencia',

            'empleado.persona.formacionesAcademicas' =>
                fn ($query) => $query
                    ->orderByDesc('es_principal')
                    ->orderByDesc('anio_graduacion')
                    ->orderByDesc('id'),

            'empleado.persona.formacionesAcademicas.pais',

            'empleado.cuentasBancarias' =>
                fn ($query) => $query
                    ->orderByDesc('activo')
                    ->orderByDesc('es_principal')
                    ->orderByDesc('id'),

            'empleado.cuentasBancarias.institucionFinanciera',
        ]);

        return view('portal.docentes.show', [
            'docente' => $docente,
        ]);
    }

    public function edit(Docente $docente): View
    {
        $docente->load([
            'empleado.persona',
        ]);

        return view('portal.docentes.edit', [
            'docente' => $docente,
        ]);
    }

    public function update(
        UpdateDocenteRequest $request,
        Docente $docente
    ): RedirectResponse {
        try {
            $datos = $request->validated();

            unset(
                $datos['empleado_id'],
                $datos['codigo_docente']
            );

            DB::transaction(
                fn () => $docente->update($datos)
            );

            return redirect()
                ->route(
                    'portal.docentes.show',
                    $docente
                )
                ->with(
                    'success',
                    'La información del docente fue actualizada correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al actualizar docente.',
                [
                    'docente_id' => $docente->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible actualizar el perfil docente.'
                );
        }
    }

    public function cambiarEstado(
        Docente $docente
    ): RedirectResponse {
        $nuevoEstado = $docente->estado === 'activo'
            ? 'inactivo'
            : 'activo';

        try {
            $docente->update([
                'estado' => $nuevoEstado,
            ]);

            return back()->with(
                'success',
                $nuevoEstado === 'activo'
                    ? 'El docente fue activado correctamente.'
                    : 'El docente fue desactivado correctamente.'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error al cambiar estado de docente.',
                [
                    'docente_id' => $docente->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()->with(
                'error',
                'No fue posible cambiar el estado del docente.'
            );
        }
    }

    private function obtenerEmpleadosDisponibles()
    {
        return Empleado::query()
            ->activos()
            ->doesntHave('docente')
            ->with('persona')
            ->orderBy('codigo_empleado')
            ->get();
    }
}