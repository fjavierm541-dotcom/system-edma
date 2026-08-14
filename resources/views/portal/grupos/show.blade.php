@extends('layouts.portal')

@section('title', $grupo->etiqueta_completa . ' | Portal EDMA')

@section('page-title', 'Grupo académico')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>{{ $grupo->etiqueta_completa }}</h1>

            <p>
                Consulte la configuración, horarios y docentes
                asociados al grupo.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.grupos.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route(
                    'portal.grupos.edit',
                    $grupo
                ) }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-pencil-square"></i>
                Editar grupo
            </a>

        </div>

    </div>

@endsection

@section('content')

    <div class="row g-4">

        <div class="col-12 col-xl-4">

            <section class="portal-card portal-profile-card">

                <div class="portal-profile-cover"></div>

                <div class="portal-profile-content">

                    <div class="portal-profile-photo">
                        <span>
                            <i class="bi bi-people"></i>
                        </span>
                    </div>

                    <h2>{{ $grupo->nombre }}</h2>

                    <span class="portal-employee-code mt-2">
                        {{ $grupo->codigo }}
                    </span>

                    <div class="mt-3">

                        @if ($grupo->estado === 'activo')

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Grupo activo
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Grupo inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Modalidad</span>
                        <strong>Virtual</strong>
                    </div>

                    <div>
                        <span>Cupo máximo</span>
                        <strong>{{ $grupo->cupo_maximo }}</strong>
                    </div>

                </div>

            </section>

        </div>

        <div class="col-12 col-xl-8">

            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h2>Información general</h2>

                        <p>
                            Configuración académica del grupo.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Nombre</span>
                        <strong>{{ $grupo->nombre }}</strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Código institucional</span>
                        <strong>{{ $grupo->codigo }}</strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Programa</span>
                        <strong>
                            {{ $grupo->nivel?->programa?->nombre }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Nivel</span>
                        <strong>
                            {{ $grupo->nivel?->nombre }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Segmento</span>

                        <strong>
                            {{ $grupo->nivel?->programa?->segmento === 'niños'
                                ? 'Niños'
                                : 'Jóvenes y adultos' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Período académico</span>

                        <strong>
                            {{ $grupo->periodoAcademico?->nombre }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Fecha de inicio</span>

                        <strong>
                            {{ $grupo->fecha_inicio
                                ? $grupo->fecha_inicio
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No definida' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Fecha de finalización</span>

                        <strong>
                            {{ $grupo->fecha_fin
                                ? $grupo->fecha_fin
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No definida' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Cupo</span>

                        <strong>
                            {{ $grupo->cupo_minimo }}
                            a
                            {{ $grupo->cupo_maximo }}
                            estudiantes
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Modalidad</span>
                        <strong>Virtual</strong>

                    </div>

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Observaciones</span>

                        <strong>
                            {{ $grupo->observaciones
                                ?: 'Sin observaciones registradas' }}
                        </strong>

                    </div>

                </div>

            </section>

            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-clock"></i>
                    </div>

                    <div>
                        <h2>Días y horarios</h2>

                        <p>
                            Horarios asignados actualmente al grupo.
                        </p>
                    </div>

                </div>

                @if ($grupo->horarios->isNotEmpty())

                    <div class="portal-academic-list">

                        @foreach ($grupo->horarios as $asignacion)

                            <article class="portal-academic-item">

                                <div class="portal-academic-icon">
                                    <i class="bi bi-clock"></i>
                                </div>

                                <div class="portal-academic-info">

                                    <strong>
                                        {{ str(
                                            $asignacion->dia_semana
                                        )->title() }}
                                    </strong>

                                    <span>
                                        {{ $asignacion->horario?->nombre }}
                                    </span>

                                    <small>
                                        {{ $asignacion->horario?->hora_inicio }}
                                        -
                                        {{ $asignacion->horario?->hora_fin }}
                                    </small>

                                </div>

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <h3>No hay horarios asignados</h3>

                        <p>
                            El siguiente paso será seleccionar
                            los días y horarios de clase.
                        </p>

                    </div>

                @endif

            </section>

            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-easel"></i>
                    </div>

                    <div>
                        <h2>Docentes</h2>

                        <p>
                            Docentes asignados al grupo.
                        </p>
                    </div>

                </div>

                <div class="portal-empty-state portal-empty-state-documents">

                    <div class="portal-empty-icon">
                        <i class="bi bi-easel"></i>
                    </div>

                    <h3>Asignación docente pendiente</h3>

                    <p>
                        Una vez configurados los horarios,
                        podrá asignarse el docente responsable.
                    </p>

                </div>

            </section>

        </div>

    </div>

@endsection