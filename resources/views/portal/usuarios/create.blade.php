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

                        {{-- =================================================
                             Persona
                             ================================================= --}}

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


                        {{-- =================================================
                             Información de acceso
                             ================================================= --}}

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


                            {{-- Rol --}}
                            <div class="col-md-6">

                                <label class="form-label text-muted small">
                                    Rol
                                </label>

                                <div class="form-control bg-light">
                                    {{ $usuarioCreado['rol'] }}
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


                        {{-- =================================================
                             Aviso de primer acceso
                             ================================================= --}}

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


                        {{-- =================================================
                             Acciones
                             ================================================= --}}

                        <div
                            class="d-flex flex-wrap justify-content-end gap-2 mt-4"
                        >

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

                                {{-- =============================================
                                     Persona
                                     ============================================= --}}

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
                                                data-rol="{{ $candidato['rol'] }}"
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


                                {{-- =============================================
                                     Datos de la cuenta
                                     ============================================= --}}

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


                                    {{-- =========================================
                                         Rol automático
                                         ========================================= --}}

                                    <div
                                        class="mt-4"
                                        id="rolAutomaticoContainer"
                                    >

                                        <label class="form-label fw-semibold">
                                            Rol
                                        </label>

                                        <div
                                            class="form-control bg-light"
                                            id="rolAutomatico"
                                        >
                                            —
                                        </div>

                                        <div class="form-text">
                                            El rol se determina automáticamente según
                                            el expediente de la persona.
                                        </div>

                                    </div>


                                    {{-- =========================================
                                         Rol administrativo
                                         ========================================= --}}

                                    <div
                                        class="mt-4 d-none"
                                        id="rolAdministrativoContainer"
                                    >

                                        <label
                                            for="rol_id"
                                            class="form-label fw-semibold"
                                        >
                                            Rol
                                        </label>

                                        <select
                                            name="rol_id"
                                            id="rol_id"
                                            class="form-select @error('rol_id') is-invalid @enderror"
                                        >

                                            <option value="">
                                                Seleccione un rol
                                            </option>

                                            @foreach ($rolesAdministrativos as $rol)

                                                <option
                                                    value="{{ $rol->id }}"
                                                    @selected(old('rol_id') == $rol->id)
                                                >
                                                    {{ $rol->nombre }}
                                                </option>

                                            @endforeach

                                        </select>

                                        @error('rol_id')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                        <div class="form-text">
                                            Seleccione el tipo de acceso que tendrá
                                            este empleado.
                                        </div>

                                    </div>


                                    {{-- =========================================
                                         Contraseña temporal
                                         ========================================= --}}

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

                                {{-- =============================================
                                     Sin candidatos
                                     ============================================= --}}

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


                        {{-- =============================================
                             Acciones
                             ============================================= --}}

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

    const rolAutomaticoContainer =
        document.getElementById('rolAutomaticoContainer');

    const rolAutomatico =
        document.getElementById('rolAutomatico');

    const rolAdministrativoContainer =
        document.getElementById('rolAdministrativoContainer');

    const rolSelect =
        document.getElementById('rol_id');

    const botonCrear =
        document.getElementById('botonCrearUsuario');


    function actualizarFormulario() {

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

            if (rolSelect) {
                rolSelect.required = false;
            }

            return;
        }


        const tipo =
            option.dataset.tipo;

        const tipoLabel =
            option.dataset.tipoLabel;

        const codigo =
            option.dataset.codigo;

        const rol =
            option.dataset.rol;


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
        | Empleado no docente
        |--------------------------------------------------------------------------
        */

        if (tipo === 'empleado') {

            if (rolAutomaticoContainer) {
                rolAutomaticoContainer.classList.add('d-none');
            }

            if (rolAdministrativoContainer) {
                rolAdministrativoContainer.classList.remove('d-none');
            }

            if (rolSelect) {

                rolSelect.required = true;

                if (botonCrear) {
                    botonCrear.disabled =
                        rolSelect.value === '';
                }
            }

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Estudiante / Docente
        |--------------------------------------------------------------------------
        */

        if (rolAdministrativoContainer) {
            rolAdministrativoContainer.classList.add('d-none');
        }

        if (rolAutomaticoContainer) {
            rolAutomaticoContainer.classList.remove('d-none');
        }

        if (rolAutomatico) {
            rolAutomatico.textContent =
                rol || '—';
        }

        if (rolSelect) {

            rolSelect.required = false;
            rolSelect.value = '';
        }

        if (botonCrear) {
            botonCrear.disabled = false;
        }
    }


    if (personaSelect) {

        personaSelect.addEventListener(
            'change',
            actualizarFormulario
        );

        if (personaSelect.value) {
            actualizarFormulario();
        }
    }


    if (rolSelect) {

        rolSelect.addEventListener(
            'change',
            function () {

                if (! personaSelect) {
                    return;
                }

                const option =
                    personaSelect.options[
                        personaSelect.selectedIndex
                    ];

                if (
                    option.value
                    && option.dataset.tipo === 'empleado'
                    && botonCrear
                ) {
                    botonCrear.disabled =
                        this.value === '';
                }

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Usuario recién creado - Copiar Código EDMA
    |--------------------------------------------------------------------------
    */

    const copiarCodigoUsuario =
        document.getElementById('copiarCodigoUsuario');

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
    | Usuario recién creado - Copiar contraseña
    |--------------------------------------------------------------------------
    */

    const copiarPasswordUsuario =
        document.getElementById('copiarPasswordUsuario');

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
    | Usuario recién creado - Copiar todas las credenciales
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