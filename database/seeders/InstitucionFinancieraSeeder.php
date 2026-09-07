<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitucionFinancieraSeeder extends Seeder
{
    public function run(): void
    {
        $fecha = now();

        $instituciones = [
            [
                'codigo' => 'ATLANTIDA',
                'nombre' => 'Banco Atlántida, S.A.',
                'activo' => true,
            ],
            [
                'codigo' => 'OCCIDENTE',
                'nombre' => 'Banco de Occidente, S.A.',
                'activo' => true,
            ],
            [
                'codigo' => 'FICOHSA',
                'nombre' => 'Banco Financiera Comercial Hondureña, S.A. (FICOHSA)',
                'activo' => true,
            ],
            [
                'codigo' => 'BAC',
                'nombre' => 'Banco de América Central Honduras, S.A. (BAC)',
                'activo' => true,
            ], 
        ];

        $registros = collect($instituciones)
            ->map(function (array $institucion) use ($fecha): array {
                return [
                    ...$institucion,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ];
            })
            ->all();

        DB::table('instituciones_financieras')->upsert(
            $registros,
            ['codigo'],
            [
                'nombre',
                'activo',
                'updated_at',
            ]
        );
    }
}