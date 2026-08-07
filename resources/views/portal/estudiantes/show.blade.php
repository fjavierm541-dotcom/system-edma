@extends('layouts.portal')

@section('title', $estudiante->codigo_estudiante . ' | Portal EDMA')

@section('page-title', 'Expediente estudiantil')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de estudiantes
            </span>

            <h1>{{ $estudiante->persona->nombre_completo }}</h1>

            <p>
                Consulte la información personal, administrativa
                y académica del estudiante.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.estudiantes.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route('portal.estudiantes.edit', $estudiante) }}"
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
        $persona = $estudiante->persona;

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

        {{-- Columna lateral --}}
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
                                {{ $persona->iniciales ?: 'ES' }}
                            </span>

                        @endif

                    </div>

                    <h2>{{ $persona->nombre_completo }}</h2>

                    <span class="portal-student-code mt-2">
                        {{ $estudiante->codigo_estudiante }}
                    </span>

                    <div class="mt-3">

                        @if ($estudiante->estado === 'activo')

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Estudiante activo
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Estudiante inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Fecha de ingreso</span>

                        <strong>
                            {{ $estudiante->fecha_ingreso
                                ? $estudiante->fecha_ingreso
                                    ->translatedFormat('d M Y')
                                : 'No registrada' }}
                        </strong>
                    </div>

                    <div>
                        <span>Escolaridad</span>

                        <strong>
                            {{ $estudiante->nivelEscolaridad?->nombre
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
                            'portal.estudiantes.edit',
                            $estudiante
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>
                            <strong>Editar expediente</strong>
                            <small>Actualizar información estudiantil</small>
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
                        data-bs-target="#changeStudentStatusModal"
                    >
                        <span>
                            <i class="bi bi-person-gear"></i>
                        </span>

                        <div>
                            <strong>
                                {{ $estudiante->estado === 'activo'
                                    ? 'Desactivar estudiante'
                                    : 'Activar estudiante' }}
                            </strong>

                            <small>
                                {{ $estudiante->estado === 'activo'
                                    ? 'Conservará su historial académico'
                                    : 'Habilitar nuevamente el expediente' }}
                            </small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </button>

                </div>

            </section>

        </div>

        {{-- Contenido principal --}}
        <div class="col-12 col-xl-8 col-xxl-9">

            {{-- Información estudiantil --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <div>
                        <h2>Información estudiantil</h2>
                        <p>Datos propios del expediente.</p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">
                        <span>Código institucional</span>

                        <strong>
                            {{ $estudiante->codigo_estudiante }}
                        </strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Fecha de ingreso</span>

                        <strong>
                            {{ $estudiante->fecha_ingreso
                                ? $estudiante->fecha_ingreso
                                    ->translatedFormat('d \d\e F \d\e Y')
                                : 'No registrada' }}
                        </strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Estado</span>

                        <strong>
                            {{ str($estudiante->estado)->title() }}
                        </strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Nivel de escolaridad</span>

                        <strong>
                            {{ $estudiante->nivelEscolaridad?->nombre
                                ?: 'No especificado' }}
                        </strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Profesión u ocupación</span>

                        <strong>
                            {{ $estudiante->profesion_ocupacion
                                ?: 'No especificada' }}
                        </strong>
                    </div>

                    <div class="portal-detail-item portal-detail-item-full">
                        <span>Observaciones</span>

                        <strong>
                            {{ $estudiante->observaciones
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
                        <p>Información proveniente del módulo Personas.</p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">
                        <span>Nombre completo</span>
                        <strong>{{ $persona->nombre_completo }}</strong>
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
                                    ->translatedFormat('d \d\e F \d\e Y')
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
                            <a href="mailto:{{ $persona->correo_personal }}">
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

                </div>

            </section>

            {{-- Responsables --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <div>
                        <h2>Responsables</h2>
                        <p>Personas responsables asociadas al estudiante.</p>
                    </div>

                </div>

                @if ($estudiante->responsables->isNotEmpty())

                    <div class="portal-responsible-list">

                        @foreach ($estudiante->responsables as $responsable)

                            @php
                                $personaResponsable =
                                    $responsable->personaResponsable;
                            @endphp

                            <article class="portal-responsible-item">

                                <div class="portal-responsible-avatar">
                                    {{ $personaResponsable?->iniciales ?: 'RP' }}
                                </div>

                                <div class="portal-responsible-info">

                                    <strong>
                                        {{ $personaResponsable?->nombre_completo
                                            ?: 'Persona no disponible' }}
                                    </strong>

                                    <span>
                                        {{ $responsable->parentesco
                                            ? str($responsable->parentesco)
                                                ->replace('_', ' ')
                                                ->title()
                                            : 'Parentesco no especificado' }}
                                    </span>

                                    @if ($personaResponsable?->telefono_movil)
                                        <small>
                                            {{ $personaResponsable->telefono_movil }}
                                        </small>
                                    @endif

                                </div>

                                <div class="portal-responsible-badges">

                                    @if ($responsable->es_principal)
                                        <span class="portal-status-badge portal-status-active">
                                            Principal
                                        </span>
                                    @endif

                                    @if ($responsable->recibe_notificaciones)
                                        <span class="portal-small-badge">
                                            Recibe notificaciones
                                        </span>
                                    @endif

                                </div>

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-person-exclamation"></i>
                        </div>

                        <h3>No hay responsables registrados</h3>

                        <p>
                            Los responsables se agregarán cuando corresponda,
                            especialmente para estudiantes menores de edad.
                        </p>

                    </div>

                @endif

            </section>

            {{-- Secciones académicas futuras --}}
            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>

                    <div>
                        <h2>Trayectoria académica</h2>
                        <p>Matrículas, niveles, grupos y calificaciones.</p>
                    </div>

                </div>

                <div class="portal-empty-state portal-empty-state-documents">

                    <div class="portal-empty-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    <h3>Información académica pendiente</h3>

                    <p>
                        Esta sección se habilitará cuando se desarrollen
                        los módulos de matrículas, grupos y evaluaciones.
                    </p>

                </div>

            </section>

        </div>

    </div>

    {{-- Modal de estado --}}
    <div
        class="modal fade"
        id="changeStudentStatusModal"
        tabindex="-1"
        aria-labelledby="changeStudentStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    action="{{ route(
                        'portal.estudiantes.cambiar-estado',
                        $estudiante
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
                                id="changeStudentStatusModalLabel"
                            >
                                {{ $estudiante->estado === 'activo'
                                    ? 'Desactivar estudiante'
                                    : 'Activar estudiante' }}
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

                            @if ($estudiante->estado === 'activo')

                                ¿Desea desactivar a
                                <strong>{{ $persona->nombre_completo }}</strong>?
                                Su historial académico permanecerá disponible.

                            @else

                                ¿Desea activar nuevamente a
                                <strong>{{ $persona->nombre_completo }}</strong>?

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
                                {{ $estudiante->estado === 'activo'
                                    ? 'portal-btn-danger'
                                    : 'portal-btn-primary' }}"
                        >
                            {{ $estudiante->estado === 'activo'
                                ? 'Desactivar'
                                : 'Activar' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection