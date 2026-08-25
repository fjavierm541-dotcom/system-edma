<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\GrupoDocente;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MisGruposDocenteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listado de grupos asignados
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): View {
        $docente =
            $this->obtenerDocenteAutenticado(
                $request
            );

        /*
        |--------------------------------------------------------------------------
        | Obtener grupos asignados
        |--------------------------------------------------------------------------
        */

        $asignaciones = GrupoDocente::query()
            ->where(
                'docente_id',
                $docente->id
            )
            ->where(
                'activo',
                true
            )
            ->whereHas(
                'grupo',
                function ($query): void {
                    $query->where(
                        'estado',
                        '!=',
                        'cancelado'
                    );
                }
            )
            ->with([
                'grupo.nivel.programa',
                'grupo.periodoAcademico',
                'grupo.horarios.horario',

                'grupo.matriculas' =>
                    function ($query): void {
                        $query->whereIn(
                            'estado',
                            [
                                'pendiente',
                                'activa',
                            ]
                        );
                    },
            ])
            ->get()
            ->sortBy(
                function (
                    GrupoDocente $asignacion
                ): array {
                    $grupo =
                        $asignacion->grupo;

                    $prioridadEstado =
                        match ($grupo->estado) {
                            'activo' => 1,
                            'planificado' => 2,
                            'finalizado' => 3,
                            default => 4,
                        };

                    $fechaPeriodo =
                        $grupo
                            ->periodoAcademico
                            ?->fecha_inicio
                            ?->timestamp
                        ?? 0;

                    return [
                        $prioridadEstado,
                        -$fechaPeriodo,
                        $grupo->nombre,
                    ];
                }
            )
            ->values();

        return view(
            'portal.docente.mis-grupos.index',
            [
                'docente' =>
                    $docente,

                'persona' =>
                    $docente
                        ->empleado
                        ->persona,

                'asignaciones' =>
                    $asignaciones,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Detalle de grupo
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        Grupo $grupo
    ): View {
        $docente =
            $this->obtenerDocenteAutenticado(
                $request
            );

        /*
        |--------------------------------------------------------------------------
        | Validar que el grupo pertenezca al docente
        |--------------------------------------------------------------------------
        */

        $asignacion = GrupoDocente::query()
            ->where(
                'grupo_id',
                $grupo->id
            )
            ->where(
                'docente_id',
                $docente->id
            )
            ->where(
                'activo',
                true
            )
            ->first();

        abort_unless(
            $asignacion,
            403,
            'No tienes acceso a este grupo.'
        );

        /*
        |--------------------------------------------------------------------------
        | Cargar información académica del grupo
        |--------------------------------------------------------------------------
        */

        $grupo->load([
            'nivel.programa',
            'periodoAcademico',
            'horarios.horario',

            'matriculas' =>
                function ($query): void {
                    $query
                        ->whereIn(
                            'estado',
                            [
                                'pendiente',
                                'activa',
                            ]
                        )
                        ->with([
                            'estudiante.persona',
                            'calificacionFinal',
                        ])
                        ->orderBy('id');
                },
        ]);

        /*
        |--------------------------------------------------------------------------
        | Datos derivados
        |--------------------------------------------------------------------------
        */

        $cantidadEstudiantes =
            $grupo
                ->matriculas
                ->count();

        $cantidadCalificados =
            $grupo
                ->matriculas
                ->filter(
                    fn ($matricula) =>
                        $matricula
                            ->calificacionFinal
                        &&
                        in_array(
                            $matricula
                                ->calificacionFinal
                                ->estado,
                            [
                                'confirmada',
                                'bloqueada',
                            ],
                            true
                        )
                )
                ->count();

        $cantidadPendientes =
            max(
                0,
                $cantidadEstudiantes
                - $cantidadCalificados
            );

            /*
|--------------------------------------------------------------------------
| Ventana de carga de calificaciones
|--------------------------------------------------------------------------
*/

$periodo =
    $grupo->periodoAcademico;

$calificacionesDesde =
    $periodo->calificaciones_desde;

$calificacionesHasta =
    $periodo->calificaciones_hasta;

$ahora =
    now();

if (
    !$calificacionesDesde
    ||
    !$calificacionesHasta
) {

    $estadoCargaCalificaciones =
        'no_configurada';

} elseif (
    $ahora->lt(
        $calificacionesDesde
    )
) {

    $estadoCargaCalificaciones =
        'programada';

} elseif (
    $ahora->betweenIncluded(
        $calificacionesDesde,
        $calificacionesHasta
    )
) {

    $estadoCargaCalificaciones =
        'abierta';

} else {

    $estadoCargaCalificaciones =
        'cerrada';
}

$puedeCargarCalificaciones =
    $estadoCargaCalificaciones
    === 'abierta';

        return view(
            'portal.docente.mis-grupos.show',
            [
                'grupo' =>
                    $grupo,

                'asignacion' =>
                    $asignacion,

                'cantidadEstudiantes' =>
                    $cantidadEstudiantes,

                'cantidadCalificados' =>
                    $cantidadCalificados,

                'cantidadPendientes' =>
                    $cantidadPendientes,

                'estadoCargaCalificaciones' =>
                    $estadoCargaCalificaciones,

                'puedeCargarCalificaciones' =>
                    $puedeCargarCalificaciones,

                'calificacionesDesde' =>
                    $calificacionesDesde,

                'calificacionesHasta' =>
                    $calificacionesHasta,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener docente autenticado
    |--------------------------------------------------------------------------
    */

    private function obtenerDocenteAutenticado(
        Request $request
    ): Docente {
        $user =
            $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Docente'),
            403
        );

        $user->load('persona');

        $persona =
            $user->persona;

        abort_unless(
            $persona,
            403,
            'No se encontró un expediente personal asociado a tu cuenta.'
        );

        $docente = Docente::query()
            ->whereHas(
                'empleado',
                function ($query) use (
                    $persona
                ): void {
                    $query->where(
                        'persona_id',
                        $persona->id
                    );
                }
            )
            ->with([
                'empleado.persona',
            ])
            ->first();

        abort_unless(
            $docente,
            403,
            'No se encontró un expediente docente asociado a tu cuenta.'
        );

        return $docente;
    }

    
}