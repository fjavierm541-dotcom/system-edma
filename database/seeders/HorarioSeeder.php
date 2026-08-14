<?php

namespace Database\Seeders;

use App\Models\Horario;
use Illuminate\Database\Seeder;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Horarios estándar EDMA
        |--------------------------------------------------------------------------
        |
        | Desde las 7:00 a. m. hasta las 9:00 p. m.
        | Cada horario tiene una duración de una hora.
        |
        */

        for ($hora = 7; $hora <= 20; $hora++) {

            $horaInicio = sprintf(
                '%02d:00:00',
                $hora
            );

            $horaFin = sprintf(
                '%02d:00:00',
                $hora + 1
            );

            $codigoVisual = sprintf(
                '%02d00',
                $hora
            );

            $nombre = "Horario {$codigoVisual}";

            Horario::query()->updateOrCreate(
                [
                    'hora_inicio' => $horaInicio,
                    'hora_fin' => $horaFin,
                    'zona_horaria' => 'America/Tegucigalpa',
                ],
                [
                    'nombre' => $nombre,
                    'activo' => true,
                ]
            );
        }
    }
}