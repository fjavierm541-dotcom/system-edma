@extends('layouts.portal')

@section('title', $empleado->codigo_empleado . ' | Portal EDMA')

@section('page-title', 'Expediente laboral')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de recursos humanos
            </span>

            <h1>{{ $empleado->persona->nombre_completo }}</h1>

            <p>
                Consulte la información personal y laboral
                asociada al empleado.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.empleados.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route('portal.empleados.edit', $empleado) }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-pencil-square"></i>
                Editar expediente
            </a>

        </div>

    </div>

@endsection

@section('content')

    @php
        $persona = $empleado->persona;

        $numeroWhatsapp = null;

        if (
            $persona->telefono_movil &&
            $persona->telefono_movil_whatsapp
        ) {
            $numeroWhatsapp = preg_replace(
                '/\D+/',
                '',
                $persona->telefono_movil
            );

            if (
                strlen($numeroWhatsapp) === 8 &&
                $persona->paisResidencia?->codigo_iso2 === 'HN'
            ) {
                $numeroWhatsapp = '504' . $numeroWhatsapp;
            }
        }
    @endphp

    <div class="row g-4">

        {{-- =====================================================
             Columna lateral
             ===================================================== --}}
        <div class="col-12 col-xl-4 col-xxl-3">

            <section class="portal-card portal-profile-card">

                <div class="portal-profile-cover"></div>

                <div class="portal-profile-content">

                    <div class="portal-profile-photo">

                        @if ($persona->foto_perfil)

                            <img
                                src="{{ asset(
                                    'storage/' . $persona->foto_perfil
                                ) }}"
                                alt="Fotografía de {{ $persona->nombre_completo }}"
                            >

                        @else

                            <span>
                                {{ $persona->iniciales ?: 'EM' }}
                            </span>

                        @endif

                    </div>

                    <h2>{{ $persona->nombre_completo }}</h2>

                    <span class="portal-employee-code mt-2">
                        {{ $empleado->codigo_empleado }}
                    </span>

                    <div class="mt-3">

                        @if ($empleado->estado === 'activo')

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Empleado activo
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Empleado inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Ingreso</span>

                        <strong>
                            {{ $empleado->fecha_ingreso
                                ? $empleado->fecha_ingreso
                                    ->translatedFormat('d M Y')
                                : 'No registrada' }}
                        </strong>
                    </div>

                    <div>
                        <span>Rol adicional</span>

                        <strong>
                            {{ $empleado->docente
                                ? 'Docente'
                                : 'Empleado' }}
                        </strong>
                    </div>

                </div>

            </section>

            {{-- Acciones --}}
            <section class="portal-card portal-profile-actions-card">

                <div class="portal-card-header">

                    <div>
                        <h2>Acciones</h2>
                        <p>Operaciones disponibles.</p>
                    </div>

                </div>

                <div class="portal-profile-actions">

                    <a
                        href="{{ route(
                            'portal.empleados.edit',
                            $empleado
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>
                            <strong>Editar expediente</strong>
                            <small>Actualizar información laboral</small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a
                        href="{{ route(
                            'portal.personas.show',
                            $persona
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-person-vcard"></i>
                        </span>

                        <div>
                            <strong>Ver datos personales</strong>
                            <small>Consultar expediente general</small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    @if ($numeroWhatsapp)

                        <a
                            href="https://wa.me/{{ $numeroWhatsapp }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="portal-profile-action"
                        >
                            <span class="portal-profile-action-success">
                                <i class="bi bi-whatsapp"></i>
                            </span>

                            <div>
                                <strong>Contactar por WhatsApp</strong>
                                <small>{{ $persona->telefono_movil }}</small>
                            </div>

                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>

                    @endif

                    @if ($empleado->docente)

                        <div class="portal-profile-action">

                            <span>
                                <i class="bi bi-easel"></i>
                            </span>

                            <div>
                                <strong>También es docente</strong>

                                <small>
                                    {{ $empleado->docente->codigo_docente }}
                                </small>
                            </div>

                            <i class="bi bi-check-circle"></i>

                        </div>

                    @else

                        <div class="portal-profile-action">

                            <span>
                                <i class="bi bi-easel"></i>
                            </span>

                            <div>
                                <strong>No registrado como docente</strong>

                                <small>
                                    Podrá asociarse desde el módulo Docentes.
                                </small>
                            </div>

                            <i class="bi bi-dash-circle"></i>

                        </div>

                    @endif

                    <button
                        type="button"
                        class="portal-profile-action portal-profile-action-button"
                        data-bs-toggle="modal"
                        data-bs-target="#changeEmployeeStatusModal"
                    >
                        <span>
                            <i class="bi bi-person-gear"></i>
                        </span>

                        <div>
                            <strong>
                                {{ $empleado->estado === 'activo'
                                    ? 'Desactivar empleado'
                                    : 'Activar empleado' }}
                            </strong>

                            <small>
                                {{ $empleado->estado === 'activo'
                                    ? 'Conservará su historial laboral'
                                    : 'Habilitar nuevamente el expediente' }}
                            </small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </button>

                </div>

            </section>

        </div>

        {{-- =====================================================
             Contenido principal
             ===================================================== --}}
        <div class="col-12 col-xl-8 col-xxl-9">

            {{-- Información laboral --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>

                    <div>
                        <h2>Información laboral</h2>

                        <p>
                            Datos propios del expediente del empleado.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Código institucional</span>

                        <strong>
                            {{ $empleado->codigo_empleado }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Fecha de ingreso</span>

                        <strong>
                            {{ $empleado->fecha_ingreso
                                ? $empleado->fecha_ingreso
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No registrada' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Fecha de salida</span>

                        <strong>
                            {{ $empleado->fecha_salida
                                ? $empleado->fecha_salida
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'Relación laboral vigente' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Estado</span>

                        <strong>
                            {{ str($empleado->estado)->title() }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Cantidad de hijos</span>

                        <strong>
                            {{ $empleado->cantidad_hijos ?? 0 }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Institución laboral actual</span>

                        <strong>
                            {{ $empleado->institucion_laboral_actual
                                ?: 'No especificada' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Horario laboral actual</span>

                        <strong>
                            {{ $empleado->horario_laboral_actual
                                ?: 'No especificado' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Observaciones</span>

                        <strong>
                            {{ $empleado->observaciones
                                ?: 'Sin observaciones registradas' }}
                        </strong>

                    </div>

                </div>

            </section>

            {{-- Datos personales --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <div>
                        <h2>Datos personales</h2>

                        <p>
                            Información proveniente del módulo Personas.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Nombre completo</span>

                        <strong>
                            {{ $persona->nombre_completo }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Documento</span>

                        <strong>
                            {{ $persona->numero_documento
                                ?: 'No registrado' }}
                        </strong>

                        @if ($persona->tipo_documento)

                            <small>
                                {{ str($persona->tipo_documento)
                                    ->replace('_', ' ')
                                    ->title() }}
                            </small>

                        @endif

                    </div>

                    <div class="portal-detail-item">

                        <span>RTN</span>

                        <strong>
                            {{ $persona->rtn ?: 'No registrado' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Fecha de nacimiento</span>

                        <strong>
                            {{ $persona->fecha_nacimiento
                                ? $persona->fecha_nacimiento
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No especificada' }}
                        </strong>

                        @if ($persona->fecha_nacimiento)

                            <small>
                                {{ $persona->fecha_nacimiento->age }} años
                            </small>

                        @endif

                    </div>

                    <div class="portal-detail-item">

                        <span>Correo personal</span>

                        @if ($persona->correo_personal)

                            <a
                                href="mailto:{{ $persona->correo_personal }}"
                            >
                                {{ $persona->correo_personal }}
                            </a>

                        @else

                            <strong>No registrado</strong>

                        @endif

                    </div>

                    <div class="portal-detail-item">

                        <span>Teléfono móvil</span>

                        <strong>
                            {{ $persona->telefono_movil
                                ?: 'No registrado' }}
                        </strong>

                        @if (
                            $persona->telefono_movil &&
                            $persona->telefono_movil_whatsapp
                        )

                            <small class="portal-detail-whatsapp">
                                <i class="bi bi-whatsapp"></i>
                                Disponible en WhatsApp
                            </small>

                        @endif

                    </div>

                    <div class="portal-detail-item">

                        <span>País de residencia</span>

                        <strong>
                            {{ $persona->paisResidencia?->nombre
                                ?: 'No especificado' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Dirección</span>

                        <strong>
                            {{ $persona->direccion
                                ?: 'No registrada' }}
                        </strong>

                    </div>

                </div>

            </section>

           {{-- Formación académica --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header portal-section-header-actions">

                    <div class="d-flex align-items-center gap-3">

                        <div class="portal-form-section-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>

                        <div>
                            <h2>Formación académica</h2>

                            <p>
                                Estudios, títulos y certificaciones registrados
                                para esta persona.
                            </p>
                        </div>

                    </div>

                    <button
                        type="button"
                        class="btn portal-btn-secondary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#addAcademicTrainingModal"
                    >
                        <i class="bi bi-plus-circle"></i>
                        Agregar formación
                    </button>

                </div>

                @if ($persona->formacionesAcademicas->isNotEmpty())

                    <div class="portal-academic-list">

                        @foreach (
                            $persona->formacionesAcademicas
                            as $formacion
                        )

                            <article
                                class="portal-academic-item
                                    {{ $formacion->estado !== 'activo'
                                        ? 'portal-academic-item-inactive'
                                        : '' }}"
                            >

                                <div class="portal-academic-icon">
                                    <i class="bi bi-mortarboard"></i>
                                </div>

                                <div class="portal-academic-info">

                                    <div class="d-flex align-items-center flex-wrap gap-2">

                                        <strong>
                                            {{ $formacion->titulo_obtenido
                                                ?: $formacion->nivel_academico }}
                                        </strong>

                                        @if ($formacion->es_principal)

                                            <span class="portal-small-badge">
                                                Principal
                                            </span>

                                        @endif

                                        @if ($formacion->estado !== 'activo')

                                            <span class="portal-status-badge portal-status-inactive">
                                                Inactiva
                                            </span>

                                        @endif

                                    </div>

                                    <span>
                                        {{ $formacion->nivel_academico
                                            ?: 'Nivel no especificado' }}
                                    </span>

                                    <small>
                                        {{ collect([
                                            $formacion->institucion_educativa,
                                            $formacion->pais?->nombre,
                                            $formacion->anio_graduacion,
                                        ])->filter()->implode(' · ') }}
                                    </small>

                                    @if ($formacion->documentoPersona)

                                        <small>
                                            <i class="bi bi-paperclip"></i>

                                            Documento relacionado:
                                            {{ $formacion->documentoPersona->nombre_original
                                                ?? 'Documento adjunto' }}
                                        </small>

                                    @endif

                                </div>

                                <div class="dropdown">

                                    <button
                                        type="button"
                                        class="portal-table-action"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        aria-label="Opciones de formación académica"
                                    >
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editAcademicTrainingModal"
                                                data-action="{{ route(
                                                    'portal.empleados.formaciones-academicas.update',
                                                    [
                                                        $empleado,
                                                        $formacion
                                                    ]
                                                ) }}"
                                                data-nivel="{{ $formacion->nivel_academico }}"
                                                data-titulo="{{ $formacion->titulo_obtenido }}"
                                                data-institucion="{{ $formacion->institucion_educativa }}"
                                                data-pais="{{ $formacion->pais_id }}"
                                                data-anio="{{ $formacion->anio_graduacion }}"
                                                data-documento="{{ $formacion->documento_persona_id }}"
                                                data-principal="{{ $formacion->es_principal ? '1' : '0' }}"
                                                data-estado="{{ $formacion->estado }}"
                                                data-observaciones="{{ $formacion->observaciones }}"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                Editar formación
                                            </button>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>

                                            <form
                                                action="{{ route(
                                                    'portal.empleados.formaciones-academicas.cambiar-estado',
                                                    [
                                                        $empleado,
                                                        $formacion
                                                    ]
                                                ) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="dropdown-item
                                                        {{ $formacion->estado === 'activo'
                                                            ? 'text-warning-emphasis'
                                                            : 'text-success' }}"
                                                >
                                                    <i class="bi
                                                        {{ $formacion->estado === 'activo'
                                                            ? 'bi-toggle-off'
                                                            : 'bi-toggle-on' }}">
                                                    </i>

                                                    {{ $formacion->estado === 'activo'
                                                        ? 'Desactivar formación'
                                                        : 'Activar formación' }}
                                                </button>

                                            </form>

                                        </li>

                                    </ul>

                                </div>

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>

                        <h3>No hay formación académica registrada</h3>

                        <p>
                            Agregue estudios, títulos, certificaciones
                            o formación relevante de la persona.
                        </p>

                        <button
                            type="button"
                            class="btn portal-btn-secondary mt-3"
                            data-bs-toggle="modal"
                            data-bs-target="#addAcademicTrainingModal"
                        >
                            <i class="bi bi-plus-circle"></i>
                            Agregar formación
                        </button>

                    </div>

                @endif

            </section>

            {{-- Cuentas bancarias --}}
<section class="portal-card portal-detail-card">

    <div class="portal-form-section-header portal-section-header-actions">

        <div class="d-flex align-items-center gap-3">

            <div class="portal-form-section-icon">
                <i class="bi bi-bank"></i>
            </div>

            <div>
                <h2>Cuentas bancarias</h2>

                <p>
                    Información financiera vinculada al empleado.
                </p>
            </div>

        </div>

        <button
            type="button"
            class="btn portal-btn-secondary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#addBankAccountModal"
            @disabled($institucionesFinancieras->isEmpty())
        >
            <i class="bi bi-plus-circle"></i>
            Agregar cuenta
        </button>

    </div>

    @if ($empleado->cuentasBancarias->isNotEmpty())

        <div class="portal-bank-list">

            @foreach ($empleado->cuentasBancarias as $cuenta)

                <article
                    class="portal-bank-item
                        {{ !$cuenta->activo
                            ? 'portal-bank-item-inactive'
                            : '' }}"
                >

                    <div class="portal-bank-icon">
                        <i class="bi bi-bank"></i>
                    </div>

                    <div class="portal-bank-info">

                        <div class="d-flex align-items-center flex-wrap gap-2">

                            <strong>
                                {{ $cuenta->institucionFinanciera?->nombre
                                    ?: 'Institución no disponible' }}
                            </strong>

                            @if ($cuenta->es_principal)

                                <span class="portal-small-badge">
                                    Principal
                                </span>

                            @endif

                            @if (!$cuenta->activo)

                                <span class="portal-status-badge portal-status-inactive">
                                    Inactiva
                                </span>

                            @endif

                        </div>

                        <span>
                            {{ str($cuenta->tipo_cuenta)
                                ->replace('_', ' ')
                                ->title() }}
                        </span>

                        <small>
                            Cuenta:
                            {{ $cuenta->numero_cuenta }}

                            @if ($cuenta->moneda)
                                · {{ $cuenta->moneda }}
                            @endif
                        </small>

                        @if ($cuenta->nombre_titular)

                            <small>
                                Titular:
                                {{ $cuenta->nombre_titular }}
                            </small>

                        @endif

                        @if (
                            $cuenta->fecha_inicio ||
                            $cuenta->fecha_fin
                        )

                            <small>
                                Vigencia:

                                {{ $cuenta->fecha_inicio
                                    ? $cuenta->fecha_inicio
                                        ->translatedFormat('d M Y')
                                    : 'Sin fecha inicial' }}

                                —

                                {{ $cuenta->fecha_fin
                                    ? $cuenta->fecha_fin
                                        ->translatedFormat('d M Y')
                                    : 'Actual' }}
                            </small>

                        @endif

                    </div>

                    <div class="dropdown">

                        <button
                            type="button"
                            class="portal-table-action"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            aria-label="Opciones de la cuenta bancaria"
                        >
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editBankAccountModal"

                                    data-action="{{ route(
                                        'portal.empleados.cuentas-bancarias.update',
                                        [
                                            $empleado,
                                            $cuenta
                                        ]
                                    ) }}"

                                    data-institucion="{{ $cuenta->institucion_financiera_id }}"
                                    data-numero="{{ $cuenta->numero_cuenta }}"
                                    data-tipo="{{ $cuenta->tipo_cuenta }}"
                                    data-moneda="{{ $cuenta->moneda }}"
                                    data-titular="{{ $cuenta->nombre_titular }}"
                                    data-principal="{{ $cuenta->es_principal ? '1' : '0' }}"
                                    data-activo="{{ $cuenta->activo ? '1' : '0' }}"
                                    data-inicio="{{ $cuenta->fecha_inicio?->format('Y-m-d') }}"
                                    data-fin="{{ $cuenta->fecha_fin?->format('Y-m-d') }}"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                    Editar cuenta
                                </button>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>

                                <form
                                    action="{{ route(
                                        'portal.empleados.cuentas-bancarias.cambiar-estado',
                                        [
                                            $empleado,
                                            $cuenta
                                        ]
                                    ) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="dropdown-item
                                            {{ $cuenta->activo
                                                ? 'text-warning-emphasis'
                                                : 'text-success' }}"
                                    >
                                        <i class="bi
                                            {{ $cuenta->activo
                                                ? 'bi-toggle-off'
                                                : 'bi-toggle-on' }}">
                                        </i>

                                        {{ $cuenta->activo
                                            ? 'Desactivar cuenta'
                                            : 'Activar cuenta' }}
                                    </button>

                                </form>

                            </li>

                        </ul>

                    </div>

                </article>

            @endforeach

        </div>

    @else

        <div class="portal-empty-state portal-empty-state-documents">

            <div class="portal-empty-icon">
                <i class="bi bi-bank"></i>
            </div>

            <h3>No hay cuentas bancarias registradas</h3>

            <p>
                Agregue la información bancaria utilizada
                para los procesos administrativos del empleado.
            </p>

            @if ($institucionesFinancieras->isNotEmpty())

                <button
                    type="button"
                    class="btn portal-btn-secondary mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#addBankAccountModal"
                >
                    <i class="bi bi-plus-circle"></i>
                    Agregar cuenta
                </button>

            @endif

        </div>

    @endif

</section>

            {{-- Docencia --}}
            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-easel"></i>
                    </div>

                    <div>
                        <h2>Perfil docente</h2>

                        <p>
                            Relación del empleado con el área académica.
                        </p>
                    </div>

                </div>

                @if ($empleado->docente)

                    <div class="portal-detail-grid">

                        <div class="portal-detail-item">

                            <span>Código docente</span>

                            <strong>
                                {{ $empleado->docente->codigo_docente }}
                            </strong>

                        </div>

                        <div class="portal-detail-item">

                            <span>Especialidad</span>

                            <strong>
                                {{ $empleado->docente->especialidad
                                    ?: 'No especificada' }}
                            </strong>

                        </div>

                        <div class="portal-detail-item">

                            <span>Inicio de docencia</span>

                            <strong>
                                {{ $empleado->docente->fecha_inicio_docencia
                                    ? $empleado->docente
                                        ->fecha_inicio_docencia
                                        ->translatedFormat(
                                            'd \d\e F \d\e Y'
                                        )
                                    : 'No registrada' }}
                            </strong>

                        </div>

                        <div class="portal-detail-item">

                            <span>Estado docente</span>

                            <strong>
                                {{ str(
                                    $empleado->docente->estado
                                )->title() }}
                            </strong>

                        </div>

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-easel"></i>
                        </div>

                        <h3>No posee perfil docente</h3>

                        <p>
                            Este empleado podrá convertirse en docente
                            desde el módulo correspondiente cuando aplique.
                        </p>

                    </div>

                @endif

            </section>

        </div>

    </div>


    {{-- Modal para agregar formación académica --}}
<div
    class="modal fade"
    id="addAcademicTrainingModal"
    tabindex="-1"
    aria-labelledby="addAcademicTrainingModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content portal-modal">

            <form
                action="{{ route(
                    'portal.empleados.formaciones-academicas.store',
                    $empleado
                ) }}"
                method="POST"
            >
                @csrf

                <div class="modal-header">

                    <div>
                        <span class="portal-modal-eyebrow">
                            Formación académica
                        </span>

                        <h2
                            class="modal-title"
                            id="addAcademicTrainingModalLabel"
                        >
                            Agregar formación
                        </h2>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-12 col-md-6">

                            <label
                                for="nivel_academico"
                                class="form-label portal-form-label"
                            >
                                Nivel académico
                                <span class="portal-required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nivel_academico"
                                id="nivel_academico"
                                value="{{ old('nivel_academico') }}"
                                class="form-control portal-form-control
                                    @error('nivel_academico') is-invalid @enderror"
                                maxlength="100"
                                placeholder="Ej. Licenciatura, Bachillerato, Certificación"
                                required
                            >

                            @error('nivel_academico')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="titulo_obtenido"
                                class="form-label portal-form-label"
                            >
                                Título obtenido
                            </label>

                            <input
                                type="text"
                                name="titulo_obtenido"
                                id="titulo_obtenido"
                                value="{{ old('titulo_obtenido') }}"
                                class="form-control portal-form-control
                                    @error('titulo_obtenido') is-invalid @enderror"
                                maxlength="180"
                                placeholder="Ej. Licenciado en Lenguas Extranjeras"
                            >

                            @error('titulo_obtenido')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12">

                            <label
                                for="institucion_educativa"
                                class="form-label portal-form-label"
                            >
                                Institución educativa
                            </label>

                            <input
                                type="text"
                                name="institucion_educativa"
                                id="institucion_educativa"
                                value="{{ old('institucion_educativa') }}"
                                class="form-control portal-form-control
                                    @error('institucion_educativa') is-invalid @enderror"
                                maxlength="180"
                            >

                            @error('institucion_educativa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="pais_id"
                                class="form-label portal-form-label"
                            >
                                País
                            </label>

                            <select
                                name="pais_id"
                                id="pais_id"
                                class="form-select portal-form-control
                                    @error('pais_id') is-invalid @enderror"
                            >
                                <option value="">
                                    No especificado
                                </option>

                                @foreach ($paises as $pais)

                                    <option
                                        value="{{ $pais->id }}"
                                        @selected(
                                            (string) old('pais_id')
                                            === (string) $pais->id
                                        )
                                    >
                                        {{ $pais->nombre }}
                                    </option>

                                @endforeach

                            </select>

                            @error('pais_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="anio_graduacion"
                                class="form-label portal-form-label"
                            >
                                Año de graduación
                            </label>

                            <input
                                type="text"
                                name="anio_graduacion"
                                id="anio_graduacion"
                                value="{{ old('anio_graduacion') }}"
                                class="form-control portal-form-control
                                    @error('anio_graduacion') is-invalid @enderror"
                                maxlength="4"
                                inputmode="numeric"
                                pattern="[0-9]{4}"
                                placeholder="{{ now()->year }}"
                            >

                            @error('anio_graduacion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12">

                            <label
                                for="documento_persona_id"
                                class="form-label portal-form-label"
                            >
                                Documento relacionado
                            </label>

                            <select
                                name="documento_persona_id"
                                id="documento_persona_id"
                                class="form-select portal-form-control
                                    @error('documento_persona_id') is-invalid @enderror"
                            >
                                <option value="">
                                    Sin documento relacionado
                                </option>

                                @foreach ($documentosPersona as $documento)

                                    <option
                                        value="{{ $documento->id }}"
                                        @selected(
                                            (string) old(
                                                'documento_persona_id'
                                            )
                                            === (string) $documento->id
                                        )
                                    >
                                        {{ $documento->nombre_original
                                            ?? 'Documento #' . $documento->id }}
                                    </option>

                                @endforeach

                            </select>

                            @error('documento_persona_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="portal-form-help">
                                Solo puede relacionarse con documentos
                                previamente agregados a la persona.
                            </div>

                        </div>

                        <div class="col-12">

                            <div class="portal-responsible-options">

                                <div class="form-check form-switch">

                                    <input
                                        type="checkbox"
                                        name="es_principal"
                                        value="1"
                                        id="academic_es_principal"
                                        class="form-check-input"
                                        @checked(old('es_principal'))
                                    >

                                    <label
                                        for="academic_es_principal"
                                        class="form-check-label"
                                    >
                                        Formación principal
                                    </label>

                                    <small>
                                        Identifica el nivel o título más relevante
                                        para el expediente.
                                    </small>

                                </div>

                            </div>

                        </div>

                        <div class="col-12">

                            <label
                                for="academic_observaciones"
                                class="form-label portal-form-label"
                            >
                                Observaciones
                            </label>

                            <textarea
                                name="observaciones"
                                id="academic_observaciones"
                                rows="3"
                                maxlength="1000"
                                class="form-control portal-form-control
                                    @error('observaciones') is-invalid @enderror"
                            >{{ old('observaciones') }}</textarea>

                            @error('observaciones')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <input
                        type="hidden"
                        name="estado"
                        value="activo"
                    >

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn portal-btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn portal-btn-primary"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Guardar formación
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

{{-- Modal para editar formación académica --}}
<div
    class="modal fade"
    id="editAcademicTrainingModal"
    tabindex="-1"
    aria-labelledby="editAcademicTrainingModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content portal-modal">

            <form
                method="POST"
                id="editAcademicTrainingForm"
            >
                @csrf
                @method('PUT')

                <div class="modal-header">

                    <div>
                        <span class="portal-modal-eyebrow">
                            Formación académica
                        </span>

                        <h2
                            class="modal-title"
                            id="editAcademicTrainingModalLabel"
                        >
                            Editar formación
                        </h2>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_nivel_academico"
                                class="form-label portal-form-label"
                            >
                                Nivel académico
                            </label>

                            <input
                                type="text"
                                name="nivel_academico"
                                id="edit_nivel_academico"
                                maxlength="100"
                                class="form-control portal-form-control"
                                required
                            >

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_titulo_obtenido"
                                class="form-label portal-form-label"
                            >
                                Título obtenido
                            </label>

                            <input
                                type="text"
                                name="titulo_obtenido"
                                id="edit_titulo_obtenido"
                                maxlength="180"
                                class="form-control portal-form-control"
                            >

                        </div>

                        <div class="col-12">

                            <label
                                for="edit_institucion_educativa"
                                class="form-label portal-form-label"
                            >
                                Institución educativa
                            </label>

                            <input
                                type="text"
                                name="institucion_educativa"
                                id="edit_institucion_educativa"
                                maxlength="180"
                                class="form-control portal-form-control"
                            >

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_pais_id"
                                class="form-label portal-form-label"
                            >
                                País
                            </label>

                            <select
                                name="pais_id"
                                id="edit_pais_id"
                                class="form-select portal-form-control"
                            >
                                <option value="">
                                    No especificado
                                </option>

                                @foreach ($paises as $pais)
                                    <option value="{{ $pais->id }}">
                                        {{ $pais->nombre }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_anio_graduacion"
                                class="form-label portal-form-label"
                            >
                                Año de graduación
                            </label>

                            <input
                                type="text"
                                name="anio_graduacion"
                                id="edit_anio_graduacion"
                                maxlength="4"
                                inputmode="numeric"
                                pattern="[0-9]{4}"
                                class="form-control portal-form-control"
                            >

                        </div>

                        <div class="col-12">

                            <label
                                for="edit_documento_persona_id"
                                class="form-label portal-form-label"
                            >
                                Documento relacionado
                            </label>

                            <select
                                name="documento_persona_id"
                                id="edit_documento_persona_id"
                                class="form-select portal-form-control"
                            >
                                <option value="">
                                    Sin documento relacionado
                                </option>

                                @foreach ($documentosPersona as $documento)

                                    <option value="{{ $documento->id }}">
                                        {{ $documento->nombre_original
                                            ?? 'Documento #' . $documento->id }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-12">

                            <div class="portal-responsible-options">

                                <div class="form-check form-switch">

                                    <input
                                        type="checkbox"
                                        name="es_principal"
                                        value="1"
                                        id="edit_academic_es_principal"
                                        class="form-check-input"
                                    >

                                    <label
                                        for="edit_academic_es_principal"
                                        class="form-check-label"
                                    >
                                        Formación principal
                                    </label>

                                </div>

                            </div>

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_academic_estado"
                                class="form-label portal-form-label"
                            >
                                Estado
                            </label>

                            <select
                                name="estado"
                                id="edit_academic_estado"
                                class="form-select portal-form-control"
                            >
                                <option value="activo">
                                    Activa
                                </option>

                                <option value="inactivo">
                                    Inactiva
                                </option>
                            </select>

                        </div>

                        <div class="col-12">

                            <label
                                for="edit_academic_observaciones"
                                class="form-label portal-form-label"
                            >
                                Observaciones
                            </label>

                            <textarea
                                name="observaciones"
                                id="edit_academic_observaciones"
                                rows="3"
                                maxlength="1000"
                                class="form-control portal-form-control"
                            ></textarea>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn portal-btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn portal-btn-primary"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

{{-- Modal para agregar cuenta bancaria --}}
<div
    class="modal fade"
    id="addBankAccountModal"
    tabindex="-1"
    aria-labelledby="addBankAccountModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content portal-modal">

            <form
                action="{{ route(
                    'portal.empleados.cuentas-bancarias.store',
                    $empleado
                ) }}"
                method="POST"
            >
                @csrf

                <div class="modal-header">

                    <div>
                        <span class="portal-modal-eyebrow">
                            Información financiera
                        </span>

                        <h2
                            class="modal-title"
                            id="addBankAccountModalLabel"
                        >
                            Agregar cuenta bancaria
                        </h2>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-12">

                            <label
                                for="institucion_financiera_id"
                                class="form-label portal-form-label"
                            >
                                Institución financiera
                                <span class="portal-required">*</span>
                            </label>

                            <select
                                name="institucion_financiera_id"
                                id="institucion_financiera_id"
                                class="form-select portal-form-control
                                    @error('institucion_financiera_id') is-invalid @enderror"
                                required
                            >
                                <option value="">
                                    Seleccione una institución
                                </option>

                                @foreach ($institucionesFinancieras as $institucion)

                                    <option
                                        value="{{ $institucion->id }}"
                                        @selected(
                                            (string) old(
                                                'institucion_financiera_id'
                                            )
                                            === (string) $institucion->id
                                        )
                                    >
                                        {{ $institucion->nombre }}
                                    </option>

                                @endforeach

                            </select>

                            @error('institucion_financiera_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="numero_cuenta"
                                class="form-label portal-form-label"
                            >
                                Número de cuenta
                                <span class="portal-required">*</span>
                            </label>

                            <input
                                type="text"
                                name="numero_cuenta"
                                id="numero_cuenta"
                                value="{{ old('numero_cuenta') }}"
                                class="form-control portal-form-control
                                    @error('numero_cuenta') is-invalid @enderror"
                                maxlength="50"
                                autocomplete="off"
                                required
                            >

                            @error('numero_cuenta')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="tipo_cuenta"
                                class="form-label portal-form-label"
                            >
                                Tipo de cuenta
                                <span class="portal-required">*</span>
                            </label>

                            <select
                                name="tipo_cuenta"
                                id="tipo_cuenta"
                                class="form-select portal-form-control
                                    @error('tipo_cuenta') is-invalid @enderror"
                                required
                            >
                                <option value="">
                                    Seleccione una opción
                                </option>

                                <option
                                    value="ahorros"
                                    @selected(old('tipo_cuenta') === 'ahorros')
                                >
                                    Cuenta de ahorros
                                </option>

                                <option
                                    value="cheques"
                                    @selected(old('tipo_cuenta') === 'cheques')
                                >
                                    Cuenta de cheques
                                </option>

                                <option
                                    value="corriente"
                                    @selected(old('tipo_cuenta') === 'corriente')
                                >
                                    Cuenta corriente
                                </option>

                                <option
                                    value="otro"
                                    @selected(old('tipo_cuenta') === 'otro')
                                >
                                    Otro tipo
                                </option>
                            </select>

                            @error('tipo_cuenta')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12 col-md-4">

                            <label
                                for="moneda"
                                class="form-label portal-form-label"
                            >
                                Moneda
                                <span class="portal-required">*</span>
                            </label>

                            <select
                                name="moneda"
                                id="moneda"
                                class="form-select portal-form-control
                                    @error('moneda') is-invalid @enderror"
                                required
                            >
                                <option
                                    value="HNL"
                                    @selected(
                                        old('moneda', 'HNL') === 'HNL'
                                    )
                                >
                                    Lempiras (HNL)
                                </option>

                                <option
                                    value="USD"
                                    @selected(old('moneda') === 'USD')
                                >
                                    Dólares (USD)
                                </option>

                                <option
                                    value="EUR"
                                    @selected(old('moneda') === 'EUR')
                                >
                                    Euros (EUR)
                                </option>
                            </select>

                            @error('moneda')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12 col-md-8">

                            <label
                                for="nombre_titular"
                                class="form-label portal-form-label"
                            >
                                Nombre del titular
                                <span class="portal-required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nombre_titular"
                                id="nombre_titular"
                                value="{{ old(
                                    'nombre_titular',
                                    $persona->nombre_completo
                                ) }}"
                                class="form-control portal-form-control
                                    @error('nombre_titular') is-invalid @enderror"
                                maxlength="180"
                                required
                            >

                            @error('nombre_titular')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="fecha_inicio_cuenta"
                                class="form-label portal-form-label"
                            >
                                Fecha de inicio
                            </label>

                            <input
                                type="date"
                                name="fecha_inicio"
                                id="fecha_inicio_cuenta"
                                value="{{ old('fecha_inicio') }}"
                                class="form-control portal-form-control
                                    @error('fecha_inicio') is-invalid @enderror"
                            >

                            @error('fecha_inicio')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="fecha_fin_cuenta"
                                class="form-label portal-form-label"
                            >
                                Fecha de finalización
                            </label>

                            <input
                                type="date"
                                name="fecha_fin"
                                id="fecha_fin_cuenta"
                                value="{{ old('fecha_fin') }}"
                                class="form-control portal-form-control
                                    @error('fecha_fin') is-invalid @enderror"
                            >

                            @error('fecha_fin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="portal-form-help">
                                Déjela vacía si la cuenta continúa vigente.
                            </div>

                        </div>

                        <div class="col-12">

                            <div class="portal-responsible-options">

                                <div class="form-check form-switch">

                                    <input
                                        type="checkbox"
                                        name="es_principal"
                                        value="1"
                                        id="bank_es_principal"
                                        class="form-check-input"
                                        @checked(old('es_principal'))
                                    >

                                    <label
                                        for="bank_es_principal"
                                        class="form-check-label"
                                    >
                                        Cuenta principal
                                    </label>

                                    <small>
                                        Será la cuenta bancaria principal
                                        del empleado.
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                    <input
                        type="hidden"
                        name="activo"
                        value="1"
                    >

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn portal-btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn portal-btn-primary"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Guardar cuenta
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>


{{-- Modal para editar cuenta bancaria --}}
<div
    class="modal fade"
    id="editBankAccountModal"
    tabindex="-1"
    aria-labelledby="editBankAccountModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content portal-modal">

            <form
                method="POST"
                id="editBankAccountForm"
            >
                @csrf
                @method('PUT')

                <div class="modal-header">

                    <div>
                        <span class="portal-modal-eyebrow">
                            Información financiera
                        </span>

                        <h2
                            class="modal-title"
                            id="editBankAccountModalLabel"
                        >
                            Editar cuenta bancaria
                        </h2>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-12">

                            <label
                                for="edit_institucion_financiera_id"
                                class="form-label portal-form-label"
                            >
                                Institución financiera
                            </label>

                            <select
                                name="institucion_financiera_id"
                                id="edit_institucion_financiera_id"
                                class="form-select portal-form-control"
                                required
                            >
                                @foreach ($institucionesFinancieras as $institucion)

                                    <option value="{{ $institucion->id }}">
                                        {{ $institucion->nombre }}
                                    </option>

                                @endforeach
                            </select>

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_numero_cuenta"
                                class="form-label portal-form-label"
                            >
                                Número de cuenta
                            </label>

                            <input
                                type="text"
                                name="numero_cuenta"
                                id="edit_numero_cuenta"
                                maxlength="50"
                                class="form-control portal-form-control"
                                required
                            >

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_tipo_cuenta"
                                class="form-label portal-form-label"
                            >
                                Tipo de cuenta
                            </label>

                            <select
                                name="tipo_cuenta"
                                id="edit_tipo_cuenta"
                                class="form-select portal-form-control"
                                required
                            >
                                <option value="ahorros">
                                    Cuenta de ahorros
                                </option>

                                <option value="cheques">
                                    Cuenta de cheques
                                </option>

                                <option value="corriente">
                                    Cuenta corriente
                                </option>

                                <option value="otro">
                                    Otro tipo
                                </option>
                            </select>

                        </div>

                        <div class="col-12 col-md-4">

                            <label
                                for="edit_moneda"
                                class="form-label portal-form-label"
                            >
                                Moneda
                            </label>

                            <select
                                name="moneda"
                                id="edit_moneda"
                                class="form-select portal-form-control"
                                required
                            >
                                <option value="HNL">
                                    Lempiras (HNL)
                                </option>

                                <option value="USD">
                                    Dólares (USD)
                                </option>

                                <option value="EUR">
                                    Euros (EUR)
                                </option>
                            </select>

                        </div>

                        <div class="col-12 col-md-8">

                            <label
                                for="edit_nombre_titular"
                                class="form-label portal-form-label"
                            >
                                Nombre del titular
                            </label>

                            <input
                                type="text"
                                name="nombre_titular"
                                id="edit_nombre_titular"
                                maxlength="180"
                                class="form-control portal-form-control"
                                required
                            >

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_fecha_inicio_cuenta"
                                class="form-label portal-form-label"
                            >
                                Fecha de inicio
                            </label>

                            <input
                                type="date"
                                name="fecha_inicio"
                                id="edit_fecha_inicio_cuenta"
                                class="form-control portal-form-control"
                            >

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_fecha_fin_cuenta"
                                class="form-label portal-form-label"
                            >
                                Fecha de finalización
                            </label>

                            <input
                                type="date"
                                name="fecha_fin"
                                id="edit_fecha_fin_cuenta"
                                class="form-control portal-form-control"
                            >

                        </div>

                        <div class="col-12">

                            <div class="portal-responsible-options">

                                <div class="form-check form-switch">

                                    <input
                                        type="checkbox"
                                        name="es_principal"
                                        value="1"
                                        id="edit_bank_es_principal"
                                        class="form-check-input"
                                    >

                                    <label
                                        for="edit_bank_es_principal"
                                        class="form-check-label"
                                    >
                                        Cuenta principal
                                    </label>

                                </div>

                                <div class="form-check form-switch">

                                    <input
                                        type="checkbox"
                                        name="activo"
                                        value="1"
                                        id="edit_bank_activo"
                                        class="form-check-input"
                                    >

                                    <label
                                        for="edit_bank_activo"
                                        class="form-check-label"
                                    >
                                        Cuenta activa
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn portal-btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn portal-btn-primary"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

    {{-- =====================================================
         Modal de cambio de estado
         ===================================================== --}}
    <div
        class="modal fade"
        id="changeEmployeeStatusModal"
        tabindex="-1"
        aria-labelledby="changeEmployeeStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    action="{{ route(
                        'portal.empleados.cambiar-estado',
                        $empleado
                    ) }}"
                    method="POST"
                >
                    @csrf
                    @method('PATCH')

                    <div class="modal-header">

                        <div>

                            <span class="portal-modal-eyebrow">
                                Confirmación
                            </span>

                            <h2
                                class="modal-title"
                                id="changeEmployeeStatusModalLabel"
                            >
                                {{ $empleado->estado === 'activo'
                                    ? 'Desactivar empleado'
                                    : 'Activar empleado' }}
                            </h2>

                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"
                        ></button>

                    </div>

                    <div class="modal-body">

                        <div class="portal-modal-warning-icon">
                            <i class="bi bi-person-gear"></i>
                        </div>

                        <p class="mb-0">

                            @if ($empleado->estado === 'activo')

                                ¿Desea desactivar a
                                <strong>
                                    {{ $persona->nombre_completo }}
                                </strong>?
                                Su historial laboral permanecerá disponible.

                            @else

                                ¿Desea activar nuevamente a
                                <strong>
                                    {{ $persona->nombre_completo }}
                                </strong>?

                            @endif

                        </p>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn portal-btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="btn
                                {{ $empleado->estado === 'activo'
                                    ? 'portal-btn-danger'
                                    : 'portal-btn-primary' }}"
                        >
                            {{ $empleado->estado === 'activo'
                                ? 'Desactivar'
                                : 'Activar' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>


    @push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editModal = document.getElementById(
            'editAcademicTrainingModal'
        );

        const createYear = document.getElementById(
            'anio_graduacion'
        );

        const editYear = document.getElementById(
            'edit_anio_graduacion'
        );

        const allowOnlyDigits = (
            element,
            maximumLength
        ) => {
            if (!element) {
                return;
            }

            element.value = element.value
                .replace(/\D/g, '')
                .slice(0, maximumLength);
        };

        createYear?.addEventListener(
            'input',
            () => allowOnlyDigits(createYear, 4)
        );

        editYear?.addEventListener(
            'input',
            () => allowOnlyDigits(editYear, 4)
        );

        if (!editModal) {
            return;
        }

        editModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const form = document.getElementById(
                'editAcademicTrainingForm'
            );

            form.action = button.dataset.action;

            document.getElementById(
                'edit_nivel_academico'
            ).value = button.dataset.nivel || '';

            document.getElementById(
                'edit_titulo_obtenido'
            ).value = button.dataset.titulo || '';

            document.getElementById(
                'edit_institucion_educativa'
            ).value = button.dataset.institucion || '';

            document.getElementById(
                'edit_pais_id'
            ).value = button.dataset.pais || '';

            document.getElementById(
                'edit_anio_graduacion'
            ).value = button.dataset.anio || '';

            document.getElementById(
                'edit_documento_persona_id'
            ).value = button.dataset.documento || '';

            document.getElementById(
                'edit_academic_es_principal'
            ).checked =
                button.dataset.principal === '1';

            document.getElementById(
                'edit_academic_estado'
            ).value =
                button.dataset.estado || 'activo';

            document.getElementById(
                'edit_academic_observaciones'
            ).value =
                button.dataset.observaciones || '';
        });
    });
</script>

@endpush

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editModal = document.getElementById(
            'editBankAccountModal'
        );

        const createStartDate = document.getElementById(
            'fecha_inicio_cuenta'
        );

        const createEndDate = document.getElementById(
            'fecha_fin_cuenta'
        );

        const updateCreateEndDateMinimum = () => {
            if (
                !createStartDate ||
                !createEndDate
            ) {
                return;
            }

            createEndDate.min =
                createStartDate.value || '';
        };

        createStartDate?.addEventListener(
            'change',
            updateCreateEndDateMinimum
        );

        updateCreateEndDateMinimum();

        if (!editModal) {
            return;
        }

        editModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const form = document.getElementById(
                'editBankAccountForm'
            );

            const institution = document.getElementById(
                'edit_institucion_financiera_id'
            );

            const accountNumber = document.getElementById(
                'edit_numero_cuenta'
            );

            const accountType = document.getElementById(
                'edit_tipo_cuenta'
            );

            const currency = document.getElementById(
                'edit_moneda'
            );

            const holder = document.getElementById(
                'edit_nombre_titular'
            );

            const principal = document.getElementById(
                'edit_bank_es_principal'
            );

            const active = document.getElementById(
                'edit_bank_activo'
            );

            const startDate = document.getElementById(
                'edit_fecha_inicio_cuenta'
            );

            const endDate = document.getElementById(
                'edit_fecha_fin_cuenta'
            );

            form.action = button.dataset.action;

            institution.value =
                button.dataset.institucion || '';

            accountNumber.value =
                button.dataset.numero || '';

            accountType.value =
                button.dataset.tipo || '';

            currency.value =
                button.dataset.moneda || 'HNL';

            holder.value =
                button.dataset.titular || '';

            principal.checked =
                button.dataset.principal === '1';

            active.checked =
                button.dataset.activo === '1';

            startDate.value =
                button.dataset.inicio || '';

            endDate.value =
                button.dataset.fin || '';

            endDate.min =
                startDate.value || '';

            startDate.onchange = () => {
                endDate.min =
                    startDate.value || '';
            };
        });
    });
</script>

@endpush


@endsection