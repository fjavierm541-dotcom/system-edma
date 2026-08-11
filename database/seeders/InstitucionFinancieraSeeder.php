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
                'codigo' => 'BANTRAB',
                'nombre' => 'Banco de los Trabajadores, S.A.',
                'activo' => true,
            ],
            [
                'codigo' => 'FICENSA',
                'nombre' => 'Banco Financiera Centroamericana, S.A. (FICENSA)',
                'activo' => true,
            ],
            [
                'codigo' => 'BANHCAFE',
                'nombre' => 'Banco Hondureño del Café, S.A. (BANHCAFE)',
                'activo' => true,
            ],
            [
                'codigo' => 'BANPAIS',
                'nombre' => 'Banco del País, S.A. (BANPAÍS)',
                'activo' => true,
            ],
            [
                'codigo' => 'FICOHSA',
                'nombre' => 'Banco Financiera Comercial Hondureña, S.A. (FICOHSA)',
                'activo' => true,
            ],
            [
                'codigo' => 'LAFISE',
                'nombre' => 'Banco Lafise Honduras, S.A.',
                'activo' => true,
            ],
            [
                'codigo' => 'DAVIVIENDA',
                'nombre' => 'Banco Davivienda Honduras, S.A.',
                'activo' => true,
            ],
            [
                'codigo' => 'PROMERICA',
                'nombre' => 'Banco Promérica, S.A.',
                'activo' => true,
            ],
            [
                'codigo' => 'BANRURAL',
                'nombre' => 'Banco de Desarrollo Rural Honduras, S.A. (BANRURAL)',
                'activo' => true,
            ],
            [
                'codigo' => 'AZTECA',
                'nombre' => 'Banco Azteca de Honduras, S.A.',
                'activo' => true,
            ],
            [
                'codigo' => 'POPULAR',
                'nombre' => 'Banco Popular, S.A.',
                'activo' => true,
            ],
            [
                'codigo' => 'BAC',
                'nombre' => 'Banco de América Central Honduras, S.A. (BAC)',
                'activo' => true,
            ],
            [
                'codigo' => 'CITI',
                'nombre' => 'Banco de Honduras, S.A. (Citi Honduras)',
                'activo' => true,
            ],
            [
                'codigo' => 'BANADESA',
                'nombre' => 'Banco Nacional de Desarrollo Agrícola (BANADESA)',
                'activo' => true,
            ],
            [
                'codigo' => 'BANHPROVI',
                'nombre' => 'Banco Hondureño para la Producción y la Vivienda (BANHPROVI)',
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