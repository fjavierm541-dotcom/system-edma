<?php

namespace App\Http\Controllers\Portal;
use App\Models\Horario;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrupoRequest;
use App\Http\Requests\UpdateGrupoRequest;
use App\Models\Grupo;
use App\Models\Nivel;
use App\Models\PeriodoAcademico;
use App\Models\Programa;
use App\Services\Grupos\CrearGrupoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GrupoController extends Controller
{
    public function __construct(
        private readonly CrearGrupoService $crearGrupoService
    ) {
    }

    public function index(Request $request): View
    {
        $termino = trim(
            (string) $request->query(
                'buscar',
                ''
            )
        );

        $estado = $request->query('estado');

        $programaId = $request->integer(
            'programa'
        );

        $periodoId = $request->integer(
            'periodo'
        );

        $grupos = Grupo::query()
            ->with([
                'nivel.programa',
                'periodoAcademico',
            ])
            ->buscar($termino)
            ->when(
                $programaId > 0,
                fn (Builder $query) =>
                    $query->whereHas(
                        'nivel',
                        fn (Builder $nivelQuery) =>
                            $nivelQuery->where(
                                'programa_id',
                                $programaId
                            )
                    )
            )
            ->when(
                $periodoId > 0,
                fn (Builder $query) =>
                    $query->where(
                        'periodo_academico_id',
                        $periodoId
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
            ->orderByDesc('fecha_inicio')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        $programas = Programa::query()
            ->orderBy('nombre')
            ->get([
                'id',
                'codigo',
                'nombre',
            ]);

        $periodos = PeriodoAcademico::query()
            ->orderByDesc('fecha_inicio')
            ->get([
                'id',
                'codigo',
                'nombre',
                'estado',
            ]);

        $resumen = [
            'total' =>
                Grupo::query()->count(),

            'activos' =>
                Grupo::query()
                    ->activos()
                    ->count(),

            'inactivos' =>
                Grupo::query()
                    ->inactivos()
                    ->count(),
        ];

        return view('portal.grupos.index', [
            'grupos' => $grupos,
            'programas' => $programas,
            'periodos' => $periodos,
            'resumen' => $resumen,
            'termino' => $termino,
            'estadoSeleccionado' => $estado,
            'programaSeleccionado' =>
                $programaId,
            'periodoSeleccionado' =>
                $periodoId,
        ]);
    }

    public function create(
        Request $request
    ): View {
        $programas = Programa::query()
            ->activos()
            ->orderBy('nombre')
            ->get([
                'id',
                'codigo',
                'nombre',
                'segmento',
            ]);

        $niveles = Nivel::query()
            ->activos()
            ->whereHas(
                'programa',
                fn (Builder $query) =>
                    $query->where(
                        'estado',
                        'activo'
                    )
            )
            ->with('programa')
            ->orderBy('programa_id')
            ->orderBy('orden')
            ->get();

        $periodos = PeriodoAcademico::query()
            ->activos()
            ->orderByDesc('fecha_inicio')
            ->get();

        return view('portal.grupos.create', [
            'programas' => $programas,
            'niveles' => $niveles,
            'periodos' => $periodos,
            'programaSeleccionado' =>
                $request->integer('programa'),
            'nivelSeleccionado' =>
                $request->integer('nivel'),
            'periodoSeleccionado' =>
                $request->integer('periodo'),
        ]);
    }

    public function store(
        StoreGrupoRequest $request
    ): RedirectResponse {
        try {
            $grupo =
                $this->crearGrupoService
                    ->ejecutar(
                        $request->validated()
                    );

            return redirect()
                ->route(
                    'portal.grupos.show',
                    $grupo
                )
                ->with(
                    'success',
                    'El grupo fue registrado correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al registrar grupo.',
                [
                    'nivel_id' =>
                        $request->input('nivel_id'),

                    'periodo_academico_id' =>
                        $request->input(
                            'periodo_academico_id'
                        ),

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
                    'No fue posible registrar el grupo.'
                );
        }
    }

    public function show(
        Grupo $grupo
    ): View {
        $grupo->load([
            'nivel.programa',
            'periodoAcademico',

            'horarios' => fn ($query) =>
                $query
                    ->with('horario')
                    ->orderByRaw("
                        FIELD(
                            dia_semana,
                            'lunes',
                            'martes',
                            'miércoles',
                            'jueves',
                            'viernes',
                            'sábado',
                            'domingo'
                        )
                    ")
                    ->orderBy('id'),
        ]);

        $horariosDisponibles = Horario::query()
            ->activos()
            ->orderBy('hora_inicio')
            ->orderBy('nombre')
            ->get();

        return view('portal.grupos.show', [
            'grupo' => $grupo,
            'horariosDisponibles' => $horariosDisponibles,
        ]);
    }

    public function edit(
        Grupo $grupo
    ): View {
        $grupo->load([
            'nivel.programa',
            'periodoAcademico',
        ]);

        $programas = Programa::query()
            ->where(function (
                Builder $query
            ) use ($grupo) {
                $query
                    ->where(
                        'estado',
                        'activo'
                    )
                    ->orWhere(
                        'id',
                        $grupo
                            ->nivel
                            ->programa_id
                    );
            })
            ->orderBy('nombre')
            ->get();

        $niveles = Nivel::query()
            ->where(function (
                Builder $query
            ) use ($grupo) {
                $query
                    ->where(
                        'estado',
                        'activo'
                    )
                    ->orWhere(
                        'id',
                        $grupo->nivel_id
                    );
            })
            ->with('programa')
            ->orderBy('programa_id')
            ->orderBy('orden')
            ->get();

        $periodos = PeriodoAcademico::query()
            ->where(function (
                Builder $query
            ) use ($grupo) {
                $query
                    ->where(
                        'estado',
                        'activo'
                    )
                    ->orWhere(
                        'id',
                        $grupo
                            ->periodo_academico_id
                    );
            })
            ->orderByDesc('fecha_inicio')
            ->get();

        return view('portal.grupos.edit', [
            'grupo' => $grupo,
            'programas' => $programas,
            'niveles' => $niveles,
            'periodos' => $periodos,
            'programaSeleccionado' =>
                $grupo
                    ->nivel
                    ->programa_id,
            'nivelSeleccionado' =>
                $grupo->nivel_id,
            'periodoSeleccionado' =>
                $grupo
                    ->periodo_academico_id,
        ]);
    }

    public function update(
        UpdateGrupoRequest $request,
        Grupo $grupo
    ): RedirectResponse {
        try {
            $datos = $request->validated();

            /*
            |--------------------------------------------------------------------------
            | Datos institucionales no editables
            |--------------------------------------------------------------------------
            */

            unset($datos['codigo']);

            $datos['modalidad'] = 'virtual';
            $datos['cupo_minimo'] = 3;
            $datos['cupo_maximo'] = 25;

            $grupo->update($datos);

            return redirect()
                ->route(
                    'portal.grupos.show',
                    $grupo
                )
                ->with(
                    'success',
                    'El grupo fue actualizado correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al actualizar grupo.',
                [
                    'grupo_id' =>
                        $grupo->id,

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
                    'No fue posible actualizar el grupo.'
                );
        }
    }

    public function cambiarEstado(
        Grupo $grupo
    ): RedirectResponse {
        try {
            $nuevoEstado =
                $grupo->estado === 'activo'
                    ? 'inactivo'
                    : 'activo';

            if (
                $nuevoEstado === 'activo' &&
                (
                    $grupo->nivel?->estado
                        !== 'activo' ||
                    $grupo
                        ->nivel
                        ?->programa
                        ?->estado
                        !== 'activo' ||
                    $grupo
                        ->periodoAcademico
                        ?->estado
                        !== 'activo'
                )
            ) {
                return back()->with(
                    'error',
                    'No puede activarse el grupo mientras su nivel, programa o período académico se encuentre inactivo.'
                );
            }

            $grupo->update([
                'estado' => $nuevoEstado,
            ]);

            return back()->with(
                'success',
                $nuevoEstado === 'activo'
                    ? 'El grupo fue activado correctamente.'
                    : 'El grupo fue desactivado correctamente.'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error al cambiar estado del grupo.',
                [
                    'grupo_id' => $grupo->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()->with(
                'error',
                'No fue posible cambiar el estado del grupo.'
            );
        }
    }
}