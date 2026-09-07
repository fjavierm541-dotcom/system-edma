<?php

namespace App\Http\Requests;

use App\Models\Empleado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreFormacionAcademicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nivel_academico' =>
                $this->normalizeNullableText(
                    $this->input('nivel_academico')
                ),

            'titulo_obtenido' =>
                $this->normalizeNullableText(
                    $this->input('titulo_obtenido')
                ),

            'institucion_educativa' =>
                $this->normalizeNullableText(
                    $this->input('institucion_educativa')
                ),

            'observaciones' =>
                $this->normalizeNullableText(
                    $this->input('observaciones')
                ),

            'es_principal' =>
                $this->boolean('es_principal'),

            'estado' =>
                $this->normalizeNullableText(
                    $this->input('estado')
                ) ?? 'activo',
        ]);
    }

    public function rules(): array
    {
        $empleado = $this->route('empleado');

        $personaId =
            $empleado instanceof Empleado
                ? $empleado->persona_id
                : null;

        return [
            'nivel_academico' => [
                'required',
                'string',
                'max:50',
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

                Rule::exists(
                    'paises',
                    'id'
                )->where(
                    fn ($query) =>
                        $query->where(
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

            /*
            |--------------------------------------------------------------------------
            | Documento ya existente
            |--------------------------------------------------------------------------
            */

            'documento_persona_id' => [
                'nullable',
                'integer',
                

                Rule::exists(
                    'documentos_persona',
                    'id'
                )->where(
                    function ($query) use ($personaId) {
                        $query
                            ->where(
                                'persona_id',
                                $personaId
                            )
                            ->whereNull(
                                'deleted_at'
                            );
                    }
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Nuevo documento
            |--------------------------------------------------------------------------
            */

            'documento_nuevo' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
                
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

    public function after(): array
{
    return [
        function (Validator $validator): void {

            if (
                $this->filled('documento_persona_id') &&
                $this->hasFile('documento_nuevo')
            ) {
                $validator->errors()->add(
                    'documento_persona_id',
                    'Seleccione un documento existente o suba uno nuevo, pero no ambas opciones.'
                );

                $validator->errors()->add(
                    'documento_nuevo',
                    'Seleccione un documento existente o suba uno nuevo, pero no ambas opciones.'
                );
            }
        },
    ];
}

    public function messages(): array
    {
        return [
            'nivel_academico.required' =>
                'Debe indicar el nivel académico.',

            'nivel_academico.max' =>
                'El nivel académico no puede superar los 50 caracteres.',

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
                'El documento seleccionado no pertenece a esta persona o ya no está disponible.',

            'documento_nuevo.file' =>
                'El documento adjunto no es un archivo válido.',

            'documento_nuevo.mimes' =>
                'El documento debe ser un archivo PDF, JPG, JPEG o PNG.',

            'documento_nuevo.max' =>
                'El documento no puede superar los 5 MB.',

            'documento_nuevo.prohibited_with' =>
                'Seleccione un documento existente o suba uno nuevo, pero no ambas opciones.',

            'estado.required' =>
                'Debe seleccionar el estado de la formación académica.',

            'estado.in' =>
                'El estado seleccionado no es válido.',

            'observaciones.max' =>
                'Las observaciones no pueden superar los 1,000 caracteres.',
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

        return $value === ''
            ? null
            : $value;
    }
}