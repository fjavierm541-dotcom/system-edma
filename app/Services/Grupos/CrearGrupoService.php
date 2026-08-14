<?php

namespace App\Services\Grupos;

use App\Models\Grupo;
use App\Models\PeriodoAcademico;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class CrearGrupoService
{
    private const PREFIJO = 'EDMA-GRP';

    private const LONGITUD_CORRELATIVO = 3;

    private const TIEMPO_ESPERA_BLOQUEO = 10;

    /**
     * Crear un grupo con código institucional único.
     *
     * @throws Throwable
     */
    public function ejecutar(array $datos): Grupo
    {
        $periodo = PeriodoAcademico::query()
            ->findOrFail($datos['periodo_academico_id']);

        $anio = (int) $periodo
            ->fecha_inicio
            ->format('Y');

        $nombreBloqueo = sprintf(
            'edma_codigo_grupo_%d',
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
                    'No fue posible reservar la generación del código del grupo.'
                );
            }

            return DB::transaction(
                function () use (
                    $datos,
                    $anio
                ): Grupo {
                    unset($datos['codigo']);

                    $datos['codigo'] =
                        $this->generarSiguienteCodigo($anio);

                    /*
                    |--------------------------------------------------------------------------
                    | Valores institucionales
                    |--------------------------------------------------------------------------
                    */

                    $datos['modalidad'] = 'virtual';
                    $datos['cupo_minimo'] = 3;
                    $datos['cupo_maximo'] = 25;

                    $datos['estado'] =
                        $datos['estado'] ?? 'activo';

                    return Grupo::query()->create($datos);
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

        $ultimoCorrelativo = Grupo::withTrashed()
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

        if (
            $siguienteCorrelativo >
            $maximoCorrelativo
        ) {
            throw new RuntimeException(
                "Se agotó la numeración de grupos para el año {$anio}."
            );
        }

        return sprintf(
            '%s%0' .
            self::LONGITUD_CORRELATIVO .
            'd',
            $prefijoAnual,
            $siguienteCorrelativo
        );
    }
}