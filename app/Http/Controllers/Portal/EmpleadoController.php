<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpleadoRequest;
use App\Http\Requests\UpdateEmpleadoRequest;
use App\Models\Empleado;
use App\Models\Persona;
use App\Services\Empleados\CrearEmpleadoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmpleadoController extends Controller
{
    public function __construct(
        private readonly CrearEmpleadoService $crearEmpleadoService
    ) {
    }

    /**
     * Mostrar listado de empleados.
     */
    public function index(Request $request): View
    {
        $termino = trim(
            (string) $request->query('buscar', '')
        );

        $estado = $request->query('estado');

        $empleados = Empleado::query()
            ->with([
                'persona.paisResidencia',
                'docente',
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
            ->orderByDesc('fecha_ingreso')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $resumen = [
            'total' => Empleado::query()->count(),

            'activos' => Empleado::query()
                ->where('estado', 'activo')
                ->count(),

            'inactivos' => Empleado::query()
                ->where('estado', 'inactivo')
                ->count(),
        ];

        return view('portal.empleados.index', [
            'empleados' => $empleados,
            'resumen' => $resumen,
            'termino' => $termino,
            'estadoSeleccionado' => $estado,
        ]);
    }

    /**
     * Mostrar formulario de registro.
     */
    public function create(Request $request): View
    {
        $personaSeleccionada = null;

        $personaId = $request->integer('persona');

        if ($personaId > 0) {
            $personaSeleccionada = Persona::query()
                ->activas()
                ->doesntHave('empleado')
                ->find($personaId);
        }

        return view('portal.empleados.create', [
            'personasDisponibles' =>
                $this->obtenerPersonasDisponibles(),

            'personaSeleccionada' =>
                $personaSeleccionada,
        ]);
    }

    /**
     * Guardar expediente del empleado.
     */
    public function store(
        StoreEmpleadoRequest $request
    ): RedirectResponse {
        try {
            $datos = $request->validated();

            /*
             * El código nunca se recibe desde el formulario.
             */
            unset($datos['codigo_empleado']);

            $empleado = $this->crearEmpleadoService->ejecutar(
                $datos,
                $datos['fecha_ingreso'] ?? null
            );

            return redirect()
                ->route(
                    'portal.empleados.show',
                    $empleado
                )
                ->with(
                    'success',
                    'El expediente del empleado fue creado correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al registrar un empleado.',
                [
                    'persona_id' =>
                        $request->input('persona_id'),

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
                    'Ocurrió un error al crear el expediente del empleado. Intente nuevamente.'
                );
        }
    }

   /**
 * Mostrar expediente del empleado.
 */
public function show(Empleado $empleado): View
{
    $empleado->load([
        'persona.paisResidencia',

        'persona.documentos' =>
            fn ($query) => $query
                ->orderByDesc('created_at'),

        'persona.formacionesAcademicas' =>
            fn ($query) => $query
                ->orderByDesc('es_principal')
                ->orderByDesc('anio_graduacion')
                ->orderByDesc('id'),

        'persona.formacionesAcademicas.pais',

        'persona.formacionesAcademicas.documentoPersona',

        'cuentasBancarias' =>
            fn ($query) => $query
                ->orderByDesc('activo')
                ->orderByDesc('es_principal')
                ->orderByDesc('id'),

        'cuentasBancarias.institucionFinanciera',

        'docente',
    ]);

    $paises = \App\Models\Pais::query()
        ->activos()
        ->orderBy('nombre')
        ->get([
            'id',
            'nombre',
        ]);

    $documentosPersona = $empleado->persona
        ->documentos
        ->values();

    return view('portal.empleados.show', [
        'empleado' => $empleado,
        'paises' => $paises,
        'documentosPersona' => $documentosPersona,
    ]);
}

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Empleado $empleado): View
    {
        $empleado->load([
            'persona.paisResidencia',
            'docente',
        ]);

        return view('portal.empleados.edit', [
            'empleado' => $empleado,
        ]);
    }

    /**
     * Actualizar expediente laboral.
     */
    public function update(
        UpdateEmpleadoRequest $request,
        Empleado $empleado
    ): RedirectResponse {
        try {
            $datos = $request->validated();

            /*
             * La persona y código del empleado son inmutables.
             */
            unset(
                $datos['persona_id'],
                $datos['codigo_empleado']
            );

            DB::transaction(
                fn () => $empleado->update($datos)
            );

            return redirect()
                ->route(
                    'portal.empleados.show',
                    $empleado
                )
                ->with(
                    'success',
                    'La información del empleado fue actualizada correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al actualizar un empleado.',
                [
                    'empleado_id' => $empleado->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Ocurrió un error al actualizar el expediente. Intente nuevamente.'
                );
        }
    }

    /**
     * Activar o desactivar empleado.
     */
    public function cambiarEstado(
        Empleado $empleado
    ): RedirectResponse {
        $nuevoEstado = $empleado->estado === 'activo'
            ? 'inactivo'
            : 'activo';

        try {
            DB::transaction(
                function () use (
                    $empleado,
                    $nuevoEstado
                ): void {
                    $empleado->update([
                        'estado' => $nuevoEstado,
                    ]);

                    /*
                     * Si el empleado también es docente y se desactiva,
                     * no cambiaremos todavía automáticamente el estado
                     * del docente.
                     *
                     * Esa regla se definirá cuando desarrollemos
                     * específicamente el módulo Docentes.
                     */
                }
            );

            return back()->with(
                'success',
                $nuevoEstado === 'activo'
                    ? 'El empleado fue activado correctamente.'
                    : 'El empleado fue desactivado correctamente.'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error al cambiar el estado de un empleado.',
                [
                    'empleado_id' => $empleado->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()->with(
                'error',
                'No fue posible cambiar el estado del empleado.'
            );
        }
    }

    /**
     * Personas activas que todavía no son empleados.
     */
    private function obtenerPersonasDisponibles()
    {
        return Persona::query()
            ->activas()
            ->doesntHave('empleado')
            ->orderBy('primer_apellido')
            ->orderBy('primer_nombre')
            ->get([
                'id',
                'primer_nombre',
                'segundo_nombre',
                'primer_apellido',
                'segundo_apellido',
                'tipo_documento',
                'numero_documento',
                'correo_personal',
                'telefono_movil',
                'foto_perfil',
            ]);
    }
}