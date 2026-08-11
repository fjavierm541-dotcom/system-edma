<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCuentaBancariaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'numero_cuenta' => $this->normalizeAccountNumber(
                $this->input('numero_cuenta')
            ),

            'tipo_cuenta' => $this->normalizeNullableText(
                $this->input('tipo_cuenta')
            ),

            'moneda' => $this->normalizeCurrency(
                $this->input('moneda')
            ),

            'nombre_titular' => $this->normalizeNullableText(
                $this->input('nombre_titular')
            ),

            'es_principal' => $this->boolean(
                'es_principal'
            ),

            'activo' => $this->boolean(
                'activo',
                true
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            'institucion_financiera_id' => [
                'required',
                'integer',

                Rule::exists(
                    'instituciones_financieras',
                    'id'
                )->where(
                    fn ($query) => $query->where(
                        'activo',
                        true
                    )
                ),
            ],

            'numero_cuenta' => [
                'required',
                'string',
                'max:50',
            ],

            'tipo_cuenta' => [
                'required',
                'string',
                'max:50',
            ],

            'moneda' => [
                'required',
                'string',
                'max:10',
            ],

            'nombre_titular' => [
                'required',
                'string',
                'max:180',
            ],

            'es_principal' => [
                'boolean',
            ],

            'activo' => [
                'boolean',
            ],

            'fecha_inicio' => [
                'nullable',
                'date',
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'institucion_financiera_id.required' =>
                'Debe seleccionar una institución financiera.',

            'institucion_financiera_id.exists' =>
                'La institución financiera seleccionada no existe o está inactiva.',

            'numero_cuenta.required' =>
                'Debe ingresar el número de cuenta.',

            'numero_cuenta.max' =>
                'El número de cuenta no puede superar los 50 caracteres.',

            'tipo_cuenta.required' =>
                'Debe indicar el tipo de cuenta.',

            'tipo_cuenta.max' =>
                'El tipo de cuenta no puede superar los 50 caracteres.',

            'moneda.required' =>
                'Debe seleccionar la moneda de la cuenta.',

            'moneda.max' =>
                'La moneda seleccionada no es válida.',

            'nombre_titular.required' =>
                'Debe indicar el nombre del titular de la cuenta.',

            'nombre_titular.max' =>
                'El nombre del titular no puede superar los 180 caracteres.',

            'fecha_inicio.date' =>
                'La fecha de inicio no tiene un formato válido.',

            'fecha_fin.date' =>
                'La fecha de finalización no tiene un formato válido.',

            'fecha_fin.after_or_equal' =>
                'La fecha de finalización no puede ser anterior a la fecha de inicio.',
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

    private function normalizeAccountNumber(
        mixed $value
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        $value = mb_strtoupper(
            preg_replace('/\s+/u', '', trim($value))
        );

        return $value === '' ? null : $value;
    }

    private function normalizeCurrency(
        mixed $value
    ): mixed {
        if (!is_string($value)) {
            return $value;
        }

        $value = mb_strtoupper(trim($value));

        return $value === '' ? null : $value;
    }
}