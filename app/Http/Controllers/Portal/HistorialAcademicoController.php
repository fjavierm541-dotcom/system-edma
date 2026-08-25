<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistorialAcademicoController extends Controller
{
    public function index(
        Request $request
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Estudiante'),
            403
        );

        $user->load([
            'persona.estudiante.nivelAutorizado.programa',
        ]);

        $persona =
            $user->persona;

        $estudiante =
            $persona?->estudiante;

        abort_unless(
            $persona
            && $estudiante,
            403,
            'No se encontró un expediente de estudiante asociado a tu cuenta.'
        );

        $matriculas = Matricula::query()
            ->where(
                'estudiante_id',
                $estudiante->id
            )
            ->with([
                'grupo.nivel.programa',
                'grupo.periodoAcademico',
                'calificacionFinal',
            ])
            ->get()
            ->sortByDesc(
                fn (Matricula $matricula) =>
                    $matricula
                        ->grupo
                        ->periodoAcademico
                        ->fecha_inicio
                        ?->timestamp
                    ?? 0
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Agrupar por año
        |--------------------------------------------------------------------------
        */

        $historialPorAnio =
            $matriculas
                ->groupBy(
                    fn (Matricula $matricula) =>
                        $matricula
                            ->grupo
                            ->periodoAcademico
                            ->fecha_inicio
                            ?->format('Y')
                        ?? 'Sin año'
                );

        return view(
            'portal.estudiante.historial-academico',
            [
                'persona' =>
                    $persona,

                'estudiante' =>
                    $estudiante,

                'historialPorAnio' =>
                    $historialPorAnio,
            ]
        );
    }
}