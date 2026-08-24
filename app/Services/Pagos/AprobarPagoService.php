<?php

namespace App\Services\Pagos;

use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AprobarPagoService
{
    public function __construct(
        private readonly AplicarPagoCuotasService
            $aplicarPagoCuotasService
    ) {
    }

    public function ejecutar(
        Pago $pago,
        float $montoConfirmado,
        int $usuarioId
    ): Pago {
        return DB::transaction(
            function () use (
                $pago,
                $montoConfirmado,
                $usuarioId
            ): Pago {

                $pago = Pago::query()
                    ->whereKey(
                        $pago->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $pago->estado
                    !== 'pendiente_revision'
                ) {
                    throw new RuntimeException(
                        'Este pago ya fue procesado anteriormente.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Si ya existe matrícula, comprobar saldo antes de aprobar
                |--------------------------------------------------------------------------
                */

                if ($pago->matricula_id) {

                    $matricula = $pago
                        ->matricula()
                        ->with(
                            'cuotas.aplicacionesPago'
                        )
                        ->first();

                    if (!$matricula) {
                        throw new RuntimeException(
                            'No se encontró la matrícula asociada al pago.'
                        );
                    }

                    $saldoPendiente =
                        (float) $matricula
                            ->cuotas
                            ->sum(
                                function ($cuota): float {

                                    $pagado =
                                        (float) $cuota
                                            ->aplicacionesPago
                                            ->sum(
                                                'monto_aplicado'
                                            );

                                    return max(
                                        0,
                                        (float) $cuota->monto
                                        - $pagado
                                    );
                                }
                            );

                    if (
                        $montoConfirmado
                        > $saldoPendiente
                    ) {
                        throw new RuntimeException(
                            'El monto confirmado supera el saldo pendiente de la matrícula.'
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Monto definitivo confirmado por Administración
                |--------------------------------------------------------------------------
                */

                $pago->update([
                    'monto_total' =>
                        $montoConfirmado,

                    'estado' =>
                        'aprobado',

                    'revisado_at' =>
                        now(),

                    'revisado_por' =>
                        $usuarioId,

                    'motivo_rechazo' =>
                        null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Aplicar a cuotas si ya existe matrícula
                |--------------------------------------------------------------------------
                */

                if ($pago->matricula_id) {
                    $this
                        ->aplicarPagoCuotasService
                        ->ejecutar(
                            $pago->fresh([
                                'matricula.cuotas.aplicacionesPago',
                            ])
                        );
                }

                return $pago->fresh([
                    'estudiante.persona',
                    'periodoAcademico',
                    'matricula.cuotas',
                    'comprobantes',
                ]);
            },
            3
        );
    }
}