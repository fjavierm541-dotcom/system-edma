<?php

namespace App\Services\Seguridad;

class GenerarPasswordTemporalService
{
    private const LONGITUD = 12;

    public function generar(): string
    {
        $minusculas =
            'abcdefghijkmnopqrstuvwxyz';

        $mayusculas =
            'ABCDEFGHJKLMNPQRSTUVWXYZ';

        $numeros =
            '23456789';

        $especiales =
            '!@#$%&*+-_?';

        /*
         * Garantizamos al menos un carácter
         * de cada grupo requerido.
         */
        $caracteres = [
            $this->obtenerCaracter($minusculas),
            $this->obtenerCaracter($mayusculas),
            $this->obtenerCaracter($numeros),
            $this->obtenerCaracter($especiales),
        ];

        $todos =
            $minusculas .
            $mayusculas .
            $numeros .
            $especiales;

        while (count($caracteres) < self::LONGITUD) {
            $caracteres[] =
                $this->obtenerCaracter($todos);
        }

        return $this->mezclar($caracteres);
    }

    private function obtenerCaracter(
        string $grupo
    ): string {
        return $grupo[
            random_int(
                0,
                strlen($grupo) - 1
            )
        ];
    }

    private function mezclar(
        array $caracteres
    ): string {
        for (
            $i = count($caracteres) - 1;
            $i > 0;
            $i--
        ) {
            $j = random_int(0, $i);

            [
                $caracteres[$i],
                $caracteres[$j],
            ] = [
                $caracteres[$j],
                $caracteres[$i],
            ];
        }

        return implode('', $caracteres);
    }
}