<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        /*
         * Temporalmente se permite la solicitud.
         * Cuando implementemos autenticación y Policies,
         * esta autorización será controlada por permisos.
         */
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'primer_nombre' => $this->normalizeText(
                $this->input('primer_nombre')
            ),

            'segundo_nombre' => $this->normalizeNullableText(
                $this->input('segundo_nombre')
            ),

            'primer_apellido' => $this->normalizeText(
                $this->input('primer_apellido')
            ),

            'segundo_apellido' => $this->normalizeNullableText(
                $this->input('segundo_apellido')
            ),

            'tipo_documento' => $this->normalizeNullableText(
                $this->input('tipo_documento')
            ),

            'numero_documento' => $this->normalizeDocument(
                $this->input('numero_documento')
            ),

            'rtn' => $this->normalizeDocument(
                $this->input('rtn')
            ),

            'correo_personal' => $this->normalizeEmail(
                $this->input('correo_personal')
            ),

            'telefono_movil' => $this->normalizeNullableText(
                $this->input('telefono_movil')
            ),

            'telefono_fijo' => $this->normalizeNullableText(
                $this->input('telefono_fijo')
            ),

            'nacionalidad' => $this->normalizeNullableText(
                $this->input('nacionalidad')
            ),

            'ciudad_municipio' => $this->normalizeNullableText(
                $this->input('ciudad_municipio')
            ),

            'departamento_estado' => $this->normalizeNullableText(
                $this->input('departamento_estado')
            ),

            'direccion' => $this->normalizeNullableText(
                $this->input('direccion')
            ),

            'telefono_movil_whatsapp' => $this->boolean(
                'telefono_movil_whatsapp'
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Datos personales
            |--------------------------------------------------------------------------
            */

            'primer_nombre' => [
                'required',
                'string',
                'max:50',
            ],

            'segundo_nombre' => [
                'nullable',
                'string',
                'max:50',
            ],

            'primer_apellido' => [
                'required',
                'string',
                'max:50',
            ],

            'segundo_apellido' => [
                'nullable',
                'string',
                'max:50',
            ],

            'fecha_nacimiento' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'sexo' => [
                'nullable',
                'string',
                'max:20',
            ],

            'estado_civil' => [
                'nullable',
                'string',
                'max:30',
            ],

            'nacionalidad' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Identificación
            |--------------------------------------------------------------------------
            */

            'tipo_documento' => [
                'nullable',
                'string',
                'max:30',
                'required_with:numero_documento',
            ],

            'numero_documento' => [
                'nullable',
                'string',
                'max:50',
                'required_with:tipo_documento',

                Rule::unique('personas', 'numero_documento')
                    ->where(
                        fn ($query) => $query->where(
                            'tipo_documento',
                            $this->input('tipo_documento')
                        )
                    ),
            ],

            'rtn' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('personas', 'rtn'),
            ],

            /*
            |--------------------------------------------------------------------------
            | Contacto
            |--------------------------------------------------------------------------
            */

            'correo_personal' => [
                'nullable',
                'email:rfc',
                'max:150',
            ],

            'telefono_movil' => [
                'nullable',
                'string',
                'max:30',
            ],

            'telefono_fijo' => [
                'nullable',
                'string',
                'max:30',
            ],

            'telefono_movil_whatsapp' => [
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Residencia
            |--------------------------------------------------------------------------
            */

            'pais_residencia_id' => [
                'nullable',
                'integer',

                Rule::exists('paises', 'id')
                    ->where(
                        fn ($query) => $query->where('activo', true)
                    ),
            ],

            'direccion' => [
                'nullable',
                'string',
            ],

            'ciudad_municipio' => [
                'nullable',
                'string',
                'max:120',
            ],

            'departamento_estado' => [
                'nullable',
                'string',
                'max:120',
            ],

            /*
            |--------------------------------------------------------------------------
            | Fotografía y estado
            |--------------------------------------------------------------------------
            */

            'foto_perfil' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072',
            ],

            'estado' => [
                'nullable',
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
            'primer_nombre.required' =>
                'El primer nombre es obligatorio.',

            'primer_nombre.max' =>
                'El primer nombre no puede superar los 50 caracteres.',

            'segundo_nombre.max' =>
                'El segundo nombre no puede superar los 50 caracteres.',

            'primer_apellido.required' =>
                'El primer apellido es obligatorio.',

            'primer_apellido.max' =>
                'El primer apellido no puede superar los 50 caracteres.',

            'segundo_apellido.max' =>
                'El segundo apellido no puede superar los 50 caracteres.',

            'fecha_nacimiento.date' =>
                'La fecha de nacimiento no tiene un formato válido.',

            'fecha_nacimiento.before_or_equal' =>
                'La fecha de nacimiento no puede ser posterior a hoy.',

            'tipo_documento.required_with' =>
                'Debe seleccionar el tipo de documento.',

            'numero_documento.required_with' =>
                'Debe ingresar el número del documento.',

            'numero_documento.unique' =>
                'Ya existe una persona registrada con este tipo y número de documento.',

            'rtn.unique' =>
                'El RTN ingresado ya pertenece a otra persona.',

            'correo_personal.email' =>
                'El correo personal no tiene un formato válido.',

            'pais_residencia_id.exists' =>
                'El país de residencia seleccionado no es válido o está inactivo.',

            'foto_perfil.image' =>
                'El archivo seleccionado debe ser una imagen.',

            'foto_perfil.mimes' =>
                'La fotografía debe estar en formato JPG, JPEG, PNG o WEBP.',

            'foto_perfil.max' =>
                'La fotografía no puede superar los 3 MB.',

            'estado.in' =>
                'El estado seleccionado no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'primer_nombre' => 'primer nombre',
            'segundo_nombre' => 'segundo nombre',
            'primer_apellido' => 'primer apellido',
            'segundo_apellido' => 'segundo apellido',
            'tipo_documento' => 'tipo de documento',
            'numero_documento' => 'número de documento',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'estado_civil' => 'estado civil',
            'correo_personal' => 'correo personal',
            'telefono_movil' => 'teléfono móvil',
            'telefono_fijo' => 'teléfono fijo',
            'pais_residencia_id' => 'país de residencia',
            'ciudad_municipio' => 'ciudad o municipio',
            'departamento_estado' => 'departamento o estado',
            'foto_perfil' => 'fotografía de perfil',
        ];
    }

    private function normalizeText(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return preg_replace(
            '/\s+/u',
            ' ',
            trim($value)
        );
    }

    private function normalizeNullableText(mixed $value): mixed
    {
        $value = $this->normalizeText($value);

        return $value === '' ? null : $value;
    }

    private function normalizeDocument(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = mb_strtoupper(trim($value));

        return $value === '' ? null : $value;
    }

    private function normalizeEmail(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = mb_strtolower(trim($value));

        return $value === '' ? null : $value;
    }
}