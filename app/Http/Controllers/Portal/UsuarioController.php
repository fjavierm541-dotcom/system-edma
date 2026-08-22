<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\Seguridad\GenerarPasswordTemporalService;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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


public function create()
{
    $personas = Persona::query()
        ->with([
            'estudiante',
            'empleado.docente',
            'usuario',
        ])
        ->whereDoesntHave('usuario')
        ->where(function ($query) {
            $query
                ->whereHas('estudiante')
                ->orWhereHas('empleado');
        })
        ->orderBy('primer_nombre')
        ->orderBy('primer_apellido')
        ->get();

    $candidatos = $personas
        ->map(function (Persona $persona) {

            /*
            |--------------------------------------------------------------------------
            | Estudiante
            |--------------------------------------------------------------------------
            */

            if ($persona->estudiante) {
                return [
                    'persona_id' => $persona->id,
                    'nombre' => $persona->nombre_completo,
                    'documento' => $persona->numero_documento,
                    'tipo' => 'estudiante',
                    'tipo_label' => 'Estudiante',
                    'rol' => 'Estudiante',
                    'codigo' => $persona->estudiante->codigo_estudiante,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Docente
            |--------------------------------------------------------------------------
            */

            if (
                $persona->empleado
                && $persona->empleado->docente
            ) {
                return [
                    'persona_id' => $persona->id,
                    'nombre' => $persona->nombre_completo,
                    'documento' => $persona->numero_documento,
                    'tipo' => 'docente',
                    'tipo_label' => 'Docente',
                    'rol' => 'Docente',
                    'codigo' => $persona->empleado->docente->codigo_docente,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Empleado no docente
            |--------------------------------------------------------------------------
            */

            if ($persona->empleado) {
                return [
                    'persona_id' => $persona->id,
                    'nombre' => $persona->nombre_completo,
                    'documento' => $persona->numero_documento,
                    'tipo' => 'empleado',
                    'tipo_label' => 'Empleado',
                    'rol' => null,
                    'codigo' => $persona->empleado->codigo_empleado,
                ];
            }

            return null;
        })
        ->filter()
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Roles disponibles para empleados no docentes
    |--------------------------------------------------------------------------
    |
    | Estudiante y Docente nunca se seleccionan manualmente.
    | Por ahora el único rol administrativo permitido es Administrador.
    |
    */

    $rolesAdministrativos = Rol::query()
        ->where('nombre', 'Administrador')
        ->orderBy('nombre')
        ->get();

    return view(
        'portal.usuarios.create',
        compact(
            'candidatos',
            'rolesAdministrativos'
        )
    );
}


public function store(
    Request $request,
    GenerarPasswordTemporalService $generadorPassword
) {
    $datos = $request->validate([
        'persona_id' => [
            'required',
            'integer',
            'exists:personas,id',
        ],
        'rol_id' => [
            'nullable',
            'integer',
            'exists:roles,id',
        ],
    ], [
        'persona_id.required' =>
            'Seleccione una persona.',

        'persona_id.exists' =>
            'La persona seleccionada no es válida.',

        'rol_id.exists' =>
            'El rol seleccionado no es válido.',
    ]);

    $resultado = DB::transaction(
        function () use (
            $datos,
            $generadorPassword
        ) {

            $persona = Persona::query()
                ->with([
                    'usuario',
                    'estudiante',
                    'empleado.docente',
                ])
                ->lockForUpdate()
                ->findOrFail($datos['persona_id']);


            /*
            |--------------------------------------------------------------------------
            | La persona no puede tener ya un usuario
            |--------------------------------------------------------------------------
            */

            if ($persona->usuario) {
                throw ValidationException::withMessages([
                    'persona_id' =>
                        'Esta persona ya tiene una cuenta de usuario.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Determinar tipo, código EDMA y rol
            |--------------------------------------------------------------------------
            */

            $codigo = null;
            $rol = null;
            $tipo = null;


            /*
            |--------------------------------------------------------------------------
            | Estudiante
            |--------------------------------------------------------------------------
            */

            if ($persona->estudiante) {

                $codigo =
                    $persona->estudiante->codigo_estudiante;

                $tipo = 'Estudiante';

                $rol = Rol::query()
                    ->where('nombre', 'Estudiante')
                    ->where('activo', true)
                    ->first();

                if (! $rol) {
                    throw ValidationException::withMessages([
                        'persona_id' =>
                            'El rol Estudiante no se encuentra disponible.',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Docente
            |--------------------------------------------------------------------------
            */

            elseif (
                $persona->empleado
                && $persona->empleado->docente
            ) {

                $codigo =
                    $persona->empleado
                        ->docente
                        ->codigo_docente;

                $tipo = 'Docente';

                $rol = Rol::query()
                    ->where('nombre', 'Docente')
                    ->where('activo', true)
                    ->first();

                if (! $rol) {
                    throw ValidationException::withMessages([
                        'persona_id' =>
                            'El rol Docente no se encuentra disponible.',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Empleado no docente
            |--------------------------------------------------------------------------
            */

            elseif ($persona->empleado) {

                $codigo =
                    $persona->empleado->codigo_empleado;

                $tipo = 'Empleado';

                if (empty($datos['rol_id'])) {
                    throw ValidationException::withMessages([
                        'rol_id' =>
                            'Seleccione el rol que tendrá este empleado.',
                    ]);
                }

                $rol = Rol::query()
                    ->whereKey($datos['rol_id'])
                    ->where('activo', true)
                    ->where('nombre', 'Administrador')
                    ->first();

                if (! $rol) {
                    throw ValidationException::withMessages([
                        'rol_id' =>
                            'El rol seleccionado no está permitido para este empleado.',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Persona no elegible
            |--------------------------------------------------------------------------
            */

            else {
                throw ValidationException::withMessages([
                    'persona_id' =>
                        'La persona seleccionada no puede tener una cuenta de usuario.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Verificar código institucional
            |--------------------------------------------------------------------------
            */

            if (blank($codigo)) {
                throw ValidationException::withMessages([
                    'persona_id' =>
                        'La persona seleccionada no tiene un Código EDMA asignado.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Evitar duplicidad del Código EDMA
            |--------------------------------------------------------------------------
            */

            if (
                User::query()
                    ->where('username', $codigo)
                    ->exists()
            ) {
                throw ValidationException::withMessages([
                    'persona_id' =>
                        'Ya existe una cuenta asociada a este Código EDMA.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Generar contraseña temporal
            |--------------------------------------------------------------------------
            */

            $passwordTemporal =
                $generadorPassword->generar();


            /*
            |--------------------------------------------------------------------------
            | Crear cuenta
            |--------------------------------------------------------------------------
            */

            $usuario = User::create([
                'persona_id' => $persona->id,
                'username' => $codigo,
                'email' => $persona->correo_personal,
                'password' => $passwordTemporal,
                'debe_cambiar_password' => true,
                'activo' => true,
                'ultimo_acceso_at' => null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Asignar rol
            |--------------------------------------------------------------------------
            */

            $usuario->roles()->attach($rol->id);


            return [
                'usuario_id' => $usuario->id,
                'nombre' => $persona->nombre_completo,
                'documento' => $persona->numero_documento,
                'tipo' => $tipo,
                'rol' => $rol->nombre,
                'codigo' => $codigo,
                'password_temporal' => $passwordTemporal,
            ];
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Mostrar resultado en la misma pantalla
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('portal.usuarios.create')
        ->with(
            'usuario_creado',
            $resultado
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