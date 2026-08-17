<?php

namespace App\Services\Solicitudes;

use App\Models\SolicitudInscripcion;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class RechazarSolicitudInscripcionService
{
    /**
     * @throws Throwable
     */
    public function ejecutar(
        SolicitudInscripcion $solicitud,
        string $motivoRechazo,
        ?int $usuarioAdministradorId = null
    ): SolicitudInscripcion {
        return DB::transaction(
            function () use (
                $solicitud,
                $motivoRechazo,
                $usuarioAdministradorId
            ): SolicitudInscripcion {

                /*
                |--------------------------------------------------------------------------
                | 1. Bloquear solicitud
                |--------------------------------------------------------------------------
                */

                $solicitud = SolicitudInscripcion::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $solicitud->id
                    );

                /*
                |--------------------------------------------------------------------------
                | 2. Validar estado
                |--------------------------------------------------------------------------
                |
                | Una solicitud solamente puede rechazarse
                | después de haber iniciado su revisión.
                |
                */

                if (
                    $solicitud->estado
                    !== 'en_revision'
                ) {
                    throw new RuntimeException(
                        'La solicitud debe encontrarse en revisión antes de poder rechazarse.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 3. Validar motivo
                |--------------------------------------------------------------------------
                */

                $motivoRechazo =
                    trim($motivoRechazo);

                if ($motivoRechazo === '') {
                    throw new RuntimeException(
                        'Debe indicar el motivo por el cual se rechaza la solicitud.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 4. Obtener pago asociado
                |--------------------------------------------------------------------------
                */

                $pagos = $solicitud
                    ->pagos()
                    ->lockForUpdate()
                    ->get();

                if ($pagos->count() !== 1) {
                    throw new RuntimeException(
                        'La solicitud debe tener exactamente un pago inicial asociado.'
                    );
                }

                $pago = $pagos->first();

                /*
                |--------------------------------------------------------------------------
                | 5. Validar estado del pago
                |--------------------------------------------------------------------------
                */

                if (
                    $pago->estado
                    !== 'pendiente_revision'
                ) {
                    throw new RuntimeException(
                        'El pago asociado ya fue procesado y la solicitud no puede rechazarse mediante este flujo.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 6. Anular pago
                |--------------------------------------------------------------------------
                |
                | El pago no se marca como "rechazado" porque el rechazo
                | corresponde a toda la Solicitud de Inscripción.
                |
                | "rechazado" se reservará para un rechazo específico
                | del pago o comprobante.
                |
                */

                $pago->update([
                    'estado' =>
                        'anulado',

                    'revisado_at' =>
                        now(),

                    'revisado_por' =>
                        $usuarioAdministradorId,

                    'motivo_rechazo' =>
                        null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | 7. Rechazar solicitud
                |--------------------------------------------------------------------------
                */

                $solicitud->update([
                    'estado' =>
                        'rechazada',

                    'motivo_rechazo' =>
                        $motivoRechazo,

                    'resuelta_at' =>
                        now(),

                    'revisada_at' =>
                        $solicitud->revisada_at
                        ?? now(),

                    'revisada_por' =>
                        $usuarioAdministradorId,
                ]);

                return $solicitud;
            },
            3
        );
    }
}