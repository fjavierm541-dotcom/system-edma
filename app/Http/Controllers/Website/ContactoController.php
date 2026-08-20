<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\ContactoWebMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate(
            [
                'website' => [
                    'nullable',
                    'max:0',
                ],

                'nombre' => [
                    'required',
                    'string',
                    'max:120',
                ],

                'correo' => [
                    'required',
                    'email',
                    'max:150',
                ],

                'asunto' => [
                    'required',
                    'string',
                    'max:150',
                ],

                'mensaje' => [
                    'required',
                    'string',
                    'min:10',
                    'max:3000',
                ],
            ],
            [
                'website.max' => 'No fue posible procesar el mensaje.',
                'nombre.required' => 'Ingresa tu nombre completo.',

                'correo.required' => 'Ingresa tu correo electrónico.',
                'correo.email' => 'Ingresa un correo electrónico válido.',

                'asunto.required' => 'Indica el asunto de tu consulta.',

                'mensaje.required' => 'Escribe tu mensaje.',
                'mensaje.min' => 'El mensaje debe contener al menos 10 caracteres.',
                'mensaje.max' => 'El mensaje no puede superar los 3000 caracteres.',
            ]
        );

        Mail::to(
            config('services.edma.contact_email')
        )->send(
            new ContactoWebMail(
                nombre: $datos['nombre'],
                correo: $datos['correo'],
                asunto: $datos['asunto'],
                mensaje: $datos['mensaje'],
            )
        );

        return back()->with(
            'contacto_exito',
            'Tu mensaje fue enviado correctamente. Nuestro equipo podrá responderte a través del correo proporcionado.'
        );
    }
}