<?php

namespace App\Services\Estudiantes;

use App\Models\Estudiante;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class CrearEstudianteService
{
    private const PREFIJO = 'EDMA';

    private const LONGITUD_CORRELATIVO = 5;

    private const TIEMPO_ESPERA_BLOQUEO = 10;

    /**
     * Crear un estudiante con un código institucional único.
     *
     * @throws Throwable
     */
        public function ejecutar(
            array $datos,
            CarbonInterface|string|null $fechaReferencia = null
        ): Estudiante {
            $fecha = $fechaReferencia instanceof CarbonInterface
                ? $fechaReferencia
                : ($fechaReferencia
                    ? \Illuminate\Support\Carbon::parse($fechaReferencia)
                    : now());

            $anio = (int) $fecha->format('Y');

        $nombreBloqueo = sprintf(
            'edma_codigo_estudiante_%d',
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
                    'No fue posible reservar la generación del código del estudiante.'
                );
            }

            return DB::transaction(function () use (
                $datos,
                $anio
            ): Estudiante {
                $codigo = $this->generarSiguienteCodigo($anio);

                /*
                 * El código nunca se toma directamente del formulario.
                 * Siempre lo genera internamente el sistema.
                 */
                unset($datos['codigo_estudiante']);

                $datos['codigo_estudiante'] = $codigo;

                $datos['fecha_ingreso'] =
                    $datos['fecha_ingreso'] ?? now()->toDateString();

                $datos['estado'] =
                    $datos['estado'] ?? 'activo';

                return Estudiante::query()->create($datos);
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
     * Obtener el siguiente código disponible para un año.
     */
    private function generarSiguienteCodigo(int $anio): string
    {
        $prefijoAnual = sprintf(
            '%s-%d-',
            self::PREFIJO,
            $anio
        );

        /*
         * Se incluyen registros eliminados lógicamente.
         * Un código histórico nunca debe reutilizarse.
         */
        $ultimoCorrelativo = Estudiante::withTrashed()
            ->where(
                'codigo_estudiante',
                'like',
                $prefijoAnual . '%'
            )
            ->selectRaw(
                'MAX(
                    CAST(
                        RIGHT(codigo_estudiante, ?) AS UNSIGNED
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
                "Se agotó la numeración de estudiantes para el año {$anio}."
            );
        }

        return sprintf(
            '%s%0' . self::LONGITUD_CORRELATIVO . 'd',
            $prefijoAnual,
            $siguienteCorrelativo
        );
    }
}