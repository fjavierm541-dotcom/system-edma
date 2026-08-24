<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class EstadoCuentaController extends Controller
{
    public function index(
        Request $request
    ): View {
        $user = $request->user();

        abort_unless(
            $user
            && $user->tieneRol('Estudiante'),
            403
        );

        $user->load(
            'persona.estudiante'
        );

        $estudiante =
            $user->persona?->estudiante;

        abort_unless(
            $estudiante,
            403,
            'No se encontró un expediente de estudiante asociado a tu cuenta.'
        );

        /*
        |--------------------------------------------------------------------------
        | 1. Obtener todas las matrículas del estudiante
        |--------------------------------------------------------------------------
        */

        $matriculas = Matricula::query()
            ->where(
                'estudiante_id',
                $estudiante->id
            )
            ->with([
                'grupo.nivel.programa',
                'grupo.periodoAcademico',

                'cuotas' => function ($query): void {
                    $query->orderBy(
                        'numero_cuota'
                    );
                },

                'cuotas.aplicacionesPago.pago',
            ])
            ->get()
            ->sortByDesc(
                fn (Matricula $matricula) =>
                    $matricula
                        ->grupo
                        ->periodoAcademico
                        ->fecha_inicio
                        ?->timestamp
                    ?? 0
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | 2. Sin matrículas
        |--------------------------------------------------------------------------
        */

        if ($matriculas->isEmpty()) {
            return view(
                'portal.estado-cuenta.index',
                [
                    'estudiante' =>
                        $estudiante,

                    'matriculaSeleccionada' =>
                        null,

                    'cuotas' =>
                        collect(),

                    'pagos' =>
                        collect(),

                    'totalNivel' =>
                        0,

                    'totalPagado' =>
                        0,

                    'saldoPendiente' =>
                        0,

                    'pagosEnRevision' =>
                        collect(),

                    'tienePagoRechazado' =>
                        false,

                    'estadosCuenta' =>
                        collect(),

                    'aniosDisponibles' =>
                        collect(),

                    'anioSeleccionado' =>
                        null,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Año seleccionado
        |--------------------------------------------------------------------------
        */

        $aniosDisponibles =
            $matriculas
                ->map(
                    fn (Matricula $matricula) =>
                        $matricula
                            ->grupo
                            ->periodoAcademico
                            ->fecha_inicio
                            ?->format('Y')
                )
                ->filter()
                ->unique()
                ->sortDesc()
                ->values();

        $anioSeleccionado =
            $request->filled('anio')
                ? (string) $request->query('anio')
                : null;

        /*
        |--------------------------------------------------------------------------
        | 4. Determinar matrícula seleccionada
        |--------------------------------------------------------------------------
        */

        $matriculaSeleccionada = null;

        if ($request->filled('matricula')) {
            $matriculaSeleccionada =
                $matriculas->first(
                    fn (Matricula $matricula) =>
                        $matricula->id
                        ===
                        (int) $request->query(
                            'matricula'
                        )
                );
        }

        /*
         * Si no se seleccionó una matrícula,
         * buscamos primero una matrícula activa
         * correspondiente a un período vigente.
         */
        if (!$matriculaSeleccionada) {
            $matriculaSeleccionada =
                $matriculas->first(
                    function (
                        Matricula $matricula
                    ): bool {
                        if (
                            $matricula->estado
                            !== 'activa'
                        ) {
                            return false;
                        }

                        return in_array(
                            $matricula
                                ->grupo
                                ->periodoAcademico
                                ->estado,
                            [
                                'matricula_abierta',
                                'en_curso',
                            ],
                            true
                        );
                    }
                );
        }

        /*
         * Si no existe una vigente,
         * mostramos la más reciente.
         */
        $matriculaSeleccionada ??=
            $matriculas->first();

        /*
        |--------------------------------------------------------------------------
        | 5. Preparar cuenta seleccionada
        |--------------------------------------------------------------------------
        */

        $resultado =
            $this->prepararEstadoCuenta(
                $matriculaSeleccionada,
                $estudiante->id
            );

        /*
        |--------------------------------------------------------------------------
        | 6. Construir resumen de todas las cuentas
        |--------------------------------------------------------------------------
        */

        $estadosCuenta =
            $matriculas
                ->filter(
                    function (
                        Matricula $matricula
                    ) use (
                        $anioSeleccionado
                    ): bool {
                        if (!$anioSeleccionado) {
                            return true;
                        }

                        $anio =
                            $matricula
                                ->grupo
                                ->periodoAcademico
                                ->fecha_inicio
                                ?->format('Y');

                        return
                            $anio
                            ===
                            $anioSeleccionado;
                    }
                )
                ->map(
                    function (
                        Matricula $matricula
                    ) use (
                        $estudiante
                    ): array {
                        $datos =
                            $this
                                ->calcularTotalesMatricula(
                                    $matricula
                                );

                        return [
                            'matricula' =>
                                $matricula,

                            'total' =>
                                $datos[
                                    'total'
                                ],

                            'pagado' =>
                                $datos[
                                    'pagado'
                                ],

                            'saldo' =>
                                $datos[
                                    'saldo'
                                ],

                            'es_actual' =>
                                $matricula->estado
                                === 'activa'
                                &&
                                in_array(
                                    $matricula
                                        ->grupo
                                        ->periodoAcademico
                                        ->estado,
                                    [
                                        'matricula_abierta',
                                        'en_curso',
                                    ],
                                    true
                                ),
                        ];
                    }
                )
                ->values();

        return view(
            'portal.estado-cuenta.index',
            [
                'estudiante' =>
                    $estudiante,

                'matriculaSeleccionada' =>
                    $matriculaSeleccionada,

                'cuotas' =>
                    $resultado['cuotas'],

                'pagos' =>
                    $resultado['pagos'],

                'totalNivel' =>
                    $resultado[
                        'totalNivel'
                    ],

                'totalPagado' =>
                    $resultado[
                        'totalPagado'
                    ],

                'saldoPendiente' =>
                    $resultado[
                        'saldoPendiente'
                    ],

                'pagosEnRevision' =>
                    $resultado[
                        'pagosEnRevision'
                    ],

                'tienePagoRechazado' =>
                    $resultado[
                        'tienePagoRechazado'
                    ],

                'estadosCuenta' =>
                    $estadosCuenta,

                'aniosDisponibles' =>
                    $aniosDisponibles,

                'anioSeleccionado' =>
                    $anioSeleccionado,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Preparar estado de cuenta seleccionado
    |--------------------------------------------------------------------------
    */

    private function prepararEstadoCuenta(
        Matricula $matricula,
        int $estudianteId
    ): array {
        $cuotas =
            $matricula
                ->cuotas
                ->map(
                    function ($cuota) {

                        /*
                         * Solo cuentan pagos
                         * administrativamente aprobados.
                         */
                        $pagado =
                            (float)
                            $cuota
                                ->aplicacionesPago
                                ->filter(
                                    fn (
                                        $aplicacion
                                    ) =>
                                        $aplicacion
                                            ->pago
                                            ?->estado
                                        ===
                                        'aprobado'
                                )
                                ->sum(
                                    'monto_aplicado'
                                );

                        $monto =
                            (float)
                            $cuota->monto;

                        $saldo =
                            max(
                                0,
                                $monto - $pagado
                            );

                        if ($saldo <= 0.009) {
                            $estadoVisible =
                                'pagada';
                        } elseif ($pagado > 0) {
                            $estadoVisible =
                                'parcial';
                        } elseif (
                            $cuota
                                ->fecha_vencimiento
                            &&
                            $cuota
                                ->fecha_vencimiento
                                ->isPast()
                        ) {
                            $estadoVisible =
                                'vencida';
                        } else {
                            $estadoVisible =
                                'pendiente';
                        }

                        $cuota->setAttribute(
                            'monto_pagado_calculado',
                            $pagado
                        );

                        $cuota->setAttribute(
                            'saldo_calculado',
                            $saldo
                        );

                        $cuota->setAttribute(
                            'estado_visible',
                            $estadoVisible
                        );

                        return $cuota;
                    }
                );

        $totalNivel =
            (float)
            $matricula
                ->precio_nivel_acordado;

        $totalPagado =
            (float)
            $cuotas
                ->sum(
                    'monto_pagado_calculado'
                );

        $saldoPendiente =
            max(
                0,
                $totalNivel
                - $totalPagado
            );

        /*
        |--------------------------------------------------------------------------
        | Pagos específicamente ligados a esta matrícula
        |--------------------------------------------------------------------------
        */

        $pagos =
            Pago::query()
                ->where(
                    'estudiante_id',
                    $estudianteId
                )
                ->where(
                    'periodo_academico_id',
                    $matricula
                        ->grupo
                        ->periodo_academico_id
                )
                ->where(
                    function ($query) use (
                        $matricula
                    ): void {
                        $query
                            ->where(
                                'matricula_id',
                                $matricula->id
                            )
                            ->orWhere(
                                'solicitud_inscripcion_id',
                                '!=',
                                null
                            );
                    }
                )
                ->with([
                    'periodoAcademico',
                    'aplicacionesCuotas.cuota',
                ])
                ->orderByDesc(
                    'fecha_pago'
                )
                ->orderByDesc(
                    'id'
                )
                ->get()
                ->filter(
                    function (
                        Pago $pago
                    ) use (
                        $matricula
                    ): bool {
                        /*
                         * Los pagos posteriores
                         * deben pertenecer a la matrícula.
                         */
                        if (
                            $pago->matricula_id
                        ) {
                            return
                                $pago
                                    ->matricula_id
                                ===
                                $matricula->id;
                        }

                        /*
                         * El pago inicial puede haber
                         * nacido desde la solicitud.
                         */
                        return
                            $pago
                                ->periodo_academico_id
                            ===
                            $matricula
                                ->grupo
                                ->periodo_academico_id;
                    }
                )
                ->values();

        return [
            'cuotas' =>
                $cuotas,

            'pagos' =>
                $pagos,

            'totalNivel' =>
                $totalNivel,

            'totalPagado' =>
                $totalPagado,

            'saldoPendiente' =>
                $saldoPendiente,

            'pagosEnRevision' =>
                $pagos->where(
                    'estado',
                    'pendiente_revision'
                ),

            'tienePagoRechazado' =>
                $pagos->contains(
                    fn (Pago $pago) =>
                        $pago->estado
                        === 'rechazado'
                ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Calcular resumen de una matrícula
    |--------------------------------------------------------------------------
    */

    private function calcularTotalesMatricula(
        Matricula $matricula
    ): array {
        $total =
            (float)
            $matricula
                ->precio_nivel_acordado;

        $pagado = 0.0;

        foreach (
            $matricula->cuotas
            as $cuota
        ) {
            $pagado +=
                (float)
                $cuota
                    ->aplicacionesPago
                    ->filter(
                        fn ($aplicacion) =>
                            $aplicacion
                                ->pago
                                ?->estado
                            === 'aprobado'
                    )
                    ->sum(
                        'monto_aplicado'
                    );
        }

        return [
            'total' =>
                $total,

            'pagado' =>
                $pagado,

            'saldo' =>
                max(
                    0,
                    $total - $pagado
                ),
        ];
    }
}