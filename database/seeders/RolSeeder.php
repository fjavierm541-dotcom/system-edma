<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'Administrador',
                'descripcion' =>
                    'Acceso administrativo al Sistema EDMA.',
                'activo' => true,
            ],
            [
                'nombre' => 'Docente',
                'descripcion' =>
                    'Acceso a funciones académicas asignadas al docente.',
                'activo' => true,
            ],
            [
                'nombre' => 'Estudiante',
                'descripcion' =>
                    'Acceso al Portal EDMA para estudiantes.',
                'activo' => true,
            ],
        ];

        foreach ($roles as $rol) {
            Rol::query()->updateOrCreate(
                [
                    'nombre' => $rol['nombre'],
                ],
                $rol
            );
        }
    }
}