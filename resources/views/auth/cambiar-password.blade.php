<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cambiar contraseña | EDMA Portal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

<div class="container min-vh-100 d-flex align-items-center justify-content-center">

    <div class="row w-100 justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="card shadow border-0">

                <div class="card-body p-4">

                    <h1 class="h4 fw-bold mb-2">
                        Establezca una nueva contraseña
                    </h1>

                    <p class="text-muted">
                        Está utilizando una contraseña temporal.
                        Antes de continuar deberá establecer una contraseña personal.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            Revise la información ingresada.
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('password.change.update') }}"
                    >

                        @csrf
                        @method('PUT')

                        <div class="mb-3">

                            <label
                                for="password"
                                class="form-label"
                            >
                                Nueva contraseña
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                                autofocus
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="mb-3">

                                <label
                                    for="password"
                                    class="form-label"
                                >
                                    Nueva contraseña
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                    autofocus
                                >

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Debe contener al menos 8 caracteres, incluyendo una letra mayúscula,
                                    una letra minúscula, un número y un carácter especial.
                                </div>

                            </div>

                        </div>

                        <div class="mb-4">

                            <label
                                for="password_confirmation"
                                class="form-label"
                            >
                                Confirmar nueva contraseña
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                required
                            >

                        </div>

                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Guardar nueva contraseña
                            </button>

                        </div>

                    </form>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="mt-3 text-center"
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