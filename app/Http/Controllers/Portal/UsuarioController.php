<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\Seguridad\GenerarPasswordTemporalService;


class UsuarioController extends Controller
{
    /**
     * Mostrar listado de cuentas de usuario.
     */
    public function index(Request $request)
    {
        $termino = trim(
            (string) $request->query('buscar', '')
        );

        $estado = $request->query('estado');
        $rol = $request->query('rol');

        $usuarios = User::query()
            ->with([
                'persona',
                'roles',
            ])
            ->when(
                $termino !== '',
                function (Builder $query) use ($termino): void {
                    $query->where(
                        function (Builder $subquery) use ($termino): void {
                            $subquery
                                ->where(
                                    'username',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhereHas(
                                    'persona',
                                    function (Builder $personaQuery) use ($termino): void {
                                        $personaQuery
                                            ->where(
                                                'primer_nombre',
                                                'like',
                                                "%{$termino}%"
                                            )
                                            ->orWhere(
                                                'segundo_nombre',
                                                'like',
                                                "%{$termino}%"
                                            )
                                            ->orWhere(
                                                'primer_apellido',
                                                'like',
                                                "%{$termino}%"
                                            )
                                            ->orWhere(
                                                'segundo_apellido',
                                                'like',
                                                "%{$termino}%"
                                            )
                                            ->orWhere(
                                                'numero_documento',
                                                'like',
                                                "%{$termino}%"
                                            );
                                    }
                                );
                        }
                    );
                }
            )
            ->when(
                in_array($estado, ['activo', 'inactivo'], true),
                function (Builder $query) use ($estado): void {
                    $query->where(
                        'activo',
                        $estado === 'activo'
                    );
                }
            )
            ->when(
                filled($rol),
                function (Builder $query) use ($rol): void {
                    $query->whereHas(
                        'roles',
                        function (Builder $rolQuery) use ($rol): void {
                            $rolQuery->where(
                                'nombre',
                                $rol
                            );
                        }
                    );
                }
            )
            ->orderBy('username')
            ->paginate(15)
            ->withQueryString();

        $resumen = [
            'total' => User::query()->count(),
            'activos' => User::query()
                ->where('activo', true)
                ->count(),
            'inactivos' => User::query()
                ->where('activo', false)
                ->count(),
            'cambio_password' => User::query()
                ->where('debe_cambiar_password', true)
                ->count(),
        ];

        return view(
            'portal.usuarios.index',
            compact(
                'usuarios',
                'resumen',
                'termino',
                'estado',
                'rol'
            )
        );
    }



    public function cambiarEstado(Request $request, User $usuario)
{
    $administradorActual = $request->user();

    if ($administradorActual->id === $usuario->id) {
        return back()->with(
            'error',
            'No puede desactivar su propia cuenta mientras tiene una sesión iniciada.'
        );
    }

    $usuario->update([
        'activo' => ! $usuario->activo,
    ]);

    $mensaje = $usuario->activo
        ? 'La cuenta de usuario ha sido activada correctamente.'
        : 'La cuenta de usuario ha sido desactivada correctamente.';

    return back()->with('success', $mensaje);
}


public function restablecerPassword(
    Request $request,
    User $usuario,
    GenerarPasswordTemporalService $generadorPassword
) {
    try {

        $passwordTemporal = $generadorPassword->generar();

        $usuario->update([
            'password' => $passwordTemporal,
            'debe_cambiar_password' => true,
        ]);

        return back()->with([
            'password_temporal' => $passwordTemporal,
            'usuario_password_temporal' => $usuario->username,
            'modal_tipo' => 'password_generado',
        ]);

    } catch (\Throwable $e) {

        report($e);

        return back()->with([
            'modal_tipo' => 'error',
            'modal_titulo' => 'No se pudo restablecer la contraseña',
            'modal_mensaje' => 'Ocurrió un problema al generar la nueva contraseña temporal. Intente nuevamente.',
        ]);
    }
}
}