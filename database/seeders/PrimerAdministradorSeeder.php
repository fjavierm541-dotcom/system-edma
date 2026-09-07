<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\Rol;
use App\Models\User;
use App\Services\Empleados\CrearEmpleadoService;
use App\Services\Seguridad\GenerarPasswordTemporalService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PrimerAdministradorSeeder extends Seeder
{
    /**
     * Crear la primera cuenta administrativa de EDMA.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Verificar si ya existe un Administrador
        |--------------------------------------------------------------------------
        */

        $administradorExistente = User::query()
            ->whereHas(
                'roles',
                function ($query): void {
                    $query->where(
                        'nombre',
                        'Administrador'
                    );
                }
            )
            ->exists();

        if ($administradorExistente) {

            $this->command?->warn(
                'Ya existe al menos un usuario con rol Administrador. Se omitió la creación del administrador inicial.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Obtener rol Administrador
        |--------------------------------------------------------------------------
        */

        $rolAdministrador = Rol::query()
            ->where('nombre', 'Administrador')
            ->where('activo', true)
            ->first();

        if (! $rolAdministrador) {
            throw new RuntimeException(
                'No existe un rol Administrador activo. Ejecute primero RolSeeder.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Obtener servicios
        |--------------------------------------------------------------------------
        */

        $crearEmpleado =
            app(CrearEmpleadoService::class);

        $generadorPassword =
            app(GenerarPasswordTemporalService::class);


        /*
        |--------------------------------------------------------------------------
        | Generar contraseña temporal
        |--------------------------------------------------------------------------
        */

        $passwordTemporal =
            $generadorPassword->generar();


        /*
        |--------------------------------------------------------------------------
        | Crear Persona → Empleado → Usuario
        |--------------------------------------------------------------------------
        */

        $resultado = DB::transaction(
            function () use (
                $crearEmpleado,
                $rolAdministrador,
                $passwordTemporal
            ) {

                /*
                |--------------------------------------------------------------------------
                | Persona
                |--------------------------------------------------------------------------
                */

                $persona = Persona::query()
                    ->create([
                        'primer_nombre' =>
                            'Javier',

                        'segundo_nombre' =>
                            null,

                        'primer_apellido' =>
                            'Estrada',

                        'segundo_apellido' =>
                            'Medina',

                        'correo_personal' =>
                            'fjavierm541@gmail.com',

                        'estado' =>
                            'activo',
                    ]);


                /*
                |--------------------------------------------------------------------------
                | Empleado
                |--------------------------------------------------------------------------
                |
                | CrearEmpleadoService debe generar automáticamente:
                |
                | EDMA-EMP-2026-0001
                |
                */

                $empleado =
                    $crearEmpleado->ejecutar([
                        'persona_id' =>
                            $persona->id,

                        'fecha_ingreso' =>
                            now()->toDateString(),

                        'estado' =>
                            'activo',

                        'observaciones' =>
                            'Administrador inicial del Sistema Académico EDMA.',
                    ]);


                /*
                |--------------------------------------------------------------------------
                | Usuario
                |--------------------------------------------------------------------------
                */

                $usuario = User::query()
                    ->create([
                        'persona_id' =>
                            $persona->id,

                        'username' =>
                            $empleado->codigo_empleado,

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
                | Rol
                |--------------------------------------------------------------------------
                */

                $usuario
                    ->roles()
                    ->attach(
                        $rolAdministrador->id
                    );


                /*
                |--------------------------------------------------------------------------
                | Resultado
                |--------------------------------------------------------------------------
                */

                return [
                    'nombre' =>
                        $persona->nombre_completo,

                    'codigo' =>
                        $usuario->username,

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

        $this->command?->newLine();

        $this->command?->info(
            'Administrador inicial de EDMA creado correctamente.'
        );

        $this->command?->newLine();

        $this->command?->line(
            'Nombre: ' .
            $resultado['nombre']
        );

        $this->command?->line(
            'Código EDMA: ' .
            $resultado['codigo']
        );

        $this->command?->line(
            'Contraseña temporal: ' .
            $resultado['password_temporal']
        );

        $this->command?->newLine();

        $this->command?->warn(
            'Guarde la contraseña temporal. El usuario deberá cambiarla al iniciar sesión.'
        );

        $this->command?->newLine();
    }
}