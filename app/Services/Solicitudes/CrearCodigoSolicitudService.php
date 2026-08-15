<?php

namespace App\Services\Solicitudes;

use App\Models\SolicitudInscripcion;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CrearCodigoSolicitudService
{
    private const PREFIJO = 'EDMA-SOL';

    private const LONGITUD = 5;

    public function generar(int $anio): string
    {
        $nombreBloqueo =
            "edma_codigo_solicitud_{$anio}";

        $resultado = DB::selectOne(
            'SELECT GET_LOCK(?, 10) AS adquirido',
            [$nombreBloqueo]
        );

        $adquirido =
            (int) ($resultado->adquirido ?? 0) === 1;

        if (!$adquirido) {
            throw new RuntimeException(
                'No fue posible generar el código de la solicitud.'
            );
        }

        try {
            $prefijo =
                self::PREFIJO . '-' . $anio . '-';

            $ultimo =
                SolicitudInscripcion::withTrashed()
                    ->where(
                        'codigo_solicitud',
                        'like',
                        $prefijo . '%'
                    )
                    ->selectRaw(
                        'MAX(
                            CAST(
                                RIGHT(codigo_solicitud, ?)
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