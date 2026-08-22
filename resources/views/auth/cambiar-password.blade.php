<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Cambiar contraseña | EDMA Portal
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="bg-light">

<div
    class="container min-vh-100 d-flex align-items-center justify-content-center py-4"
>

    <div class="row w-100 justify-content-center">

        <div class="col-md-7 col-lg-5">

            <div class="card shadow border-0 rounded-4">

                <div class="card-body p-4 p-md-5">

                    {{-- =====================================================
                         Encabezado
                         ===================================================== --}}

                    <div class="mb-4">

                        <h1 class="h4 fw-bold mb-2">
                            Establezca una nueva contraseña
                        </h1>

                        <p class="text-muted mb-0">
                            Está utilizando una contraseña temporal.
                            Antes de continuar deberá establecer una
                            contraseña personal.
                        </p>

                    </div>


                    {{-- =====================================================
                         Formulario
                         ===================================================== --}}

                    <form
                        method="POST"
                        action="{{ route('password.change.update') }}"
                    >

                        @csrf
                        @method('PUT')


                        {{-- =================================================
                             Nueva contraseña
                             ================================================= --}}

                        <div class="mb-4">

                            <label
                                for="password"
                                class="form-label fw-semibold"
                            >
                                Nueva contraseña
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control
                                    @error('password')
                                        is-invalid
                                    @enderror"
                                autocomplete="new-password"
                                required
                                autofocus
                            >

                            @error('password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror


                            <div class="form-text mt-2">

                                Debe contener al menos 8 caracteres,
                                incluyendo una letra mayúscula,
                                una letra minúscula, un número y
                                un carácter especial.

                            </div>

                        </div>


                        {{-- =================================================
                             Confirmar contraseña
                             ================================================= --}}

                        <div class="mb-4">

                            <label
                                for="password_confirmation"
                                class="form-label fw-semibold"
                            >
                                Confirmar nueva contraseña
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                autocomplete="new-password"
                                required
                            >

                        </div>


                        {{-- =================================================
                             Guardar
                             ================================================= --}}

                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Guardar nueva contraseña
                            </button>

                        </div>

                    </form>


                    {{-- =====================================================
                         Cerrar sesión
                         ===================================================== --}}

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="mt-4 text-center"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-link text-muted"
                        >
                            Cerrar sesión
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>