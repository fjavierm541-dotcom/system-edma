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
     * Guardar la nueva contraseña personal.
     */
   public function update(Request $request)
{
    $request->validate([
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*?&.#_\-]/',
        ],
    ], [
        'password.required' =>
            'Ingrese una nueva contraseña.',

        'password.min' =>
            'La contraseña debe contener al menos 8 caracteres.',

        'password.confirmed' =>
            'Las contraseñas ingresadas no coinciden.',

        'password.regex' =>
            'La contraseña debe incluir al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.',
    ]);

    $user = $request->user();

    $nuevaPassword = (string) $request->input('password');

    if (
        Hash::check(
            $nuevaPassword,
            $user->getAuthPassword()
        )
    ) {
        return back()
            ->withInput()
            ->withErrors([
                'password' =>
                    'La nueva contraseña debe ser diferente a la contraseña que está utilizando actualmente.',
            ]);
    }

    $user->update([
        'password' => $nuevaPassword,
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