<?php

namespace App\Services\Solicitudes;

use App\Models\Nivel;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\SolicitudInscripcion;
use App\Models\SolicitudResponsable;
use App\Services\Pagos\CrearCodigoPagoService;
use App\Services\Periodos\ObtenerPeriodoMatriculaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class CrearSolicitudInscripcionService
{
    public function __construct(
        private readonly CrearCodigoSolicitudService $codigoSolicitudService,
        private readonly CrearCodigoPagoService $codigoPagoService,
        private readonly ObtenerPeriodoMatriculaService $periodoService
    ) {
    }

    /**
     * Crear una nueva solicitud pública de inscripción.
     *
     * @throws Throwable
     */
    public function ejecutar(
        array $datos,
        UploadedFile $comprobante
    ): SolicitudInscripcion {
        $rutaComprobante = null;

        try {
            /*
            |--------------------------------------------------------------------------
            | Período académico
            |--------------------------------------------------------------------------
            |
            | Por ahora esperamos un único período abierto.
            | Si posteriormente existen varios, el formulario podrá solicitarlo.
            |
            */

            $periodo =
                $this->periodoService->obtenerUnico();

            /*
            |--------------------------------------------------------------------------
            | Nivel solicitado
            |--------------------------------------------------------------------------
            */

            $nivelSolicitado = Nivel::query()
                ->with('programa')
                ->findOrFail(
                    $datos['nivel_solicitado_id']
                );

            /*
            |--------------------------------------------------------------------------
            | Nivel inicial A0
            |--------------------------------------------------------------------------
            |
            | Debemos encontrar el A0 correspondiente al mismo programa,
            | porque Niños y Jóvenes y adultos pueden manejar niveles
            | diferentes aunque compartan el nombre A0.
            |
            */

            $nivelA0 = Nivel::query()
                ->where(
                    'programa_id',
                    $nivelSolicitado->programa_id
                )
                ->where(
                    'estado',
                    'activo'
                )
                ->whereNull('deleted_at')
                ->where(
                    function ($query) {
                        $query
                            ->where('codigo', 'A0')
                            ->orWhere('nombre', 'A0');
                    }
                )
                ->first();

            if (!$nivelA0) {
                throw new RuntimeException(
                    'No se encontró el nivel inicial A0 para el programa seleccionado.'
                );
            }

            $requiereExamenUbicacion =
                $nivelSolicitado->id !== $nivelA0->id;

            /*
            |--------------------------------------------------------------------------
            | Guardar comprobante físicamente
            |--------------------------------------------------------------------------
            */

            $rutaComprobante = $comprobante->store(
                'comprobantes/solicitudes/' . now()->format('Y/m'),
                'public'
            );

            if (!$rutaComprobante) {
                throw new RuntimeException(
                    'No fue posible almacenar el comprobante de pago.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Transacción principal
            |--------------------------------------------------------------------------
            */

            return DB::transaction(
                function () use (
                    $datos,
                    $comprobante,
                    $rutaComprobante,
                    $periodo,
                    $nivelSolicitado,
                    $requiereExamenUbicacion
                ): SolicitudInscripcion {

                    /*
                    |--------------------------------------------------------------------------
                    | 1. Persona aspirante
                    |--------------------------------------------------------------------------
                    */

                    $persona = $this->obtenerOCrearPersonaAspirante(
                        $datos
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | 2. Solicitud
                    |--------------------------------------------------------------------------
                    */

                    $codigoSolicitud =
                        $this->codigoSolicitudService->generar(
                            (int) now()->format('Y')
                        );

                    $solicitud =
                        SolicitudInscripcion::query()->create([
                            'codigo_solicitud' =>
                                $codigoSolicitud,

                            'persona_id' =>
                                $persona->id,

                            'fuente_referencia_id' =>
                                $datos['fuente_referencia_id']
                                    ?? null,

                            'fuente_referencia_otro' =>
                                $datos['fuente_referencia_otro']
                                    ?? null,

                            'segmento_solicitado' =>
                                $datos['segmento_solicitado'],

                            'nivel_solicitado_id' =>
                                $nivelSolicitado->id,

                            /*
                             * Todavía no existe un nivel autorizado
                             * porque la administración no ha aprobado
                             * la solicitud.
                             */
                            'nivel_autorizado_id' =>
                                null,

                            'requiere_examen_ubicacion' =>
                                $requiereExamenUbicacion,

                            'estado' =>
                                'pendiente',

                            'enviada_at' =>
                                now(),

                            'revisada_at' =>
                                null,

                            'resuelta_at' =>
                                null,

                            'revisada_por' =>
                                null,

                            'observaciones_solicitante' =>
                                $datos[
                                    'observaciones_solicitante'
                                ] ?? null,

                            'observaciones_administracion' =>
                                null,

                            'motivo_rechazo' =>
                                null,

                            'recomienda_otro_estudiante' =>
                                $datos[
                                    'recomienda_otro_estudiante'
                                ] ?? false,
                        ]);

                    /*
                    |--------------------------------------------------------------------------
                    | 3. Responsable, únicamente si es menor de 18
                    |--------------------------------------------------------------------------
                    */

                    $edad = (int) (
                        $datos['edad_calculada'] ?? 0
                    );

                    if ($edad < 18) {
                        $responsable =
                            $this->obtenerOCrearResponsable(
                                $datos
                            );

                        SolicitudResponsable::query()->create([
                            'solicitud_inscripcion_id' =>
                                $solicitud->id,

                            'responsable_persona_id' =>
                                $responsable->id,

                            'parentesco' =>
                                $datos['parentesco'],

                            'es_principal' =>
                                true,

                            'recibe_notificaciones' =>
                                $datos[
                                    'responsable_recibe_notificaciones'
                                ] ?? true,
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 4. Pago pendiente
                    |--------------------------------------------------------------------------
                    */

                    $codigoPago =
                        $this->codigoPagoService->generar(
                            (int) $periodo
                                ->fecha_inicio
                                ->format('Y')
                        );

                    $pago = Pago::query()->create([
                        'codigo_pago' =>
                            $codigoPago,

                        'solicitud_inscripcion_id' =>
                            $solicitud->id,

                        /*
                        * Aún no existe estudiante.
                        */
                        'estudiante_id' =>
                            null,

                        'periodo_academico_id' =>
                            $periodo->id,

                        /*
                        * Aún no existe matrícula.
                        */
                        'matricula_id' =>
                            null,

                        'monto_total' =>
                            $datos['monto_total'],

                        'metodo_pago' =>
                            $datos['metodo_pago'],

                        'fecha_pago' =>
                            $datos['fecha_pago'],

                        'numero_referencia' =>
                            $datos['numero_referencia']
                                ?? null,

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

                    /*
                    |--------------------------------------------------------------------------
                    | 5. Comprobante
                    |--------------------------------------------------------------------------
                    */

                    $pago->comprobantes()->create([
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

                    return $solicitud->load([
                        'persona',
                        'nivelSolicitado.programa',
                        'responsables.responsable',
                        'pagos.comprobantes',
                    ]);
                },
                3
            );
        } catch (Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | Limpieza del archivo
            |--------------------------------------------------------------------------
            |
            | Si falló la transacción después de haber guardado el archivo,
            | evitamos dejar comprobantes huérfanos en storage.
            |
            */

            if (
                $rutaComprobante &&
                Storage::disk('public')->exists(
                    $rutaComprobante
                )
            ) {
                Storage::disk('public')->delete(
                    $rutaComprobante
                );
            }

            throw $exception;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Persona aspirante
    |--------------------------------------------------------------------------
    */

    private function obtenerOCrearPersonaAspirante(
        array $datos
    ): Persona {
        $persona = Persona::query()
            ->where(
                'tipo_documento',
                $datos['tipo_documento']
            )
            ->where(
                'numero_documento',
                $datos['numero_documento']
            )
            ->first();

        if (!$persona) {
            return Persona::query()->create([
                'primer_nombre' =>
                    $datos['primer_nombre'],

                'segundo_nombre' =>
                    $datos['segundo_nombre']
                        ?? null,

                'primer_apellido' =>
                    $datos['primer_apellido'],

                'segundo_apellido' =>
                    $datos['segundo_apellido']
                        ?? null,

                'tipo_documento' =>
                    $datos['tipo_documento'],

                'numero_documento' =>
                    $datos['numero_documento'],

                'fecha_nacimiento' =>
                    $datos['fecha_nacimiento'],

                'sexo' =>
                    $datos['sexo'],

                'nacionalidad' =>
                    $datos['nacionalidad']
                        ?? null,

                'correo_personal' =>
                    $datos['correo_personal'],

                'telefono_movil' =>
                    $datos['telefono_movil'],

                'telefono_movil_whatsapp' =>
                    $datos[
                        'telefono_movil_whatsapp'
                    ] ?? false,

                'pais_residencia_id' =>
                    $datos['pais_residencia_id'],

                'direccion' =>
                    $datos['direccion'],

                'ciudad_municipio' =>
                    $datos['ciudad_municipio'],

                'departamento_estado' =>
                    $datos['departamento_estado'],

                'estado' =>
                    'activo',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Persona ya existente
        |--------------------------------------------------------------------------
        |
        | Actualizamos únicamente información de contacto y residencia.
        | No modificamos nombre, documento, sexo o nacimiento desde
        | una solicitud pública sobre una persona ya existente.
        |
        */

        $persona->update([
            'correo_personal' =>
                $datos['correo_personal'],

            'telefono_movil' =>
                $datos['telefono_movil'],

            'telefono_movil_whatsapp' =>
                $datos[
                    'telefono_movil_whatsapp'
                ] ?? false,

            'pais_residencia_id' =>
                $datos['pais_residencia_id'],

            'direccion' =>
                $datos['direccion'],

            'ciudad_municipio' =>
                $datos['ciudad_municipio'],

            'departamento_estado' =>
                $datos['departamento_estado'],
        ]);

        return $persona;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsable
    |--------------------------------------------------------------------------
    */

    private function obtenerOCrearResponsable(
        array $datos
    ): Persona {
        $responsable = Persona::query()
            ->where(
                'tipo_documento',
                $datos[
                    'responsable_tipo_documento'
                ]
            )
            ->where(
                'numero_documento',
                $datos[
                    'responsable_numero_documento'
                ]
            )
            ->first();

        if (!$responsable) {
            return Persona::query()->create([
                'primer_nombre' =>
                    $datos[
                        'responsable_primer_nombre'
                    ],

                'segundo_nombre' =>
                    $datos[
                        'responsable_segundo_nombre'
                    ] ?? null,

                'primer_apellido' =>
                    $datos[
                        'responsable_primer_apellido'
                    ],

                'segundo_apellido' =>
                    $datos[
                        'responsable_segundo_apellido'
                    ] ?? null,

                'tipo_documento' =>
                    $datos[
                        'responsable_tipo_documento'
                    ],

                'numero_documento' =>
                    $datos[
                        'responsable_numero_documento'
                    ],

                'correo_personal' =>
                    $datos[
                        'responsable_correo'
                    ],

                'telefono_movil' =>
                    $datos[
                        'responsable_telefono'
                    ],

                'telefono_movil_whatsapp' =>
                    true,

                'pais_residencia_id' =>
                    $datos[
                        'responsable_pais_residencia_id'
                    ],

                /*
                 * Estos datos no son solicitados nuevamente
                 * al responsable dentro del formulario.
                 */
                'estado' =>
                    'activo',
            ]);
        }

        $responsable->update([
            'correo_personal' =>
                $datos[
                    'responsable_correo'
                ],

            'telefono_movil' =>
                $datos[
                    'responsable_telefono'
                ],

            'pais_residencia_id' =>
                $datos[
                    'responsable_pais_residencia_id'
                ],
        ]);

        return $responsable;
    }
}