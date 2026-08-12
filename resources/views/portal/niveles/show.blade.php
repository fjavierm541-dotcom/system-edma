@extends('layouts.portal')

@section('title', $nivel->nombre . ' | Portal EDMA')

@section('page-title', 'Nivel académico')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>
                Nivel {{ $nivel->nombre }}
            </h1>

            <p>
                Consulte la configuración académica y los
                grupos asociados a este nivel.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.niveles.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route(
                    'portal.niveles.edit',
                    $nivel
                ) }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-pencil-square"></i>
                Editar nivel
            </a>

        </div>

    </div>

@endsection

@section('content')

    <div class="row g-4">

        {{-- Columna lateral --}}
        <div class="col-12 col-xl-4">

            <section class="portal-card portal-profile-card">

                <div class="portal-profile-cover"></div>

                <div class="portal-profile-content">

                    <div class="portal-profile-photo">

                        <span>
                            {{ $nivel->nombre }}
                        </span>

                    </div>

                    <h2>
                        Nivel {{ $nivel->nombre }}
                    </h2>

                    <span class="portal-employee-code mt-2">
                        {{ $nivel->codigo }}
                    </span>

                    <div class="mt-3">

                        @if ($nivel->estado === 'activo')

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Nivel activo
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Nivel inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Duración</span>

                        <strong>
                            {{ $nivel->duracion_semanas }}
                            semanas
                        </strong>
                    </div>

                    <div>
                        <span>Nota mínima</span>

                        <strong>
                            {{ $nivel->nota_minima_aprobacion !== null
                                ? number_format(
                                    (float) $nivel->nota_minima_aprobacion,
                                    0
                                ) . '/100'
                                : 'No definida' }}
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
                            'portal.niveles.edit',
                            $nivel
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>
                            <strong>Editar nivel</strong>
                            <small>
                                Actualizar su configuración
                            </small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a
                        href="{{ route(
                            'portal.programas.show',
                            $nivel->programa
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-journal-bookmark"></i>
                        </span>

                        <div>
                            <strong>Ver programa</strong>

                            <small>
                                {{ $nivel->programa?->nombre }}
                            </small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <button
                        type="button"
                        class="portal-profile-action portal-profile-action-button"
                        data-bs-toggle="modal"
                        data-bs-target="#changeLevelStatusModal"
                    >
                        <span>
                            <i class="bi bi-toggle-on"></i>
                        </span>

                        <div>
                            <strong>
                                {{ $nivel->estado === 'activo'
                                    ? 'Desactivar nivel'
                                    : 'Activar nivel' }}
                            </strong>

                            <small>
                                {{ $nivel->estado === 'activo'
                                    ? 'Dejará de estar disponible para nuevos procesos'
                                    : 'Volverá a estar disponible' }}
                            </small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </button>

                </div>

            </section>

        </div>

        {{-- Contenido principal --}}
        <div class="col-12 col-xl-8">

            {{-- Información --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h2>Información del nivel</h2>

                        <p>
                            Configuración académica utilizada
                            para este nivel.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Código</span>

                        <strong>
                            {{ $nivel->codigo }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Nombre</span>

                        <strong>
                            {{ $nivel->nombre }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Programa</span>

                        <strong>
                            {{ $nivel->programa?->nombre
                                ?: 'No disponible' }}
                        </strong>

                        @if ($nivel->programa)

                            <small>
                                {{ $nivel->programa->codigo }}
                            </small>

                        @endif

                    </div>

                    <div class="portal-detail-item">

                        <span>Orden académico</span>

                        <strong>
                            {{ $nivel->orden }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Duración</span>

                        <strong>
                            {{ $nivel->duracion_semanas }}
                            semanas
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Nota mínima de aprobación</span>

                        <strong>
                            {{ $nivel->nota_minima_aprobacion !== null
                                ? number_format(
                                    (float) $nivel->nota_minima_aprobacion,
                                    2
                                ) . ' / 100'
                                : 'No definida' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Estado</span>

                        <strong>
                            {{ str($nivel->estado)->title() }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Grupos registrados</span>

                        <strong>
                            {{ $nivel->grupos->count() }}
                        </strong>

                    </div>

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Descripción</span>

                        <strong>
                            {{ $nivel->descripcion
                                ?: 'Sin descripción registrada' }}
                        </strong>

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
                        <h2>Grupos</h2>

                        <p>
                            Grupos académicos registrados
                            para este nivel.
                        </p>
                    </div>

                </div>

                @if ($nivel->grupos->isNotEmpty())

                    <div class="portal-academic-list">

                        @foreach ($nivel->grupos as $grupo)

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
                                        {{ str($grupo->modalidad)->title() }}
                                        · Cupo máximo:
                                        {{ $grupo->cupo_maximo }}
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
                            Cuando se creen grupos para este nivel,
                            aparecerán en esta sección.
                        </p>

                    </div>

                @endif

            </section>

        </div>

    </div>

    {{-- Cambio de estado --}}
    <div
        class="modal fade"
        id="changeLevelStatusModal"
        tabindex="-1"
        aria-labelledby="changeLevelStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    action="{{ route(
                        'portal.niveles.cambiar-estado',
                        $nivel
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
                                id="changeLevelStatusModalLabel"
                            >
                                {{ $nivel->estado === 'activo'
                                    ? 'Desactivar nivel'
                                    : 'Activar nivel' }}
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
                            <i class="bi bi-layers"></i>
                        </div>

                        <p class="mb-0">

                            @if ($nivel->estado === 'activo')

                                ¿Desea desactivar el nivel
                                <strong>{{ $nivel->nombre }}</strong>?
                                La información registrada se conservará.

                            @else

                                ¿Desea activar nuevamente el nivel
                                <strong>{{ $nivel->nombre }}</strong>?

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
                                {{ $nivel->estado === 'activo'
                                    ? 'portal-btn-danger'
                                    : 'portal-btn-primary' }}"
                        >
                            {{ $nivel->estado === 'activo'
                                ? 'Desactivar'
                                : 'Activar' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection