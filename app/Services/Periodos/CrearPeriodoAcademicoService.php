<?php

namespace App\Services\Periodos;

use App\Models\PeriodoAcademico;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class CrearPeriodoAcademicoService
{
    private const PREFIJO = 'EDMA-PER';

    private const LONGITUD_CORRELATIVO = 3;

    private const TIEMPO_ESPERA_BLOQUEO = 10;

    /**
     * Crear un período académico con código único.
     *
     * @throws Throwable
     */
    public function ejecutar(
        array $datos,
        CarbonInterface|string|null $fechaReferencia = null
    ): PeriodoAcademico {
        $fecha = $fechaReferencia instanceof CarbonInterface
            ? $fechaReferencia
            : ($fechaReferencia
                ? Carbon::parse($fechaReferencia)
                : now());

        $anio = (int) $fecha->format('Y');

        $nombreBloqueo = sprintf(
            'edma_codigo_periodo_%d',
            $anio
        );

        $bloqueoAdquirido = false;

        try {
            $resultadoBloqueo = DB::selectOne(
                'SELECT GET_LOCK(?, ?) AS adquirido',
                [
                    $nombreBloqueo,
                    self::TIEMPO_ESPERA_BLOQUEO,
                ]
            );

            $bloqueoAdquirido =
                (int) ($resultadoBloqueo->adquirido ?? 0) === 1;

            if (!$bloqueoAdquirido) {
                throw new RuntimeException(
                    'No fue posible reservar la generación del código del período.'
                );
            }

            return DB::transaction(
                function () use ($datos, $anio): PeriodoAcademico {
                    unset($datos['codigo']);

                    $datos['codigo'] =
                        $this->generarSiguienteCodigo($anio);

                    return PeriodoAcademico::query()
                        ->create($datos);
                },
                3
            );
        } finally {
            if ($bloqueoAdquirido) {
                DB::select(
                    'SELECT RELEASE_LOCK(?)',
                    [$nombreBloqueo]
                );
            }
        }
    }

    private function generarSiguienteCodigo(
        int $anio
    ): string {
        $prefijoAnual = sprintf(
            '%s-%d-',
            self::PREFIJO,
            $anio
        );

        $ultimoCorrelativo =
            PeriodoAcademico::withTrashed()
                ->where(
                    'codigo',
                    'like',
                    $prefijoAnual . '%'
                )
                ->selectRaw(
                    'MAX(
                        CAST(
                            RIGHT(codigo, ?) AS UNSIGNED
                        )
                    ) AS ultimo_correlativo',
                    [self::LONGITUD_CORRELATIVO]
                )
                ->value('ultimo_correlativo');

        $siguienteCorrelativo =
            ((int) $ultimoCorrelativo) + 1;

        $maximoCorrelativo =
            (10 ** self::LONGITUD_CORRELATIVO) - 1;

        if ($siguienteCorrelativo > $maximoCorrelativo) {
            throw new RuntimeException(
                "Se agotó la numeración de períodos para el año {$anio}."
            );
        }

        return sprintf(
            '%s%0' . self::LONGITUD_CORRELATIVO . 'd',
            $prefijoAnual,
            $siguienteCorrelativo
        );
    }
}