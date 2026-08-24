<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarPagoRequest;
use App\Http\Requests\RechazarPagoRequest;
use App\Models\Pago;
use App\Services\Pagos\AprobarPagoService;
use App\Services\Pagos\RechazarPagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class PagoAdminController extends Controller
{
    public function index(
        Request $request
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol(
                'Administrador'
            ),
            403
        );

        $pagos = Pago::query()
            ->with([
                'estudiante.persona',
                'periodoAcademico',
                'matricula.grupo.nivel',
                'comprobantes',
            ])
            ->orderByRaw(
                "
                CASE
                    WHEN estado = 'pendiente_revision'
                    THEN 0
                    ELSE 1
                END
                "
            )
            ->orderByRaw(
                "
                CASE
                    WHEN estado = 'pendiente_revision'
                    THEN created_at
                    ELSE NULL
                END ASC
                "
            )
            ->orderByRaw(
                "
                CASE
                    WHEN estado <> 'pendiente_revision'
                    THEN revisado_at
                    ELSE NULL
                END DESC
                "
            )
            ->orderByDesc(
                'id'
            )
            ->get();

        return view(
            'portal.pagos.admin.index',
            [
                'pagos' =>
                    $pagos,
            ]
        );
    }

    public function show(
        Request $request,
        Pago $pago
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol(
                'Administrador'
            ),
            403
        );

        $pago->load([
            'estudiante.persona',
            'periodoAcademico',
            'matricula.grupo.nivel.programa',
            'matricula.cuotas.aplicacionesPago',
            'comprobantes',
            'revisadoPor.persona',
        ]);

        return view(
            'portal.pagos.admin.show',
            [
                'pago' =>
                    $pago,
            ]
        );
    }

    public function aprobar(
        AprobarPagoRequest $request,
        Pago $pago,
        AprobarPagoService $service
    ): RedirectResponse {
        try {
            $service->ejecutar(
                $pago,
                (float) $request
                    ->validated(
                        'monto_confirmado'
                    ),
                $request->user()->id
            );

            return redirect()
                ->route(
                    'portal.admin.pagos.index'
                )
                ->with(
                    'success',
                    'El pago fue aprobado y aplicado correctamente.'
                );
        } catch (RuntimeException $exception) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }

    public function rechazar(
        RechazarPagoRequest $request,
        Pago $pago,
        RechazarPagoService $service
    ): RedirectResponse {
        try {
            $service->ejecutar(
                $pago,
                $request->validated(
                    'motivo_rechazo'
                ),
                $request->user()->id
            );

            return redirect()
                ->route(
                    'portal.admin.pagos.index'
                )
                ->with(
                    'success',
                    'El pago fue rechazado correctamente.'
                );
        } catch (RuntimeException $exception) {
            return back()
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }
}