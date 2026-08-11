<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFormacionAcademicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nivel_academico' => $this->normalizeNullableText(
                $this->input('nivel_academico')
            ),

            'titulo_obtenido' => $this->normalizeNullableText(
                $this->input('titulo_obtenido')
            ),

            'institucion_educativa' => $this->normalizeNullableText(
                $this->input('institucion_educativa')
            ),

            'observaciones' => $this->normalizeNullableText(
                $this->input('observaciones')
            ),

            'es_principal' => $this->boolean(
                'es_principal'
            ),

            'estado' => $this->normalizeNullableText(
                $this->input('estado')
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            'nivel_academico' => [
                'required',
                'string',
                'max:100',
            ],

            'titulo_obtenido' => [
                'nullable',
                'string',
                'max:180',
            ],

            'institucion_educativa' => [
                'nullable',
                'string',
                'max:180',
            ],

            'pais_id' => [
                'nullable',
                'integer',

                Rule::exists('paises', 'id')
                    ->where(
                        fn ($query) => $query->where(
                            'activo',
                            true
                        )
                    ),
            ],

            'anio_graduacion' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . now()->year,
            ],

            'documento_persona_id' => [
                'nullable',
                'integer',
                'exists:documentos_persona,id',
            ],

            'es_principal' => [
                'boolean',
            ],

            'estado' => [
                'required',
                Rule::in([
                    'activo',
                    'inactivo',
                ]),
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nivel_academico.required' =>
                'Debe indicar el nivel académico.',

            'nivel_academico.max' =>
                'El nivel académico no puede superar los 100 caracteres.',

            'titulo_obtenido.max' =>
                'El título obtenido no puede superar los 180 caracteres.',

            'institucion_educativa.max' =>
                'La institución educativa no puede superar los 180 caracteres.',

            'pais_id.exists' =>
                'El país seleccionado no es válido o está inactivo.',

            'anio_graduacion.integer' =>
                'El año de graduación debe ser un número entero.',

            'anio_graduacion.min' =>
                'El año de graduación no parece válido.',

            'anio_graduacion.max' =>
                'El año de graduación no puede ser posterior al año actual.',

            'documento_persona_id.exists' =>
                'El documento seleccionado no existe.',

            'estado.required' =>
                'Debe seleccionar el estado de la formación académica.',

            'estado.in' =>
                'El estado seleccionado no es válido.',

            'observaciones.max' =>
                'Las observaciones no pueden superar los 1000 caracteres.',
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