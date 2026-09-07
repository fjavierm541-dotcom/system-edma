<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\DocumentoPersona;
use App\Models\Persona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentoPersonaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Ver documento
    |--------------------------------------------------------------------------
    */

    public function ver(
        Persona $persona,
        DocumentoPersona $documento
    ): StreamedResponse {
        $this->comprobarPertenencia(
            $persona,
            $documento
        );

        abort_unless(
            Storage::disk('public')->exists(
                $documento->ruta_archivo
            ),
            404,
            'El archivo no se encuentra disponible.'
        );

        return Storage::disk('public')->response(
            $documento->ruta_archivo,
            $documento->nombre_original,
            [
                'Content-Disposition' =>
                    'inline; filename="' .
                    $documento->nombre_original .
                    '"',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Descargar documento
    |--------------------------------------------------------------------------
    */

    public function descargar(
        Persona $persona,
        DocumentoPersona $documento
    ): StreamedResponse {
        $this->comprobarPertenencia(
            $persona,
            $documento
        );

        abort_unless(
            Storage::disk('public')->exists(
                $documento->ruta_archivo
            ),
            404,
            'El archivo no se encuentra disponible.'
        );

        return Storage::disk('public')->download(
            $documento->ruta_archivo,
            $documento->nombre_original
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cambiar verificación
    |--------------------------------------------------------------------------
    */

    public function cambiarVerificacion(
        Persona $persona,
        DocumentoPersona $documento
    ): RedirectResponse {
        $this->comprobarPertenencia(
            $persona,
            $documento
        );

        $nuevoEstado =
            !$documento->verificado;

        $documento->update([
            'verificado' =>
                $nuevoEstado,

            'verificado_at' =>
                $nuevoEstado
                    ? now()
                    : null,

            'verificado_por' =>
                $nuevoEstado
                    ? auth()->id()
                    : null,
        ]);

        return back()->with(
            'success',
            $nuevoEstado
                ? 'El documento fue marcado como verificado.'
                : 'El documento fue marcado como pendiente de verificación.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Seguridad
    |--------------------------------------------------------------------------
    */

    private function comprobarPertenencia(
        Persona $persona,
        DocumentoPersona $documento
    ): void {
        abort_unless(
            $documento->persona_id ===
                $persona->id,
            404
        );
    }
}