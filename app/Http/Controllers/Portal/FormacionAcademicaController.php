<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormacionAcademicaRequest;
use App\Http\Requests\UpdateFormacionAcademicaRequest;
use App\Models\Empleado;
use App\Models\FormacionAcademica;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class FormacionAcademicaController extends Controller
{
    public function store(
        StoreFormacionAcademicaRequest $request,
        Empleado $empleado
    ): RedirectResponse {
        try {
            DB::transaction(
                function () use (
                    $request,
                    $empleado
                ): void {
                    $datos = $request->validated();

                    if ($datos['es_principal'] ?? false) {
                        FormacionAcademica::query()
                            ->where(
                                'persona_id',
                                $empleado->persona_id
                            )
                            ->where('es_principal', true)
                            ->update([
                                'es_principal' => false,
                            ]);
                    }

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
            Log::error(
                'Error al registrar formación académica.',
                [
                    'empleado_id' => $empleado->id,
                    'persona_id' => $empleado->persona_id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
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

        try {
            DB::transaction(
                function () use (
                    $request,
                    $empleado,
                    $formacion
                ): void {
                    $datos = $request->validated();

                    if ($datos['es_principal'] ?? false) {
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
                                'es_principal' => false,
                            ]);
                    }

                    $formacion->update($datos);
                }
            );

            return back()->with(
                'success',
                'La formación académica fue actualizada correctamente.'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error al actualizar formación académica.',
                [
                    'empleado_id' => $empleado->id,
                    'formacion_id' => $formacion->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
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
                'estado' => $nuevoEstado,
            ];

            if ($nuevoEstado === 'inactivo') {
                $datos['es_principal'] = false;
            }

            $formacion->update($datos);

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
                    'empleado_id' => $empleado->id,
                    'formacion_id' => $formacion->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()->with(
                'error',
                'No fue posible cambiar el estado de la formación académica.'
            );
        }
    }

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