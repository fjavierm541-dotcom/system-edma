<?php

namespace App\Services\Matriculas;

use App\Models\Estudiante;
use App\Models\Grupo;
use App\Models\HistorialEstadoMatricula;
use App\Models\Matricula;
use App\Models\MatriculaCuota;
use App\Models\Pago;
use App\Models\PagoCuota;
use App\Models\PeriodoAcademico;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class CrearMatriculaService
{
    private const PRECIO_NIVEL = 2100.00;

    private const CANTIDAD_CUOTAS = 3;

    private const MONTO_CUOTA = 700.00;

    private const MONTO_MORA = 100.00;

    public function __construct(
        private readonly CrearCodigoMatriculaService
            $crearCodigoMatriculaService
    ) {
    }

    /**
     * @throws Throwable
     */
    public function ejecutar(
        Estudiante $estudiante,
        int $grupoId,
        int $usuarioId
    ): Matricula {
        return DB::transaction(
            function () use (
                $estudiante,
                $grupoId,
                $usuarioId
            ): Matricula {

                /*
                |--------------------------------------------------------------------------
                | Período disponible
                |--------------------------------------------------------------------------
                */

                $periodo = PeriodoAcademico::query()
                    ->where(
                        'estado',
                        'matricula_abierta'
                    )
                    ->whereDate(
                        'fecha_inicio_matricula',
                        '<=',
                        now()->toDateString()
                    )
                    ->whereDate(
                        'fecha_fin_matricula',
                        '>=',
                        now()->toDateString()
                    )
                    ->lockForUpdate()
                    ->get();

                if ($periodo->count() !== 1) {
                    throw new RuntimeException(
                        'No existe un período válido para completar la matrícula.'
                    );
                }

                $periodo = $periodo->first();

                /*
                |--------------------------------------------------------------------------
                | Validar estudiante
                |--------------------------------------------------------------------------
                */

                if ($estudiante->estado !== 'activo') {
                    throw new RuntimeException(
                        'Tu expediente no se encuentra habilitado para matricularte.'
                    );
                }

                if (!$estudiante->nivel_autorizado_id) {
                    throw new RuntimeException(
                        'No tienes un nivel autorizado para continuar.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Bloquear y validar grupo
                |--------------------------------------------------------------------------
                */

                $grupo = Grupo::query()
                    ->whereKey($grupoId)
                    ->lockForUpdate()
                    ->first();

                if (!$grupo) {
                    throw new RuntimeException(
                        'El grupo seleccionado ya no se encuentra disponible.'
                    );
                }

                if (
                    $grupo->estado !== 'activo'
                    || $grupo->periodo_academico_id !== $periodo->id
                    || $grupo->nivel_id !== $estudiante->nivel_autorizado_id
                ) {
                    throw new RuntimeException(
                        'El grupo seleccionado no es compatible con tu matrícula.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Evitar matrícula duplicada
                |--------------------------------------------------------------------------
                */

                $duplicada = Matricula::query()
                    ->where(
                        'estudiante_id',
                        $estudiante->id
                    )
                    ->whereHas(
                        'grupo',
                        function ($query) use (
                            $periodo,
                            $estudiante
                        ): void {
                            $query
                                ->where(
                                    'periodo_academico_id',
                                    $periodo->id
                                )
                                ->where(
                                    'nivel_id',
                                    $estudiante->nivel_autorizado_id
                                );
                        }
                    )
                    ->whereIn(
                        'estado',
                        [
                            'activa',
                        ]
                    )
                    ->exists();

                if ($duplicada) {
                    throw new RuntimeException(
                        'Ya tienes una matrícula activa para este nivel y período.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Validar cupo
                |--------------------------------------------------------------------------
                */

                $ocupados = Matricula::query()
                    ->where(
                        'grupo_id',
                        $grupo->id
                    )
                    ->where(
                        'estado',
                        'activa'
                    )
                    ->count();

                if ($ocupados >= $grupo->cupo_maximo) {
                    throw new RuntimeException(
                        'El grupo seleccionado ya alcanzó su cupo máximo.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Obtener pago aprobado
                |--------------------------------------------------------------------------
                */

                $pago = Pago::query()
                    ->where(
                        'estudiante_id',
                        $estudiante->id
                    )
                    ->where(
                        'periodo_academico_id',
                        $periodo->id
                    )
                    ->where(
                        'estado',
                        'aprobado'
                    )
                    ->whereNull(
                        'matricula_id'
                    )
                    ->lockForUpdate()
                    ->orderBy('id')
                    ->first();

                if (!$pago) {
                    throw new RuntimeException(
                        'No encontramos un pago aprobado disponible para completar tu matrícula.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Crear matrícula
                |--------------------------------------------------------------------------
                */

                $anio = (int) $periodo
                    ->fecha_inicio
                    ->format('Y');

                $matricula = Matricula::query()->create([
                    'codigo_matricula' =>
                        $this->crearCodigoMatriculaService
                            ->generar($anio),

                    'estudiante_id' =>
                        $estudiante->id,

                    'grupo_id' =>
                        $grupo->id,

                    'fecha_matricula' =>
                        now()->toDateString(),

                    'precio_nivel_acordado' =>
                        self::PRECIO_NIVEL,

                    'cantidad_cuotas' =>
                        self::CANTIDAD_CUOTAS,

                    'monto_mora_acordado' =>
                        self::MONTO_MORA,

                    'estado' =>
                        'activa',

                    'aprobada_at' =>
                        now(),

                    /*
                     * Primera matrícula:
                     * ya fue aprobada administrativamente
                     * durante la solicitud.
                     */
                    'aprobada_por' =>
                        $pago->revisado_por,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Crear cuotas
                |--------------------------------------------------------------------------
                */

                $cuotas = collect();

                for (
                    $numero = 1;
                    $numero <= self::CANTIDAD_CUOTAS;
                    $numero++
                ) {
                    $cuotas->push(
                        MatriculaCuota::query()->create([
                            'matricula_id' =>
                                $matricula->id,

                            'numero_cuota' =>
                                $numero,

                            'concepto' =>
                                "Mensualidad {$numero} de " .
                                self::CANTIDAD_CUOTAS,

                            'monto' =>
                                self::MONTO_CUOTA,

                            /*
                             * Primera cuota: fecha de matrícula.
                             * Las siguientes: cada mes.
                             */
                            'fecha_vencimiento' =>
                                now()
                                    ->startOfDay()
                                    ->addMonthsNoOverflow(
                                        $numero - 1
                                    )
                                    ->toDateString(),

                            'estado' =>
                                'pendiente',
                        ])
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Aplicar pago inicial a cuotas
                |--------------------------------------------------------------------------
                */

                $saldoPago =
                    (float) $pago->monto_total;

                foreach ($cuotas as $cuota) {
                    if ($saldoPago <= 0) {
                        break;
                    }

                    $montoAplicar = min(
                        $saldoPago,
                        (float) $cuota->monto
                    );

                    PagoCuota::query()->create([
                        'pago_id' =>
                            $pago->id,

                        'matricula_cuota_id' =>
                            $cuota->id,

                        'monto_aplicado' =>
                            $montoAplicar,
                    ]);

                    if (
                        $montoAplicar >=
                        (float) $cuota->monto
                    ) {
                        $cuota->update([
                            'estado' =>
                                'pagada',

                            'fecha_pago_completo' =>
                                $pago->fecha_pago
                                    ? $pago->fecha_pago
                                        ->toDateString()
                                    : now()->toDateString(),
                        ]);
                    } else {
                        $cuota->update([
                            'estado' =>
                                'parcial',
                        ]);
                    }

                    $saldoPago -= $montoAplicar;
                }

                /*
                |--------------------------------------------------------------------------
                | Vincular pago
                |--------------------------------------------------------------------------
                */

                $pago->update([
                    'matricula_id' =>
                        $matricula->id,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Historial inicial
                |--------------------------------------------------------------------------
                */

                HistorialEstadoMatricula::query()->create([
                    'matricula_id' =>
                        $matricula->id,

                    'estado_anterior' =>
                        null,

                    'estado_nuevo' =>
                        'activa',

                    'motivo' =>
                        'Matrícula confirmada por el estudiante.',

                    'cambiado_por' =>
                        $usuarioId,

                    'cambiado_at' =>
                        now(),
                ]);

                return $matricula->load([
                    'grupo.nivel.programa',
                    'grupo.periodoAcademico',
                    'cuotas',
                    'pagos',
                ]);
            },
            3
        );
    }
}