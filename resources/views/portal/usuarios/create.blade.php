@extends('layouts.portal')

@section('title', 'Crear usuario | Portal EDMA')

@section('page-title', 'Crear usuario')


@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Seguridad y acceso
            </span>

            <h1>
                Crear usuario
            </h1>

            <p>
                Cree una cuenta de acceso para un estudiante,
                docente o empleado registrado en EDMA.
            </p>

        </div>

    </div>

@endsection


@section('content')

    {{-- =========================================================
         Usuario creado correctamente
         ========================================================= --}}

    @if (session('usuario_creado'))

        @php
            $usuarioCreado = session('usuario_creado');
        @endphp

        <div class="row justify-content-center">

            <div class="col-xl-8">

                <section class="portal-card">

                    <div class="portal-card-header">

                        <div>

                            <span class="portal-page-eyebrow">
                                Cuenta creada
                            </span>

                            <h2 class="mb-1">
                                Usuario creado correctamente
                            </h2>

                            <p class="mb-0">
                                Entregue estas credenciales al usuario.
                            </p>

                        </div>

                    </div>


                    <div class="p-4">

                        {{-- Persona --}}

                        <div class="mb-4">

                            <div class="small text-muted mb-1">
                                Persona
                            </div>

                            <div class="fw-semibold fs-5">
                                {{ $usuarioCreado['nombre'] }}
                            </div>

                            <div class="text-muted">

                                Documento:

                                {{
                                    $usuarioCreado['documento']
                                    ?: 'No registrado'
                                }}

                            </div>

                        </div>


                        {{-- Información de acceso --}}

                        <div class="row g-3">

                            {{-- Tipo --}}

                            <div class="col-md-6">

                                <label class="form-label text-muted small">
                                    Tipo
                                </label>

                                <div class="form-control bg-light">
                                    {{ $usuarioCreado['tipo'] }}
                                </div>

                            </div>


                            {{-- Roles --}}

                            <div class="col-md-6">

                                <label class="form-label text-muted small">
                                    {{ count($usuarioCreado['roles'] ?? []) === 1 ? 'Rol' : 'Roles' }}
                                </label>

                                <div class="form-control bg-light">

                                    @forelse ($usuarioCreado['roles'] ?? [] as $rol)

                                        <span class="badge text-bg-light border me-1">
                                            {{ $rol }}
                                        </span>

                                    @empty

                                        Sin rol

                                    @endforelse

                                </div>

                            </div>


                            {{-- Código EDMA --}}

                            <div class="col-md-6">

                                <label class="form-label text-muted small">
                                    Código EDMA
                                </label>

                                <div class="input-group">

                                    <input
                                        type="text"
                                        class="form-control fw-semibold"
                                        id="codigoUsuarioCreado"
                                        value="{{ $usuarioCreado['codigo'] }}"
                                        readonly
                                    >

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        id="copiarCodigoUsuario"
                                    >
                                        <i class="bi bi-copy"></i>
                                        Copiar
                                    </button>

                                </div>

                            </div>


                            {{-- Contraseña temporal --}}

                            <div class="col-md-6">

                                <label class="form-label text-muted small">
                                    Contraseña temporal
                                </label>

                                <div class="input-group">

                                    <input
                                        type="text"
                                        class="form-control fw-semibold"
                                        id="passwordUsuarioCreado"
                                        value="{{ $usuarioCreado['password_temporal'] }}"
                                        readonly
                                    >

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        id="copiarPasswordUsuario"
                                    >
                                        <i class="bi bi-copy"></i>
                                        Copiar
                                    </button>

                                </div>

                            </div>

                        </div>


                        {{-- Aviso de primer acceso --}}

                        <div class="mt-4 p-3 bg-light rounded-3">

                            <div class="d-flex gap-3 align-items-start">

                                <i class="bi bi-shield-lock fs-5"></i>

                                <div>

                                    <div class="fw-semibold mb-1">
                                        Primer inicio de sesión
                                    </div>

                                    <p class="small text-muted mb-0">
                                        Esta contraseña es temporal.
                                        Al iniciar sesión, el usuario deberá
                                        establecer una contraseña personal
                                        antes de continuar utilizando EDMA Portal.
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Acciones --}}

                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">

                            <button
                                type="button"
                                class="btn portal-btn-secondary"
                                id="copiarCredencialesUsuario"
                            >
                                <i class="bi bi-copy me-1"></i>
                                Copiar credenciales
                            </button>

                            <a
                                href="{{ route('portal.usuarios.index') }}"
                                class="btn portal-btn-primary"
                            >
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver al listado
                            </a>

                        </div>

                    </div>

                </section>

            </div>

        </div>


    {{-- =========================================================
         Formulario para crear usuario
         ========================================================= --}}

    @else

        <div class="row justify-content-center">

            <div class="col-xl-9">

                <section class="portal-card">

                    <div class="portal-card-header">

                        <div>

                            <h2>
                                Nueva cuenta de acceso
                            </h2>

                            <p>
                                Seleccione la persona a quien desea habilitar
                                el acceso al sistema.
                            </p>

                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('portal.usuarios.store') }}"
                        id="formCrearUsuario"
                    >

                        @csrf

                        <div class="p-4">

                            @if ($candidatos->isNotEmpty())

                                {{-- Persona --}}

                                <div class="mb-4">

                                    <label
                                        for="persona_id"
                                        class="form-label fw-semibold"
                                    >
                                        Persona
                                    </label>

                                    <select
                                        name="persona_id"
                                        id="persona_id"
                                        class="form-select @error('persona_id') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Seleccione una persona
                                        </option>

                                        @foreach ($candidatos as $candidato)

                                            <option
                                                value="{{ $candidato['persona_id'] }}"
                                                data-tipo="{{ $candidato['tipo'] }}"
                                                data-tipo-label="{{ $candidato['tipo_label'] }}"
                                                data-rol-fijo="{{ $candidato['rol_fijo'] }}"
                                                data-codigo="{{ $candidato['codigo'] }}"
                                                @selected(
                                                    old('persona_id') == $candidato['persona_id']
                                                )
                                            >
                                                {{ $candidato['nombre'] }}
                                                ·
                                                {{ $candidato['documento'] ?: 'Sin documento' }}
                                                ·
                                                {{ $candidato['tipo_label'] }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('persona_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    <div class="form-text">
                                        Solo aparecen estudiantes, docentes y empleados
                                        que todavía no tienen una cuenta de acceso.
                                    </div>

                                </div>


                                {{-- Datos de la cuenta --}}

                                <div
                                    id="datosUsuario"
                                    class="d-none"
                                >

                                    <hr class="my-4">

                                    <h3 class="h6 fw-bold mb-3">
                                        Información de la cuenta
                                    </h3>


                                    <div class="row g-3">

                                        {{-- Código EDMA --}}

                                        <div class="col-md-6">

                                            <label class="form-label fw-semibold">
                                                Código EDMA
                                            </label>

                                            <div
                                                class="form-control bg-light"
                                                id="codigoUsuario"
                                            >
                                                —
                                            </div>

                                            <div class="form-text">
                                                Este código será utilizado para iniciar sesión.
                                            </div>

                                        </div>


                                        {{-- Tipo --}}

                                        <div class="col-md-6">

                                            <label class="form-label fw-semibold">
                                                Tipo
                                            </label>

                                            <div
                                                class="form-control bg-light"
                                                id="tipoUsuario"
                                            >
                                                —
                                            </div>

                                        </div>

                                    </div>


                                    {{-- Rol fijo para estudiante --}}

                                    <div
                                        class="mt-4"
                                        id="rolFijoContainer"
                                    >

                                        <label class="form-label fw-semibold">
                                            Rol
                                        </label>

                                        <div
                                            class="form-control bg-light"
                                            id="rolFijoUsuario"
                                        >
                                            —
                                        </div>

                                        <div class="form-text">
                                            El rol se determina automáticamente
                                            según el expediente de la persona.
                                        </div>

                                    </div>


                                    {{-- Roles para personal --}}

                                    <div
                                        class="mt-4 d-none"
                                        id="rolesPersonalContainer"
                                    >

                                        <label class="form-label fw-semibold">
                                            Roles de acceso
                                        </label>

                                        <div
                                            class="border rounded-3 p-3"
                                            id="rolesPersonalLista"
                                        >

                                            @foreach ($rolesPersonal as $rol)

                                                <div
                                                    class="form-check mb-2 rol-personal-option"
                                                    data-rol-nombre="{{ $rol->nombre }}"
                                                >

                                                    <input
                                                        class="form-check-input rol-personal-checkbox"
                                                        type="checkbox"
                                                        name="roles[]"
                                                        value="{{ $rol->id }}"
                                                        id="rol_{{ $rol->id }}"
                                                        @checked(
                                                            in_array(
                                                                $rol->id,
                                                                old('roles', [])
                                                            )
                                                        )
                                                    >

                                                    <label
                                                        class="form-check-label"
                                                        for="rol_{{ $rol->id }}"
                                                    >
                                                        {{ $rol->nombre }}
                                                    </label>

                                                </div>

                                            @endforeach

                                        </div>

                                        @error('roles')

                                            <div class="text-danger small mt-2">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                        @error('roles.*')

                                            <div class="text-danger small mt-2">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                        <div
                                            class="form-text"
                                            id="rolesPersonalAyuda"
                                        >
                                            Seleccione al menos un rol para esta cuenta.
                                        </div>

                                    </div>


                                    {{-- Contraseña temporal --}}

                                    <div class="mt-4 p-3 bg-light rounded-3">

                                        <div class="d-flex gap-3 align-items-start">

                                            <i class="bi bi-key fs-5"></i>

                                            <div>

                                                <div class="fw-semibold mb-1">
                                                    Contraseña temporal
                                                </div>

                                                <p class="small text-muted mb-0">
                                                    El sistema generará automáticamente
                                                    una contraseña temporal segura.
                                                    El usuario deberá establecer una
                                                    contraseña personal cuando inicie
                                                    sesión por primera vez.
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                            @else

                                {{-- Sin candidatos --}}

                                <div class="text-center py-5">

                                    <i
                                        class="bi bi-person-check fs-1 text-muted d-block mb-3"
                                    ></i>

                                    <h3 class="h5">
                                        No hay personas pendientes
                                    </h3>

                                    <p class="text-muted mb-0">
                                        Actualmente no existen estudiantes, docentes
                                        o empleados elegibles que necesiten una
                                        cuenta de usuario.
                                    </p>

                                </div>

                            @endif

                        </div>


                        {{-- Acciones --}}

                        <div class="border-top p-3 d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('portal.usuarios.index') }}"
                                class="btn portal-btn-secondary"
                            >
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver al listado
                            </a>

                            @if ($candidatos->isNotEmpty())

                                <button
                                    type="submit"
                                    class="btn portal-btn-primary"
                                    id="botonCrearUsuario"
                                    disabled
                                >
                                    <i class="bi bi-person-plus me-1"></i>
                                    Crear usuario
                                </button>

                            @endif

                        </div>

                    </form>

                </section>

            </div>

        </div>

    @endif

@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Formulario de creación
    |--------------------------------------------------------------------------
    */

    const personaSelect =
        document.getElementById('persona_id');

    const datosUsuario =
        document.getElementById('datosUsuario');

    const codigoUsuario =
        document.getElementById('codigoUsuario');

    const tipoUsuario =
        document.getElementById('tipoUsuario');

    const rolFijoContainer =
        document.getElementById('rolFijoContainer');

    const rolFijoUsuario =
        document.getElementById('rolFijoUsuario');

    const rolesPersonalContainer =
        document.getElementById('rolesPersonalContainer');

    const rolesPersonalAyuda =
        document.getElementById('rolesPersonalAyuda');

    const botonCrear =
        document.getElementById('botonCrearUsuario');

    const rolesCheckboxes =
        document.querySelectorAll(
            '.rol-personal-checkbox'
        );

    const rolOptions =
        document.querySelectorAll(
            '.rol-personal-option'
        );


    function desmarcarRoles() {

        rolesCheckboxes.forEach(
            function (checkbox) {
                checkbox.checked = false;
            }
        );
    }


    function contarRolesSeleccionados() {

        return Array.from(
            rolesCheckboxes
        ).filter(
            checkbox => checkbox.checked
        ).length;
    }


    function actualizarBotonCrear() {

        if (! personaSelect || ! botonCrear) {
            return;
        }

        const option =
            personaSelect.options[
                personaSelect.selectedIndex
            ];

        if (! option.value) {

            botonCrear.disabled = true;

            return;
        }


        const tipo =
            option.dataset.tipo;


        /*
        |--------------------------------------------------------------------------
        | Estudiante
        |--------------------------------------------------------------------------
        */

        if (tipo === 'estudiante') {

            botonCrear.disabled = false;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Personal
        |--------------------------------------------------------------------------
        */

        botonCrear.disabled =
            contarRolesSeleccionados() === 0;
    }


    function configurarRolesPorTipo(tipo) {

        rolOptions.forEach(
            function (option) {

                const nombreRol =
                    option.dataset.rolNombre;

                /*
                |--------------------------------------------------------------------------
                | Docente
                |--------------------------------------------------------------------------
                |
                | Puede seleccionar:
                | - Docente
                | - Administrador
                |
                */

                if (tipo === 'docente') {

                    option.classList.remove('d-none');

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Empleado no docente
                |--------------------------------------------------------------------------
                |
                | Por ahora solamente Administrador.
                |
                */

                if (tipo === 'empleado') {

                    if (nombreRol === 'Administrador') {

                        option.classList.remove('d-none');

                    } else {

                        option.classList.add('d-none');

                        const checkbox =
                            option.querySelector(
                                '.rol-personal-checkbox'
                            );

                        if (checkbox) {
                            checkbox.checked = false;
                        }
                    }

                    return;
                }


                option.classList.add('d-none');

            }
        );
    }


    function actualizarFormulario(
        conservarRoles = false
    ) {

        if (! personaSelect) {
            return;
        }


        const option =
            personaSelect.options[
                personaSelect.selectedIndex
            ];


        if (! option.value) {

            if (datosUsuario) {
                datosUsuario.classList.add('d-none');
            }

            if (botonCrear) {
                botonCrear.disabled = true;
            }

            return;
        }


        const tipo =
            option.dataset.tipo;

        const tipoLabel =
            option.dataset.tipoLabel;

        const codigo =
            option.dataset.codigo;

        const rolFijo =
            option.dataset.rolFijo;


        if (datosUsuario) {
            datosUsuario.classList.remove('d-none');
        }


        if (codigoUsuario) {

            codigoUsuario.textContent =
                codigo || 'Sin código EDMA';
        }


        if (tipoUsuario) {

            tipoUsuario.textContent =
                tipoLabel || '—';
        }


        /*
        |--------------------------------------------------------------------------
        | Estudiante
        |--------------------------------------------------------------------------
        */

        if (tipo === 'estudiante') {

            if (! conservarRoles) {
                desmarcarRoles();
            }

            if (rolesPersonalContainer) {
                rolesPersonalContainer.classList.add('d-none');
            }

            if (rolFijoContainer) {
                rolFijoContainer.classList.remove('d-none');
            }

            if (rolFijoUsuario) {
                rolFijoUsuario.textContent =
                    rolFijo || 'Estudiante';
            }

            actualizarBotonCrear();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Docente / Empleado
        |--------------------------------------------------------------------------
        */

        if (! conservarRoles) {
            desmarcarRoles();
        }


        if (rolFijoContainer) {
            rolFijoContainer.classList.add('d-none');
        }


        if (rolesPersonalContainer) {
            rolesPersonalContainer.classList.remove('d-none');
        }


        configurarRolesPorTipo(
            tipo
        );


        if (rolesPersonalAyuda) {

            if (tipo === 'docente') {

                rolesPersonalAyuda.textContent =
                    'Seleccione Docente, Administrador o ambos según las funciones que tendrá esta persona.';

            } else {

                rolesPersonalAyuda.textContent =
                    'Seleccione el rol que tendrá este empleado.';
            }
        }


        actualizarBotonCrear();
    }


    if (personaSelect) {

        personaSelect.addEventListener(
            'change',
            function () {

                actualizarFormulario(
                    false
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Restaurar old()
        |--------------------------------------------------------------------------
        */

        if (personaSelect.value) {

            actualizarFormulario(
                true
            );
        }
    }


    rolesCheckboxes.forEach(
        function (checkbox) {

            checkbox.addEventListener(
                'change',
                actualizarBotonCrear
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Copiar Código EDMA
    |--------------------------------------------------------------------------
    */

    const copiarCodigoUsuario =
        document.getElementById(
            'copiarCodigoUsuario'
        );


    if (copiarCodigoUsuario) {

        copiarCodigoUsuario.addEventListener(
            'click',
            async function () {

                const codigo =
                    document.getElementById(
                        'codigoUsuarioCreado'
                    ).value;

                await navigator.clipboard.writeText(
                    codigo
                );

                this.innerHTML =
                    '<i class="bi bi-check-lg"></i> Copiado';
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Copiar contraseña
    |--------------------------------------------------------------------------
    */

    const copiarPasswordUsuario =
        document.getElementById(
            'copiarPasswordUsuario'
        );


    if (copiarPasswordUsuario) {

        copiarPasswordUsuario.addEventListener(
            'click',
            async function () {

                const password =
                    document.getElementById(
                        'passwordUsuarioCreado'
                    ).value;

                await navigator.clipboard.writeText(
                    password
                );

                this.innerHTML =
                    '<i class="bi bi-check-lg"></i> Copiada';
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Copiar todas las credenciales
    |--------------------------------------------------------------------------
    */

    const copiarCredencialesUsuario =
        document.getElementById(
            'copiarCredencialesUsuario'
        );


    if (copiarCredencialesUsuario) {

        copiarCredencialesUsuario.addEventListener(
            'click',
            async function () {

                const codigo =
                    document.getElementById(
                        'codigoUsuarioCreado'
                    ).value;

                const password =
                    document.getElementById(
                        'passwordUsuarioCreado'
                    ).value;

                const credenciales =
                    `Código EDMA: ${codigo}\nContraseña temporal: ${password}`;

                await navigator.clipboard.writeText(
                    credenciales
                );

                this.innerHTML =
                    '<i class="bi bi-check-lg"></i> Credenciales copiadas';
            }
        );
    }

});
</script>

@endpush