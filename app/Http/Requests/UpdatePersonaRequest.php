<?php

namespace App\Http\Requests;

use App\Models\Persona;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $tipoDocumento = $this->normalizeNullableText(
            $this->input('tipo_documento')
        );

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

            'tipo_documento' => $tipoDocumento,

            'numero_documento' => $this->normalizeDocument(
                $this->input('numero_documento'),
                $tipoDocumento
            ),

            'rtn' => $this->normalizeNumericDocument(
                $this->input('rtn')
            ),

            'correo_personal' => $this->normalizeEmail(
                $this->input('correo_personal')
            ),

            'telefono_movil' => $this->normalizePhone(
                $this->input('telefono_movil')
            ),

            'telefono_fijo' => $this->normalizePhone(
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

            'eliminar_foto_perfil' => $this->boolean(
                'eliminar_foto_perfil'
            ),
        ]);
    }

    public function rules(): array
    {
        $persona = $this->route('persona');

        $personaId = $persona instanceof Persona
            ? $persona->getKey()
            : $persona;

        $tipoDocumento = $this->input('tipo_documento');

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

                Rule::when(
                    in_array(
                        $tipoDocumento,
                        ['dni', 'identidad_menor'],
                        true
                    ),
                    ['digits:13']
                ),

                Rule::unique('personas', 'numero_documento')
                    ->where(
                        fn ($query) => $query->where(
                            'tipo_documento',
                            $tipoDocumento
                        )
                    )
                    ->ignore($personaId),
            ],

            'rtn' => [
                'nullable',
                'digits:14',

                Rule::unique('personas', 'rtn')
                    ->ignore($personaId),
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
                'regex:/^\d{8,15}$/',
                'required_if:telefono_movil_whatsapp,1',
            ],

            'telefono_fijo' => [
                'nullable',
                'regex:/^\d{8,15}$/',
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
                        fn ($query) => $query->where(
                            'activo',
                            true
                        )
                    ),
            ],

            'direccion' => [
                'nullable',
                'string',
                'max:500',
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

            'eliminar_foto_perfil' => [
                'nullable',
                'boolean',
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

            'numero_documento.digits' =>
                'El DNI o identidad de menor debe contener exactamente 13 dígitos.',

            'numero_documento.unique' =>
                'Ya existe otra persona registrada con este tipo y número de documento.',

            'rtn.digits' =>
                'El RTN debe contener exactamente 14 dígitos.',

            'rtn.unique' =>
                'El RTN ingresado ya pertenece a otra persona.',

            'correo_personal.email' =>
                'El correo personal no tiene un formato válido.',

            'telefono_movil.required_if' =>
                'Debe ingresar un teléfono móvil para indicar que está disponible en WhatsApp.',

            'telefono_movil.regex' =>
                'El teléfono móvil debe contener entre 8 y 15 dígitos.',

            'telefono_fijo.regex' =>
                'El teléfono fijo debe contener entre 8 y 15 dígitos.',

            'pais_residencia_id.exists' =>
                'El país de residencia seleccionado no es válido o está inactivo.',

            'direccion.max' =>
                'La dirección no puede superar los 500 caracteres.',

            'foto_perfil.image' =>
                'El archivo seleccionado debe ser una imagen.',

            'foto_perfil.mimes' =>
                'La fotografía debe estar en formato JPG, JPEG, PNG o WEBP.',

            'foto_perfil.max' =>
                'La fotografía no puede superar los 3 MB.',

            'estado.required' =>
                'Debe seleccionar el estado de la persona.',

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

    private function normalizeDocument(
        mixed $value,
        ?string $tipoDocumento = null
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (
            in_array(
                $tipoDocumento,
                ['dni', 'identidad_menor'],
                true
            )
        ) {
            return preg_replace('/\D+/', '', $value);
        }

        return mb_strtoupper($value);
    }

    private function normalizeNumericDocument(
        mixed $value
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        $value = preg_replace('/\D+/', '', trim($value));

        return $value === '' ? null : $value;
    }

    private function normalizePhone(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = preg_replace('/\D+/', '', trim($value));

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