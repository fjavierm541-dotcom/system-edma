<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNivelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'codigo' => strtoupper(
                trim((string) $this->input('codigo'))
            ),

            'nombre' => $this->normalizeNullableText(
                $this->input('nombre')
            ),

            'descripcion' => $this->normalizeNullableText(
                $this->input('descripcion')
            ),

            'estado' => $this->normalizeNullableText(
                $this->input('estado')
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            'programa_id' => [
                'required',
                'integer',

                Rule::exists('programas', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('estado', 'activo')
                            ->whereNull('deleted_at')
                    ),
            ],

            'codigo' => [
                'required',
                'string',
                'max:20',

                /*
                 * El código debe ser único dentro
                 * del programa seleccionado.
                 */
                Rule::unique('niveles', 'codigo')
                    ->where(
                        fn ($query) => $query
                            ->where(
                                'programa_id',
                                $this->input('programa_id')
                            )
                            ->whereNull('deleted_at')
                    ),
            ],

            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'orden' => [
                'required',
                'integer',
                'min:1',
                'max:255',

                /*
                 * Dentro de un mismo programa no debe
                 * repetirse la posición académica.
                 */
                Rule::unique('niveles', 'orden')
                    ->where(
                        fn ($query) => $query
                            ->where(
                                'programa_id',
                                $this->input('programa_id')
                            )
                            ->whereNull('deleted_at')
                    ),
            ],

            'duracion_semanas' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],

            'nota_minima_aprobacion' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
                'decimal:0,2',
            ],

            'estado' => [
                'required',
                Rule::in([
                    'activo',
                    'inactivo',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'programa_id.required' =>
                'Debe seleccionar el programa al que pertenecerá el nivel.',

            'programa_id.exists' =>
                'El programa seleccionado no existe o no se encuentra activo.',

            'codigo.required' =>
                'El código del nivel es obligatorio.',

            'codigo.unique' =>
                'Ya existe un nivel con este código dentro del programa.',

            'codigo.max' =>
                'El código no puede superar los 20 caracteres.',

            'nombre.required' =>
                'El nombre del nivel es obligatorio.',

            'nombre.max' =>
                'El nombre no puede superar los 100 caracteres.',

            'descripcion.max' =>
                'La descripción no puede superar los 2,000 caracteres.',

            'orden.required' =>
                'Debe indicar el orden académico del nivel.',

            'orden.integer' =>
                'El orden debe ser un número entero.',

            'orden.min' =>
                'El orden debe comenzar en 1.',

            'orden.unique' =>
                'Ya existe otro nivel con esta posición dentro del programa.',

            'duracion_semanas.required' =>
                'La duración del nivel es obligatoria.',

            'duracion_semanas.integer' =>
                'La duración debe expresarse en semanas completas.',

            'duracion_semanas.min' =>
                'La duración debe ser de al menos una semana.',

            'nota_minima_aprobacion.numeric' =>
                'La nota mínima debe ser un valor numérico.',

            'nota_minima_aprobacion.min' =>
                'La nota mínima no puede ser menor que 0.',

            'nota_minima_aprobacion.max' =>
                'La nota mínima no puede superar 100.',

            'nota_minima_aprobacion.decimal' =>
                'La nota mínima puede contener como máximo dos decimales.',

            'estado.required' =>
                'Debe seleccionar el estado del nivel.',

            'estado.in' =>
                'El estado seleccionado no es válido.',
        ];
    }

    private function normalizeNullableText(
        mixed $value
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        $value = preg_replace(
            '/\s+/u',
            ' ',
            trim($value)
        );

        return $value === '' ? null : $value;
    }
}