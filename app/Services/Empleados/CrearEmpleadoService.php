<?php

namespace App\Services\Empleados;

use App\Models\Empleado;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class CrearEmpleadoService
{
    private const PREFIJO = 'EDMA-EMP';

    private const LONGITUD_CORRELATIVO = 4;

    private const TIEMPO_ESPERA_BLOQUEO = 10;

    /**
     * Crear un empleado con código institucional único.
     *
     * @throws Throwable
     */
    public function ejecutar(
        array $datos,
        CarbonInterface|string|null $fechaReferencia = null
    ): Empleado {
        $fecha = $fechaReferencia instanceof CarbonInterface
            ? $fechaReferencia
            : ($fechaReferencia
                ? Carbon::parse($fechaReferencia)
                : now());

        $anio = (int) $fecha->format('Y');

        $nombreBloqueo = sprintf(
            'edma_codigo_empleado_%d',
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
                    'No fue posible reservar la generación del código del empleado.'
                );
            }

            return DB::transaction(function () use (
                $datos,
                $anio
            ): Empleado {
                $codigo = $this->generarSiguienteCodigo($anio);

                unset($datos['codigo_empleado']);

                $datos['codigo_empleado'] = $codigo;

                $datos['fecha_ingreso'] =
                    $datos['fecha_ingreso'] ?? now()->toDateString();

                $datos['estado'] =
                    $datos['estado'] ?? 'activo';

                return Empleado::query()->create($datos);
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

    /**
     * Obtener el siguiente código disponible.
     */
    private function generarSiguienteCodigo(int $anio): string
    {
        $prefijoAnual = sprintf(
            '%s-%d-',
            self::PREFIJO,
            $anio
        );

        $ultimoCorrelativo = Empleado::withTrashed()
            ->where(
                'codigo_empleado',
                'like',
                $prefijoAnual . '%'
            )
            ->selectRaw(
                'MAX(
                    CAST(
                        RIGHT(codigo_empleado, ?) AS UNSIGNED
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
                "Se agotó la numeración de empleados para el año {$anio}."
            );
        }

        return sprintf(
            '%s%0' . self::LONGITUD_CORRELATIVO . 'd',
            $prefijoAnual,
            $siguienteCorrelativo
        );
    }
}