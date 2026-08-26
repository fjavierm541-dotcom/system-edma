<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Mostrar formulario de inicio de sesión.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Procesar inicio de sesión.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Ingrese su código EDMA.',
            'password.required' => 'Ingrese su contraseña.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalizar Código EDMA
        |--------------------------------------------------------------------------
        |
        | El usuario puede ingresar:
        |
        | 20260005
        | 2026-0005
        | EDMA-2026-0005
        |
        | DOC20260002
        | DOC-2026-0002
        | EDMA-DOC-2026-0002
        |
        | EMP20260004
        | EMP-2026-0004
        | EDMA-EMP-2026-0004
        |
        */

        $codigoEdma = $this->normalizarCodigoEdma(
            $credentials['username']
        );

        /*
        |--------------------------------------------------------------------------
        | Intentar autenticación
        |--------------------------------------------------------------------------
        */

        if (! Auth::attempt([
            'username' => $codigoEdma,
            'password' => $credentials['password'],
            'activo' => true,
        ])) {

            throw ValidationException::withMessages([
                'username' => 'El código EDMA o la contraseña son incorrectos.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerar sesión
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Registrar último acceso
        |--------------------------------------------------------------------------
        */

        $user->update([
            'ultimo_acceso_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirigir al portal
        |--------------------------------------------------------------------------
        */

        return redirect()->intended(
            route('portal.inicio')
        );
    }

    /**
     * Normalizar el Código EDMA ingresado.
     */
    private function normalizarCodigoEdma(string $codigo): string
    {
        /*
        |--------------------------------------------------------------------------
        | Limpiar entrada
        |--------------------------------------------------------------------------
        */

        $codigo = strtoupper(
            trim($codigo)
        );

        /*
         * Eliminamos espacios y guiones.
         *
         * Ejemplo:
         * EDMA-DOC-2026-0002
         * →
         * EDMADOC20260002
         */

        $codigo = str_replace(
            [' ', '-'],
            '',
            $codigo
        );

        /*
        |--------------------------------------------------------------------------
        | Eliminar prefijo EDMA si fue escrito
        |--------------------------------------------------------------------------
        */

        if (str_starts_with($codigo, 'EDMA')) {
            $codigo = substr(
                $codigo,
                4
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Docente
        |--------------------------------------------------------------------------
        |
        | Entrada:
        | DOC20260002
        |
        | Resultado:
        | EDMA-DOC-2026-0002
        |
        */

        if (preg_match(
            '/^DOC(\d{4})(\d{4})$/',
            $codigo,
            $coincidencias
        )) {

            return sprintf(
                'EDMA-DOC-%s-%s',
                $coincidencias[1],
                $coincidencias[2]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Empleado / Administrador
        |--------------------------------------------------------------------------
        |
        | Entrada:
        | EMP20260004
        |
        | Resultado:
        | EDMA-EMP-2026-0004
        |
        */

        if (preg_match(
            '/^EMP(\d{4})(\d{4})$/',
            $codigo,
            $coincidencias
        )) {

            return sprintf(
                'EDMA-EMP-%s-%s',
                $coincidencias[1],
                $coincidencias[2]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Estudiante
        |--------------------------------------------------------------------------
        |
        | Entrada:
        | 20260005
        |
        | Resultado:
        | EDMA-2026-0005
        |
        */

        if (preg_match(
            '/^(\d{4})(\d{4})$/',
            $codigo,
            $coincidencias
        )) {

            return sprintf(
                'EDMA-%s-%s',
                $coincidencias[1],
                $coincidencias[2]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Formato no reconocido
        |--------------------------------------------------------------------------
        |
        | Devolvemos un valor que no podrá coincidir con ningún usuario.
        | El mensaje mostrado seguirá siendo genérico para no revelar
        | información sobre cuentas existentes.
        |
        */

        return '__CODIGO_EDMA_INVALIDO__';
    }

    /**
     * Cerrar sesión.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Sesión cerrada correctamente.'
            );
    }
}