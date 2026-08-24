<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RechazarPagoRequest extends FormRequest
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
            'motivo_rechazo' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'motivo_rechazo.required' =>
                'Indica el motivo por el cual se rechaza el pago.',

            'motivo_rechazo.max' =>
                'El motivo del rechazo no puede superar los 1000 caracteres.',
        ];
    }
}