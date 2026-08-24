<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePagoEstudianteRequest;
use App\Models\Pago;
use App\Services\Pagos\RegistrarPagoEstudianteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class PagosEstudianteController extends Controller
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

        $pagos = Pago::query()
            ->where(
                'estudiante_id',
                $estudiante->id
            )
            ->with([
                'periodoAcademico',
                'matricula.grupo.nivel',
                'comprobantes',
                'aplicacionesCuotas.cuota',
            ])
            ->orderByDesc('fecha_pago')
            ->orderByDesc('id')
            ->get();

        return view(
            'portal.pagos.index',
            [
                'estudiante' => $estudiante,
                'pagos' => $pagos,
            ]
        );
    }

    public function store(
        StorePagoEstudianteRequest $request,
        RegistrarPagoEstudianteService $service
    ): RedirectResponse {
        $user = $request->user();

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

        try {
            $pago = $service->ejecutar(
                $estudiante,
                $request->validated(),
                $request->file('comprobante')
            );

            return redirect()
                ->route(
                    'portal.pagos.index'
                )
                ->with(
                    'success',
                    "Tu pago {$pago->codigo_pago} fue registrado y enviado a revisión."
                );
        } catch (RuntimeException $exception) {
            return redirect()
                ->route(
                    'portal.pagos.index'
                )
                ->withInput()
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }
}