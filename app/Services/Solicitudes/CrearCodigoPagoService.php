<?php

namespace App\Services\Pagos;

use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CrearCodigoPagoService
{
    private const PREFIJO = 'EDMA-PAG';

    private const LONGITUD = 5;

    public function generar(int $anio): string
    {
        $nombreBloqueo =
            "edma_codigo_pago_{$anio}";

        $resultado = DB::selectOne(
            'SELECT GET_LOCK(?, 10) AS adquirido',
            [$nombreBloqueo]
        );

        $adquirido =
            (int) ($resultado->adquirido ?? 0) === 1;

        if (!$adquirido) {
            throw new RuntimeException(
                'No fue posible generar el código del pago.'
            );
        }

        try {
            $prefijo =
                self::PREFIJO . '-' . $anio . '-';

            $ultimo =
                Pago::withTrashed()
                    ->where(
                        'codigo_pago',
                        'like',
                        $prefijo . '%'
                    )
                    ->selectRaw(
                        'MAX(
                            CAST(
                                RIGHT(codigo_pago, ?)
                                AS UNSIGNED
                            )
                        ) AS ultimo',
                        [self::LONGITUD]
                    )
                    ->value('ultimo');

            $siguiente =
                ((int) $ultimo) + 1;

            return sprintf(
                '%s%0' . self::LONGITUD . 'd',
                $prefijo,
                $siguiente
            );
        } finally {
            DB::select(
                'SELECT RELEASE_LOCK(?)',
                [$nombreBloqueo]
            );
        }
    }
}