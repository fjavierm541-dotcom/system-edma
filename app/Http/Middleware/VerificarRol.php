<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        foreach ($roles as $rol) {
            if ($user->tieneRol($rol)) {
                return $next($request);
            }
        }

        abort(403, 'No tiene permisos para acceder a esta sección.');
    }
}
