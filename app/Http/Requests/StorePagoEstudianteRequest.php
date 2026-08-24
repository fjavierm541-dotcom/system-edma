<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePagoEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol('Estudiante')
            ?? false;
    }

    public function rules(): array
    {
        return [
            'monto_total' => [
                'required',
                'numeric',
                'min:700',
                'max:999999.99',
            ],

            'metodo_pago' => [
                'required',
                Rule::in([
                    'transferencia',
                    'deposito',
                    'tigo_money',
                    'otro',
                ]),
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

            'comprobante' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'monto_total.required' =>
                'Ingresa el monto del pago.',

            'monto_total.numeric' =>
                'El monto del pago debe ser válido.',

            'monto_total.min' =>
                'El pago mínimo corresponde a una mensualidad de L 700.00.',

            'metodo_pago.required' =>
                'Selecciona el método utilizado para realizar el pago.',

            'metodo_pago.in' =>
                'El método de pago seleccionado no es válido.',

            'fecha_pago.required' =>
                'Indica la fecha en que realizaste el pago.',

            'fecha_pago.date' =>
                'La fecha del pago no es válida.',

            'fecha_pago.before_or_equal' =>
                'La fecha del pago no puede ser posterior al día de hoy.',

            'numero_referencia.max' =>
                'El número de transacción o referencia no puede superar los 100 caracteres.',

            'comprobante.required' =>
                'Adjunta el comprobante de tu pago.',

            'comprobante.file' =>
                'El comprobante adjunto no es válido.',

            'comprobante.mimes' =>
                'El comprobante debe ser una imagen JPG, PNG o un archivo PDF.',

            'comprobante.max' =>
                'El comprobante no debe superar los 5 MB.',
        ];
    }
}