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
                                Estudios y títulos registrados
                                para esta persona.
                            </p>
                        </div>

                    </div>

                    <span class="portal-results-count">
                        {{ $persona->formacionesAcademicas->count() }}
                    </span>

                </div>

                @if ($persona->formacionesAcademicas->isNotEmpty())

                    <div class="portal-academic-list">

                        @foreach (
                            $persona->formacionesAcademicas
                            as $formacion
                        )

                            <article class="portal-academic-item">

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

                                </div>

                                @if ($formacion->documentoPersona)

                                    <span
                                        class="portal-small-badge"
                                        title="Tiene documento relacionado"
                                    >
                                        <i class="bi bi-paperclip"></i>
                                        Documento
                                    </span>

                                @endif

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
                            Los estudios, títulos y certificaciones
                            podrán incorporarse posteriormente.
                        </p>

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

                    <span class="portal-results-count">
                        {{ $empleado->cuentasBancarias->count() }}
                    </span>

                </div>

                @if ($empleado->cuentasBancarias->isNotEmpty())

                    <div class="portal-bank-list">

                        @foreach (
                            $empleado->cuentasBancarias
                            as $cuenta
                        )

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
                            La información bancaria del empleado
                            podrá agregarse posteriormente.
                        </p>

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

@endsection