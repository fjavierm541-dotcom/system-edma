<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComprobanteMatriculaController extends Controller
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

        $user->load(
            'persona.estudiante'
        );

        $estudiante =
            $user->persona?->estudiante;

        abort_unless(
            $estudiante,
            403,
            'No se encontró un expediente de estudiante asociado a tu cuenta.'
        );

        $matricula = Matricula::query()
            ->where(
                'estudiante_id',
                $estudiante->id
            )
            ->where(
                'estado',
                'activa'
            )
            ->whereHas(
                'grupo.periodoAcademico',
                function ($query): void {
                    $query->whereIn(
                        'estado',
                        [
                            'matricula_abierta',
                            'en_curso',
                        ]
                    );
                }
            )
            ->with([
                'estudiante.persona',
                'grupo.nivel.programa',
                'grupo.periodoAcademico',
                'grupo.horarios.horario',
                'grupo.docentes.docente.empleado.persona',
            ])
            ->latest('fecha_matricula')
            ->first();

        return view(
            'portal.matriculas.comprobante',
            [
                'estudiante' =>
                    $estudiante,

                'matricula' =>
                    $matricula,
            ]
        );
    }
}