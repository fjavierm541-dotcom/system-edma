<?php

namespace App\Services\Docentes;

use App\Models\Docente;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class CrearDocenteService
{
    private const PREFIJO = 'EDMA-DOC';

    private const LONGITUD_CORRELATIVO = 5;

    private const TIEMPO_ESPERA_BLOQUEO = 10;

    /**
     * Crear un docente con código institucional único.
     *
     * @throws Throwable
     */
    public function ejecutar(
        array $datos,
        CarbonInterface|string|null $fechaReferencia = null
    ): Docente {
        $fecha = $fechaReferencia instanceof CarbonInterface
            ? $fechaReferencia
            : ($fechaReferencia
                ? Carbon::parse($fechaReferencia)
                : now());

        $anio = (int) $fecha->format('Y');

        $nombreBloqueo = sprintf(
            'edma_codigo_docente_%d',
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
                    'No fue posible reservar la generación del código docente.'
                );
            }

            return DB::transaction(function () use (
                $datos,
                $anio
            ): Docente {
                unset($datos['codigo_docente']);

                $datos['codigo_docente'] =
                    $this->generarSiguienteCodigo($anio);

                $datos['fecha_inicio_docencia'] =
                    $datos['fecha_inicio_docencia']
                    ?? now()->toDateString();

                $datos['estado'] =
                    $datos['estado'] ?? 'activo';

                return Docente::query()->create($datos);
            }, 3);
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

        $ultimoCorrelativo = Docente::withTrashed()
            ->where(
                'codigo_docente',
                'like',
                $prefijoAnual . '%'
            )
            ->selectRaw(
                'MAX(
                    CAST(
                        RIGHT(codigo_docente, ?) AS UNSIGNED
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
                "Se agotó la numeración de docentes para el año {$anio}."
            );
        }

        return sprintf(
            '%s%0' . self::LONGITUD_CORRELATIVO . 'd',
            $prefijoAnual,
            $siguienteCorrelativo
        );
    }
}