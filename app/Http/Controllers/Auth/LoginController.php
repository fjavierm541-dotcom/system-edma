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
            'username.required' => 'Ingrese su usuario.',
            'password.required' => 'Ingrese su contraseña.',
        ]);

        if (! Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'activo' => true,
        ], $request->boolean('remember'))) {

            throw ValidationException::withMessages([
                'username' => 'El usuario o la contraseña son incorrectos.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        $user->update([
            'ultimo_acceso_at' => now(),
        ]);

        return redirect()->intended(
            route('portal.inicio')
        );
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
            ->with('success', 'Sesión cerrada correctamente.');
    }
}