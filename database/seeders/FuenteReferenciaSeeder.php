<?php

namespace Database\Seeders;

use App\Models\FuenteReferencia;
use Illuminate\Database\Seeder;

class FuenteReferenciaSeeder extends Seeder
{
    public function run(): void
    {
        $fuentes = [
            [
                'codigo' => 'REDES_SOCIALES',
                'nombre' => 'Redes sociales',
                'requiere_especificacion' => false,
                'activo' => true,
            ],
            [
                'codigo' => 'RECOMENDACION_ESTUDIANTE',
                'nombre' => 'Recomendación de un estudiante de EDMA',
                'requiere_especificacion' => false,
                'activo' => true,
            ],
            [
                'codigo' => 'RECOMENDACION_CONOCIDO',
                'nombre' => 'Recomendación de un familiar o conocido',
                'requiere_especificacion' => false,
                'activo' => true,
            ],
            [
                'codigo' => 'PUBLICIDAD_INTERNET',
                'nombre' => 'Publicidad en internet',
                'requiere_especificacion' => false,
                'activo' => true,
            ],
            [
                'codigo' => 'MATERIAL_IMPRESO',
                'nombre' => 'Volante o material impreso',
                'requiere_especificacion' => false,
                'activo' => true,
            ],
            [
                'codigo' => 'EVENTO_EDMA',
                'nombre' => 'Evento o actividad de EDMA',
                'requiere_especificacion' => false,
                'activo' => true,
            ],
            [
                'codigo' => 'BUSQUEDA_INTERNET',
                'nombre' => 'Búsqueda en internet',
                'requiere_especificacion' => false,
                'activo' => true,
            ],
            [
                'codigo' => 'OTRO',
                'nombre' => 'Otro',
                'requiere_especificacion' => true,
                'activo' => true,
            ],
        ];

        foreach ($fuentes as $fuente) {
            FuenteReferencia::query()->updateOrCreate(
                [
                    'codigo' => $fuente['codigo'],
                ],
                [
                    'nombre' =>
                        $fuente['nombre'],

                    'requiere_especificacion' =>
                        $fuente['requiere_especificacion'],

                    'activo' =>
                        $fuente['activo'],
                ]
            );
        }
    }
}