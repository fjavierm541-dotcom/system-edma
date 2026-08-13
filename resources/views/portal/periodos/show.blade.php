@extends('layouts.portal')

@section('title', $periodo->nombre . ' | Portal EDMA')

@section('page-title', 'Período académico')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>{{ $periodo->nombre }}</h1>

            <p>
                Consulte las fechas de matrícula, desarrollo académico
                y los grupos asociados al período.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.periodos.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route(
                    'portal.periodos.edit',
                    $periodo
                ) }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-pencil-square"></i>
                Editar período
            </a>

        </div>

    </div>

@endsection

@section('content')

    <div class="row g-4">

        {{-- Lateral --}}
        <div class="col-12 col-xl-4">

            <section class="portal-card portal-profile-card">

                <div class="portal-profile-cover"></div>

                <div class="portal-profile-content">

                    <div class="portal-profile-photo">
                        <span>
                            <i class="bi bi-calendar3"></i>
                        </span>
                    </div>

                    <h2>
                        {{ $periodo->nombre }}
                    </h2>

                    <span class="portal-employee-code mt-2">
                        {{ $periodo->codigo }}
                    </span>

                    <div class="mt-3">

                        @if ($periodo->estado === 'activo')

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Período activo
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Período inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Grupos</span>

                        <strong>
                            {{ $periodo->grupos->count() }}
                        </strong>
                    </div>

                    <div>
                        <span>Situación</span>

                        <strong>
                            @if ($periodo->matricula_abierta)
                                Matrícula abierta
                            @elseif ($periodo->en_curso)
                                En curso
                            @elseif (
                                $periodo->fecha_inicio &&
                                now()->lt($periodo->fecha_inicio)
                            )
                                Próximo
                            @else
                                Finalizado
                            @endif
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
                            'portal.periodos.edit',
                            $periodo
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>
                            <strong>Editar período</strong>
                            <small>Actualizar fechas y configuración</small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <button
                        type="button"
                        class="portal-profile-action portal-profile-action-button"
                        data-bs-toggle="modal"
                        data-bs-target="#changePeriodStatusModal"
                    >
                        <span>
                            <i class="bi bi-toggle-on"></i>
                        </span>

                        <div>
                            <strong>
                                {{ $periodo->estado === 'activo'
                                    ? 'Desactivar período'
                                    : 'Activar período' }}
                            </strong>

                            <small>
                                Los registros asociados se conservarán.
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
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h2>Información general</h2>

                        <p>
                            Fechas y configuración principal del período.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Código institucional</span>

                        <strong>
                            {{ $periodo->codigo }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Estado</span>

                        <strong>
                            {{ str($periodo->estado)->title() }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Inicio de matrícula</span>

                        <strong>
                            {{ $periodo->fecha_inicio_matricula
                                ? $periodo->fecha_inicio_matricula
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No definida' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Cierre de matrícula</span>

                        <strong>
                            {{ $periodo->fecha_fin_matricula
                                ? $periodo->fecha_fin_matricula
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No definida' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Inicio académico</span>

                        <strong>
                            {{ $periodo->fecha_inicio
                                ? $periodo->fecha_inicio
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No definida' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Finalización académica</span>

                        <strong>
                            {{ $periodo->fecha_fin
                                ? $periodo->fecha_fin
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No definida' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Observaciones</span>

                        <strong>
                            {{ $periodo->observaciones
                                ?: 'Sin observaciones registradas' }}
                        </strong>

                    </div>

                </div>

            </section>

            {{-- Estado actual --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>

                    <div>
                        <h2>Estado de las fechas</h2>

                        <p>
                            Consulte rápidamente la situación actual
                            del período y su matrícula.
                        </p>
                    </div>

                </div>

                <div class="row g-3">

                    <div class="col-12 col-md-6">

                        <div class="portal-detail-item h-100">

                            <span>Matrícula</span>

                            <strong>
                                {{ $periodo->matricula_abierta
                                    ? 'Abierta'
                                    : 'Cerrada' }}
                            </strong>

                            <small>
                                @if ($periodo->matricula_abierta)
                                    Actualmente se encuentra dentro
                                    de las fechas de matrícula.
                                @elseif (
                                    $periodo->fecha_inicio_matricula &&
                                    now()->lt(
                                        $periodo->fecha_inicio_matricula
                                    )
                                )
                                    La matrícula todavía no ha iniciado.
                                @else
                                    La fecha de matrícula ya finalizó
                                    o no está disponible.
                                @endif
                            </small>

                        </div>

                    </div>

                    <div class="col-12 col-md-6">

                        <div class="portal-detail-item h-100">

                            <span>Desarrollo académico</span>

                            <strong>
                                @if ($periodo->en_curso)
                                    En curso
                                @elseif (
                                    $periodo->fecha_inicio &&
                                    now()->lt($periodo->fecha_inicio)
                                )
                                    Próximo
                                @else
                                    Finalizado
                                @endif
                            </strong>

                            <small>
                                La situación se determina según
                                las fechas configuradas.
                            </small>

                        </div>

                    </div>

                </div>

            </section>

            {{-- Grupos --}}
            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <div>
                        <h2>Grupos del período</h2>

                        <p>
                            Grupos académicos asociados a este período.
                        </p>
                    </div>

                </div>

                @if ($periodo->grupos->isNotEmpty())

                    <div class="portal-academic-list">

                        @foreach ($periodo->grupos as $grupo)

                            <article class="portal-academic-item">

                                <div class="portal-academic-icon">
                                    <i class="bi bi-people"></i>
                                </div>

                                <div class="portal-academic-info">

                                    <strong>
                                        {{ $grupo->nombre }}
                                    </strong>

                                    <span>
                                        {{ $grupo->codigo }}
                                    </span>

                                    <small>
                                        {{ $grupo->nivel?->programa?->nombre }}
                                        · Nivel
                                        {{ $grupo->nivel?->nombre }}
                                        · {{ str($grupo->modalidad)->title() }}
                                    </small>

                                </div>

                                @if ($grupo->estado === 'activo')

                                    <span class="portal-status-badge portal-status-active">
                                        <span></span>
                                        Activo
                                    </span>

                                @else

                                    <span class="portal-status-badge portal-status-inactive">
                                        <span></span>
                                        {{ str($grupo->estado)->title() }}
                                    </span>

                                @endif

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <h3>No hay grupos registrados</h3>

                        <p>
                            Los grupos que se creen para este período
                            aparecerán en esta sección.
                        </p>

                    </div>

                @endif

            </section>

        </div>

    </div>

    {{-- Modal --}}
    <div
        class="modal fade"
        id="changePeriodStatusModal"
        tabindex="-1"
        aria-labelledby="changePeriodStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    action="{{ route(
                        'portal.periodos.cambiar-estado',
                        $periodo
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
                                id="changePeriodStatusModalLabel"
                            >
                                {{ $periodo->estado === 'activo'
                                    ? 'Desactivar período'
                                    : 'Activar período' }}
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
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <p class="mb-0">

                            @if ($periodo->estado === 'activo')

                                ¿Desea desactivar
                                <strong>{{ $periodo->nombre }}</strong>?
                                Los grupos y registros asociados se conservarán.

                            @else

                                ¿Desea activar nuevamente
                                <strong>{{ $periodo->nombre }}</strong>?

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
                                {{ $periodo->estado === 'activo'
                                    ? 'portal-btn-danger'
                                    : 'portal-btn-primary' }}"
                        >
                            {{ $periodo->estado === 'activo'
                                ? 'Desactivar'
                                : 'Activar' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection
