<?php

namespace App\Services\Pagos;

use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\PeriodoAcademico;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class RegistrarPagoEstudianteService
{
    public function __construct(
        private readonly CrearCodigoPagoService
            $crearCodigoPagoService
    ) {
    }

    /**
     * @throws Throwable
     */
    public function ejecutar(
        Estudiante $estudiante,
        array $datos,
        UploadedFile $comprobante
    ): Pago {
        $rutaComprobante = null;

        try {
            /*
            |--------------------------------------------------------------------------
            | 1. Identificar matrícula/período
            |--------------------------------------------------------------------------
            |
            | Si ya tiene matrícula activa, el pago corresponde a ella.
            | Si todavía no se ha matriculado, buscamos el período que
            | actualmente tiene abierta la matrícula.
            |
            */

            $matricula = Matricula::query()
                ->where(
                    'estudiante_id',
                    $estudiante->id
                )
                ->where(
                    'estado',
                    'activa'
                )
                ->whereHas(
                    'grupo.periodoAcademico',
                    function ($query): void {
                        $query->whereIn(
                            'estado',
                            [
                                'matricula_abierta',
                                'en_curso',
                            ]
                        );
                    }
                )
                ->with([
                    'grupo.periodoAcademico',
                    'cuotas.aplicacionesPago',
                ])
                ->latest('id')
                ->first();

            if ($matricula) {
                $periodo =
                    $matricula
                        ->grupo
                        ->periodoAcademico;
            } else {
                $periodos = PeriodoAcademico::query()
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
                    ->get();

                if ($periodos->isEmpty()) {
                    throw new RuntimeException(
                        'Actualmente no hay un período disponible para registrar este pago.'
                    );
                }

                if ($periodos->count() > 1) {
                    throw new RuntimeException(
                        'Existe más de un período disponible. Comunícate con Administración para continuar.'
                    );
                }

                $periodo =
                    $periodos->first();
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Evitar otro pago pendiente para el mismo período
            |--------------------------------------------------------------------------
            |
            | El estudiante no debe subir comprobantes repetidamente mientras
            | Administración todavía está revisando el anterior.
            |
            */

            $pagoPendiente = Pago::query()
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
                    'pendiente_revision'
                )
                ->exists();

            if ($pagoPendiente) {
                throw new RuntimeException(
                    'Ya tienes un pago pendiente de revisión para este período. Espera la respuesta de Administración antes de registrar otro.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Validar saldo cuando ya existe matrícula
            |--------------------------------------------------------------------------
            */

            if ($matricula) {
                $totalCuotas =
                    (float) $matricula
                        ->cuotas
                        ->sum('monto');

                $totalAplicado =
                    (float) $matricula
                        ->cuotas
                        ->sum(
                            function ($cuota): float {
                                return (float) $cuota
                                    ->aplicacionesPago
                                    ->sum('monto_aplicado');
                            }
                        );

                $saldoPendiente =
                    max(
                        0,
                        $totalCuotas
                        - $totalAplicado
                    );

                if ($saldoPendiente <= 0) {
                    throw new RuntimeException(
                        'Tu cuenta para este período ya se encuentra completamente pagada.'
                    );
                }

                if (
                    (float) $datos['monto_total']
                    > $saldoPendiente
                ) {
                    throw new RuntimeException(
                        'El monto ingresado supera el saldo pendiente de tu cuenta.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Guardar comprobante
            |--------------------------------------------------------------------------
            */

            $rutaComprobante =
                $comprobante->store(
                    'comprobantes/pagos/'
                    . now()->format('Y/m'),
                    'public'
                );

            if (!$rutaComprobante) {
                throw new RuntimeException(
                    'No fue posible almacenar el comprobante de pago.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 5. Crear pago y comprobante
            |--------------------------------------------------------------------------
            */

            $pago = DB::transaction(
                function () use (
                    $estudiante,
                    $datos,
                    $comprobante,
                    $rutaComprobante,
                    $periodo,
                    $matricula
                ): Pago {
                    $codigoPago =
                        $this
                            ->crearCodigoPagoService
                            ->generar(
                                (int) $periodo
                                    ->fecha_inicio
                                    ->format('Y')
                            );

                    $pago = Pago::query()
                        ->create([
                            'codigo_pago' =>
                                $codigoPago,

                            'solicitud_inscripcion_id' =>
                                null,

                            'estudiante_id' =>
                                $estudiante->id,

                            'periodo_academico_id' =>
                                $periodo->id,

                            'matricula_id' =>
                                $matricula?->id,

                            'monto_total' =>
                                $datos['monto_total'],

                            'metodo_pago' =>
                                $datos['metodo_pago'],

                            'fecha_pago' =>
                                $datos['fecha_pago'],

                            'numero_referencia' =>
                                $datos[
                                    'numero_referencia'
                                ] ?? null,

                            'estado' =>
                                'pendiente_revision',

                            'revisado_at' =>
                                null,

                            'revisado_por' =>
                                null,

                            'motivo_rechazo' =>
                                null,

                            'observaciones' =>
                                null,
                        ]);

                    $pago
                        ->comprobantes()
                        ->create([
                            'nombre_original' =>
                                $comprobante
                                    ->getClientOriginalName(),

                            'nombre_almacenado' =>
                                basename(
                                    $rutaComprobante
                                ),

                            'ruta_archivo' =>
                                $rutaComprobante,

                            'extension' =>
                                strtolower(
                                    $comprobante
                                        ->getClientOriginalExtension()
                                ),

                            'mime_type' =>
                                $comprobante
                                    ->getMimeType(),

                            'tamano_bytes' =>
                                $comprobante
                                    ->getSize(),
                        ]);

                    return $pago;
                }
            );

            return $pago->load([
                'periodoAcademico',
                'matricula',
                'comprobantes',
            ]);
        } catch (Throwable $exception) {
            if ($rutaComprobante) {
                Storage::disk('public')
                    ->delete(
                        $rutaComprobante
                    );
            }

            throw $exception;
        }
    }
}