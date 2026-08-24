<?php

namespace App\Services\Pagos;

use App\Models\Pago;
use App\Models\PagoCuota;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AplicarPagoCuotasService
{
    public function ejecutar(Pago $pago): void
    {
        if ($pago->estado !== 'aprobado') {
            throw new RuntimeException(
                'El pago debe estar aprobado antes de aplicarlo a las cuotas.'
            );
        }

        if (!$pago->matricula_id) {
            /*
             * El pago inicial de una solicitud todavía puede no tener matrícula
             * hasta que el estudiante formalice su matrícula.
             *
             * En ese caso no hacemos nada aquí.
             */
            return;
        }

        DB::transaction(
            function () use ($pago): void {

                /*
                |--------------------------------------------------------------------------
                | Evitar aplicar dos veces el mismo pago
                |--------------------------------------------------------------------------
                */

                $yaAplicado = PagoCuota::query()
                    ->where(
                        'pago_id',
                        $pago->id
                    )
                    ->exists();

                if ($yaAplicado) {
                    throw new RuntimeException(
                        'Este pago ya fue aplicado a las cuotas de la matrícula.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Obtener cuotas en orden
                |--------------------------------------------------------------------------
                */

                $cuotas = $pago
                    ->matricula
                    ->cuotas()
                    ->with('aplicacionesPago')
                    ->orderBy('numero_cuota')
                    ->lockForUpdate()
                    ->get();

                if ($cuotas->isEmpty()) {
                    throw new RuntimeException(
                        'La matrícula no tiene cuotas registradas.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Saldo del pago
                |--------------------------------------------------------------------------
                */

                $saldoPago =
                    (float) $pago->monto_total;

                if ($saldoPago <= 0) {
                    throw new RuntimeException(
                        'El monto confirmado del pago debe ser mayor que cero.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Aplicar primero a las cuotas más antiguas
                |--------------------------------------------------------------------------
                */

                foreach ($cuotas as $cuota) {

                    if ($saldoPago <= 0) {
                        break;
                    }

                    $montoAplicadoCuota =
                        (float) $cuota
                            ->aplicacionesPago
                            ->sum(
                                'monto_aplicado'
                            );

                    $saldoCuota =
                        max(
                            0,
                            (float) $cuota->monto
                            - $montoAplicadoCuota
                        );

                    /*
                     * Ya está cubierta.
                     */
                    if ($saldoCuota <= 0) {
                        continue;
                    }

                    $montoAplicar =
                        min(
                            $saldoPago,
                            $saldoCuota
                        );

                    PagoCuota::query()->create([
                        'pago_id' =>
                            $pago->id,

                        'matricula_cuota_id' =>
                            $cuota->id,

                        'monto_aplicado' =>
                            $montoAplicar,
                    ]);

                    $nuevoTotalAplicado =
                        $montoAplicadoCuota
                        + $montoAplicar;

                    if (
                        $nuevoTotalAplicado
                        >= (float) $cuota->monto
                    ) {
                        $cuota->update([
                            'estado' =>
                                'pagada',

                            'fecha_pago_completo' =>
                                $pago->fecha_pago
                                    ?->toDateString()
                                ?? now()->toDateString(),
                        ]);
                    } else {
                        $cuota->update([
                            'estado' =>
                                'parcial',

                            'fecha_pago_completo' =>
                                null,
                        ]);
                    }

                    $saldoPago -=
                        $montoAplicar;
                }

                /*
                |--------------------------------------------------------------------------
                | Evitar pagos superiores al saldo real
                |--------------------------------------------------------------------------
                */

                if ($saldoPago > 0.009) {
                    throw new RuntimeException(
                        'El monto confirmado supera el saldo pendiente de la matrícula.'
                    );
                }
            },
            3
        );
    }
}