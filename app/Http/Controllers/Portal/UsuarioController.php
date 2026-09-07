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
                    'codigo' => $persona->estudiante->codigo_estudiante,
                    'rol_fijo' => 'Estudiante',
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
                    'codigo' => $persona->empleado->docente->codigo_docente,
                    'rol_fijo' => null,
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
                    'codigo' => $persona->empleado->codigo_empleado,
                    'rol_fijo' => null,
                ];
            }

            return null;
        })
        ->filter()
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Roles disponibles para personal
    |--------------------------------------------------------------------------
    */

    $rolesPersonal = Rol::query()
        ->where('activo', true)
        ->whereIn('nombre', [
            'Administrador',
            'Docente',
        ])
        ->orderBy('nombre')
        ->get();

    return view(
        'portal.usuarios.create',
        compact(
            'candidatos',
            'rolesPersonal'
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

        'roles' => [
            'nullable',
            'array',
        ],

        'roles.*' => [
            'integer',
            'exists:roles,id',
        ],
    ], [
        'persona_id.required' =>
            'Seleccione una persona.',

        'persona_id.exists' =>
            'La persona seleccionada no es válida.',

        'roles.array' =>
            'La selección de roles no es válida.',

        'roles.*.exists' =>
            'Uno de los roles seleccionados no es válido.',
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
                ->findOrFail(
                    $datos['persona_id']
                );


            /*
            |--------------------------------------------------------------------------
            | Evitar segundo usuario para la misma persona
            |--------------------------------------------------------------------------
            */

            if ($persona->usuario) {
                throw ValidationException::withMessages([
                    'persona_id' =>
                        'Esta persona ya tiene una cuenta de usuario.',
                ]);
            }


            $codigo = null;
            $tipo = null;
            $rolesAsignar = collect();


            /*
            |--------------------------------------------------------------------------
            | ESTUDIANTE
            |--------------------------------------------------------------------------
            |
            | El rol no puede seleccionarse manualmente.
            |
            */

            if ($persona->estudiante) {

                $codigo =
                    $persona->estudiante
                        ->codigo_estudiante;

                $tipo = 'Estudiante';

                $rolEstudiante = Rol::query()
                    ->where('nombre', 'Estudiante')
                    ->where('activo', true)
                    ->first();

                if (! $rolEstudiante) {
                    throw ValidationException::withMessages([
                        'persona_id' =>
                            'El rol Estudiante no se encuentra disponible.',
                    ]);
                }

                $rolesAsignar->push(
                    $rolEstudiante
                );
            }


            /*
            |--------------------------------------------------------------------------
            | DOCENTE
            |--------------------------------------------------------------------------
            |
            | Puede tener:
            |
            | - Docente
            | - Administrador
            | - ambos
            |
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

                $rolesSeleccionados =
                    collect(
                        $datos['roles'] ?? []
                    )
                    ->unique()
                    ->values();


                if ($rolesSeleccionados->isEmpty()) {
                    throw ValidationException::withMessages([
                        'roles' =>
                            'Seleccione al menos un rol para el docente.',
                    ]);
                }


                $rolesAsignar = Rol::query()
                    ->whereIn(
                        'id',
                        $rolesSeleccionados
                    )
                    ->where('activo', true)
                    ->whereIn('nombre', [
                        'Docente',
                        'Administrador',
                    ])
                    ->get();


                /*
                 * Si se envió un rol manipulado o no permitido,
                 * la cantidad resultante será diferente.
                 */
                if (
                    $rolesAsignar->count()
                    !== $rolesSeleccionados->count()
                ) {
                    throw ValidationException::withMessages([
                        'roles' =>
                            'Uno de los roles seleccionados no está permitido para un docente.',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | EMPLEADO NO DOCENTE
            |--------------------------------------------------------------------------
            |
            | Por ahora solamente Administrador.
            | Más adelante aquí podremos incorporar nuevos roles de personal.
            |
            */

            elseif ($persona->empleado) {

                $codigo =
                    $persona->empleado
                        ->codigo_empleado;

                $tipo = 'Empleado';

                $rolesSeleccionados =
                    collect(
                        $datos['roles'] ?? []
                    )
                    ->unique()
                    ->values();


                if ($rolesSeleccionados->isEmpty()) {
                    throw ValidationException::withMessages([
                        'roles' =>
                            'Seleccione el rol que tendrá este empleado.',
                    ]);
                }


                $rolesAsignar = Rol::query()
                    ->whereIn(
                        'id',
                        $rolesSeleccionados
                    )
                    ->where('activo', true)
                    ->where('nombre', 'Administrador')
                    ->get();


                if (
                    $rolesAsignar->count()
                    !== $rolesSeleccionados->count()
                ) {
                    throw ValidationException::withMessages([
                        'roles' =>
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
            | Verificar Código EDMA
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
            | Evitar Código EDMA duplicado
            |--------------------------------------------------------------------------
            */

            if (
                User::query()
                    ->where(
                        'username',
                        $codigo
                    )
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
            | Crear usuario
            |--------------------------------------------------------------------------
            */

            $usuario = User::create([
                'persona_id' =>
                    $persona->id,

                'username' =>
                    $codigo,

                'email' =>
                    $persona->correo_personal,

                'password' =>
                    $passwordTemporal,

                'debe_cambiar_password' =>
                    true,

                'activo' =>
                    true,

                'ultimo_acceso_at' =>
                    null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Asignar roles
            |--------------------------------------------------------------------------
            */

            $usuario->roles()->attach(
                $rolesAsignar->pluck('id')->all()
            );


            /*
            |--------------------------------------------------------------------------
            | Resultado
            |--------------------------------------------------------------------------
            */

            return [
                'usuario_id' =>
                    $usuario->id,

                'nombre' =>
                    $persona->nombre_completo,

                'documento' =>
                    $persona->numero_documento,

                'tipo' =>
                    $tipo,

                'roles' =>
                    $rolesAsignar
                        ->pluck('nombre')
                        ->values()
                        ->all(),

                'codigo' =>
                    $codigo,

                'password_temporal' =>
                    $passwordTemporal,
            ];
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Mostrar credenciales
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'portal.usuarios.create'
        )
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