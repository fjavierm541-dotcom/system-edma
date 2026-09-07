<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormacionAcademicaRequest;
use App\Http\Requests\UpdateFormacionAcademicaRequest;
use App\Models\DocumentoPersona;
use App\Models\Empleado;
use App\Models\FormacionAcademica;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FormacionAcademicaController extends Controller
{
    public function store(
        StoreFormacionAcademicaRequest $request,
        Empleado $empleado
    ): RedirectResponse {
        $rutaArchivoCreado = null;

        try {
            DB::transaction(
                function () use (
                    $request,
                    $empleado,
                    &$rutaArchivoCreado
                ): void {
                    $datos = $request->validated();

                    /*
                    |--------------------------------------------------------------------------
                    | Nuevo documento
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $request->hasFile(
                            'documento_nuevo'
                        )
                    ) {
                        $archivo =
                            $request->file(
                                'documento_nuevo'
                            );

                        $documento =
                            $this->crearDocumentoPersona(
                                $archivo,
                                $empleado,
                                $rutaArchivoCreado
                            );

                        $datos['documento_persona_id'] =
                            $documento->id;
                    }

                    unset(
                        $datos['documento_nuevo']
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Formación principal
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $datos['es_principal']
                        ?? false
                    ) {
                        FormacionAcademica::query()
                            ->where(
                                'persona_id',
                                $empleado->persona_id
                            )
                            ->where(
                                'es_principal',
                                true
                            )
                            ->update([
                                'es_principal' =>
                                    false,
                            ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Crear formación
                    |--------------------------------------------------------------------------
                    */

                    $datos['persona_id'] =
                        $empleado->persona_id;

                    FormacionAcademica::query()
                        ->create($datos);
                }
            );

            return back()->with(
                'success',
                'La formación académica fue registrada correctamente.'
            );

        } catch (Throwable $exception) {

            if ($rutaArchivoCreado) {
                Storage::disk('public')
                    ->delete(
                        $rutaArchivoCreado
                    );
            }

            Log::error(
                'Error al registrar formación académica.',
                [
                    'empleado_id' =>
                        $empleado->id,

                    'persona_id' =>
                        $empleado->persona_id,

                    'usuario_id' =>
                        auth()->id(),

                    'exception' =>
                        $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible registrar la formación académica.'
                );
        }
    }

    public function update(
        UpdateFormacionAcademicaRequest $request,
        Empleado $empleado,
        FormacionAcademica $formacion
    ): RedirectResponse {
        $this->comprobarPertenencia(
            $empleado,
            $formacion
        );

        $rutaArchivoCreado = null;

        try {
            DB::transaction(
                function () use (
                    $request,
                    $empleado,
                    $formacion,
                    &$rutaArchivoCreado
                ): void {
                    $datos = $request->validated();

                    /*
                    |--------------------------------------------------------------------------
                    | Nuevo documento
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $request->hasFile(
                            'documento_nuevo'
                        )
                    ) {
                        $archivo =
                            $request->file(
                                'documento_nuevo'
                            );

                        $documento =
                            $this->crearDocumentoPersona(
                                $archivo,
                                $empleado,
                                $rutaArchivoCreado
                            );

                        $datos['documento_persona_id'] =
                            $documento->id;
                    }

                    unset(
                        $datos['documento_nuevo']
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Formación principal
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $datos['es_principal']
                        ?? false
                    ) {
                        FormacionAcademica::query()
                            ->where(
                                'persona_id',
                                $empleado->persona_id
                            )
                            ->where(
                                'id',
                                '!=',
                                $formacion->id
                            )
                            ->where(
                                'es_principal',
                                true
                            )
                            ->update([
                                'es_principal' =>
                                    false,
                            ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Actualizar formación
                    |--------------------------------------------------------------------------
                    */

                    $formacion->update(
                        $datos
                    );
                }
            );

            return back()->with(
                'success',
                'La formación académica fue actualizada correctamente.'
            );

        } catch (Throwable $exception) {

            if ($rutaArchivoCreado) {
                Storage::disk('public')
                    ->delete(
                        $rutaArchivoCreado
                    );
            }

            Log::error(
                'Error al actualizar formación académica.',
                [
                    'empleado_id' =>
                        $empleado->id,

                    'formacion_id' =>
                        $formacion->id,

                    'usuario_id' =>
                        auth()->id(),

                    'exception' =>
                        $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible actualizar la formación académica.'
                );
        }
    }

    public function cambiarEstado(
        Empleado $empleado,
        FormacionAcademica $formacion
    ): RedirectResponse {
        $this->comprobarPertenencia(
            $empleado,
            $formacion
        );

        try {
            $nuevoEstado =
                $formacion->estado === 'activo'
                    ? 'inactivo'
                    : 'activo';

            $datos = [
                'estado' =>
                    $nuevoEstado,
            ];

            if (
                $nuevoEstado === 'inactivo'
            ) {
                $datos['es_principal'] =
                    false;
            }

            $formacion->update(
                $datos
            );

            return back()->with(
                'success',
                $nuevoEstado === 'activo'
                    ? 'La formación académica fue activada correctamente.'
                    : 'La formación académica fue desactivada correctamente.'
            );

        } catch (Throwable $exception) {
            Log::error(
                'Error al cambiar estado de formación académica.',
                [
                    'empleado_id' =>
                        $empleado->id,

                    'formacion_id' =>
                        $formacion->id,

                    'usuario_id' =>
                        auth()->id(),

                    'exception' =>
                        $exception,
                ]
            );

            return back()->with(
                'error',
                'No fue posible cambiar el estado de la formación académica.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Crear documento de persona
    |--------------------------------------------------------------------------
    */

    private function crearDocumentoPersona(
        UploadedFile $archivo,
        Empleado $empleado,
        ?string &$rutaArchivoCreado
    ): DocumentoPersona {
        $rutaArchivoCreado =
            $archivo->store(
                'personas/'
                    . $empleado->persona_id
                    . '/documentos',
                'public'
            );

        return DocumentoPersona::query()
            ->create([
                'persona_id' =>
                    $empleado->persona_id,

                'tipo_documento' =>
                    'formacion_academica',

                'nombre_original' =>
                    $archivo
                        ->getClientOriginalName(),

                'nombre_almacenado' =>
                    basename(
                        $rutaArchivoCreado
                    ),

                'ruta_archivo' =>
                    $rutaArchivoCreado,

                'extension' =>
                    strtolower(
                        $archivo
                            ->getClientOriginalExtension()
                    ),

                'mime_type' =>
                    $archivo->getMimeType(),

                'tamano_bytes' =>
                    $archivo->getSize(),

                'verificado' => true,

                'verificado_at' => now(),

                'verificado_por' => auth()->id(),

                'estado' =>
                    'activo',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Comprobar pertenencia
    |--------------------------------------------------------------------------
    */

    private function comprobarPertenencia(
        Empleado $empleado,
        FormacionAcademica $formacion
    ): void {
        abort_unless(
            $formacion->persona_id ===
                $empleado->persona_id,
            404
        );
    }
}