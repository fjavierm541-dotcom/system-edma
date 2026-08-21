<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | EDMA Portal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-4">

        <div class="row w-100 justify-content-center">

            <div class="col-12 col-md-8 col-lg-5 col-xl-4">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">

                            <h1 class="h3 fw-bold mb-2">
                                EDMA Portal
                            </h1>

                            <p class="text-muted mb-0">
                                Ingrese sus credenciales para continuar
                            </p>

                        </div>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                Revise los datos ingresados e intente nuevamente.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.store') }}">
                            @csrf

                            <div class="mb-3">

                                <label for="username" class="form-label fw-semibold">
                                    Usuario
                                </label>

                                <input
                                    type="text"
                                    class="form-control form-control-lg @error('username') is-invalid @enderror"
                                    id="username"
                                    name="username"
                                    value="{{ old('username') }}"
                                    autocomplete="username"
                                    autofocus
                                    required
                                >

                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="mb-3">

                                <label for="password" class="form-label fw-semibold">
                                    Contraseña
                                </label>

                                <input
                                    type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    autocomplete="current-password"
                                    required
                                >

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="form-check mb-4">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                >

                                <label class="form-check-label" for="remember">
                                    Mantener sesión iniciada
                                </label>

                            </div>

                            <div class="d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-lg fw-semibold"
                                >
                                    Iniciar sesión
                                </button>

                            </div>

                        </form>

                        <div class="text-center mt-4">

                            <small class="text-muted">
                                Utilice las credenciales proporcionadas por Edumerican Academy Honduras.
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>