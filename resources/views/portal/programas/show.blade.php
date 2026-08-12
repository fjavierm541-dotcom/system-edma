@extends('layouts.portal')

@section('title', $programa->nombre . ' | Portal EDMA')

@section('page-title', 'Programa académico')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>{{ $programa->nombre }}</h1>

            <p>
                Consulte la configuración general y los niveles
                asociados al programa.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.programas.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route(
                    'portal.programas.edit',
                    $programa
                ) }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-pencil-square"></i>
                Editar programa
            </a>

        </div>

    </div>

@endsection

@section('content')

    <div class="row g-4">

        {{-- =====================================================
             Columna lateral
             ===================================================== --}}
        <div class="col-12 col-xl-4">

            <section class="portal-card portal-profile-card">

                <div class="portal-profile-cover"></div>

                <div class="portal-profile-content">

                    <div class="portal-profile-photo">
                        <span>
                            <i class="bi bi-journal-bookmark"></i>
                        </span>
                    </div>

                    <h2>
                        {{ $programa->nombre }}
                    </h2>

                    <span class="portal-employee-code mt-2">
                        {{ $programa->codigo }}
                    </span>

                    <div class="mt-3">

                        @if ($programa->estado === 'activo')

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Programa activo
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Programa inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Segmento</span>

                        <strong>
                            {{ $programa->segmento === 'niños'
                                ? 'Niños'
                                : (
                                    $programa->segmento === 'jóvenes_adultos'
                                        ? 'Jóvenes y adultos'
                                        : str($programa->segmento)
                                            ->replace('_', ' ')
                                            ->title()
                                ) }}
                        </strong>
                    </div>

                    <div>
                        <span>Niveles</span>

                        <strong>
                            {{ $programa->niveles->count() }}
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
                            'portal.programas.edit',
                            $programa
                        ) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>
                            <strong>Editar programa</strong>
                            <small>Actualizar información general</small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    @if ($programa->estado === 'activo')

                        <a
                            href="{{ route(
                                'portal.niveles.create',
                                ['programa' => $programa->id]
                            ) }}"
                            class="portal-profile-action"
                        >
                            <span>
                                <i class="bi bi-plus-circle"></i>
                            </span>

                            <div>
                                <strong>Agregar nivel</strong>
                                <small>
                                    Registrar un nuevo nivel en este programa
                                </small>
                            </div>

                            <i class="bi bi-chevron-right"></i>
                        </a>

                    @endif

                    <button
                        type="button"
                        class="portal-profile-action portal-profile-action-button"
                        data-bs-toggle="modal"
                        data-bs-target="#changeProgramStatusModal"
                    >
                        <span>
                            <i class="bi bi-toggle-on"></i>
                        </span>

                        <div>
                            <strong>
                                {{ $programa->estado === 'activo'
                                    ? 'Desactivar programa'
                                    : 'Activar programa' }}
                            </strong>

                            <small>
                                {{ $programa->estado === 'activo'
                                    ? 'Dejará de estar disponible para nuevos procesos'
                                    : 'Volverá a estar disponible para su gestión' }}
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
                            Configuración principal del programa académico.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Código</span>

                        <strong>
                            {{ $programa->codigo }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Estado</span>

                        <strong>
                            {{ str($programa->estado)->title() }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Segmento</span>

                        <strong>
                            {{ $programa->segmento === 'niños'
                                ? 'Niños'
                                : (
                                    $programa->segmento === 'jóvenes_adultos'
                                        ? 'Jóvenes y adultos'
                                        : str($programa->segmento)
                                            ->replace('_', ' ')
                                            ->title()
                                ) }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Rango de edad</span>

                        <strong>
                            {{ $programa->segmento === 'niños'
                                ? '7 a 13 años'
                                : (
                                    $programa->segmento === 'jóvenes_adultos'
                                        ? '14 años en adelante'
                                        : 'No definido'
                                ) }}
                        </strong>

                    </div>

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Descripción</span>

                        <strong>
                            {{ $programa->descripcion
                                ?: 'Sin descripción registrada' }}
                        </strong>

                    </div>

                </div>

            </section>

            {{-- =====================================================
                 Niveles académicos
                 ===================================================== --}}
            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header portal-section-header-actions">

                    <div class="d-flex align-items-center gap-3">

                        <div class="portal-form-section-icon">
                            <i class="bi bi-layers"></i>
                        </div>

                        <div>
                            <h2>Niveles académicos</h2>

                            <p>
                                Consulte y administre los niveles que
                                conforman este programa.
                            </p>
                        </div>

                    </div>

                    <div class="d-flex align-items-center gap-2">

                        <span class="portal-results-count">
                            {{ $programa->niveles->count() }}
                        </span>

                        @if ($programa->estado === 'activo')

                            <a
                                href="{{ route(
                                    'portal.niveles.create',
                                    ['programa' => $programa->id]
                                ) }}"
                                class="btn portal-btn-secondary btn-sm"
                            >
                                <i class="bi bi-plus-circle"></i>
                                Agregar nivel
                            </a>

                        @endif

                    </div>

                </div>

                @if ($programa->niveles->isNotEmpty())

                    <div class="portal-academic-list">

                        @foreach ($programa->niveles as $nivel)

                            <article
                                class="portal-academic-item
                                    {{ $nivel->estado !== 'activo'
                                        ? 'portal-academic-item-inactive'
                                        : '' }}"
                            >

                                <div class="portal-academic-icon">
                                    <i class="bi bi-layers"></i>
                                </div>

                                <div class="portal-academic-info">

                                    <a
                                        href="{{ route(
                                            'portal.niveles.show',
                                            $nivel
                                        ) }}"
                                        class="portal-person-name"
                                    >
                                        Nivel {{ $nivel->nombre }}
                                    </a>

                                    <span>
                                        Código: {{ $nivel->codigo }}
                                    </span>

                                    <small>
                                        Posición {{ $nivel->orden }}
                                        · {{ $nivel->duracion_semanas }} semanas

                                        @if (
                                            $nivel->nota_minima_aprobacion
                                            !== null
                                        )
                                            · Nota mínima:
                                            {{ number_format(
                                                (float) $nivel
                                                    ->nota_minima_aprobacion,
                                                0
                                            ) }}/100
                                        @endif
                                    </small>

                                </div>

                                @if ($nivel->estado === 'activo')

                                    <span class="portal-status-badge portal-status-active">
                                        <span></span>
                                        Activo
                                    </span>

                                @else

                                    <span class="portal-status-badge portal-status-inactive">
                                        <span></span>
                                        Inactivo
                                    </span>

                                @endif

                                <a
                                    href="{{ route(
                                        'portal.niveles.show',
                                        $nivel
                                    ) }}"
                                    class="portal-table-action"
                                    aria-label="Ver nivel {{ $nivel->nombre }}"
                                >
                                    <i class="bi bi-chevron-right"></i>
                                </a>

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-layers"></i>
                        </div>

                        <h3>No hay niveles registrados</h3>

                        <p>
                            Agregue los niveles que forman parte
                            de este programa académico.
                        </p>

                        @if ($programa->estado === 'activo')

                            <a
                                href="{{ route(
                                    'portal.niveles.create',
                                    ['programa' => $programa->id]
                                ) }}"
                                class="btn portal-btn-primary mt-3"
                            >
                                <i class="bi bi-plus-circle"></i>
                                Agregar primer nivel
                            </a>

                        @endif

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
        id="changeProgramStatusModal"
        tabindex="-1"
        aria-labelledby="changeProgramStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    action="{{ route(
                        'portal.programas.cambiar-estado',
                        $programa
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
                                id="changeProgramStatusModalLabel"
                            >
                                {{ $programa->estado === 'activo'
                                    ? 'Desactivar programa'
                                    : 'Activar programa' }}
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
                            <i class="bi bi-journal-bookmark"></i>
                        </div>

                        <p class="mb-0">

                            @if ($programa->estado === 'activo')

                                ¿Desea desactivar el programa
                                <strong>{{ $programa->nombre }}</strong>?
                                Los niveles y la información académica
                                registrada se conservarán.

                            @else

                                ¿Desea activar nuevamente el programa
                                <strong>{{ $programa->nombre }}</strong>?

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
                                {{ $programa->estado === 'activo'
                                    ? 'portal-btn-danger'
                                    : 'portal-btn-primary' }}"
                        >
                            {{ $programa->estado === 'activo'
                                ? 'Desactivar'
                                : 'Activar' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection