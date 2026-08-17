<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Grupo;
use App\Models\Persona;
use App\Models\SolicitudInscripcion;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Mostrar resumen general del Portal EDMA.
     */
    public function index(): View
    {
        $personasRegistradas = Persona::query()
            ->count();

        $estudiantesActivos = Estudiante::query()
            ->where('estado', 'activo')
            ->count();

        $estudiantesInactivos = Estudiante::query()
            ->where('estado', 'inactivo')
            ->count();

        $docentesActivos = Docente::query()
            ->where('estado', 'activo')
            ->count();

        $docentesInactivos = Docente::query()
            ->where('estado', 'inactivo')
            ->count();

        $solicitudesPendientes =
            SolicitudInscripcion::query()
                ->whereIn(
                    'estado',
                    [
                        'pendiente',
                        'en_revision',
                    ]
                )
                ->count();

        $gruposActivos = Grupo::query()
            ->where('estado', 'activo')
            ->count();

        return view(
            'portal.dashboard',
            [
                'personasRegistradas' =>
                    $personasRegistradas,

                'estudiantesActivos' =>
                    $estudiantesActivos,

                'estudiantesInactivos' =>
                    $estudiantesInactivos,

                'docentesActivos' =>
                    $docentesActivos,

                'docentesInactivos' =>
                    $docentesInactivos,

                'solicitudesPendientes' =>
                    $solicitudesPendientes,

                'gruposActivos' =>
                    $gruposActivos,
            ]
        );
    }
}