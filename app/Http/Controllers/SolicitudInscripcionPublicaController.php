<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudInscripcionPublicaRequest;
use App\Models\FuenteReferencia;
use App\Models\Nivel;
use App\Models\Pais;
use App\Models\Programa;
use App\Services\Periodos\ObtenerPeriodoMatriculaService;
use App\Services\Solicitudes\CrearSolicitudInscripcionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class SolicitudInscripcionPublicaController extends Controller
{
    public function __construct(
        private readonly CrearSolicitudInscripcionService $crearSolicitudService,
        private readonly ObtenerPeriodoMatriculaService $periodoService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Países
        |--------------------------------------------------------------------------
        */

        $paises = Pais::query()
            ->orderBy('nombre')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Programas disponibles
        |--------------------------------------------------------------------------
        |
        | Solo mostramos programas activos.
        | El segmento se utilizará posteriormente para mostrar únicamente
        | las opciones correspondientes a la edad del aspirante.
        |
        */

        $programas = Programa::query()
            ->where('estado', 'activo')
            ->whereNull('deleted_at')
            ->orderBy('nombre')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Niveles disponibles
        |--------------------------------------------------------------------------
        */

        $niveles = Nivel::query()
            ->where('estado', 'activo')
            ->whereNull('deleted_at')
            ->orderBy('programa_id')
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Fuentes de referencia
        |--------------------------------------------------------------------------
        */

        $fuentesReferencia = FuenteReferencia::query()
    ->activas()
    ->ordenadas()
    ->get();

        /*
        |--------------------------------------------------------------------------
        | Períodos disponibles
        |--------------------------------------------------------------------------
        |
        | No obligaremos al aspirante a escoger un período cuando solamente
        | exista uno disponible.
        |
        */

        $periodosDisponibles =
            $this->periodoService
                ->obtenerDisponibles();

        return view(
            'inscripciones.solicitud',
            [
                'paises' =>
                    $paises,

                'programas' =>
                    $programas,

                'niveles' =>
                    $niveles,

                'fuentesReferencia' =>
                    $fuentesReferencia,

                'periodosDisponibles' =>
                    $periodosDisponibles,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar solicitud
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreSolicitudInscripcionPublicaRequest $request
    ): RedirectResponse {
        try {
            $solicitud =
                $this->crearSolicitudService
                    ->ejecutar(
                        $request->validated(),
                        $request->file(
                            'comprobante_pago'
                        )
                    );

            return redirect()
                ->route(
                    'inscripciones.solicitud.exito',
                    [
                        'codigo' =>
                            $solicitud
                                ->codigo_solicitud,
                    ]
                );

        } catch (RuntimeException $exception) {

            /*
            |--------------------------------------------------------------------------
            | Error esperado de negocio
            |--------------------------------------------------------------------------
            |
            | Ejemplos:
            | - No hay período abierto.
            | - Hay más de un período abierto.
            | - No existe A0 para el programa.
            |
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    $exception->getMessage()
                );

        } catch (Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | Error inesperado
            |--------------------------------------------------------------------------
            |
            | El detalle técnico queda en el log.
            | El aspirante recibe únicamente un mensaje comprensible.
            |
            */

            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible enviar la solicitud en este momento. Revise la información e inténtelo nuevamente.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Confirmación
    |--------------------------------------------------------------------------
    */

    public function success(
        string $codigo
    ): View {
        /*
        |--------------------------------------------------------------------------
        | Código únicamente para mostrar confirmación
        |--------------------------------------------------------------------------
        |
        | No mostramos datos personales de la solicitud desde una ruta
        | pública basada únicamente en el código.
        |
        */

        return view(
            'inscripciones.exito',
            [
                'codigoSolicitud' =>
                    $codigo,
            ]
        );
    }
}