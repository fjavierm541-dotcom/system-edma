<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForzarCambioPassword
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user &&
            $user->debe_cambiar_password &&
            ! $request->routeIs('password.change.*') &&
            ! $request->routeIs('logout')
        ) {
            return redirect()->route('password.change.edit');
        }

        return $next($request);
    }
}