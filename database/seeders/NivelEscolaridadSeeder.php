<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelEscolaridadSeeder extends Seeder
{
    public function run(): void
    {
        $fecha = now();

        $niveles = [
            [
                'codigo' => 'sin_escolaridad',
                'nombre' => 'Sin escolaridad',
                'orden' => 1,
                'activo' => true,
            ],
            [
                'codigo' => 'preescolar',
                'nombre' => 'Preescolar',
                'orden' => 2,
                'activo' => true,
            ],
            [
                'codigo' => 'primaria_incompleta',
                'nombre' => 'Primaria incompleta',
                'orden' => 3,
                'activo' => true,
            ],
            [
                'codigo' => 'primaria_completa',
                'nombre' => 'Primaria completa',
                'orden' => 4,
                'activo' => true,
            ],
            [
                'codigo' => 'secundaria_incompleta',
                'nombre' => 'Secundaria incompleta',
                'orden' => 5,
                'activo' => true,
            ],
            [
                'codigo' => 'secundaria_completa',
                'nombre' => 'Secundaria completa',
                'orden' => 6,
                'activo' => true,
            ],
            [
                'codigo' => 'tecnica',
                'nombre' => 'Educación técnica',
                'orden' => 7,
                'activo' => true,
            ],
            [
                'codigo' => 'universidad_incompleta',
                'nombre' => 'Universidad incompleta',
                'orden' => 8,
                'activo' => true,
            ],
            [
                'codigo' => 'universidad_completa',
                'nombre' => 'Universidad completa',
                'orden' => 9,
                'activo' => true,
            ],
            [
                'codigo' => 'posgrado',
                'nombre' => 'Posgrado',
                'orden' => 10,
                'activo' => true,
            ],
        ];

        $registros = collect($niveles)
            ->map(function (array $nivel) use ($fecha): array {
                return [
                    ...$nivel,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ];
            })
            ->all();

        DB::table('niveles_escolaridad')->upsert(
            $registros,
            ['codigo'],
            [
                'nombre',
                'orden',
                'activo',
                'updated_at',
            ]
        );
    }
}