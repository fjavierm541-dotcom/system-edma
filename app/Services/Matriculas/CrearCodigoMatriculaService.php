<?php

namespace App\Services\Matriculas;

use App\Models\Matricula;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CrearCodigoMatriculaService
{
    private const PREFIJO = 'EDMA-MAT';

    private const LONGITUD = 5;

    public function generar(int $anio): string
    {
        $nombreBloqueo =
            "edma_codigo_matricula_{$anio}";

        $resultado = DB::selectOne(
            'SELECT GET_LOCK(?, 10) AS adquirido',
            [$nombreBloqueo]
        );

        $adquirido =
            (int) ($resultado->adquirido ?? 0) === 1;

        if (!$adquirido) {
            throw new RuntimeException(
                'No fue posible generar el código de la matrícula.'
            );
        }

        try {
            $prefijo =
                self::PREFIJO . '-' . $anio . '-';

            $ultimo = Matricula::withTrashed()
                ->where(
                    'codigo_matricula',
                    'like',
                    $prefijo . '%'
                )
                ->selectRaw(
                    'MAX(
                        CAST(
                            RIGHT(codigo_matricula, ?)
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