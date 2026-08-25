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
                Consulte la planificación académica, matrícula,
                carga de calificaciones y grupos asociados al período.
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

    @php
        $estadoEtiqueta = match ($periodo->estado) {
            'planificado' => 'Planificado',
            'matricula_abierta' => 'Matrícula abierta',
            'en_curso' => 'En curso',
            'finalizado' => 'Finalizado',
            'cancelado' => 'Cancelado',
            default => 'Estado no definido',
        };

        $estadoActivo = in_array(
            $periodo->estado,
            [
                'matricula_abierta',
                'en_curso',
            ],
            true
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

                        <span
                            class="portal-status-badge
                                {{ $estadoActivo
                                    ? 'portal-status-active'
                                    : 'portal-status-inactive' }}"
                        >
                            <span></span>

                            {{ $estadoEtiqueta }}
                        </span>

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
                        <span>Estado</span>

                        <strong>
                            {{ $estadoEtiqueta }}
                        </strong>
                    </div>

                </div>

            </section>

            <section class="portal-card portal-profile-actions-card">

                <div class="portal-card-header">

                    <div>
                        <h2>Acciones</h2>

                        <p>
                            Operaciones disponibles.
                        </p>
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

                            <small>
                                Actualizar fechas, estado y configuración
                            </small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                </div>

            </section>

        </div>

        {{-- Principal --}}
        <div class="col-12 col-xl-8">

            {{-- Información general --}}
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
                            {{ $estadoEtiqueta }}
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

            {{-- Carga de calificaciones --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-journal-check"></i>
                    </div>

                    <div>
                        <h2>Carga de calificaciones</h2>

                        <p>
                            Ventana establecida para el registro y
                            modificación de calificaciones finales.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Inicio de carga</span>

                        <strong>
                            @if ($periodo->calificaciones_desde)

                                {{ $periodo->calificaciones_desde
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y, h:i A'
                                    ) }}

                            @else

                                No definida

                            @endif
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Fecha y hora límite</span>

                        <strong>
                            @if ($periodo->calificaciones_hasta)

                                {{ $periodo->calificaciones_hasta
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y, h:i A'
                                    ) }}

                            @else

                                No definida

                            @endif
                        </strong>

                    </div>

                </div>

                <div class="mt-3">

                    <div class="portal-inline-notice">

                        <i
                            class="bi
                                {{ $periodo->carga_calificaciones_abierta
                                    ? 'bi-unlock'
                                    : 'bi-lock' }}"
                        ></i>

                        <div>

                            <strong>
                                @if (
                                    !$periodo->calificaciones_desde ||
                                    !$periodo->calificaciones_hasta
                                )

                                    Ventana no configurada

                                @elseif (
                                    $periodo->carga_calificaciones_abierta
                                )

                                    Carga de calificaciones habilitada

                                @elseif (
                                    now()->lt(
                                        $periodo->calificaciones_desde
                                    )
                                )

                                    Carga de calificaciones pendiente

                                @else

                                    Carga de calificaciones finalizada

                                @endif
                            </strong>

                            <span>
                                @if (
                                    !$periodo->calificaciones_desde ||
                                    !$periodo->calificaciones_hasta
                                )

                                    Administración todavía no ha definido
                                    la ventana de carga de calificaciones
                                    para este período.

                                @elseif (
                                    $periodo->carga_calificaciones_abierta
                                )

                                    Los docentes se encuentran actualmente
                                    dentro de la ventana autorizada para
                                    registrar y modificar calificaciones.

                                @elseif (
                                    now()->lt(
                                        $periodo->calificaciones_desde
                                    )
                                )

                                    La ventana de carga se encuentra
                                    configurada, pero todavía no ha iniciado.

                                @else

                                    La fecha y hora límite establecida
                                    para la carga de calificaciones
                                    ya finalizó.

                                @endif
                            </span>

                        </div>

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
                                    now()->lt(
                                        $periodo->fecha_inicio
                                    )
                                )

                                    Próximo

                                @else

                                    Finalizado

                                @endif

                            </strong>

                            <small>
                                La situación se determina según
                                las fechas académicas configuradas.
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

                                @switch($grupo->estado)

                                    @case('activo')

                                        <span class="portal-status-badge portal-status-active">
                                            <span></span>
                                            Activo
                                        </span>

                                        @break

                                    @case('planificado')

                                        <span class="portal-status-badge portal-status-inactive">
                                            <span></span>
                                            Planificado
                                        </span>

                                        @break

                                    @case('finalizado')

                                        <span class="portal-status-badge portal-status-inactive">
                                            <span></span>
                                            Finalizado
                                        </span>

                                        @break

                                    @case('cancelado')

                                        <span class="portal-status-badge portal-status-inactive">
                                            <span></span>
                                            Cancelado
                                        </span>

                                        @break

                                    @default

                                        <span class="portal-status-badge portal-status-inactive">
                                            <span></span>
                                            Estado no definido
                                        </span>

                                @endswitch

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

@endsection