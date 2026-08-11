<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCuentaBancariaRequest;
use App\Http\Requests\UpdateCuentaBancariaRequest;
use App\Models\CuentaBancaria;
use App\Models\Empleado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CuentaBancariaController extends Controller
{
    public function store(
        StoreCuentaBancariaRequest $request,
        Empleado $empleado
    ): RedirectResponse {
        try {
            DB::transaction(
                function () use (
                    $request,
                    $empleado
                ): void {
                    $datos = $request->validated();

                    /*
                     * Solo puede existir una cuenta principal
                     * por empleado.
                     */
                    if ($datos['es_principal'] ?? false) {
                        $this->desmarcarCuentaPrincipal(
                            $empleado
                        );
                    }

                    $datos['empleado_id'] =
                        $empleado->id;

                    CuentaBancaria::query()
                        ->create($datos);
                }
            );

            return back()->with(
                'success',
                'La cuenta bancaria fue registrada correctamente.'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error al registrar cuenta bancaria.',
                [
                    'empleado_id' => $empleado->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible registrar la cuenta bancaria.'
                );
        }
    }

    public function update(
        UpdateCuentaBancariaRequest $request,
        Empleado $empleado,
        CuentaBancaria $cuenta
    ): RedirectResponse {
        $this->comprobarPertenencia(
            $empleado,
            $cuenta
        );

        try {
            DB::transaction(
                function () use (
                    $request,
                    $empleado,
                    $cuenta
                ): void {
                    $datos = $request->validated();

                    if ($datos['es_principal'] ?? false) {
                        $this->desmarcarCuentaPrincipal(
                            $empleado,
                            $cuenta->id
                        );
                    }

                    /*
                     * Una cuenta inactiva no debe permanecer
                     * marcada como principal.
                     */
                    if (
                        array_key_exists('activo', $datos) &&
                        !$datos['activo']
                    ) {
                        $datos['es_principal'] = false;
                    }

                    $cuenta->update($datos);
                }
            );

            return back()->with(
                'success',
                'La cuenta bancaria fue actualizada correctamente.'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error al actualizar cuenta bancaria.',
                [
                    'empleado_id' => $empleado->id,
                    'cuenta_id' => $cuenta->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible actualizar la cuenta bancaria.'
                );
        }
    }

    public function cambiarEstado(
        Empleado $empleado,
        CuentaBancaria $cuenta
    ): RedirectResponse {
        $this->comprobarPertenencia(
            $empleado,
            $cuenta
        );

        try {
            DB::transaction(
                function () use ($cuenta): void {
                    $nuevoEstado = !$cuenta->activo;

                    $datos = [
                        'activo' => $nuevoEstado,
                    ];

                    if (!$nuevoEstado) {
                        $datos['es_principal'] = false;
                    }

                    $cuenta->update($datos);
                }
            );

            return back()->with(
                'success',
                $cuenta->fresh()->activo
                    ? 'La cuenta bancaria fue activada correctamente.'
                    : 'La cuenta bancaria fue desactivada correctamente.'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error al cambiar estado de cuenta bancaria.',
                [
                    'empleado_id' => $empleado->id,
                    'cuenta_id' => $cuenta->id,
                    'usuario_id' => auth()->id(),
                    'exception' => $exception,
                ]
            );

            return back()->with(
                'error',
                'No fue posible cambiar el estado de la cuenta bancaria.'
            );
        }
    }

    private function desmarcarCuentaPrincipal(
        Empleado $empleado,
        ?int $exceptoCuentaId = null
    ): void {
        CuentaBancaria::query()
            ->where(
                'empleado_id',
                $empleado->id
            )
            ->when(
                $exceptoCuentaId !== null,
                fn ($query) => $query->where(
                    'id',
                    '!=',
                    $exceptoCuentaId
                )
            )
            ->where(
                'es_principal',
                true
            )
            ->update([
                'es_principal' => false,
            ]);
    }

    private function comprobarPertenencia(
        Empleado $empleado,
        CuentaBancaria $cuenta
    ): void {
        abort_unless(
            $cuenta->empleado_id ===
                $empleado->id,
            404
        );
    }
}