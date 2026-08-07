<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;
use App\Models\Estudiante;
use App\Models\NivelEscolaridad;
use App\Models\Persona;
use App\Services\Estudiantes\CrearEstudianteService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EstudianteController extends Controller
{
    public function __construct(
        private readonly CrearEstudianteService $crearEstudianteService
    ) {
    }

    /**
     * Mostrar el listado de estudiantes.
     */
    public function index(Request $request): View
    {
        $termino = trim((string) $request->query('buscar', ''));
        $estado = $request->query('estado');

        $estudiantes = Estudiante::query()
            ->with([
                'persona.paisResidencia',
                'nivelEscolaridad',
            ])
            ->buscar($termino)
            ->when(
                in_array($estado, ['activo', 'inactivo'], true),
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
            'total' => Estudiante::query()->count(),

            'activos' => Estudiante::query()
                ->where('estado', 'activo')
                ->count(),

            'inactivos' => Estudiante::query()
                ->where('estado', 'inactivo')
                ->count(),
        ];

        return view('portal.estudiantes.index', [
            'estudiantes' => $estudiantes,
            'resumen' => $resumen,
            'termino' => $termino,
            'estadoSeleccionado' => $estado,
        ]);
    }

    /**
     * Mostrar el formulario para crear un estudiante.
     */
    public function create(Request $request): View
    {
        $personaSeleccionada = null;

        $personaId = $request->integer('persona');

        if ($personaId > 0) {
            $personaSeleccionada = Persona::query()
                ->activas()
                ->doesntHave('estudiante')
                ->find($personaId);
        }

        return view('portal.estudiantes.create', [
            'personasDisponibles' => $this->obtenerPersonasDisponibles(),
            'personaSeleccionada' => $personaSeleccionada,
            'nivelesEscolaridad' => $this->obtenerNivelesEscolaridad(),
        ]);
    }

    /**
     * Guardar un nuevo expediente estudiantil.
     */
    public function store(
        StoreEstudianteRequest $request
    ): RedirectResponse {
        try {
            $datos = $request->validated();

            /*
             * El código institucional nunca proviene del formulario.
             * CrearEstudianteService lo genera de forma segura.
             */
            unset($datos['codigo_estudiante']);

            $estudiante = $this->crearEstudianteService->ejecutar(
                $datos,
                $datos['fecha_ingreso'] ?? null
            );

            return redirect()
                ->route(
                    'portal.estudiantes.show',
                    $estudiante
                )
                ->with(
                    'success',
                    'El expediente del estudiante fue creado correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al registrar un estudiante.',
                [
                    'exception' => $exception,
                    'persona_id' => $request->input('persona_id'),
                    'usuario_id' => auth()->id(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Ocurrió un error al crear el expediente del estudiante. Intente nuevamente.'
                );
        }
    }

    /**
     * Mostrar el expediente del estudiante.
     */
    public function show(Estudiante $estudiante): View
{
    $estudiante->load([
        'persona.paisResidencia',

        'persona.documentos' => fn ($query) => $query
            ->orderByDesc('created_at'),

        'nivelEscolaridad',

        'responsables' => fn ($query) => $query
            ->orderByDesc('activo')
            ->orderByDesc('es_principal')
            ->orderBy('id'),

        'responsables.personaResponsable',
    ]);

    $personasResponsablesDisponibles = Persona::query()
        ->where('id', '!=', $estudiante->persona_id)
        ->whereNull('deleted_at')
        ->whereDoesntHave(
            'responsabilidadesEstudiantiles',
            fn (Builder $query) => $query->where(
                'estudiante_id',
                $estudiante->id
            )
        )
        ->orderBy('primer_apellido')
        ->orderBy('primer_nombre')
        ->get([
            'id',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'numero_documento',
            'telefono_movil',
            'correo_personal',
            'foto_perfil',
        ]);

    return view('portal.estudiantes.show', [
        'estudiante' => $estudiante,
        'personasResponsablesDisponibles' =>
            $personasResponsablesDisponibles,
    ]);
}

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(Estudiante $estudiante): View
    {
        $estudiante->load([
            'persona.paisResidencia',
            'nivelEscolaridad',
        ]);

        return view('portal.estudiantes.edit', [
            'estudiante' => $estudiante,
            'nivelesEscolaridad' => $this->obtenerNivelesEscolaridad(),
        ]);
    }

    /**
     * Actualizar la información propia del estudiante.
     */
    public function update(
        UpdateEstudianteRequest $request,
        Estudiante $estudiante
    ): RedirectResponse {
        try {
            $datos = $request->validated();

            /*
             * La persona y el código son inmutables después
             * de crear el expediente.
             */
            unset(
                $datos['persona_id'],
                $datos['codigo_estudiante']
            );

            DB::transaction(
                fn () => $estudiante->update($datos)
            );

            return redirect()
                ->route(
                    'portal.estudiantes.show',
                    $estudiante
                )
                ->with(
                    'success',
                    'La información del estudiante fue actualizada correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al actualizar un estudiante.',
                [
                    'estudiante_id' => $estudiante->id,
                    'exception' => $exception,
                    'usuario_id' => auth()->id(),
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
     * Cambiar el estado activo/inactivo.
     */
    public function cambiarEstado(
        Estudiante $estudiante
    ): RedirectResponse {
        $nuevoEstado = $estudiante->estado === 'activo'
            ? 'inactivo'
            : 'activo';

        try {
            $estudiante->update([
                'estado' => $nuevoEstado,
            ]);

            $mensaje = $nuevoEstado === 'activo'
                ? 'El estudiante fue activado correctamente.'
                : 'El estudiante fue desactivado correctamente.';

            return back()->with('success', $mensaje);
        } catch (Throwable $exception) {
            Log::error(
                'Error al cambiar el estado de un estudiante.',
                [
                    'estudiante_id' => $estudiante->id,
                    'exception' => $exception,
                    'usuario_id' => auth()->id(),
                ]
            );

            return back()->with(
                'error',
                'No fue posible cambiar el estado del estudiante.'
            );
        }
    }

    /**
     * Personas activas que todavía no poseen expediente.
     */
    private function obtenerPersonasDisponibles()
    {
        return Persona::query()
            ->activas()
            ->doesntHave('estudiante')
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

    /**
     * Catálogo activo de niveles de escolaridad.
     */
    private function obtenerNivelesEscolaridad()
    {
        return NivelEscolaridad::query()
            ->activos()
            ->ordenados()
            ->get([
                'id',
                'codigo',
                'nombre',
            ]);
    }
}