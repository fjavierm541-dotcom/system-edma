<?php

namespace App\Services\Pagos;

use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RechazarPagoService
{
    public function ejecutar(
        Pago $pago,
        string $motivo,
        int $usuarioId
    ): Pago {
        return DB::transaction(
            function () use (
                $pago,
                $motivo,
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

                $pago->update([
                    'estado' =>
                        'rechazado',

                    'revisado_at' =>
                        now(),

                    'revisado_por' =>
                        $usuarioId,

                    'motivo_rechazo' =>
                        trim($motivo),
                ]);

                return $pago->fresh([
                    'estudiante.persona',
                    'periodoAcademico',
                    'comprobantes',
                ]);
            },
            3
        );
    }
}