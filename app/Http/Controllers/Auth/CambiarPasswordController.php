<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CambiarPasswordController extends Controller
{
    /**
     * Mostrar formulario para establecer una nueva contraseña.
     */
    public function edit()
    {
        return view('auth.cambiar-password');
    }

    /**
     * Guardar la nueva contraseña.
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'password.required' => 'Ingrese una nueva contraseña.',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $user = $request->user();

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'La nueva contraseña debe ser diferente a la contraseña temporal.',
            ]);
        }

        $user->update([
            'password' => $request->password,
            'debe_cambiar_password' => false,
        ]);

        return redirect()
            ->route('portal.inicio')
            ->with(
                'success',
                'Su contraseña ha sido actualizada correctamente.'
            );
    }
}