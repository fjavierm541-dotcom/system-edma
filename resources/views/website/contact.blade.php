@extends('layouts.web')
@section(
    'title',
    'Contacto | Edumerican Academy Honduras'
)

@section(
    'description',
    'Comunícate con Edumerican Academy Honduras para recibir información sobre programas, inscripción, horarios y nuestros servicios educativos.'
)

@section('content')

{{-- =========================================================
     HERO CONTACTO
========================================================= --}}
<section class="edma-contact-hero">

    <div class="edma-container">

        <div class="edma-contact-hero__layout">

            {{-- Contenido --}}
            <div class="edma-contact-hero__content">

                <span class="edma-contact-hero__eyebrow">
                    Estamos para orientarte
                </span>

                <h1>
                    Hablemos sobre
                    <span>tu próximo paso.</span>
                </h1>

                <p>
                    Si tienes preguntas sobre nuestros programas, horarios,
                    proceso de inscripción o servicios educativos, puedes
                    comunicarte con nuestro equipo a través de nuestros
                    diferentes canales de atención.
                </p>

                <div class="edma-contact-hero__actions">

                    <a
                        href="#contacto"
                        class="edma-contact-hero__primary"
                    >
                        Contactar a EDMA

                        <i class="bi bi-arrow-down"></i>
                    </a>

                    <a
                        href="{{ route('inscripciones.solicitud') }}"
                        class="edma-contact-hero__secondary"
                    >
                        Solicitar inscripción

                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </div>

            </div>


            {{-- Panel visual --}}
            <div class="edma-contact-hero__visual">

                <span class="edma-contact-hero__visual-label">
                    Edumerican Academy
                </span>

                <h2>
                    Elige cómo quieres
                    comunicarte con nosotros.
                </h2>

                <div class="edma-contact-hero__quick">

                    <a href="mailto:edumerican@gmail.com">

                        <span>
                            <i class="bi bi-envelope-fill"></i>
                        </span>

                        <div>
                            <small>Correo electrónico</small>
                            <strong>edumerican@gmail.com</strong>
                        </div>

                        <i class="bi bi-arrow-up-right"></i>

                    </a>


                    <a
                        href="https://api.whatsapp.com/send?phone=50496734171&text=Hola,%20quiero%20más%20información%20sobre%20Edumerican%20Academy."
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        <span>
                            <i class="bi bi-whatsapp"></i>
                        </span>

                        <div>
                            <small>WhatsApp</small>
                            <strong>+504 9673-4171</strong>
                        </div>

                        <i class="bi bi-arrow-up-right"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     CANALES DE CONTACTO
========================================================= --}}
<section
    class="edma-contact-channels"
    id="contacto"
>

    <div class="edma-container">

        <div class="edma-contact-channels__header">

            <div
                class="edma-reveal"
                data-reveal="left"
            >

                <span class="edma-section-eyebrow">
                    Canales de atención
                </span>

                <h2>
                    Estamos más cerca
                    <span>de lo que imaginas.</span>
                </h2>

            </div>


            <p
                class="edma-reveal"
                data-reveal="right"
            >
                Selecciona el medio que prefieras para comunicarte con
                nuestro equipo. Puedes escribirnos directamente por WhatsApp,
                correo electrónico o encontrarnos en nuestras redes sociales.
            </p>

        </div>


        <div class="edma-contact-channels__grid">

            {{-- WhatsApp --}}
            <article class="edma-contact-channel edma-contact-channel--whatsapp edma-reveal">

                <span class="edma-contact-channel__icon">
                    <i class="bi bi-whatsapp"></i>
                </span>

                <small>Atención directa</small>

                <h3>WhatsApp</h3>

                <p>
                    Escríbenos para realizar consultas sobre nuestros
                    programas, horarios e inscripciones.
                </p>

                <div class="edma-contact-channel__numbers">

                    <a
                        href="https://api.whatsapp.com/send?phone=50496734171&text=Hola,%20quiero%20más%20información%20sobre%20Edumerican%20Academy."
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <span>+504 9673-4171</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                    <a
                        href="https://api.whatsapp.com/send?phone=50431470921&text=Hola,%20quiero%20más%20información%20sobre%20Edumerican%20Academy."
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <span>+504 3147-0921</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </div>

            </article>


            {{-- Correo --}}
            <article class="edma-contact-channel edma-reveal">

                <span class="edma-contact-channel__icon">
                    <i class="bi bi-envelope-fill"></i>
                </span>

                <small>Correo electrónico</small>

                <h3>Escríbenos</h3>

                <p>
                    También puedes enviarnos tus consultas directamente
                    por correo electrónico.
                </p>

                <a
                    href="mailto:edumerican@gmail.com"
                    class="edma-contact-channel__link"
                >
                    edumerican@gmail.com

                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </article>


            {{-- Redes --}}
            <article class="edma-contact-channel edma-reveal">

                <span class="edma-contact-channel__icon">
                    <i class="bi bi-share-fill"></i>
                </span>

                <small>Comunidad EDMA</small>

                <h3>Síguenos</h3>

                <p>
                    Conoce nuestras actividades, novedades y contenido
                    educativo a través de nuestras redes sociales.
                </p>

                <div class="edma-contact-channel__socials">

                    <a
                        href="https://www.facebook.com/edumerican.hn"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Facebook de Edumerican Academy"
                    >
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a
                        href="https://www.instagram.com/edumerican.hn/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram de Edumerican Academy"
                    >
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a
                        href="https://www.youtube.com/@EdumericanAcademy"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="YouTube de Edumerican Academy"
                    >
                        <i class="bi bi-youtube"></i>
                    </a>

                </div>

            </article>

        </div>

    </div>

</section>


{{-- =========================================================
     FORMULARIO
     La funcionalidad de envío se incorporará posteriormente.
========================================================= --}}
<section class="edma-contact-form-section">

    <div class="edma-container">

        <div class="edma-contact-form-layout">

            <div
                class="edma-contact-form-intro edma-reveal"
                data-reveal="left"
            >

                <span class="edma-section-eyebrow">
                    Envíanos un mensaje
                </span>

                <h2>
                    ¿Prefieres escribirnos
                    <span>desde aquí?</span>
                </h2>

                <p>
                    Déjanos tu consulta y nuestro equipo podrá comunicarse
                    contigo utilizando la información que nos proporciones.
                </p>


                <div class="edma-contact-form-intro__note">

                    <span>
                        <i class="bi bi-shield-check"></i>
                    </span>

                    <div>
                        <strong>Tu información será utilizada para atender tu consulta.</strong>

                        <p>
                            Asegúrate de ingresar correctamente tu correo
                            electrónico para que podamos responderte.
                        </p>
                    </div>

                </div>

            </div>


            {{-- Formulario visual --}}
            <div
                class="edma-contact-form-card edma-reveal"
                data-reveal="right"
            >

                <form id="edmaContactForm"
                    action="{{ route('website.contact.store') }}"
                    method="POST"
                >
                    @csrf

                    <div
                        class="edma-contact-honeypot"
                        aria-hidden="true"
                    >
                        <label for="website">Sitio web</label>

                        <input
                            type="text"
                            id="website"
                            name="website"
                            tabindex="-1"
                            autocomplete="off"
                        >
                    </div>

                    @if (session('contacto_exito'))

                        <div class="edma-contact-alert edma-contact-alert--success">

                            <span>
                                <i class="bi bi-check-circle-fill"></i>
                            </span>

                            <div>
                                <strong>Mensaje enviado</strong>

                                <p>
                                    {{ session('contacto_exito') }}
                                </p>
                            </div>

                        </div>

                    @endif


                    @if ($errors->any())

                        <div class="edma-contact-alert edma-contact-alert--error">

                            <span>
                                <i class="bi bi-exclamation-circle-fill"></i>
                            </span>

                            <div>
                                <strong>Revisa la información</strong>

                                <p>
                                    Hay algunos datos que necesitan ser corregidos antes
                                    de enviar tu mensaje.
                                </p>
                            </div>

                        </div>

                    @endif

                    <div class="edma-contact-form-card__grid">

                        <div class="edma-contact-field">

                            <label for="nombre">
                                Nombre completo
                            </label>

                            <div>
                                <i class="bi bi-person"></i>

                                <input
                                    type="text"
                                    id="nombre"
                                    name="nombre"
                                    value="{{ old('nombre') }}"
                                    placeholder="Escribe tu nombre"
                                    autocomplete="name"
                                    maxlength="120"
                                    required
                                >
                                @error('nombre')
                                    <small class="edma-contact-field__error">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                        </div>


                        <div class="edma-contact-field">

                            <label for="correo">
                                Correo electrónico
                            </label>

                            <div>
                                <i class="bi bi-envelope"></i>

                                <input
                                    type="email"
                                    id="correo"
                                    name="correo"
                                    value="{{ old('correo') }}"
                                    placeholder="correo@ejemplo.com"
                                    autocomplete="email"
                                    maxlength="150"
                                    required
                                >
                                @error('correo')
                                    <small class="edma-contact-field__error">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                        </div>

                    </div>


                    <div class="edma-contact-field">

                        <label for="asunto">
                            Asunto
                        </label>

                        <div>
                            <i class="bi bi-chat-left-text"></i>

                            <input
                                type="text"
                                id="asunto"
                                name="asunto"
                                value="{{ old('asunto') }}"
                                placeholder="¿Sobre qué deseas consultarnos?"
                                maxlength="150"
                                required
                            >
                            @error('asunto')
                                <small class="edma-contact-field__error">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                    </div>


                    <div class="edma-contact-field edma-contact-field--textarea">

                        <label for="mensaje">
                            Mensaje
                        </label>

                        <div>
                            <i class="bi bi-pencil"></i>

                            <textarea
                                id="mensaje"
                                name="mensaje"
                                rows="6"
                                minlength="10"
                                maxlength="3000"
                                placeholder="Escribe tu consulta..."
                                required
                            >{{ old('mensaje') }}</textarea>

                            @error('mensaje')
                                <small class="edma-contact-field__error">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                    </div>


                    <button
                        type="submit"
                        id="edmaContactSubmit"
                        class="edma-contact-form-card__button"
                    >
                        Enviar mensaje

                        <i class="bi bi-send-fill"></i>
                    </button>


                </form>

            </div>

        </div>

    </div>

</section>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('edmaContactForm');
    const button = document.getElementById('edmaContactSubmit');

    if (!form || !button) {
        return;
    }

    form.addEventListener('submit', function () {

        if (!form.checkValidity()) {
            return;
        }

        button.disabled = true;

        button.innerHTML = `
            <span>Enviando mensaje...</span>
            <span
                class="spinner-border spinner-border-sm"
                aria-hidden="true"
            ></span>
        `;
    });

});
</script>


@endsection