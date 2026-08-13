@extends('layouts.portal')

@section('title', $horario->nombre . ' | Portal EDMA')

@section('page-title', 'Horario')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>{{ $horario->nombre }}</h1>

            <p>
                Consulte la franja horaria y los grupos
                donde está siendo utilizada.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.horarios.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route(
                    'portal.horarios.edit',
                    $horario
                ) }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-pencil-square"></i>
                Editar horario
            </a>

        </div>

    </div>

@endsection

@section('content')

    @php
        $horaInicio = \Carbon\Carbon::createFromFormat(
            'H:i:s',
            $horario->hora_inicio
        );

        $horaFin = \Carbon\Carbon::createFromFormat(
            'H:i:s',
            $horario->hora_fin
        );
    @endphp

    <div class="row g-4">

        {{-- Lateral --}}
        <div class="col-12 col-xl-4">

            <section class="portal-card portal-profile-card">

                <div class="portal-profile-cover"></div>

                <div class="portal-profile-content">

                    <div class="portal-profile-photo">
                        <span>
                            <i class="bi bi-clock"></i>
                        </span>
                    </div>

                    <h2>
                        {{ $horario->nombre }}
                    </h2>

                    <span class="portal-employee-code mt-2">
                        {{ $horaInicio->format('g:i A') }}
                        -
                        {{ $horaFin->format('g:i A') }}
                    </span>

                    <div class="mt-3">

                        @if ($horario->activo)

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Horario activo
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Horario inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Duración</span>

                        <strong>
                            {{ $horaInicio->diffInMinutes($horaFin) }}
                            min
                        </strong>
                    </div>

                    <div>
                        <span>Asignaciones</span>

                        <strong>
                            {{ $horario->grupoHorarios->count() }}
                        </strong>
                    </div>

                </div>

            </section>

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
                            'portal.horarios.edit',
                            $horario
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>
                            <strong>Editar horario</strong>
                            <small>
                                Actualizar horas y disponibilidad
                            </small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <button
                        type="button"
                        class="portal-profile-action portal-profile-action-button"
                        data-bs-toggle="modal"
                        data-bs-target="#changeScheduleStatusModal"
                    >
                        <span>
                            <i class="bi bi-toggle-on"></i>
                        </span>

                        <div>
                            <strong>
                                {{ $horario->activo
                                    ? 'Desactivar horario'
                                    : 'Activar horario' }}
                            </strong>

                            <small>
                                {{ $horario->activo
                                    ? 'No estará disponible para nuevas asignaciones'
                                    : 'Volverá a estar disponible' }}
                            </small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </button>

                </div>

            </section>

        </div>

        {{-- Principal --}}
        <div class="col-12 col-xl-8">

            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-clock"></i>
                    </div>

                    <div>
                        <h2>Información del horario</h2>

                        <p>
                            Detalle de la franja horaria registrada.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Nombre</span>

                        <strong>
                            {{ $horario->nombre }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Estado</span>

                        <strong>
                            {{ $horario->activo
                                ? 'Activo'
                                : 'Inactivo' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Hora de inicio</span>

                        <strong>
                            {{ $horaInicio->format('g:i A') }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Hora de finalización</span>

                        <strong>
                            {{ $horaFin->format('g:i A') }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Duración</span>

                        <strong>
                            {{ $horaInicio->diffInMinutes($horaFin) }}
                            minutos
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Zona horaria</span>

                        <strong>
                            Honduras (UTC-6)
                        </strong>

                    </div>

                </div>

            </section>

            {{-- Asignaciones --}}
            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <div>
                        <h2>Grupos que utilizan este horario</h2>

                        <p>
                            Consulte dónde está asignada esta
                            franja horaria.
                        </p>
                    </div>

                </div>

                @if ($horario->grupoHorarios->isNotEmpty())

                    <div class="portal-academic-list">

                        @foreach (
                            $horario->grupoHorarios
                            as $asignacion
                        )

                            @php
                                $grupo = $asignacion->grupo;
                            @endphp

                            <article class="portal-academic-item">

                                <div class="portal-academic-icon">
                                    <i class="bi bi-people"></i>
                                </div>

                                <div class="portal-academic-info">

                                    <strong>
                                        {{ $grupo?->nombre
                                            ?: 'Grupo no disponible' }}
                                    </strong>

                                    <span>
                                        {{ str(
                                            $asignacion->dia_semana
                                        )->title() }}
                                    </span>

                                    @if ($grupo)

                                        <small>
                                            {{ $grupo->nivel?->programa?->nombre }}
                                            · Nivel
                                            {{ $grupo->nivel?->nombre }}

                                            @if ($grupo->periodoAcademico)
                                                ·
                                                {{ $grupo->periodoAcademico->nombre }}
                                            @endif
                                        </small>

                                    @endif

                                </div>

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <h3>Este horario aún no tiene grupos asignados</h3>

                        <p>
                            Las asignaciones aparecerán aquí cuando
                            se configuren los días y horarios de los grupos.
                        </p>

                    </div>

                @endif

            </section>

        </div>

    </div>

    {{-- Modal --}}
    <div
        class="modal fade"
        id="changeScheduleStatusModal"
        tabindex="-1"
        aria-labelledby="changeScheduleStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    action="{{ route(
                        'portal.horarios.cambiar-estado',
                        $horario
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
                                id="changeScheduleStatusModalLabel"
                            >
                                {{ $horario->activo
                                    ? 'Desactivar horario'
                                    : 'Activar horario' }}
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
                            <i class="bi bi-clock"></i>
                        </div>

                        <p class="mb-0">

                            @if ($horario->activo)

                                ¿Desea desactivar
                                <strong>{{ $horario->nombre }}</strong>?
                                Las asignaciones existentes se conservarán.

                            @else

                                ¿Desea activar nuevamente
                                <strong>{{ $horario->nombre }}</strong>?

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
                                {{ $horario->activo
                                    ? 'portal-btn-danger'
                                    : 'portal-btn-primary' }}"
                        >
                            {{ $horario->activo
                                ? 'Desactivar'
                                : 'Activar' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection