<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\PeriodoAcademico;
use App\Models\SolicitudInscripcion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ComprobantePago;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\RedirectResponse;
use App\Services\Solicitudes\AprobarSolicitudInscripcionService;
use RuntimeException;
use Throwable;
use App\Services\Solicitudes\RechazarSolicitudInscripcionService;


class SolicitudInscripcionController extends Controller
{
    public function index(Request $request): View
    {
        $termino = trim(
            (string) $request->query('buscar', '')
        );

        $estado = $request->query('estado');
        $segmento = $request->query('segmento');
        $periodoId = $request->query('periodo');

        $estadosPermitidos = [
            'pendiente',
            'en_revision',
            'aprobada',
            'rechazada',
        ];

        $segmentosPermitidos = [
            'niños',
            'jóvenes_adultos',
        ];

        $solicitudes = SolicitudInscripcion::query()
            ->with([
                'persona',
                'nivelSolicitado.programa',
                'pagos.periodoAcademico',
            ])
            ->buscar($termino)
            ->when(
                in_array(
                    $estado,
                    $estadosPermitidos,
                    true
                ),
                fn (Builder $query) =>
                    $query->where(
                        'estado',
                        $estado
                    )
            )
            ->when(
                in_array(
                    $segmento,
                    $segmentosPermitidos,
                    true
                ),
                fn (Builder $query) =>
                    $query->where(
                        'segmento_solicitado',
                        $segmento
                    )
            )
            ->when(
                filled($periodoId),
                function (
                    Builder $query
                ) use ($periodoId): void {
                    $query->whereHas(
                        'pagos',
                        fn (Builder $pagoQuery) =>
                            $pagoQuery->where(
                                'periodo_academico_id',
                                $periodoId
                            )
                    );
                }
            )
            ->orderByDesc('enviada_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $periodos = PeriodoAcademico::query()
            ->orderByDesc('fecha_inicio')
            ->get([
                'id',
                'codigo',
                'nombre',
            ]);

        $resumen = [
            'total' => SolicitudInscripcion::query()
                ->count(),

            'pendientes' => SolicitudInscripcion::query()
                ->where(
                    'estado',
                    'pendiente'
                )
                ->count(),

            'en_revision' => SolicitudInscripcion::query()
                ->where(
                    'estado',
                    'en_revision'
                )
                ->count(),

            'resueltas' => SolicitudInscripcion::query()
                ->whereIn(
                    'estado',
                    [
                        'aprobada',
                        'rechazada',
                    ]
                )
                ->count(),
        ];

        return view(
            'portal.solicitudes-inscripcion.index',
            [
                'solicitudes' => $solicitudes,
                'periodos' => $periodos,
                'resumen' => $resumen,
                'termino' => $termino,
                'estadoSeleccionado' => $estado,
                'segmentoSeleccionado' => $segmento,
                'periodoSeleccionado' => $periodoId,
            ]
        );
    }

        public function show(
            SolicitudInscripcion $solicitud
        ): View {
            $solicitud->load([
                'persona.paisResidencia',
                'fuenteReferencia',
                'nivelSolicitado.programa',
                'nivelAutorizado.programa',
                'revisadaPor.persona',

                'responsables.responsable.paisResidencia',

                'pagos' => function ($query) {
                    $query->orderBy('created_at');
                },

                'pagos.periodoAcademico',
               'pagos.revisadoPor.persona',
                'pagos.comprobantes',
            ]);

            return view(
                'portal.solicitudes-inscripcion.show',
                compact('solicitud')
            );
        }

        public function iniciarRevision(
    SolicitudInscripcion $solicitud
): RedirectResponse {
    if ($solicitud->estado !== 'pendiente') {
        return redirect()
            ->route(
                'portal.solicitudes-inscripcion.show',
                $solicitud
            )
            ->with(
                'error',
                'La solicitud no puede iniciar revisión porque su estado actual ya no es pendiente.'
            );
    }

    $solicitud->update([
        'estado' => 'en_revision',
        'revisada_at' => now(),

        /*
         * Se completará automáticamente
         * cuando habilitemos autenticación
         * administrativa en el Portal.
         */
        'revisada_por' => auth()->id(),
    ]);

    return redirect()
        ->route(
            'portal.solicitudes-inscripcion.show',
            $solicitud
        )
        ->with(
            'success',
            'La solicitud se encuentra ahora en revisión.'
        );
}



public function aprobar(
    SolicitudInscripcion $solicitud,
    AprobarSolicitudInscripcionService
        $aprobarSolicitudService
): RedirectResponse {
    try {
        $resultado =
            $aprobarSolicitudService
                ->ejecutar(
                    $solicitud,
                    auth()->id()
                );

        return redirect()
            ->route(
                'portal.solicitudes-inscripcion.show',
                $solicitud
            )
            ->with(
                'success',
                'La solicitud fue aprobada y el expediente del estudiante fue creado correctamente.'
            )
            ->with(
                'credenciales_temporales',
                [
                    'username' =>
                        $resultado[
                            'usuario'
                        ]->username,

                    'password' =>
                        $resultado[
                            'password_temporal'
                        ],
                ]
            );
    } catch (RuntimeException $exception) {
        return redirect()
            ->route(
                'portal.solicitudes-inscripcion.show',
                $solicitud
            )
            ->with(
                'error',
                $exception->getMessage()
            );
    } catch (Throwable $exception) {
        report($exception);

        return redirect()
            ->route(
                'portal.solicitudes-inscripcion.show',
                $solicitud
            )
            ->with(
                'error',
                'No fue posible aprobar la solicitud. Intente nuevamente.'
            );
    }
}


public function rechazar(
    Request $request,
    SolicitudInscripcion $solicitud,
    RechazarSolicitudInscripcionService
        $rechazarSolicitudService
): RedirectResponse {
    $datos = $request->validate(
        [
            'motivo_rechazo' => [
                'required',
                'string',
                'min:5',
                'max:2000',
            ],
        ],
        [
            'motivo_rechazo.required' =>
                'Debe indicar el motivo del rechazo.',

            'motivo_rechazo.min' =>
                'El motivo del rechazo debe contener al menos 5 caracteres.',

            'motivo_rechazo.max' =>
                'El motivo del rechazo es demasiado extenso.',
        ]
    );

    try {
        $rechazarSolicitudService
            ->ejecutar(
                $solicitud,
                $datos['motivo_rechazo'],
                auth()->id()
            );

        return redirect()
            ->route(
                'portal.solicitudes-inscripcion.show',
                $solicitud
            )
            ->with(
                'success',
                'La solicitud fue rechazada correctamente.'
            );
    } catch (RuntimeException $exception) {
        return redirect()
            ->route(
                'portal.solicitudes-inscripcion.show',
                $solicitud
            )
            ->with(
                'error',
                $exception->getMessage()
            );
    } catch (Throwable $exception) {
        report($exception);

        return redirect()
            ->route(
                'portal.solicitudes-inscripcion.show',
                $solicitud
            )
            ->with(
                'error',
                'No fue posible rechazar la solicitud. Intente nuevamente.'
            );
    }
}

        public function comprobante(
            SolicitudInscripcion $solicitud,
            ComprobantePago $comprobante
        ): StreamedResponse {
            $pertenece = $solicitud
                ->pagos()
                ->whereHas(
                    'comprobantes',
                    fn ($query) =>
                        $query->whereKey($comprobante->id)
                )
                ->exists();

            abort_unless($pertenece, 404);

            abort_unless(
                Storage::disk('public')
                    ->exists($comprobante->ruta_archivo),
                404
            );

            return Storage::disk('public')->response(
                $comprobante->ruta_archivo,
                $comprobante->nombre_original,
                [
                    'Content-Type' =>
                        $comprobante->mime_type
                            ?: 'application/octet-stream',

                    'Content-Disposition' =>
                        'inline; filename="' .
                        addslashes(
                            $comprobante->nombre_original
                        ) .
                        '"',
                ]
            );
        }
}