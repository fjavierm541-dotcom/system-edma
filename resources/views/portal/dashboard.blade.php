@extends('layouts.portal')

@section('title', 'Dashboard | Portal EDMA')

@section('page-title', 'Dashboard')

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Resumen general
            </span>

            <h1>
                Bienvenido al Portal EDMA
            </h1>

            <p>
                Consulte el estado general de la gestión académica
                y acceda rápidamente a los módulos del sistema.
            </p>

        </div>

        <div class="portal-page-actions">

            <span class="portal-current-date">

                <i class="bi bi-calendar3"></i>

                {{ now()->translatedFormat(
                    'd \d\e F \d\e Y'
                ) }}

            </span>

        </div>

    </div>

@endsection

@section('content')

    {{-- =====================================================
         Indicadores principales
         ===================================================== --}}
    <section class="portal-stat-grid">

        {{-- Personas --}}
        <article class="portal-stat-card">

            <div class="portal-stat-icon">
                <i class="bi bi-person-vcard"></i>
            </div>

            <div class="portal-stat-content">

                <span>
                    Personas registradas
                </span>

                <strong>
                    {{ number_format(
                        $personasRegistradas
                    ) }}
                </strong>

                <small>
                    Expedientes generales registrados
                </small>

            </div>

        </article>

        {{-- Estudiantes --}}
        <article class="portal-stat-card">

            <div class="portal-stat-icon">
                <i class="bi bi-mortarboard"></i>
            </div>

            <div class="portal-stat-content">

                <span>
                    Estudiantes
                </span>

                <strong>
                    {{ number_format(
                        $estudiantesActivos
                    ) }}
                </strong>

                <small>

                    {{ number_format(
                        $estudiantesActivos
                    ) }}
                    activos

                    @if ($estudiantesInactivos > 0)

                        ·
                        {{ number_format(
                            $estudiantesInactivos
                        ) }}
                        inactivos

                    @endif

                </small>

            </div>

        </article>

        {{-- Docentes --}}
        <article class="portal-stat-card">

            <div class="portal-stat-icon">
                <i class="bi bi-person-workspace"></i>
            </div>

            <div class="portal-stat-content">

                <span>
                    Docentes
                </span>

                <strong>
                    {{ number_format(
                        $docentesActivos
                    ) }}
                </strong>

                <small>

                    {{ number_format(
                        $docentesActivos
                    ) }}
                    activos

                    @if ($docentesInactivos > 0)

                        ·
                        {{ number_format(
                            $docentesInactivos
                        ) }}
                        inactivos

                    @endif

                </small>

            </div>

        </article>

        {{-- Solicitudes --}}
        <article
            class="portal-stat-card
                {{ $solicitudesPendientes > 0
                    ? 'portal-stat-card-highlight'
                    : '' }}"
        >

            <div class="portal-stat-icon">
                <i class="bi bi-file-earmark-check"></i>
            </div>

            <div class="portal-stat-content">

                <span>
                    Solicitudes pendientes
                </span>

                <strong>
                    {{ number_format(
                        $solicitudesPendientes
                    ) }}
                </strong>

                <small>

                    @if ($solicitudesPendientes > 0)

                        Requieren atención administrativa

                    @else

                        No hay solicitudes pendientes

                    @endif

                </small>

            </div>

        </article>

    </section>

    <div class="row g-4">

        {{-- =================================================
             Estado académico
             ================================================= --}}
        <div class="col-12 col-xl-8">

            <section class="portal-card">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Estado académico
                        </h2>

                        <p>
                            Información general de la operación
                            académica actual.
                        </p>

                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>
                            Grupos activos
                        </span>

                        <strong>
                            {{ number_format(
                                $gruposActivos
                            ) }}
                        </strong>

                        <small>
                            Grupos actualmente habilitados
                        </small>

                    </div>

                    <div class="portal-detail-item">

                        <span>
                            Estudiantes activos
                        </span>

                        <strong>
                            {{ number_format(
                                $estudiantesActivos
                            ) }}
                        </strong>

                        <small>
                            Expedientes estudiantiles activos
                        </small>

                    </div>

                    <div class="portal-detail-item">

                        <span>
                            Docentes activos
                        </span>

                        <strong>
                            {{ number_format(
                                $docentesActivos
                            ) }}
                        </strong>

                        <small>
                            Docentes disponibles en el sistema
                        </small>

                    </div>

                    <div class="portal-detail-item">

                        <span>
                            Solicitudes por revisar
                        </span>

                        <strong>
                            {{ number_format(
                                $solicitudesPendientes
                            ) }}
                        </strong>

                        <small>

                            @if ($solicitudesPendientes > 0)

                                Hay solicitudes que requieren atención

                            @else

                                Todo se encuentra al día

                            @endif

                        </small>

                    </div>

                </div>

            </section>

        </div>

        {{-- =================================================
             Accesos rápidos
             ================================================= --}}
        <div class="col-12 col-xl-4">

            <section class="portal-card">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Accesos rápidos
                        </h2>

                        <p>
                            Operaciones frecuentes.
                        </p>

                    </div>

                </div>

                <div class="portal-quick-actions">

                    {{-- Nueva persona --}}
                    <a
                        href="{{ route(
                            'portal.personas.create'
                        ) }}"
                        class="portal-quick-action"
                    >

                        <span>
                            <i class="bi bi-person-plus"></i>
                        </span>

                        <div>

                            <strong>
                                Nueva persona
                            </strong>

                            <small>
                                Registrar información personal
                            </small>

                        </div>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                    {{-- Solicitudes --}}
                    @if (
                        Route::has(
                            'portal.solicitudes-inscripcion.index'
                        )
                    )

                        <a
                            href="{{ route(
                                'portal.solicitudes-inscripcion.index'
                            ) }}"
                            class="portal-quick-action"
                        >

                            <span>
                                <i class="bi bi-file-earmark-check"></i>
                            </span>

                            <div>

                                <strong>
                                    Revisar solicitudes
                                </strong>

                                <small>

                                    @if ($solicitudesPendientes > 0)

                                        {{ $solicitudesPendientes }}
                                        pendientes de atención

                                    @else

                                        Consultar solicitudes de inscripción

                                    @endif

                                </small>

                            </div>

                            <i class="bi bi-chevron-right"></i>

                        </a>

                    @else

                        <div class="portal-quick-action">

                            <span>
                                <i class="bi bi-file-earmark-check"></i>
                            </span>

                            <div>

                                <strong>
                                    Solicitudes de inscripción
                                </strong>

                                <small>
                                    Módulo administrativo en preparación
                                </small>

                            </div>

                            <i class="bi bi-hourglass-split"></i>

                        </div>

                    @endif

                    {{-- Grupos --}}
                    <a
                        href="{{ route(
                            'portal.grupos.index'
                        ) }}"
                        class="portal-quick-action"
                    >

                        <span>
                            <i class="bi bi-people"></i>
                        </span>

                        <div>

                            <strong>
                                Consultar grupos
                            </strong>

                            <small>
                                Grupos, horarios y docentes
                            </small>

                        </div>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                    {{-- Estudiantes --}}
                    <a
                        href="{{ route(
                            'portal.estudiantes.index'
                        ) }}"
                        class="portal-quick-action"
                    >

                        <span>
                            <i class="bi bi-mortarboard"></i>
                        </span>

                        <div>

                            <strong>
                                Consultar estudiantes
                            </strong>

                            <small>
                                Expedientes estudiantiles
                            </small>

                        </div>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                </div>

            </section>

        </div>

    </div>

@endsection