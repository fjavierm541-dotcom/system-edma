@extends('layouts.portal')

@section('title', $docente->codigo_docente . ' | Portal EDMA')

@section('page-title', 'Perfil docente')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>
                {{ $docente->empleado->persona->nombre_completo }}
            </h1>

            <p>
                Consulte la información docente, laboral y académica
                asociada al perfil.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.docentes.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route('portal.docentes.edit', $docente) }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-pencil-square"></i>
                Editar perfil
            </a>

        </div>

    </div>

@endsection

@section('content')

    @php
        $empleado = $docente->empleado;
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
                                {{ $persona->iniciales ?: 'DO' }}
                            </span>

                        @endif

                    </div>

                    <h2>
                        {{ $persona->nombre_completo }}
                    </h2>

                    <span class="portal-teacher-code mt-2">
                        {{ $docente->codigo_docente }}
                    </span>

                    <div class="mt-3">

                        @if ($docente->estado === 'activo')

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Docente activo
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Docente inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Inicio de docencia</span>

                        <strong>
                            {{ $docente->fecha_inicio_docencia
                                ? $docente->fecha_inicio_docencia
                                    ->translatedFormat('d M Y')
                                : 'No registrada' }}
                        </strong>
                    </div>

                    <div>
                        <span>Especialidad</span>

                        <strong>
                            {{ $docente->especialidad
                                ?: 'No especificada' }}
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
                            'portal.docentes.edit',
                            $docente
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>
                            <strong>Editar perfil docente</strong>
                            <small>Actualizar información académica</small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a
                        href="{{ route(
                            'portal.empleados.show',
                            $empleado
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-briefcase"></i>
                        </span>

                        <div>
                            <strong>Ver expediente laboral</strong>

                            <small>
                                {{ $empleado->codigo_empleado }}
                            </small>
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

                    <button
                        type="button"
                        class="portal-profile-action portal-profile-action-button"
                        data-bs-toggle="modal"
                        data-bs-target="#changeTeacherStatusModal"
                    >
                        <span>
                            <i class="bi bi-person-gear"></i>
                        </span>

                        <div>
                            <strong>
                                {{ $docente->estado === 'activo'
                                    ? 'Desactivar docente'
                                    : 'Activar docente' }}
                            </strong>

                            <small>
                                {{ $docente->estado === 'activo'
                                    ? 'Conservará su historial docente'
                                    : 'Habilitar nuevamente el perfil' }}
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

            {{-- Información docente --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-easel"></i>
                    </div>

                    <div>
                        <h2>Información docente</h2>

                        <p>
                            Información propia de su función académica.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Código docente</span>

                        <strong>
                            {{ $docente->codigo_docente }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Especialidad</span>

                        <strong>
                            {{ $docente->especialidad
                                ?: 'No especificada' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Fecha de inicio de docencia</span>

                        <strong>
                            {{ $docente->fecha_inicio_docencia
                                ? $docente->fecha_inicio_docencia
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No registrada' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Estado docente</span>

                        <strong>
                            {{ str($docente->estado)->title() }}
                        </strong>

                    </div>

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Observaciones</span>

                        <strong>
                            {{ $docente->observaciones
                                ?: 'Sin observaciones registradas' }}
                        </strong>

                    </div>

                </div>

            </section>

            {{-- Información laboral --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>

                    <div>
                        <h2>Información laboral</h2>

                        <p>
                            Datos provenientes del expediente de empleado.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Código de empleado</span>

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

                        <span>Estado laboral</span>

                        <strong>
                            {{ str($empleado->estado)->title() }}
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

                    </div>

                    <div class="portal-detail-item">

                        <span>País de residencia</span>

                        <strong>
                            {{ $persona->paisResidencia?->nombre
                                ?: 'No especificado' }}
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
                                en el expediente de la persona.
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
                            La formación académica puede gestionarse
                            desde el expediente laboral del empleado.
                        </p>

                        <a
                            href="{{ route(
                                'portal.empleados.show',
                                $empleado
                            ) }}"
                            class="btn portal-btn-secondary mt-3"
                        >
                            <i class="bi bi-briefcase"></i>
                            Ir al expediente laboral
                        </a>

                    </div>

                @endif

            </section>

            {{-- Asignaciones académicas --}}
            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div>
                        <h2>Asignaciones académicas</h2>

                        <p>
                            Grupos y horarios asignados al docente.
                        </p>
                    </div>

                </div>

                <div class="portal-empty-state portal-empty-state-documents">

                    <div class="portal-empty-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    <h3>Asignaciones pendientes</h3>

                    <p>
                        Esta sección se habilitará cuando desarrollemos
                        los módulos de grupos, horarios y asignación docente.
                    </p>

                </div>

            </section>

        </div>

    </div>

    {{-- Modal de cambio de estado --}}
    <div
        class="modal fade"
        id="changeTeacherStatusModal"
        tabindex="-1"
        aria-labelledby="changeTeacherStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    action="{{ route(
                        'portal.docentes.cambiar-estado',
                        $docente
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
                                id="changeTeacherStatusModalLabel"
                            >
                                {{ $docente->estado === 'activo'
                                    ? 'Desactivar docente'
                                    : 'Activar docente' }}
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

                            @if ($docente->estado === 'activo')

                                ¿Desea desactivar a

                                <strong>
                                    {{ $persona->nombre_completo }}
                                </strong>

                                como docente? Su historial académico
                                permanecerá disponible.

                            @else

                                ¿Desea activar nuevamente a

                                <strong>
                                    {{ $persona->nombre_completo }}
                                </strong>

                                como docente?

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
                                {{ $docente->estado === 'activo'
                                    ? 'portal-btn-danger'
                                    : 'portal-btn-primary' }}"
                        >
                            {{ $docente->estado === 'activo'
                                ? 'Desactivar'
                                : 'Activar' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection