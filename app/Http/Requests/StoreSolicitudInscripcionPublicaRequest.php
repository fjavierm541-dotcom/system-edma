<?php

namespace App\Http\Requests;

use App\Models\Nivel;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreSolicitudInscripcionPublicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Formulario público
        |--------------------------------------------------------------------------
        |
        | No requiere autenticación.
        |
        */
        return true;
    }

    protected function prepareForValidation(): void
    {
        $fechaNacimiento = $this->input(
            'fecha_nacimiento'
        );

        $edad = null;
        $segmento = null;

        if ($fechaNacimiento) {
            try {
                $fecha = Carbon::parse(
                    $fechaNacimiento
                );

                if (!$fecha->isFuture()) {
                    $edad = $fecha->age;

                    $segmento = $edad >= 14
                        ? 'jóvenes_adultos'
                        : 'niños';
                }
            } catch (\Throwable) {
                // La regla date manejará el error.
            }
        }

        $this->merge([
            /*
            |--------------------------------------------------------------------------
            | Persona aspirante
            |--------------------------------------------------------------------------
            */

            'primer_nombre' =>
                $this->normalizarTexto(
                    $this->input('primer_nombre')
                ),

            'segundo_nombre' =>
                $this->normalizarTextoNullable(
                    $this->input('segundo_nombre')
                ),

            'primer_apellido' =>
                $this->normalizarTexto(
                    $this->input('primer_apellido')
                ),

            'segundo_apellido' =>
                $this->normalizarTextoNullable(
                    $this->input('segundo_apellido')
                ),

            'tipo_documento' =>
                mb_strtolower(
                    trim(
                        (string) $this->input(
                            'tipo_documento'
                        )
                    )
                ),

            'numero_documento' =>
                $this->normalizarDocumento(
                    $this->input('numero_documento')
                ),

            'sexo' =>
                mb_strtolower(
                    trim(
                        (string) $this->input('sexo')
                    )
                ),

            'nacionalidad' =>
                $this->normalizarTextoNullable(
                    $this->input('nacionalidad')
                ),

            'correo_personal' =>
                mb_strtolower(
                    trim(
                        (string) $this->input(
                            'correo_personal'
                        )
                    )
                ),

            'telefono_movil' =>
                $this->normalizarTelefono(
                    $this->input('telefono_movil')
                ),

            'direccion' =>
                $this->normalizarTextoNullable(
                    $this->input('direccion')
                ),

            'ciudad_municipio' =>
                $this->normalizarTextoNullable(
                    $this->input('ciudad_municipio')
                ),

            'departamento_estado' =>
                $this->normalizarTextoNullable(
                    $this->input(
                        'departamento_estado'
                    )
                ),

            /*
            |--------------------------------------------------------------------------
            | Información académica
            |--------------------------------------------------------------------------
            */

            'segmento_solicitado' =>
                $segmento,

            /*
            |--------------------------------------------------------------------------
            | Información complementaria
            |--------------------------------------------------------------------------
            */

            'fuente_referencia_otro' =>
                $this->normalizarTextoNullable(
                    $this->input(
                        'fuente_referencia_otro'
                    )
                ),

            'observaciones_solicitante' =>
                $this->normalizarTextoNullable(
                    $this->input(
                        'observaciones_solicitante'
                    )
                ),

            'recomienda_otro_estudiante' =>
                $this->boolean(
                    'recomienda_otro_estudiante'
                ),

            /*
            |--------------------------------------------------------------------------
            | Responsable
            |--------------------------------------------------------------------------
            */

            'responsable_primer_nombre' =>
                $this->normalizarTextoNullable(
                    $this->input(
                        'responsable_primer_nombre'
                    )
                ),

            'responsable_segundo_nombre' =>
                $this->normalizarTextoNullable(
                    $this->input(
                        'responsable_segundo_nombre'
                    )
                ),

            'responsable_primer_apellido' =>
                $this->normalizarTextoNullable(
                    $this->input(
                        'responsable_primer_apellido'
                    )
                ),

            'responsable_segundo_apellido' =>
                $this->normalizarTextoNullable(
                    $this->input(
                        'responsable_segundo_apellido'
                    )
                ),

            'responsable_tipo_documento' =>
                mb_strtolower(
                    trim(
                        (string) $this->input(
                            'responsable_tipo_documento'
                        )
                    )
                ),

            'responsable_numero_documento' =>
                $this->normalizarDocumento(
                    $this->input(
                        'responsable_numero_documento'
                    )
                ),

            'responsable_correo' =>
                mb_strtolower(
                    trim(
                        (string) $this->input(
                            'responsable_correo'
                        )
                    )
                ),

            'responsable_telefono' =>
                $this->normalizarTelefono(
                    $this->input(
                        'responsable_telefono'
                    )
                ),

            'parentesco' =>
                $this->normalizarTextoNullable(
                    $this->input('parentesco')
                ),

            /*
            |--------------------------------------------------------------------------
            | Pago
            |--------------------------------------------------------------------------
            */

            'metodo_pago' =>
                mb_strtolower(
                    trim(
                        (string) $this->input(
                            'metodo_pago'
                        )
                    )
                ),

            'numero_referencia' =>
                $this->normalizarTextoNullable(
                    $this->input(
                        'numero_referencia'
                    )
                ),

            /*
            |--------------------------------------------------------------------------
            | Auxiliar
            |--------------------------------------------------------------------------
            */

            'edad_calculada' => $edad,
        ]);
    }

    public function rules(): array
    {
        $esMenor =
            is_numeric(
                $this->input('edad_calculada')
            )
            &&
            (int) $this->input(
                'edad_calculada'
            ) < 18;

        return [

            /*
            |--------------------------------------------------------------------------
            | Datos personales
            |--------------------------------------------------------------------------
            */

            'primer_nombre' => [
                'required',
                'string',
                'max:80',
            ],

            'segundo_nombre' => [
                'nullable',
                'string',
                'max:80',
            ],

            'primer_apellido' => [
                'required',
                'string',
                'max:80',
            ],

            'segundo_apellido' => [
                'nullable',
                'string',
                'max:80',
            ],

            'fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:today',
                'before_or_equal:' .
                    now()
                        ->subYears(7)
                        ->toDateString(),
            ],

            'sexo' => [
                'required',
                Rule::in([
                    'masculino',
                    'femenino',
                ]),
            ],

            'tipo_documento' => [
                'required',
                Rule::in([
                    'dni',
                    'identidad_menor',
                    'pasaporte',
                ]),
            ],

            'numero_documento' => [
                'required',
                'string',
                'max:50',
            ],

            'nacionalidad' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pais_residencia_id' => [
                'required',
                'integer',

                Rule::exists(
                    'paises',
                    'id'
                ),
            ],

            'correo_personal' => [
                'required',
                'email:rfc',
                'max:150',
            ],

            'telefono_movil' => [
                'required',
                'string',
                'max:30',
            ],

            'telefono_movil_whatsapp' => [
                'nullable',
                'boolean',
            ],

            'direccion' => [
                'required',
                'string',
                'max:500',
            ],

            'ciudad_municipio' => [
                'required',
                'string',
                'max:120',
            ],

            'departamento_estado' => [
                'required',
                'string',
                'max:120',
            ],

            /*
            |--------------------------------------------------------------------------
            | Información académica
            |--------------------------------------------------------------------------
            */

            /*
             * programa_id se utiliza para controlar el formulario,
             * aunque no se almacena directamente en la solicitud.
             */
            'programa_id' => [
                'required',
                'integer',

                Rule::exists(
                    'programas',
                    'id'
                )->where(
                    fn ($query) =>
                        $query
                            ->where(
                                'estado',
                                'activo'
                            )
                            ->whereNull(
                                'deleted_at'
                            )
                ),
            ],

            'segmento_solicitado' => [
                'required',
                Rule::in([
                    'niños',
                    'jóvenes_adultos',
                ]),
            ],

            'nivel_solicitado_id' => [
                'required',
                'integer',

                Rule::exists(
                    'niveles',
                    'id'
                )->where(
                    fn ($query) =>
                        $query
                            ->where(
                                'estado',
                                'activo'
                            )
                            ->whereNull(
                                'deleted_at'
                            )
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Referencia
            |--------------------------------------------------------------------------
            */

            'fuente_referencia_id' => [
                'nullable',
                'integer',

                Rule::exists(
                    'fuentes_referencia',
                    'id'
                ),
            ],

            'fuente_referencia_otro' => [
                'nullable',
                'string',
                'max:150',
            ],

            'recomienda_otro_estudiante' => [
                'nullable',
                'boolean',
            ],

            'observaciones_solicitante' => [
                'nullable',
                'string',
                'max:2000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Responsable del menor
            |--------------------------------------------------------------------------
            */

            'responsable_primer_nombre' => [
                Rule::requiredIf($esMenor),
                'nullable',
                'string',
                'max:80',
            ],

            'responsable_segundo_nombre' => [
                'nullable',
                'string',
                'max:80',
            ],

            'responsable_primer_apellido' => [
                Rule::requiredIf($esMenor),
                'nullable',
                'string',
                'max:80',
            ],

            'responsable_segundo_apellido' => [
                'nullable',
                'string',
                'max:80',
            ],

            'responsable_tipo_documento' => [
                Rule::requiredIf($esMenor),
                'nullable',

                Rule::in([
                    'dni',
                    'pasaporte',
                ]),
            ],

            'responsable_numero_documento' => [
                Rule::requiredIf($esMenor),
                'nullable',
                'string',
                'max:50',
            ],

            'responsable_correo' => [
                Rule::requiredIf($esMenor),
                'nullable',
                'email:rfc',
                'max:150',
            ],

            'responsable_telefono' => [
                Rule::requiredIf($esMenor),
                'nullable',
                'string',
                'max:30',
            ],

            'responsable_pais_residencia_id' => [
                Rule::requiredIf($esMenor),
                'nullable',
                'integer',

                Rule::exists(
                    'paises',
                    'id'
                ),
            ],

            'parentesco' => [
                Rule::requiredIf($esMenor),
                'nullable',
                'string',
                'max:50',
            ],

            'responsable_recibe_notificaciones' => [
                'nullable',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Pago
            |--------------------------------------------------------------------------
            */

            'monto_total' => [
                'required',
                'numeric',
                'min:700',
                'max:99999999.99',
            ],

            'metodo_pago' => [
                'required',
                'string',
                'max:30',
            ],

            'fecha_pago' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'numero_referencia' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Comprobante
            |--------------------------------------------------------------------------
            */

            'comprobante_pago' => [
                'required',
                'file',

                /*
                 * Imagen o PDF.
                 */
                'mimes:jpg,jpeg,png,webp,pdf',

                /*
                 * 5 MB.
                 */
                'max:5120',
            ],

            /*
            |--------------------------------------------------------------------------
            | Términos
            |--------------------------------------------------------------------------
            */

            'acepta_declaracion' => [
                'accepted',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (
                Validator $validator
            ): void {
                $this->validarSegmentoPorEdad(
                    $validator
                );

                $this->validarNivelDelPrograma(
                    $validator
                );

                $this->validarDocumentoPorTipo(
                    $validator
                );

                $this->validarDocumentoResponsable(
                    $validator
                );
            },
        ];
    }

    private function validarSegmentoPorEdad(
        Validator $validator
    ): void {
        $edad = $this->input(
            'edad_calculada'
        );

        if (!is_numeric($edad)) {
            return;
        }

        $edad = (int) $edad;

        $segmentoEsperado =
            $edad >= 14
                ? 'jóvenes_adultos'
                : 'niños';

        if (
            $this->input(
                'segmento_solicitado'
            ) !== $segmentoEsperado
        ) {
            $validator->errors()->add(
                'fecha_nacimiento',
                'No fue posible determinar correctamente el segmento académico según la edad.'
            );
        }
    }

    private function validarNivelDelPrograma(
        Validator $validator
    ): void {
        $nivelId = $this->input(
            'nivel_solicitado_id'
        );

        $programaId = $this->input(
            'programa_id'
        );

        if (
            !$nivelId ||
            !$programaId
        ) {
            return;
        }

        $nivel = Nivel::query()
            ->with('programa')
            ->find($nivelId);

        if (!$nivel) {
            return;
        }

        if (
            (int) $nivel->programa_id
            !== (int) $programaId
        ) {
            $validator->errors()->add(
                'nivel_solicitado_id',
                'El nivel seleccionado no pertenece al programa indicado.'
            );

            return;
        }

        /*
         * También evitamos manipular el HTML para seleccionar
         * un programa de un segmento que no corresponda.
         */
        if (
            $nivel->programa &&
            $nivel->programa->segmento
            !== $this->input(
                'segmento_solicitado'
            )
        ) {
            $validator->errors()->add(
                'programa_id',
                'El programa seleccionado no corresponde al segmento determinado por la edad.'
            );
        }
    }

    private function validarDocumentoPorTipo(
        Validator $validator
    ): void {
        $tipo = $this->input(
            'tipo_documento'
        );

        $numero = $this->input(
            'numero_documento'
        );

        if (!$numero) {
            return;
        }

        if ($tipo === 'dni') {
            if (
                !preg_match(
                    '/^\d{13}$/',
                    $numero
                )
            ) {
                $validator->errors()->add(
                    'numero_documento',
                    'El DNI debe contener exactamente 13 dígitos.'
                );
            }
        }

        if (
            $tipo === 'identidad_menor' &&
            mb_strlen($numero) > 50
        ) {
            $validator->errors()->add(
                'numero_documento',
                'El número de identidad registrado no es válido.'
            );
        }
    }

    private function validarDocumentoResponsable(
        Validator $validator
    ): void {
        $tipo = $this->input(
            'responsable_tipo_documento'
        );

        $numero = $this->input(
            'responsable_numero_documento'
        );

        if (
            !$tipo ||
            !$numero
        ) {
            return;
        }

        if (
            $tipo === 'dni' &&
            !preg_match(
                '/^\d{13}$/',
                $numero
            )
        ) {
            $validator->errors()->add(
                'responsable_numero_documento',
                'El DNI del responsable debe contener exactamente 13 dígitos.'
            );
        }
    }

    public function messages(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Aspirante
            |--------------------------------------------------------------------------
            */

            'primer_nombre.required' =>
                'Ingrese el primer nombre del aspirante.',

            'primer_apellido.required' =>
                'Ingrese el primer apellido del aspirante.',

            'fecha_nacimiento.required' =>
                'Ingrese la fecha de nacimiento.',

            'fecha_nacimiento.before_or_equal' =>
                'La edad mínima para ingresar a EDMA es de 7 años.',

            'sexo.required' =>
                'Seleccione el sexo del aspirante.',

            'sexo.in' =>
                'La opción seleccionada para sexo no es válida.',

            'tipo_documento.required' =>
                'Seleccione el tipo de documento.',

            'numero_documento.required' =>
                'Ingrese el número de documento.',

            'pais_residencia_id.required' =>
                'Seleccione el país de residencia.',

            'correo_personal.required' =>
                'Ingrese un correo electrónico de contacto.',

            'correo_personal.email' =>
                'Ingrese un correo electrónico válido.',

            'telefono_movil.required' =>
                'Ingrese un número de teléfono.',

            'direccion.required' =>
                'Ingrese la dirección de residencia.',

            'ciudad_municipio.required' =>
                'Ingrese la ciudad o municipio.',

            'departamento_estado.required' =>
                'Ingrese el departamento o estado.',

            /*
            |--------------------------------------------------------------------------
            | Académico
            |--------------------------------------------------------------------------
            */

            'programa_id.required' =>
                'Seleccione el programa académico.',

            'programa_id.exists' =>
                'El programa seleccionado no está disponible.',

            'nivel_solicitado_id.required' =>
                'Seleccione el nivel al que considera que podría ingresar.',

            'nivel_solicitado_id.exists' =>
                'El nivel seleccionado no está disponible.',

            /*
            |--------------------------------------------------------------------------
            | Responsable
            |--------------------------------------------------------------------------
            */

            'responsable_primer_nombre.required' =>
                'Ingrese el nombre del responsable.',

            'responsable_primer_apellido.required' =>
                'Ingrese el apellido del responsable.',

            'responsable_tipo_documento.required' =>
                'Seleccione el tipo de documento del responsable.',

            'responsable_numero_documento.required' =>
                'Ingrese el documento del responsable.',

            'responsable_correo.required' =>
                'Ingrese el correo electrónico del responsable.',

            'responsable_correo.email' =>
                'Ingrese un correo electrónico válido para el responsable.',

            'responsable_telefono.required' =>
                'Ingrese el teléfono del responsable.',

            'responsable_pais_residencia_id.required' =>
                'Seleccione el país de residencia del responsable.',

            'parentesco.required' =>
                'Indique el parentesco o relación con el aspirante.',

            /*
            |--------------------------------------------------------------------------
            | Pago
            |--------------------------------------------------------------------------
            */

            'monto_total.required' =>
                'Ingrese el monto correspondiente al pago realizado.',

            'monto_total.numeric' =>
                'El monto del pago no es válido.',

            'metodo_pago.required' =>
                'Seleccione el método utilizado para realizar el pago.',

            'fecha_pago.required' =>
                'Ingrese la fecha en que realizó el pago.',

            'fecha_pago.before_or_equal' =>
                'La fecha del pago no puede ser posterior a hoy.',

            /*
            |--------------------------------------------------------------------------
            | Comprobante
            |--------------------------------------------------------------------------
            */

            'comprobante_pago.required' =>
                'Adjunte el comprobante del pago.',

            'comprobante_pago.mimes' =>
                'El comprobante debe ser una imagen JPG, PNG, WEBP o un archivo PDF.',

            'comprobante_pago.max' =>
                'El comprobante no puede superar los 5 MB.',

            /*
            |--------------------------------------------------------------------------
            | Declaración
            |--------------------------------------------------------------------------
            */

            'acepta_declaracion.accepted' =>
                'Debe confirmar que la información proporcionada es correcta antes de enviar la solicitud.',

                'monto_total.min' =>
    'El primer pago debe ser de al menos L 700.00.',
    
        ];
    }

    private function normalizarTexto(
        mixed $value
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        return preg_replace(
            '/\s+/u',
            ' ',
            trim($value)
        );
    }

    private function normalizarTextoNullable(
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

    private function normalizarDocumento(
        mixed $value
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        return preg_replace(
            '/[\s-]+/',
            '',
            trim($value)
        );
    }

    private function normalizarTelefono(
        mixed $value
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        return preg_replace(
            '/[^\d+]/',
            '',
            trim($value)
        );
    }
}