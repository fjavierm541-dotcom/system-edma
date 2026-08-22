<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Iniciar sesión | EDMA Portal
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="edma-auth-page">

    <main
        class="edma-auth"
        id="edmaAuth"
    >

        {{-- =====================================================
             Fondo
             ===================================================== --}}

        <div
            class="edma-auth__background"
            aria-hidden="true"
        ></div>


        {{-- =====================================================
             Halo dinámico
             ===================================================== --}}

        <div
            class="edma-auth__cursor-glow"
            aria-hidden="true"
        ></div>


        {{-- =====================================================
             Destellos
             ===================================================== --}}

        <div
            class="edma-auth__sparkles"
            aria-hidden="true"
        >

            <span
                class="edma-auth__sparkle edma-auth__sparkle--1"
                data-depth="1"
            ></span>

            <span
                class="edma-auth__sparkle edma-auth__sparkle--2"
                data-depth="1.4"
            ></span>

            <span
                class="edma-auth__sparkle edma-auth__sparkle--3"
                data-depth="0.8"
            ></span>

            <span
                class="edma-auth__sparkle edma-auth__sparkle--4"
                data-depth="1.8"
            ></span>

            <span
                class="edma-auth__sparkle edma-auth__sparkle--5"
                data-depth="1.1"
            ></span>

        </div>


        {{-- =====================================================
             Login
             ===================================================== --}}

        <div class="edma-auth__center">

            <section
                class="edma-auth-card"
                id="edmaAuthCard"
            >

                <div
                    class="edma-auth-card__top-light"
                    aria-hidden="true"
                ></div>


                {{-- Logo --}}
                <div class="edma-auth-card__logo-wrap">

                    <div class="edma-auth-card__logo">

                        <img
                            src="{{ asset('images/brand/logo-edma.png') }}"
                            alt="Edumerican Academy Honduras"
                        >

                    </div>

                </div>


                {{-- Encabezado --}}
                <header class="edma-auth-card__header">

                    <span class="edma-auth-card__eyebrow">
                        EDMA PORTAL
                    </span>

                    <h1>
                        Bienvenido
                    </h1>

                    <p>
                        Ingrese con su Código EDMA y contraseña para continuar.
                    </p>

                </header>


                {{-- Formulario --}}
                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                    class="edma-auth-form"
                >

                    @csrf


                    {{-- Código EDMA --}}
                    <div class="edma-auth-field">

                        <label
                            for="username"
                            class="edma-auth-field__label"
                        >
                            Código EDMA
                        </label>

                        <div
                            class="edma-auth-field__control
                                @error('username')
                                    edma-auth-field__control--error
                                @enderror"
                        >

                            <i class="bi bi-person-badge"></i>

                            <input
                                type="text"
                                name="username"
                                id="username"
                                value="{{ old('username') }}"
                                placeholder="Ej. EDMA-2026-00005"
                                autocomplete="username"
                                required
                                autofocus
                            >

                        </div>

                        @error('username')

                            <div class="edma-auth-field__error">

                                <i class="bi bi-exclamation-circle"></i>

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Contraseña --}}
                    <div class="edma-auth-field">

                        <label
                            for="password"
                            class="edma-auth-field__label"
                        >
                            Contraseña
                        </label>

                        <div
                            class="edma-auth-field__control
                                @error('password')
                                    edma-auth-field__control--error
                                @enderror"
                        >

                            <i class="bi bi-lock"></i>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Ingrese su contraseña"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="edma-auth-password-toggle"
                                id="togglePassword"
                                aria-label="Mostrar contraseña"
                            >

                                <i
                                    class="bi bi-eye"
                                    id="togglePasswordIcon"
                                ></i>

                            </button>

                        </div>

                        @error('password')

                            <div class="edma-auth-field__error">

                                <i class="bi bi-exclamation-circle"></i>

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Iniciar sesión --}}
                    <button
                        type="submit"
                        class="edma-auth-submit"
                    >

                        <span>
                            Iniciar sesión
                        </span>

                        <i class="bi bi-arrow-right"></i>

                    </button>

                </form>


                {{-- Acerca de --}}
                <div class="edma-auth-card__info">

                    <button
                        type="button"
                        class="edma-auth-info-button"
                        data-bs-toggle="modal"
                        data-bs-target="#modalInformacionSistema"
                    >

                        <i class="bi bi-info-circle"></i>

                        <span>
                            Acerca de
                        </span>

                    </button>

                </div>

            </section>


            {{-- Copyright --}}
            <div class="edma-auth__copyright">

                © {{ date('Y') }} Edumerican Academy Honduras.
                Todos los derechos reservados.

            </div>

        </div>

    </main>


    {{-- =========================================================
         Modal - Fuera del MAIN
         ========================================================= --}}

    <div
        class="modal fade edma-auth-modal"
        id="modalInformacionSistema"
        tabindex="-1"
        aria-labelledby="modalInformacionSistemaLabel"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content edma-auth-modal__content">

                {{-- Header --}}
                <div class="modal-header edma-auth-modal__header">

                    <div>

                        <span class="edma-auth-modal__eyebrow">
                            EDMA Portal
                        </span>

                        <h5
                            class="modal-title"
                            id="modalInformacionSistemaLabel"
                        >
                            Acerca del sistema
                        </h5>

                    </div>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>


                {{-- Body --}}
                <div class="modal-body edma-auth-modal__body">

                    <div class="edma-auth-modal__brand">

                        <div class="edma-auth-modal__logo">

                            <img
                                src="{{ asset('images/brand/logo-edma.png') }}"
                                alt="Edumerican Academy Honduras"
                            >

                        </div>

                        <div>

                            <strong>
                                Sistema Académico EDMA
                            </strong>

                            <span>
                                Edumerican Academy Honduras
                            </span>

                        </div>

                    </div>


                    {{-- Descripción --}}
                    <div class="edma-auth-modal__section">

                        <span class="edma-auth-modal__label">
                            Descripción
                        </span>

                        <p>
                            Plataforma de gestión académica y administrativa
                            desarrollada para centralizar y facilitar los
                            procesos de Edumerican Academy Honduras.
                        </p>

                    </div>


                    {{-- Versión / Año --}}
                    <div class="edma-auth-modal__grid">

                        <div>

                            <span class="edma-auth-modal__label">
                                Versión
                            </span>

                            <strong>
                                1.0
                            </strong>

                        </div>

                        <div>

                            <span class="edma-auth-modal__label">
                                Año
                            </span>

                            <strong>
                                {{ date('Y') }}
                            </strong>

                        </div>

                    </div>


                    {{-- Desarrollo --}}
                    <div class="edma-auth-modal__section">

                        <span class="edma-auth-modal__label">
                            Desarrollo
                        </span>

                        <p class="mb-0">
                            Lic. F. Javier Medina
                        </p>

                    </div>


                    {{-- Derechos --}}
                    <div class="edma-auth-modal__section">

                        <span class="edma-auth-modal__label">
                            Derechos
                        </span>

                        <p class="mb-0">
                            Sistema desarrollado para Edumerican Academy
                            Honduras. La academia conserva los derechos de uso
                            y explotación del sistema, así como la titularidad
                            de su información institucional, identidad y
                            recursos.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         JavaScript
         ========================================================= --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                /*
                |--------------------------------------------------------------------------
                | Mostrar / ocultar contraseña
                |--------------------------------------------------------------------------
                */

                const passwordInput =
                    document.getElementById('password');

                const togglePassword =
                    document.getElementById('togglePassword');

                const togglePasswordIcon =
                    document.getElementById('togglePasswordIcon');


                if (
                    passwordInput
                    && togglePassword
                    && togglePasswordIcon
                ) {

                    togglePassword.addEventListener(
                        'click',
                        function () {

                            const mostrar =
                                passwordInput.type === 'password';

                            passwordInput.type =
                                mostrar
                                    ? 'text'
                                    : 'password';

                            togglePasswordIcon.className =
                                mostrar
                                    ? 'bi bi-eye-slash'
                                    : 'bi bi-eye';

                            togglePassword.setAttribute(
                                'aria-label',
                                mostrar
                                    ? 'Ocultar contraseña'
                                    : 'Mostrar contraseña'
                            );

                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Interacción con cursor
                |--------------------------------------------------------------------------
                */

                const auth =
                    document.getElementById('edmaAuth');

                const card =
                    document.getElementById('edmaAuthCard');

                const sparkles =
                    document.querySelectorAll(
                        '.edma-auth__sparkle'
                    );


                if (
                    auth
                    && window.matchMedia(
                        '(pointer: fine)'
                    ).matches
                ) {

                    let frameId = null;


                    auth.addEventListener(
                        'mousemove',
                        function (event) {

                            if (frameId) {
                                cancelAnimationFrame(frameId);
                            }

                            frameId =
                                requestAnimationFrame(
                                    function () {

                                        const rect =
                                            auth.getBoundingClientRect();

                                        const x =
                                            event.clientX
                                            - rect.left;

                                        const y =
                                            event.clientY
                                            - rect.top;

                                        const normalizedX =
                                            x / rect.width
                                            - 0.5;

                                        const normalizedY =
                                            y / rect.height
                                            - 0.5;


                                        auth.style.setProperty(
                                            '--mouse-x',
                                            `${x}px`
                                        );

                                        auth.style.setProperty(
                                            '--mouse-y',
                                            `${y}px`
                                        );


                                        sparkles.forEach(
                                            function (sparkle) {

                                                const depth =
                                                    parseFloat(
                                                        sparkle.dataset.depth
                                                    ) || 1;

                                                const moveX =
                                                    normalizedX
                                                    * depth
                                                    * 5;

                                                const moveY =
                                                    normalizedY
                                                    * depth
                                                    * 5;

                                                sparkle.style.transform =
                                                    `translate(${moveX}px, ${moveY}px)`;

                                            }
                                        );


                                        if (card) {

                                            const rotateY =
                                                normalizedX
                                                * 0.8;

                                            const rotateX =
                                                normalizedY
                                                * -0.8;


                                            card.style.transform =
                                                `
                                                    perspective(1400px)
                                                    rotateY(${rotateY}deg)
                                                    rotateX(${rotateX}deg)
                                                `;


                                            card.style.setProperty(
                                                '--card-light-x',
                                                `${
                                                    (
                                                        normalizedX
                                                        + 0.5
                                                    )
                                                    * 100
                                                }%`
                                            );

                                            card.style.setProperty(
                                                '--card-light-y',
                                                `${
                                                    (
                                                        normalizedY
                                                        + 0.5
                                                    )
                                                    * 100
                                                }%`
                                            );

                                        }

                                    }
                                );

                        }
                    );


                    auth.addEventListener(
                        'mouseleave',
                        function () {

                            sparkles.forEach(
                                function (sparkle) {

                                    sparkle.style.transform =
                                        'translate(0, 0)';

                                }
                            );


                            if (card) {

                                card.style.transform =
                                    'perspective(1400px) rotateY(0deg) rotateX(0deg)';

                                card.style.setProperty(
                                    '--card-light-x',
                                    '50%'
                                );

                                card.style.setProperty(
                                    '--card-light-y',
                                    '18%'
                                );

                            }

                        }
                    );

                }

            }
        );

    </script>

</body>

</html>