<?php

namespace App\Services\Solicitudes;

use App\Models\Estudiante;
use App\Models\EstudianteResponsable;
use App\Models\Nivel;
use App\Models\Rol;
use App\Models\SolicitudInscripcion;
use App\Models\User;
use App\Services\Estudiantes\CrearEstudianteService;
use App\Services\Seguridad\GenerarPasswordTemporalService;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class AprobarSolicitudInscripcionService
{
    public function __construct(
        private readonly CrearEstudianteService
            $crearEstudianteService,

        private readonly GenerarPasswordTemporalService
            $generarPasswordTemporalService
    ) {
    }

    /**
     * Aprueba una Solicitud de Inscripción y crea
     * el expediente oficial del estudiante.
     *
     * Este proceso:
     *
     * - crea el estudiante;
     * - asigna el nivel inicial del programa;
     * - copia los responsables;
     * - crea la cuenta de usuario;
     * - genera una contraseña temporal;
     * - asigna el rol Estudiante;
     * - aprueba el pago;
     * - aprueba la solicitud.
     *
     * IMPORTANTE:
     * Este proceso NO crea una matrícula.
     *
     * @throws Throwable
     */
    public function ejecutar(
        SolicitudInscripcion $solicitud,
        ?int $usuarioAdministradorId = null
    ): array {
        return DB::transaction(
            function () use (
                $solicitud,
                $usuarioAdministradorId
            ): array {

                /*
                |--------------------------------------------------------------------------
                | 1. Bloquear solicitud
                |--------------------------------------------------------------------------
                |
                | Se vuelve a consultar la solicitud utilizando lockForUpdate()
                | para impedir que dos procesos intenten aprobarla al mismo
                | tiempo.
                |
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
                */

                if (
                    $solicitud->estado
                    !== 'en_revision'
                ) {
                    throw new RuntimeException(
                        'La solicitud debe encontrarse en revisión antes de poder aprobarse.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 3. Impedir estudiante duplicado
                |--------------------------------------------------------------------------
                |
                | También se consideran estudiantes eliminados lógicamente.
                |
                */

                $estudianteExistente =
                    Estudiante::withTrashed()
                        ->where(
                            'persona_id',
                            $solicitud->persona_id
                        )
                        ->exists();

                if ($estudianteExistente) {
                    throw new RuntimeException(
                        'La persona asociada a esta solicitud ya posee un expediente de estudiante.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 4. Impedir cuenta duplicada
                |--------------------------------------------------------------------------
                */

                $usuarioExistente =
                    User::query()
                        ->where(
                            'persona_id',
                            $solicitud->persona_id
                        )
                        ->exists();

                if ($usuarioExistente) {
                    throw new RuntimeException(
                        'La persona asociada a esta solicitud ya posee una cuenta de usuario.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 5. Obtener nivel solicitado
                |--------------------------------------------------------------------------
                |
                | El nivel solicitado permite determinar el programa al que
                | pertenece la Solicitud de Inscripción.
                |
                | El nivel solicitado se conserva como dato histórico.
                |
                */

                $nivelSolicitado =
                    $solicitud
                        ->nivelSolicitado()
                        ->first();

                if (!$nivelSolicitado) {
                    throw new RuntimeException(
                        'No fue posible identificar el nivel solicitado.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 6. Determinar nivel inicial del programa
                |--------------------------------------------------------------------------
                |
                | IMPORTANTE:
                |
                | No se asume que el nivel inicial se llame A0.
                |
                | Cada programa puede utilizar una nomenclatura diferente,
                | por ejemplo:
                |
                | A0
                | Nivel 1
                | Inicial
                | Básico
                | Módulo I
                |
                | Por tanto, el nivel inicial será siempre el primer nivel
                | ACTIVO del programa según el campo "orden".
                |
                */

                $nivelInicial = Nivel::query()
                    ->where(
                        'programa_id',
                        $nivelSolicitado->programa_id
                    )
                    ->where(
                        'estado',
                        'activo'
                    )
                    ->orderBy('orden')
                    ->orderBy('id')
                    ->first();

                if (!$nivelInicial) {
                    throw new RuntimeException(
                        'El programa correspondiente a esta solicitud no tiene niveles activos configurados.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 7. Obtener pago inicial
                |--------------------------------------------------------------------------
                |
                | Una Solicitud de Inscripción debe tener exactamente un
                | pago inicial asociado.
                |
                */

                $pagos = $solicitud
                    ->pagos()
                    ->lockForUpdate()
                    ->get();

                if ($pagos->count() !== 1) {
                    throw new RuntimeException(
                        'La solicitud debe tener exactamente un pago inicial asociado para poder aprobarse.'
                    );
                }

                $pago = $pagos->first();

                /*
                |--------------------------------------------------------------------------
                | 8. Validar estado del pago
                |--------------------------------------------------------------------------
                */

                if (
                    $pago->estado
                    !== 'pendiente_revision'
                ) {
                    throw new RuntimeException(
                        'El pago asociado no se encuentra pendiente de revisión.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 9. Validar comprobante
                |--------------------------------------------------------------------------
                */

                if (
                    !$pago
                        ->comprobantes()
                        ->exists()
                ) {
                    throw new RuntimeException(
                        'No se encontró un comprobante asociado al pago de esta solicitud.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 10. Obtener rol Estudiante
                |--------------------------------------------------------------------------
                */

                $rolEstudiante = Rol::query()
                    ->where(
                        'nombre',
                        'Estudiante'
                    )
                    ->where(
                        'activo',
                        true
                    )
                    ->first();

                if (!$rolEstudiante) {
                    throw new RuntimeException(
                        'El rol Estudiante no está disponible para crear la cuenta.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 11. Crear expediente oficial del estudiante
                |--------------------------------------------------------------------------
                |
                | CrearEstudianteService se encarga de generar el código
                | institucional EDMA de forma segura.
                |
                */

                $estudiante =
                    $this
                        ->crearEstudianteService
                        ->ejecutar([
                            'persona_id' =>
                                $solicitud->persona_id,

                            'nivel_escolaridad_id' =>
                                null,

                            /*
                             * El nuevo estudiante inicia en
                             * el primer nivel activo del programa.
                             */
                            'nivel_autorizado_id' =>
                                $nivelInicial->id,

                            'profesion_ocupacion' =>
                                null,

                            'fecha_ingreso' =>
                                now()->toDateString(),

                            'estado' =>
                                'activo',

                            'observaciones' =>
                                null,
                        ]);

                /*
                |--------------------------------------------------------------------------
                | 12. Copiar responsables
                |--------------------------------------------------------------------------
                |
                | Los registros de solicitud_responsables se conservan
                | como historial.
                |
                | Se crean nuevas relaciones oficiales dentro de
                | estudiante_responsables.
                |
                */

                $responsables =
                    $solicitud
                        ->responsables()
                        ->get();

                foreach (
                    $responsables
                    as $responsableSolicitud
                ) {
                    EstudianteResponsable::query()
                        ->create([
                            'estudiante_id' =>
                                $estudiante->id,

                            'responsable_persona_id' =>
                                $responsableSolicitud
                                    ->responsable_persona_id,

                            'parentesco' =>
                                $responsableSolicitud
                                    ->parentesco,

                            'es_principal' =>
                                $responsableSolicitud
                                    ->es_principal,

                            'recibe_notificaciones' =>
                                $responsableSolicitud
                                    ->recibe_notificaciones,

                            'activo' =>
                                true,
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | 13. Generar contraseña temporal
                |--------------------------------------------------------------------------
                |
                | La contraseña en texto plano solamente existe durante
                | esta ejecución.
                |
                | Nunca se almacena directamente en la base de datos.
                |
                */

                $passwordTemporal =
                    $this
                        ->generarPasswordTemporalService
                        ->generar();

                /*
                |--------------------------------------------------------------------------
                | 14. Obtener Persona
                |--------------------------------------------------------------------------
                */

                $persona =
                    $solicitud
                        ->persona()
                        ->firstOrFail();

                /*
                |--------------------------------------------------------------------------
                | 15. Crear cuenta de usuario
                |--------------------------------------------------------------------------
                |
                | El nombre de usuario será exactamente el código
                | institucional EDMA generado para el estudiante.
                |
                | Ejemplo:
                |
                | EDMA-2026-00002
                |
                | El correo puede ser NULL.
                |
                */

                $usuario = User::query()
                    ->create([
                        'persona_id' =>
                            $persona->id,

                        'username' =>
                            $estudiante
                                ->codigo_estudiante,

                        'email' =>
                            $persona
                                ->correo_personal,

                        /*
                         * User posee cast "hashed",
                         * por lo que Laravel almacena
                         * únicamente el hash.
                         */
                        'password' =>
                            $passwordTemporal,

                        /*
                         * Al tratarse de una contraseña
                         * temporal, deberá cambiarla
                         * obligatoriamente al ingresar.
                         */
                        'debe_cambiar_password' =>
                            true,

                        'activo' =>
                            true,

                        'ultimo_acceso_at' =>
                            null,
                    ]);

                /*
                |--------------------------------------------------------------------------
                | 16. Asignar rol Estudiante
                |--------------------------------------------------------------------------
                |
                | syncWithoutDetaching evita duplicar la relación.
                |
                */

                $usuario
                    ->roles()
                    ->syncWithoutDetaching([
                        $rolEstudiante->id,
                    ]);

                /*
                |--------------------------------------------------------------------------
                | 17. Aprobar pago
                |--------------------------------------------------------------------------
                |
                | El pago conserva su relación original con la solicitud
                | y ahora también queda relacionado con el estudiante.
                |
                */

                $pago->update([
                    'estudiante_id' =>
                        $estudiante->id,

                    'estado' =>
                        'aprobado',

                    'revisado_at' =>
                        now(),

                    'revisado_por' =>
                        $usuarioAdministradorId,

                    'motivo_rechazo' =>
                        null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | 18. Aprobar solicitud
                |--------------------------------------------------------------------------
                |
                | nivel_solicitado_id se conserva como dato histórico.
                |
                | nivel_autorizado_id almacena el nivel inicial real
                | correspondiente al programa.
                |
                */

                $solicitud->update([
                    'nivel_autorizado_id' =>
                        $nivelInicial->id,

                    'estado' =>
                        'aprobada',

                    'resuelta_at' =>
                        now(),

                    'revisada_at' =>
                        $solicitud->revisada_at
                        ?? now(),

                    'revisada_por' =>
                        $usuarioAdministradorId,

                    'motivo_rechazo' =>
                        null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | 19. Resultado
                |--------------------------------------------------------------------------
                |
                | password_temporal se devuelve exclusivamente para que
                | pueda mostrarse una sola vez después de la aprobación.
                |
                | NO se guarda en texto plano.
                |
                */

                return [
                    'solicitud' =>
                        $solicitud,

                    'estudiante' =>
                        $estudiante,

                    'usuario' =>
                        $usuario,

                    'nivel_inicial' =>
                        $nivelInicial,

                    'password_temporal' =>
                        $passwordTemporal,
                ];
            },
            3
        );
    }
}