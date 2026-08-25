<?php

namespace App\Console\Commands;

use App\Services\Calificaciones\BloquearCalificacionesVencidasService;
use Illuminate\Console\Command;

class BloquearCalificacionesVencidas extends Command
{
    protected $signature =
        'edma:bloquear-calificaciones';

    protected $description =
        'Bloquea las calificaciones confirmadas cuya ventana de carga ya finalizó.';

    public function handle(
        BloquearCalificacionesVencidasService $service
    ): int {
        $cantidad =
            $service->ejecutar();

        $this->info(
            "Calificaciones bloqueadas: {$cantidad}"
        );

        return self::SUCCESS;
    }
}