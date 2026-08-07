<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstudianteResponsableRequest;
use App\Http\Requests\UpdateEstudianteResponsableRequest;
use App\Models\Estudiante;
use App\Models\EstudianteResponsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EstudianteResponsableController extends Controller
{
    /**
     * Asociar un nuevo responsable al estudiante.
     */
    public function store(
        StoreEstudianteResponsableRequest $request,
        Estudiante $estudiante
    ): RedirectResponse {
        try {
            $responsable = DB::transaction(
                function () use (
                    $request,
                    $estudiante
                ): EstudianteResponsable {
                    $datos = $request->validated();

                    /*
                     * Si será el responsable principal, se desmarca
                     * cualquier responsable principal anterior.
                     */
                    if ($datos['es_principal'] ?? false) {
                        $this->desmarcarPrincipalAnterior(
                            $estudiante
                        );
                    }

                    $datos['estudiante_id'] = $estudiante->id;

                    return EstudianteResponsable::query()
                        ->create($datos);
                }
            );

            return redirect()
                ->route(
                    'portal.estudiantes.show',
                    $estudiante
                )
                ->with(
                    'success',
                    "Se agregó correctamente a {$responsable->personaResponsable->nombre_completo} como responsable."
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al agregar un responsable al estudiante.',
                [
                    'estudiante_id' => $estudiante->id,
                    'responsable_persona_id' =>
                        $request->input('responsable_persona_id'),
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible agregar al responsable. Intente nuevamente.'
                );
        }
    }

    /**
     * Actualizar la relación del responsable.
     */
    public function update(
        UpdateEstudianteResponsableRequest $request,
        Estudiante $estudiante,
        EstudianteResponsable $responsable
    ): RedirectResponse {
        $this->comprobarPertenencia(
            $estudiante,
            $responsable
        );

        try {
            DB::transaction(
                function () use (
                    $request,
                    $estudiante,
                    $responsable
                ): void {
                    $datos = $request->validated();

                    if ($datos['es_principal'] ?? false) {
                        $this->desmarcarPrincipalAnterior(
                            $estudiante,
                            $responsable->id
                        );
                    }

                    $responsable->update($datos);
                }
            );

            return redirect()
                ->route(
                    'portal.estudiantes.show',
                    $estudiante
                )
                ->with(
                    'success',
                    'La información del responsable fue actualizada correctamente.'
                );
        } catch (Throwable $exception) {
            Log::error(
                'Error al actualizar un responsable.',
                [
                    'estudiante_id' => $estudiante->id,
                    'responsable_id' => $responsable->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible actualizar al responsable. Intente nuevamente.'
                );
        }
    }

    /**
     * Activar o desactivar un responsable.
     */
    public function cambiarEstado(
        Estudiante $estudiante,
        EstudianteResponsable $responsable
    ): RedirectResponse {
        $this->comprobarPertenencia(
            $estudiante,
            $responsable
        );

        $nuevoEstado = !$responsable->activo;

        try {
            DB::transaction(
                function () use (
                    $responsable,
                    $nuevoEstado
                ): void {
                    $datos = [
                        'activo' => $nuevoEstado,
                    ];

                    /*
                     * Un responsable inactivo no puede continuar
                     * marcado como principal.
                     */
                    if (!$nuevoEstado) {
                        $datos['es_principal'] = false;
                    }

                    $responsable->update($datos);
                }
            );

            return back()->with(
                'success',
                $nuevoEstado
                    ? 'El responsable fue activado correctamente.'
                    : 'El responsable fue desactivado correctamente.'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error al cambiar el estado de un responsable.',
                [
                    'estudiante_id' => $estudiante->id,
                    'responsable_id' => $responsable->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()->with(
                'error',
                'No fue posible cambiar el estado del responsable.'
            );
        }
    }

    /**
     * Desmarcar cualquier responsable principal anterior.
     */
private function desmarcarPrincipalAnterior(
    Estudiante $estudiante,
    ?int $exceptoResponsableId = null
): void {
    EstudianteResponsable::query()
        ->where('estudiante_id', $estudiante->id)
        ->when(
            $exceptoResponsableId !== null,
            fn ($query) => $query->where(
                'id',
                '!=',
                $exceptoResponsableId
            )
        )
        ->where('es_principal', true)
        ->update([
            'es_principal' => false,
        ]);
}

    /**
     * Evitar modificar responsables de otro estudiante.
     */
    private function comprobarPertenencia(
        Estudiante $estudiante,
        EstudianteResponsable $responsable
    ): void {
        abort_unless(
            $responsable->estudiante_id === $estudiante->id,
            404
        );
    }
}