@extends('layouts.portal')

@section('title', 'Solicitudes de inscripción')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Admisiones
            </span>

            <h1>Solicitudes de inscripción</h1>

            <p>
                Revise las solicitudes enviadas por nuevos aspirantes
                y consulte el estado de cada proceso.
            </p>
        </div>

    </div>

@endsection

@section('content')

    {{-- Resumen --}}
    <section class="portal-summary-grid">

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>

            <div>
                <span>Total de solicitudes</span>

                <strong>
                    {{ number_format($resumen['total']) }}
                </strong>

                <small>
                    Solicitudes registradas
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-muted">
                <i class="bi bi-clock-history"></i>
            </div>

            <div>
                <span>Pendientes</span>

                <strong>
                    {{ number_format($resumen['pendientes']) }}
                </strong>

                <small>
                    Esperando revisión administrativa
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-search"></i>
            </div>

            <div>
                <span>En revisión</span>

                <strong>
                    {{ number_format($resumen['en_revision']) }}
                </strong>

                <small>
                    Actualmente en proceso de revisión
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-check2-circle"></i>
            </div>

            <div>
                <span>Resueltas</span>

                <strong>
                    {{ number_format($resumen['resueltas']) }}
                </strong>

                <small>
                    Solicitudes aprobadas o rechazadas
                </small>
            </div>

        </article>

    </section>


    {{-- Listado --}}
    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Solicitudes recibidas</h2>

                <p>
                    Consulte los aspirantes que han enviado una
                    Solicitud de Inscripción a EDMA.
                </p>
            </div>

            <span class="portal-results-count">

                {{ $solicitudes->total() }}

                {{ $solicitudes->total() === 1
                    ? 'resultado'
                    : 'resultados' }}

            </span>

        </div>


        {{-- Filtros --}}
        <div class="portal-filter-area">

            <form
                action="{{ route(
                    'portal.solicitudes-inscripcion.index'
                ) }}"
                method="GET"
                class="portal-filter-form"
            >

                {{-- Búsqueda --}}
                <div class="portal-search-field">

                    <i class="bi bi-search"></i>

                    <input
                        type="search"
                        name="buscar"
                        value="{{ $termino }}"
                        class="form-control"
                        placeholder="Código, aspirante, documento o correo..."
                        aria-label="Buscar solicitudes"
                    >

                </div>


                {{-- Estado --}}
                <div class="portal-filter-select">

                    <select
                        name="estado"
                        class="form-select"
                        aria-label="Filtrar por estado"
                    >

                        <option value="">
                            Todos los estados
                        </option>

                        <option
                            value="pendiente"
                            @selected(
                                $estadoSeleccionado
                                    === 'pendiente'
                            )
                        >
                            Pendientes
                        </option>

                        <option
                            value="en_revision"
                            @selected(
                                $estadoSeleccionado
                                    === 'en_revision'
                            )
                        >
                            En revisión
                        </option>

                        <option
                            value="aprobada"
                            @selected(
                                $estadoSeleccionado
                                    === 'aprobada'
                            )
                        >
                            Aprobadas
                        </option>

                        <option
                            value="rechazada"
                            @selected(
                                $estadoSeleccionado
                                    === 'rechazada'
                            )
                        >
                            Rechazadas
                        </option>

                    </select>

                </div>


                {{-- Segmento --}}
                <div class="portal-filter-select">

                    <select
                        name="segmento"
                        class="form-select"
                        aria-label="Filtrar por segmento"
                    >

                        <option value="">
                            Todos los segmentos
                        </option>

                        <option
                            value="niños"
                            @selected(
                                $segmentoSeleccionado
                                    === 'niños'
                            )
                        >
                            Niños
                        </option>

                        <option
                            value="jóvenes_adultos"
                            @selected(
                                $segmentoSeleccionado
                                    === 'jóvenes_adultos'
                            )
                        >
                            Jóvenes y adultos
                        </option>

                    </select>

                </div>


                {{-- Período --}}
                <div class="portal-filter-select">

                    <select
                        name="periodo"
                        class="form-select"
                        aria-label="Filtrar por período académico"
                    >

                        <option value="">
                            Todos los períodos
                        </option>

                        @foreach ($periodos as $periodo)

                            <option
                                value="{{ $periodo->id }}"
                                @selected(
                                    (string)
                                    $periodoSeleccionado
                                    ===
                                    (string)
                                    $periodo->id
                                )
                            >
                                {{ $periodo->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <button
                    type="submit"
                    class="btn portal-btn-primary"
                >
                    <i class="bi bi-funnel"></i>
                    Aplicar
                </button>


                @if (
                    $termino !== '' ||
                    $estadoSeleccionado ||
                    $segmentoSeleccionado ||
                    $periodoSeleccionado
                )

                    <a
                        href="{{ route(
                            'portal.solicitudes-inscripcion.index'
                        ) }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>


        @if ($solicitudes->isNotEmpty())

            <div class="portal-table-responsive">

                <table
                    class="table portal-table align-middle mb-0"
                >

                    <thead>

                        <tr>
                            <th>Solicitud</th>
                            <th>Aspirante</th>
                            <th>Documento</th>
                            <th>Segmento</th>
                            <th>Nivel solicitado</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="text-end">
                                Acciones
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($solicitudes as $solicitud)

                            <tr>

                                {{-- Solicitud --}}
                                <td>

                                    <div class="portal-table-primary">
                                        {{ $solicitud->codigo_solicitud }}
                                    </div>

                                    @php
                                        $periodo =
                                            $solicitud
                                                ->pagos
                                                ->first()
                                                ?->periodoAcademico;
                                    @endphp

                                    <small class="portal-table-secondary">

                                        {{ $periodo?->nombre
                                            ?? 'Período no disponible' }}

                                    </small>

                                </td>


                                {{-- Aspirante --}}
                                <td>

                                    <div class="portal-table-primary">
                                        {{ $solicitud
                                            ->persona
                                            ?->nombre_completo
                                            ?? 'Sin información' }}
                                    </div>

                                    @if (
                                        $solicitud
                                            ->persona
                                            ?->correo_personal
                                    )

                                        <small class="portal-table-secondary">
                                            {{ $solicitud
                                                ->persona
                                                ->correo_personal }}
                                        </small>

                                    @endif

                                </td>


                                {{-- Documento --}}
                                <td>

                                    @if (
                                        $solicitud
                                            ->persona
                                            ?->numero_documento
                                    )

                                        <div class="portal-table-primary">
                                            {{ $solicitud
                                                ->persona
                                                ->numero_documento }}
                                        </div>

                                        <small class="portal-table-secondary">

                                            {{ str(
                                                $solicitud
                                                    ->persona
                                                    ->tipo_documento
                                            )
                                                ->replace(
                                                    '_',
                                                    ' '
                                                )
                                                ->title() }}

                                        </small>

                                    @else

                                        <span class="portal-table-secondary">
                                            No registrado
                                        </span>

                                    @endif

                                </td>


                                {{-- Segmento --}}
                                <td>

                                    @if (
                                        $solicitud
                                            ->segmento_solicitado
                                        === 'niños'
                                    )

                                        <span class="portal-status-badge">
                                            <span></span>
                                            Niños
                                        </span>

                                    @else

                                        <span class="portal-status-badge">
                                            <span></span>
                                            Jóvenes y adultos
                                        </span>

                                    @endif

                                </td>


                                {{-- Nivel --}}
                                <td>

                                    <div class="portal-table-primary">

                                        {{ $solicitud
                                            ->nivelSolicitado
                                            ?->nombre
                                            ?? 'No disponible' }}

                                    </div>

                                    @if (
                                        $solicitud
                                            ->requiere_examen_ubicacion
                                    )

                                        <small class="portal-table-secondary">
                                            Requiere prueba de ubicación
                                        </small>

                                    @else

                                        <small class="portal-table-secondary">
                                            Sin prueba de ubicación
                                        </small>

                                    @endif

                                </td>


                                {{-- Fecha --}}
                                <td>

                                    <div class="portal-table-primary">

                                        {{ $solicitud->enviada_at
                                            ?->translatedFormat(
                                                'd M Y'
                                            )
                                            ?? 'No disponible' }}

                                    </div>

                                    <small class="portal-table-secondary">

                                        {{ $solicitud->enviada_at
                                            ?->format('h:i a')
                                            ?? '' }}

                                    </small>

                                </td>


                                {{-- Estado --}}
                                <td>

                                    @switch($solicitud->estado)

                                        @case('pendiente')

                                            <span
                                                class="portal-status-badge
                                                portal-status-pending"
                                            >
                                                <span></span>
                                                Pendiente
                                            </span>

                                            @break


                                        @case('en_revision')

                                            <span
                                                class="portal-status-badge"
                                            >
                                                <span></span>
                                                En revisión
                                            </span>

                                            @break


                                        @case('aprobada')

                                            <span
                                                class="portal-status-badge
                                                portal-status-active"
                                            >
                                                <span></span>
                                                Aprobada
                                            </span>

                                            @break


                                        @case('rechazada')

                                            <span
                                                class="portal-status-badge
                                                portal-status-inactive"
                                            >
                                                <span></span>
                                                Rechazada
                                            </span>

                                            @break


                                        @default

                                            <span
                                                class="portal-status-badge"
                                            >
                                                <span></span>

                                                {{ str(
                                                    $solicitud->estado
                                                )
                                                    ->replace(
                                                        '_',
                                                        ' '
                                                    )
                                                    ->title() }}
                                            </span>

                                    @endswitch

                                </td>


                                {{-- Acciones --}}
                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="btn portal-table-action"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            aria-label="Opciones"
                                        >
                                            <i class="bi bi-three-dots"></i>
                                        </button>

                                        <ul
                                            class="dropdown-menu dropdown-menu-end"
                                        >

                                            <li>

                                                <a
                                                    href="{{ route(
                                                        'portal.solicitudes-inscripcion.show',
                                                        $solicitud
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Ver solicitud
                                                </a>

                                            </li>

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="portal-table-footer">

                <div class="portal-pagination-summary">

                    Mostrando

                    <strong>
                        {{ $solicitudes->firstItem() }}
                    </strong>

                    a

                    <strong>
                        {{ $solicitudes->lastItem() }}
                    </strong>

                    de

                    <strong>
                        {{ $solicitudes->total() }}
                    </strong>

                    registros

                </div>

                <div>
                    {{ $solicitudes->links() }}
                </div>

            </div>

        @else

            <div
                class="portal-empty-state portal-empty-state-large"
            >

                <div class="portal-empty-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                @if (
                    $termino !== '' ||
                    $estadoSeleccionado ||
                    $segmentoSeleccionado ||
                    $periodoSeleccionado
                )

                    <h3>
                        No se encontraron solicitudes
                    </h3>

                    <p>
                        Pruebe cambiando los filtros o el término
                        utilizado en la búsqueda.
                    </p>

                    <a
                        href="{{ route(
                            'portal.solicitudes-inscripcion.index'
                        ) }}"
                        class="btn portal-btn-secondary mt-3"
                    >
                        Limpiar filtros
                    </a>

                @else

                    <h3>
                        No hay solicitudes de inscripción
                    </h3>

                    <p>
                        Las solicitudes enviadas desde el sitio
                        web aparecerán en este espacio.
                    </p>

                @endif

            </div>

        @endif

    </section>

@endsection