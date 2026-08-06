<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaisSeeder extends Seeder
{
    public function run(): void
    {
        $fecha = now();

        $paises = [

            /*
            |--------------------------------------------------------------------------
            | Centroamérica
            |--------------------------------------------------------------------------
            */

            [
                'codigo_iso2' => 'HN',
                'codigo_iso3' => 'HND',
                'nombre' => 'Honduras',
                'nacionalidad' => 'Hondureña',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'GT',
                'codigo_iso3' => 'GTM',
                'nombre' => 'Guatemala',
                'nacionalidad' => 'Guatemalteca',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'SV',
                'codigo_iso3' => 'SLV',
                'nombre' => 'El Salvador',
                'nacionalidad' => 'Salvadoreña',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'NI',
                'codigo_iso3' => 'NIC',
                'nombre' => 'Nicaragua',
                'nacionalidad' => 'Nicaragüense',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'CR',
                'codigo_iso3' => 'CRI',
                'nombre' => 'Costa Rica',
                'nacionalidad' => 'Costarricense',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'PA',
                'codigo_iso3' => 'PAN',
                'nombre' => 'Panamá',
                'nacionalidad' => 'Panameña',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'BZ',
                'codigo_iso3' => 'BLZ',
                'nombre' => 'Belice',
                'nacionalidad' => 'Beliceña',
                'activo' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Norteamérica
            |--------------------------------------------------------------------------
            */

            [
                'codigo_iso2' => 'MX',
                'codigo_iso3' => 'MEX',
                'nombre' => 'México',
                'nacionalidad' => 'Mexicana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'US',
                'codigo_iso3' => 'USA',
                'nombre' => 'Estados Unidos',
                'nacionalidad' => 'Estadounidense',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'CA',
                'codigo_iso3' => 'CAN',
                'nombre' => 'Canadá',
                'nacionalidad' => 'Canadiense',
                'activo' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Caribe
            |--------------------------------------------------------------------------
            */

            [
                'codigo_iso2' => 'CU',
                'codigo_iso3' => 'CUB',
                'nombre' => 'Cuba',
                'nacionalidad' => 'Cubana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'DO',
                'codigo_iso3' => 'DOM',
                'nombre' => 'República Dominicana',
                'nacionalidad' => 'Dominicana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'HT',
                'codigo_iso3' => 'HTI',
                'nombre' => 'Haití',
                'nacionalidad' => 'Haitiana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'JM',
                'codigo_iso3' => 'JAM',
                'nombre' => 'Jamaica',
                'nacionalidad' => 'Jamaiquina',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'BS',
                'codigo_iso3' => 'BHS',
                'nombre' => 'Bahamas',
                'nacionalidad' => 'Bahameña',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'BB',
                'codigo_iso3' => 'BRB',
                'nombre' => 'Barbados',
                'nacionalidad' => 'Barbadense',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'TT',
                'codigo_iso3' => 'TTO',
                'nombre' => 'Trinidad y Tobago',
                'nacionalidad' => 'Trinitense',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'PR',
                'codigo_iso3' => 'PRI',
                'nombre' => 'Puerto Rico',
                'nacionalidad' => 'Puertorriqueña',
                'activo' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | América del Sur
            |--------------------------------------------------------------------------
            */

            [
                'codigo_iso2' => 'AR',
                'codigo_iso3' => 'ARG',
                'nombre' => 'Argentina',
                'nacionalidad' => 'Argentina',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'BO',
                'codigo_iso3' => 'BOL',
                'nombre' => 'Bolivia',
                'nacionalidad' => 'Boliviana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'BR',
                'codigo_iso3' => 'BRA',
                'nombre' => 'Brasil',
                'nacionalidad' => 'Brasileña',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'CL',
                'codigo_iso3' => 'CHL',
                'nombre' => 'Chile',
                'nacionalidad' => 'Chilena',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'CO',
                'codigo_iso3' => 'COL',
                'nombre' => 'Colombia',
                'nacionalidad' => 'Colombiana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'EC',
                'codigo_iso3' => 'ECU',
                'nombre' => 'Ecuador',
                'nacionalidad' => 'Ecuatoriana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'GY',
                'codigo_iso3' => 'GUY',
                'nombre' => 'Guyana',
                'nacionalidad' => 'Guyanesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'PY',
                'codigo_iso3' => 'PRY',
                'nombre' => 'Paraguay',
                'nacionalidad' => 'Paraguaya',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'PE',
                'codigo_iso3' => 'PER',
                'nombre' => 'Perú',
                'nacionalidad' => 'Peruana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'SR',
                'codigo_iso3' => 'SUR',
                'nombre' => 'Surinam',
                'nacionalidad' => 'Surinamesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'UY',
                'codigo_iso3' => 'URY',
                'nombre' => 'Uruguay',
                'nacionalidad' => 'Uruguaya',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'VE',
                'codigo_iso3' => 'VEN',
                'nombre' => 'Venezuela',
                'nacionalidad' => 'Venezolana',
                'activo' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Europa
            |--------------------------------------------------------------------------
            */

            [
                'codigo_iso2' => 'ES',
                'codigo_iso3' => 'ESP',
                'nombre' => 'España',
                'nacionalidad' => 'Española',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'PT',
                'codigo_iso3' => 'PRT',
                'nombre' => 'Portugal',
                'nacionalidad' => 'Portuguesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'FR',
                'codigo_iso3' => 'FRA',
                'nombre' => 'Francia',
                'nacionalidad' => 'Francesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'DE',
                'codigo_iso3' => 'DEU',
                'nombre' => 'Alemania',
                'nacionalidad' => 'Alemana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'IT',
                'codigo_iso3' => 'ITA',
                'nombre' => 'Italia',
                'nacionalidad' => 'Italiana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'GB',
                'codigo_iso3' => 'GBR',
                'nombre' => 'Reino Unido',
                'nacionalidad' => 'Británica',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'IE',
                'codigo_iso3' => 'IRL',
                'nombre' => 'Irlanda',
                'nacionalidad' => 'Irlandesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'NL',
                'codigo_iso3' => 'NLD',
                'nombre' => 'Países Bajos',
                'nacionalidad' => 'Neerlandesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'BE',
                'codigo_iso3' => 'BEL',
                'nombre' => 'Bélgica',
                'nacionalidad' => 'Belga',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'CH',
                'codigo_iso3' => 'CHE',
                'nombre' => 'Suiza',
                'nacionalidad' => 'Suiza',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'AT',
                'codigo_iso3' => 'AUT',
                'nombre' => 'Austria',
                'nacionalidad' => 'Austriaca',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'SE',
                'codigo_iso3' => 'SWE',
                'nombre' => 'Suecia',
                'nacionalidad' => 'Sueca',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'NO',
                'codigo_iso3' => 'NOR',
                'nombre' => 'Noruega',
                'nacionalidad' => 'Noruega',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'DK',
                'codigo_iso3' => 'DNK',
                'nombre' => 'Dinamarca',
                'nacionalidad' => 'Danesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'FI',
                'codigo_iso3' => 'FIN',
                'nombre' => 'Finlandia',
                'nacionalidad' => 'Finlandesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'PL',
                'codigo_iso3' => 'POL',
                'nombre' => 'Polonia',
                'nacionalidad' => 'Polaca',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'CZ',
                'codigo_iso3' => 'CZE',
                'nombre' => 'República Checa',
                'nacionalidad' => 'Checa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'GR',
                'codigo_iso3' => 'GRC',
                'nombre' => 'Grecia',
                'nacionalidad' => 'Griega',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'RO',
                'codigo_iso3' => 'ROU',
                'nombre' => 'Rumanía',
                'nacionalidad' => 'Rumana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'UA',
                'codigo_iso3' => 'UKR',
                'nombre' => 'Ucrania',
                'nacionalidad' => 'Ucraniana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'RU',
                'codigo_iso3' => 'RUS',
                'nombre' => 'Rusia',
                'nacionalidad' => 'Rusa',
                'activo' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Asia y Medio Oriente
            |--------------------------------------------------------------------------
            */

            [
                'codigo_iso2' => 'CN',
                'codigo_iso3' => 'CHN',
                'nombre' => 'China',
                'nacionalidad' => 'China',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'JP',
                'codigo_iso3' => 'JPN',
                'nombre' => 'Japón',
                'nacionalidad' => 'Japonesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'KR',
                'codigo_iso3' => 'KOR',
                'nombre' => 'Corea del Sur',
                'nacionalidad' => 'Surcoreana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'IN',
                'codigo_iso3' => 'IND',
                'nombre' => 'India',
                'nacionalidad' => 'India',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'PK',
                'codigo_iso3' => 'PAK',
                'nombre' => 'Pakistán',
                'nacionalidad' => 'Pakistaní',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'BD',
                'codigo_iso3' => 'BGD',
                'nombre' => 'Bangladés',
                'nacionalidad' => 'Bangladesí',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'PH',
                'codigo_iso3' => 'PHL',
                'nombre' => 'Filipinas',
                'nacionalidad' => 'Filipina',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'ID',
                'codigo_iso3' => 'IDN',
                'nombre' => 'Indonesia',
                'nacionalidad' => 'Indonesia',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'TH',
                'codigo_iso3' => 'THA',
                'nombre' => 'Tailandia',
                'nacionalidad' => 'Tailandesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'VN',
                'codigo_iso3' => 'VNM',
                'nombre' => 'Vietnam',
                'nacionalidad' => 'Vietnamita',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'MY',
                'codigo_iso3' => 'MYS',
                'nombre' => 'Malasia',
                'nacionalidad' => 'Malasia',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'SG',
                'codigo_iso3' => 'SGP',
                'nombre' => 'Singapur',
                'nacionalidad' => 'Singapurense',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'TR',
                'codigo_iso3' => 'TUR',
                'nombre' => 'Turquía',
                'nacionalidad' => 'Turca',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'IL',
                'codigo_iso3' => 'ISR',
                'nombre' => 'Israel',
                'nacionalidad' => 'Israelí',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'SA',
                'codigo_iso3' => 'SAU',
                'nombre' => 'Arabia Saudita',
                'nacionalidad' => 'Saudí',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'AE',
                'codigo_iso3' => 'ARE',
                'nombre' => 'Emiratos Árabes Unidos',
                'nacionalidad' => 'Emiratí',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'QA',
                'codigo_iso3' => 'QAT',
                'nombre' => 'Catar',
                'nacionalidad' => 'Catarí',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'LB',
                'codigo_iso3' => 'LBN',
                'nombre' => 'Líbano',
                'nacionalidad' => 'Libanesa',
                'activo' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | África
            |--------------------------------------------------------------------------
            */

            [
                'codigo_iso2' => 'ZA',
                'codigo_iso3' => 'ZAF',
                'nombre' => 'Sudáfrica',
                'nacionalidad' => 'Sudafricana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'EG',
                'codigo_iso3' => 'EGY',
                'nombre' => 'Egipto',
                'nacionalidad' => 'Egipcia',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'MA',
                'codigo_iso3' => 'MAR',
                'nombre' => 'Marruecos',
                'nacionalidad' => 'Marroquí',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'NG',
                'codigo_iso3' => 'NGA',
                'nombre' => 'Nigeria',
                'nacionalidad' => 'Nigeriana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'GH',
                'codigo_iso3' => 'GHA',
                'nombre' => 'Ghana',
                'nacionalidad' => 'Ghanesa',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'KE',
                'codigo_iso3' => 'KEN',
                'nombre' => 'Kenia',
                'nacionalidad' => 'Keniana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'ET',
                'codigo_iso3' => 'ETH',
                'nombre' => 'Etiopía',
                'nacionalidad' => 'Etíope',
                'activo' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Oceanía
            |--------------------------------------------------------------------------
            */

            [
                'codigo_iso2' => 'AU',
                'codigo_iso3' => 'AUS',
                'nombre' => 'Australia',
                'nacionalidad' => 'Australiana',
                'activo' => true,
            ],
            [
                'codigo_iso2' => 'NZ',
                'codigo_iso3' => 'NZL',
                'nombre' => 'Nueva Zelanda',
                'nacionalidad' => 'Neozelandesa',
                'activo' => true,
            ],
        ];

        $registros = collect($paises)
            ->map(function (array $pais) use ($fecha): array {
                return [
                    ...$pais,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ];
            })
            ->all();

        DB::table('paises')->upsert(
            $registros,
            ['codigo_iso2'],
            [
                'codigo_iso3',
                'nombre',
                'nacionalidad',
                'activo',
                'updated_at',
            ]
        );
    }
}