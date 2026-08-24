<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AprobarPagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol(
            'Administrador'
        ) ?? false;
    }

    public function rules(): array
    {
        return [
            'monto_confirmado' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999.99',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'monto_confirmado.required' =>
                'Ingresa el monto confirmado del comprobante.',

            'monto_confirmado.numeric' =>
                'El monto confirmado debe ser válido.',

            'monto_confirmado.min' =>
                'El monto confirmado debe ser mayor que cero.',
        ];
    }
}