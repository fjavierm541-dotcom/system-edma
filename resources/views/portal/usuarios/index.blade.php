@extends('layouts.portal')

@section('title', 'Usuarios | Portal EDMA')

@section('page-title', 'Usuarios')


@section('page-header')

    <div class="portal-page-heading d-flex justify-content-between align-items-start gap-3">

        <div>

            <span class="portal-page-eyebrow">
                Seguridad y acceso
            </span>

            <h1>
                Usuarios del sistema
            </h1>

            <p>
                Administre las cuentas que tienen acceso a EDMA Portal,
                sus roles, estado y credenciales.
            </p>

        </div>

        <a
            href="{{ route('portal.usuarios.create') }}"
            class="btn portal-btn-primary"
        >
            <i class="bi bi-person-plus me-1"></i>
            Crear usuario
        </a>

    </div>

@endsection


@section('content')

    {{-- =========================================================
         Modal - Contraseña temporal generada
         ========================================================= --}}

    @if (
        session('modal_tipo') === 'password_generado'
        && session('password_temporal')
    )

        <div
            class="modal fade"
            id="modalPasswordTemporal"
            tabindex="-1"
            aria-labelledby="modalPasswordTemporalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 shadow">

                    <div class="modal-header">

                        <div>

                            <h5
                                class="modal-title"
                                id="modalPasswordTemporalLabel"
                            >
                                Contraseña temporal generada
                            </h5>

                            <p class="text-muted small mb-0">
                                Entregue estas credenciales al usuario.
                            </p>

                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"
                        ></button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label text-muted small">
                                Código EDMA
                            </label>

                            <div class="form-control bg-light">
                                {{ session('usuario_password_temporal') }}
                            </div>

                        </div>

                        <div>

                            <label class="form-label text-muted small">
                                Contraseña temporal
                            </label>

                            <div class="input-group">

                                <input
                                    type="text"
                                    id="passwordTemporalGenerada"
                                    class="form-control fw-semibold"
                                    value="{{ session('password_temporal') }}"
                                    readonly
                                >

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    id="copiarPasswordTemporal"
                                >
                                    <i class="bi bi-copy"></i>
                                    Copiar
                                </button>

                            </div>

                        </div>

                        <p class="small text-muted mt-3 mb-0">
                            Esta contraseña se muestra únicamente ahora.
                            El usuario deberá cambiarla cuando inicie sesión.
                        </p>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                        >
                            Entendido
                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         Modal - Error
         ========================================================= --}}

    @if (session('modal_tipo') === 'error')

        <div
            class="modal fade"
            id="modalErrorUsuario"
            tabindex="-1"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 shadow">

                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title">
                                {{ session('modal_titulo', 'Ocurrió un problema') }}
                            </h5>

                            <p class="text-muted small mb-0">
                                No se pudo completar la operación.
                            </p>

                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"
                        ></button>

                    </div>

                    <div class="modal-body">

                        <p class="mb-0">
                            {{
                                session(
                                    'modal_mensaje',
                                    'Intente nuevamente.'
                                )
                            }}
                        </p>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                        >
                            Entendido
                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         Resumen
         ========================================================= --}}

    <section class="portal-summary-grid">

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-people"></i>
            </div>

            <div>

                <span>
                    Total de usuarios
                </span>

                <strong>
                    {{ number_format($resumen['total']) }}
                </strong>

                <small>
                    Cuentas registradas
                </small>

            </div>

        </article>


        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-person-check"></i>
            </div>

            <div>

                <span>
                    Usuarios activos
                </span>

                <strong>
                    {{ number_format($resumen['activos']) }}
                </strong>

                <small>
                    Pueden iniciar sesión
                </small>

            </div>

        </article>


        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-muted">
                <i class="bi bi-person-slash"></i>
            </div>

            <div>

                <span>
                    Usuarios inactivos
                </span>

                <strong>
                    {{ number_format($resumen['inactivos']) }}
                </strong>

                <small>
                    Acceso deshabilitado
                </small>

            </div>

        </article>


        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-key"></i>
            </div>

            <div>

                <span>
                    Cambio pendiente
                </span>

                <strong>
                    {{ number_format($resumen['cambio_password']) }}
                </strong>

                <small>
                    Contraseña temporal
                </small>

            </div>

        </article>

    </section>


    {{-- =========================================================
         Listado
         ========================================================= --}}

    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>

                <h2>
                    Cuentas de acceso
                </h2>

                <p>
                    Consulte usuarios por Código EDMA, nombre o rol.
                </p>

            </div>

            <span class="portal-results-count">

                {{ $usuarios->total() }}

                {{ $usuarios->total() === 1 ? 'resultado' : 'resultados' }}

            </span>

        </div>


        {{-- =====================================================
             Filtros
             ===================================================== --}}

        <div class="portal-filter-area">

            <form
                method="GET"
                action="{{ route('portal.usuarios.index') }}"
                class="portal-filter-form"
            >

                <div class="portal-search-field">

                    <i class="bi bi-search"></i>

                    <input
                        type="search"
                        name="buscar"
                        value="{{ $termino }}"
                        class="form-control"
                        placeholder="Código EDMA, nombre o documento..."
                        aria-label="Buscar usuarios"
                    >

                </div>


                <div class="portal-filter-select">

                    <select
                        name="rol"
                        class="form-select"
                        aria-label="Filtrar por rol"
                    >

                        <option value="">
                            Todos los roles
                        </option>

                        <option
                            value="Administrador"
                            @selected($rol === 'Administrador')
                        >
                            Administrador
                        </option>

                        <option
                            value="Docente"
                            @selected($rol === 'Docente')
                        >
                            Docente
                        </option>

                        <option
                            value="Estudiante"
                            @selected($rol === 'Estudiante')
                        >
                            Estudiante
                        </option>

                    </select>

                </div>


                <div class="portal-filter-select">

                    <select
                        name="estado"
                        class="form-select"
                        aria-label="Filtrar por estado"
                    >

                        <option value="">
                            Todos los estados
                        </option>

                        <option
                            value="activo"
                            @selected($estado === 'activo')
                        >
                            Activos
                        </option>

                        <option
                            value="inactivo"
                            @selected($estado === 'inactivo')
                        >
                            Inactivos
                        </option>

                    </select>

                </div>


                <button
                    type="submit"
                    class="btn portal-btn-primary"
                >
                    <i class="bi bi-funnel"></i>
                    Aplicar
                </button>


                @if ($termino !== '' || $estado || $rol)

                    <a
                        href="{{ route('portal.usuarios.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>


        {{-- =====================================================
             Tabla
             ===================================================== --}}

        @if ($usuarios->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table align-middle mb-0">

                    <thead>

                        <tr>

                            <th>
                                Usuario
                            </th>

                            <th>
                                Código EDMA
                            </th>

                            <th>
                                Rol
                            </th>

                            <th>
                                Contraseña
                            </th>

                            <th>
                                Último acceso
                            </th>

                            <th>
                                Estado
                            </th>

                            <th class="text-end">
                                Acciones
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($usuarios as $usuario)

                            <tr>

                                {{-- Usuario --}}
                                <td>

                                    <div class="portal-person-cell">

                                        <span
                                            class="portal-person-avatar portal-person-avatar-placeholder"
                                        >
                                            {{
                                                $usuario->persona?->iniciales
                                                ?: 'US'
                                            }}
                                        </span>

                                        <div class="portal-person-data">

                                            <span class="portal-person-name">

                                                {{
                                                    $usuario->persona?->nombre_completo
                                                    ?? 'Cuenta del sistema'
                                                }}

                                            </span>

                                            <small>

                                                {{
                                                    $usuario->email
                                                    ?: 'Sin correo asociado'
                                                }}

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- Código --}}
                                <td>

                                    <div class="portal-table-primary">
                                        {{ $usuario->username }}
                                    </div>

                                </td>


                                {{-- Rol --}}
                                <td>

                                    @forelse ($usuario->roles as $rolUsuario)

                                        <span class="badge text-bg-light">
                                            {{ $rolUsuario->nombre }}
                                        </span>

                                    @empty

                                        <span class="text-muted">
                                            Sin rol
                                        </span>

                                    @endforelse

                                </td>


                                {{-- Contraseña --}}
                                <td>

                                    @if ($usuario->debe_cambiar_password)

                                        <span class="badge text-bg-warning">
                                            Cambio pendiente
                                        </span>

                                    @else

                                        <span class="badge text-bg-success">
                                            Personal
                                        </span>

                                    @endif

                                </td>


                                {{-- Último acceso --}}
                                <td>

                                    @if ($usuario->ultimo_acceso_at)

                                        <div class="portal-table-primary">

                                            {{
                                                $usuario
                                                    ->ultimo_acceso_at
                                                    ->format('d/m/Y')
                                            }}

                                        </div>

                                        <small class="text-muted">

                                            {{
                                                $usuario
                                                    ->ultimo_acceso_at
                                                    ->format('h:i A')
                                            }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Nunca
                                        </span>

                                    @endif

                                </td>


                                {{-- Estado --}}
                                <td>

                                    @if ($usuario->activo)

                                        <span class="badge text-bg-success">
                                            Activo
                                        </span>

                                    @else

                                        <span class="badge text-bg-secondary">
                                            Inactivo
                                        </span>

                                    @endif

                                </td>


                                {{-- Acciones --}}
                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="btn btn-sm portal-btn-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                        >
                                            <i class="bi bi-gear"></i>
                                            Gestionar
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                            {{-- Restablecer contraseña --}}
                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalRestablecerPassword"
                                                    data-usuario-id="{{ $usuario->id }}"
                                                    data-usuario-codigo="{{ $usuario->username }}"
                                                    data-usuario-nombre="{{ $usuario->persona?->nombre_completo ?? 'Cuenta del sistema' }}"
                                                >
                                                    <i class="bi bi-key me-2"></i>
                                                    Restablecer contraseña
                                                </button>

                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            {{-- Activar / desactivar --}}
                                            <li>

                                                @if ((int) auth()->id() === (int) $usuario->id)

                                                    <button
                                                        type="button"
                                                        class="dropdown-item text-muted"
                                                        disabled
                                                    >
                                                        <i class="bi bi-shield-lock me-2"></i>
                                                        Cuenta en uso
                                                    </button>

                                                @else

                                                    <button
                                                        type="button"
                                                        class="dropdown-item btn-cambiar-estado-usuario
                                                            {{ $usuario->activo ? 'text-danger' : 'text-success' }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalCambiarEstadoUsuario"
                                                        data-usuario-id="{{ $usuario->id }}"
                                                        data-usuario-codigo="{{ $usuario->username }}"
                                                        data-usuario-nombre="{{ $usuario->persona?->nombre_completo ?? 'Cuenta del sistema' }}"
                                                        data-usuario-activo="{{ $usuario->activo ? '1' : '0' }}"
                                                    >

                                                        @if ($usuario->activo)

                                                            <i class="bi bi-person-slash me-2"></i>
                                                            Desactivar acceso

                                                        @else

                                                            <i class="bi bi-person-check me-2"></i>
                                                            Activar acceso

                                                        @endif

                                                    </button>

                                                @endif

                                            </li>

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Paginación --}}
            <div class="p-3">

                {{ $usuarios->links() }}

            </div>

        @else

            <div class="p-4 text-center text-muted">

                <i class="bi bi-person-lock fs-2 d-block mb-2"></i>

                No se encontraron usuarios con los filtros seleccionados.

            </div>

        @endif

    </section>


    {{-- =========================================================
         Modal - Restablecer contraseña
         ========================================================= --}}

    <div
        class="modal fade"
        id="modalRestablecerPassword"
        tabindex="-1"
        aria-labelledby="modalRestablecerPasswordLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <div>

                        <h5
                            class="modal-title"
                            id="modalRestablecerPasswordLabel"
                        >
                            Restablecer contraseña
                        </h5>

                        <p class="text-muted small mb-0">
                            Se generará una nueva contraseña temporal.
                        </p>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>


                <form
                    method="POST"
                    id="formRestablecerPassword"
                >
                    @csrf
                    @method('PATCH')

                    <div class="modal-body">

                        <p class="mb-3">
                            Está a punto de restablecer la contraseña de
                            <strong id="nombreUsuarioRestablecer"></strong>.
                        </p>

                        <div class="p-3 bg-light rounded-3">

                            <div class="small text-muted">
                                Código EDMA
                            </div>

                            <div
                                class="fw-semibold"
                                id="codigoUsuarioRestablecer"
                            ></div>

                        </div>

                        <p class="small text-muted mt-3 mb-0">
                            La contraseña actual dejará de funcionar.
                            Se generará una nueva contraseña temporal y el usuario
                            deberá cambiarla al iniciar sesión.
                        </p>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-key me-1"></i>
                            Generar nueva contraseña
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Modal - Activar / desactivar usuario
         ========================================================= --}}

    <div
        class="modal fade"
        id="modalCambiarEstadoUsuario"
        tabindex="-1"
        aria-labelledby="modalCambiarEstadoUsuarioLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <div>

                        <h5
                            class="modal-title"
                            id="modalCambiarEstadoUsuarioLabel"
                        >
                            Cambiar estado del usuario
                        </h5>

                        <p
                            class="text-muted small mb-0"
                            id="descripcionCambiarEstado"
                        ></p>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>


                <form
                    method="POST"
                    id="formCambiarEstadoUsuario"
                >
                    @csrf
                    @method('PATCH')

                    <div class="modal-body">

                        <div class="mb-3">

                            <div class="small text-muted">
                                Usuario
                            </div>

                            <div
                                class="fw-semibold"
                                id="nombreCambiarEstado"
                            >
                                —
                            </div>

                        </div>

                        <div class="p-3 bg-light rounded-3">

                            <div class="small text-muted">
                                Código EDMA
                            </div>

                            <div
                                class="fw-semibold"
                                id="codigoCambiarEstado"
                            >
                                —
                            </div>

                        </div>

                        <p
                            class="small text-muted mt-3 mb-0"
                            id="mensajeCambiarEstado"
                        ></p>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="btn"
                            id="botonConfirmarEstado"
                        >
                            Confirmar
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Restablecer contraseña
    |--------------------------------------------------------------------------
    */

    const modalRestablecer =
        document.getElementById('modalRestablecerPassword');

    if (modalRestablecer) {

        modalRestablecer.addEventListener(
            'show.bs.modal',
            function (event) {

                const button =
                    event.relatedTarget;

                if (! button) {
                    return;
                }

                const usuarioId =
                    button.dataset.usuarioId;

                const codigo =
                    button.dataset.usuarioCodigo;

                const nombre =
                    button.dataset.usuarioNombre;


                document.getElementById(
                    'codigoUsuarioRestablecer'
                ).textContent = codigo;

                document.getElementById(
                    'nombreUsuarioRestablecer'
                ).textContent = nombre;


                const form =
                    document.getElementById(
                        'formRestablecerPassword'
                    );

                form.action =
                    `{{ url('/portal/usuarios') }}/${usuarioId}/restablecer-password`;
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Activar / desactivar usuario
    |--------------------------------------------------------------------------
    */

    const botonesCambiarEstado =
        document.querySelectorAll(
            '.btn-cambiar-estado-usuario'
        );

    botonesCambiarEstado.forEach(
        function (button) {

            button.addEventListener(
                'click',
                function () {

                    const usuarioId =
                        this.dataset.usuarioId;

                    const codigo =
                        this.dataset.usuarioCodigo;

                    const nombre =
                        this.dataset.usuarioNombre;

                    const activo =
                        this.dataset.usuarioActivo === '1';


                    const nombreElement =
                        document.getElementById(
                            'nombreCambiarEstado'
                        );

                    const codigoElement =
                        document.getElementById(
                            'codigoCambiarEstado'
                        );

                    const descripcionElement =
                        document.getElementById(
                            'descripcionCambiarEstado'
                        );

                    const mensajeElement =
                        document.getElementById(
                            'mensajeCambiarEstado'
                        );

                    const botonConfirmar =
                        document.getElementById(
                            'botonConfirmarEstado'
                        );

                    const form =
                        document.getElementById(
                            'formCambiarEstadoUsuario'
                        );


                    nombreElement.textContent =
                        nombre;

                    codigoElement.textContent =
                        codigo;


                    if (activo) {

                        descripcionElement.textContent =
                            'Desactivar acceso al sistema';

                        mensajeElement.textContent =
                            'El usuario ya no podrá iniciar sesión, pero conservará toda su información e historial dentro del sistema.';

                        botonConfirmar.className =
                            'btn btn-danger';

                        botonConfirmar.innerHTML =
                            '<i class="bi bi-person-slash me-1"></i> Desactivar acceso';

                    } else {

                        descripcionElement.textContent =
                            'Activar acceso al sistema';

                        mensajeElement.textContent =
                            'El usuario podrá volver a iniciar sesión utilizando sus credenciales actuales.';

                        botonConfirmar.className =
                            'btn btn-success';

                        botonConfirmar.innerHTML =
                            '<i class="bi bi-person-check me-1"></i> Activar acceso';
                    }


                    form.action =
                        `{{ url('/portal/usuarios') }}/${usuarioId}/estado`;

                }
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Mostrar contraseña temporal generada
    |--------------------------------------------------------------------------
    */

    @if (
        session('modal_tipo') === 'password_generado'
        && session('password_temporal')
    )

        const passwordModalElement =
            document.getElementById(
                'modalPasswordTemporal'
            );

        if (passwordModalElement) {

            const passwordModal =
                new bootstrap.Modal(
                    passwordModalElement
                );

            passwordModal.show();
        }


        const botonCopiar =
            document.getElementById(
                'copiarPasswordTemporal'
            );

        if (botonCopiar) {

            botonCopiar.addEventListener(
                'click',
                async function () {

                    const password =
                        document.getElementById(
                            'passwordTemporalGenerada'
                        ).value;

                    await navigator.clipboard.writeText(
                        password
                    );

                    botonCopiar.innerHTML =
                        '<i class="bi bi-check-lg"></i> Copiada';
                }
            );
        }

    @endif


    /*
    |--------------------------------------------------------------------------
    | Mostrar modal de error
    |--------------------------------------------------------------------------
    */

    @if (session('modal_tipo') === 'error')

        const modalErrorElement =
            document.getElementById(
                'modalErrorUsuario'
            );

        if (modalErrorElement) {

            const modalError =
                new bootstrap.Modal(
                    modalErrorElement
                );

            modalError.show();
        }

    @endif

});
</script>

@endpush