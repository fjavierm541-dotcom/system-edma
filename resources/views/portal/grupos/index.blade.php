@extends('layouts.portal')

@section('title', 'Grupos | Portal EDMA')

@section('page-title', 'Grupos académicos')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Grupos académicos</h1>

            <p>
                Consulte y administre los grupos organizados
                por programa, nivel y período académico.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.grupos.create') }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-plus-circle"></i>
                Nuevo grupo
            </a>

        </div>

    </div>

@endsection

@section('content')

    {{-- Resumen --}}
<section class="portal-summary-grid">

    <article class="portal-summary-card">

        <div class="portal-summary-icon">
            <i class="bi bi-people"></i>
        </div>

        <div>
            <span>Total de grupos</span>

            <strong>
                {{ number_format(
                    $resumen['total']
                ) }}
            </strong>

            <small>
                Grupos académicos registrados
            </small>
        </div>

    </article>

    <article class="portal-summary-card">

        <div class="portal-summary-icon">
            <i class="bi bi-calendar-plus"></i>
        </div>

        <div>
            <span>Planificados</span>

            <strong>
                {{ number_format(
                    $resumen['planificados']
                ) }}
            </strong>

            <small>
                Preparados para iniciar posteriormente
            </small>
        </div>

    </article>

    <article class="portal-summary-card">

        <div class="portal-summary-icon portal-summary-icon-success">
            <i class="bi bi-play-circle"></i>
        </div>

        <div>
            <span>Activos</span>

            <strong>
                {{ number_format(
                    $resumen['activos']
                ) }}
            </strong>

            <small>
                Actualmente habilitados
            </small>
        </div>

    </article>

    <article class="portal-summary-card">

        <div class="portal-summary-icon portal-summary-icon-muted">
            <i class="bi bi-check2-circle"></i>
        </div>

        <div>
            <span>Finalizados</span>

            <strong>
                {{ number_format(
                    $resumen['finalizados']
                ) }}
            </strong>

            <small>
                Grupos que concluyeron su período
            </small>
        </div>

    </article>

    <article class="portal-summary-card">

        <div class="portal-summary-icon portal-summary-icon-muted">
            <i class="bi bi-x-circle"></i>
        </div>

        <div>
            <span>Cancelados</span>

            <strong>
                {{ number_format(
                    $resumen['cancelados']
                ) }}
            </strong>

            <small>
                Grupos que no continuaron
            </small>
        </div>

    </article>

</section>

    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Grupos registrados</h2>

                <p>
                    Busque por código, nombre, nivel, programa
                    o período académico.
                </p>
            </div>

            <span class="portal-results-count">
                {{ $grupos->total() }}

                {{ $grupos->total() === 1
                    ? 'resultado'
                    : 'resultados' }}
            </span>

        </div>

        <div class="portal-filter-area">

            <form
                action="{{ route('portal.grupos.index') }}"
                method="GET"
                class="portal-filter-form"
            >

                <div class="portal-search-field">

                    <i class="bi bi-search"></i>

                    <input
                        type="search"
                        name="buscar"
                        value="{{ $termino }}"
                        class="form-control"
                        placeholder="Código, grupo, nivel o período..."
                        aria-label="Buscar grupos"
                    >

                </div>

                <div class="portal-filter-select">

                    <select
                        name="programa"
                        class="form-select"
                        aria-label="Filtrar por programa"
                    >
                        <option value="">
                            Todos los programas
                        </option>

                        @foreach ($programas as $programa)

                            <option
                                value="{{ $programa->id }}"
                                @selected(
                                    (string) $programaSeleccionado
                                    === (string) $programa->id
                                )
                            >
                                {{ $programa->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="portal-filter-select">

                    <select
                        name="periodo"
                        class="form-select"
                        aria-label="Filtrar por período"
                    >
                        <option value="">
                            Todos los períodos
                        </option>

                        @foreach ($periodos as $periodo)

                            <option
                                value="{{ $periodo->id }}"
                                @selected(
                                    (string) $periodoSeleccionado
                                    === (string) $periodo->id
                                )
                            >
                                {{ $periodo->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>

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
                            value="planificado"
                            @selected(
                                $estadoSeleccionado === 'planificado'
                            )
                        >
                            Planificado
                        </option>

                        <option
                            value="activo"
                            @selected(
                                $estadoSeleccionado === 'activo'
                            )
                        >
                            Activo
                        </option>

                        <option
                            value="finalizado"
                            @selected(
                                $estadoSeleccionado === 'finalizado'
                            )
                        >
                            Finalizado
                        </option>

                        <option
                            value="cancelado"
                            @selected(
                                $estadoSeleccionado === 'cancelado'
                            )
                        >
                            Cancelado
                        </option>

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
                    $programaSeleccionado ||
                    $periodoSeleccionado ||
                    $estadoSeleccionado
                )

                    <a
                        href="{{ route('portal.grupos.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>

        @if ($grupos->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Grupo</th>
                            <th>Período</th>
                            <th>Modalidad</th>
                            <th>Cupo</th>
                            <th>Fechas</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($grupos as $grupo)

                            <tr>

                                <td>

                                    <div class="portal-table-primary">
                                        {{ $grupo->etiqueta_completa }}
                                    </div>

                                    <small class="portal-table-secondary">
                                        {{ $grupo->codigo }}
                                    </small>

                                </td>

                                <td>

                                    <div class="portal-table-primary">
                                        {{ $grupo->periodoAcademico?->nombre
                                            ?: 'No disponible' }}
                                    </div>

                                    @if ($grupo->periodoAcademico)

                                        <small class="portal-table-secondary">
                                            {{ $grupo->periodoAcademico->codigo }}
                                        </small>

                                    @endif

                                </td>

                                <td>
                                    <span class="portal-role-badge">
                                        <i class="bi bi-camera-video"></i>
                                        Virtual
                                    </span>
                                </td>

                                <td>

                                    <strong>
                                        {{ $grupo->cupo_minimo }}
                                        -
                                        {{ $grupo->cupo_maximo }}
                                    </strong>

                                    <small class="portal-table-secondary">
                                        estudiantes
                                    </small>

                                </td>

                                <td>

                                    <div class="portal-table-primary">
                                        {{ $grupo->fecha_inicio
                                            ? $grupo->fecha_inicio
                                                ->translatedFormat('d M Y')
                                            : 'No definida' }}
                                    </div>

                                    <small class="portal-table-secondary">
                                        hasta
                                        {{ $grupo->fecha_fin
                                            ? $grupo->fecha_fin
                                                ->translatedFormat('d M Y')
                                            : 'No definida' }}
                                    </small>

                                </td>

                                <td>

                                   @switch($grupo->estado)

    @case('planificado')

        <span class="portal-status-badge">
            <span></span>
            Planificado
        </span>

        @break

    @case('activo')

        <span class="portal-status-badge portal-status-active">
            <span></span>
            Activo
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

@endswitch

                                </td>

                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="portal-table-action"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            aria-label="Opciones del grupo"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.grupos.show',
                                                        $grupo
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Ver grupo
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.grupos.edit',
                                                        $grupo
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                    Editar grupo
                                                </a>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <form
                                                    action="{{ route(
                                                        'portal.grupos.cambiar-estado',
                                                        $grupo
                                                    ) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item
                                                            {{ $grupo->estado === 'activo'
                                                                ? 'text-warning-emphasis'
                                                                : 'text-success' }}"
                                                    >
                                                        <i class="bi
                                                            {{ $grupo->estado === 'activo'
                                                                ? 'bi-toggle-off'
                                                                : 'bi-toggle-on' }}">
                                                        </i>

                                                        {{ $grupo->estado === 'activo'
                                                            ? 'Desactivar grupo'
                                                            : 'Activar grupo' }}
                                                    </button>

                                                </form>

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
                    <strong>{{ $grupos->firstItem() }}</strong>
                    a
                    <strong>{{ $grupos->lastItem() }}</strong>
                    de
                    <strong>{{ $grupos->total() }}</strong>
                    registros
                </div>

                <div>
                    {{ $grupos->links() }}
                </div>

            </div>

        @else

            <div class="portal-empty-state portal-empty-state-large">

                <div class="portal-empty-icon">
                    <i class="bi bi-people"></i>
                </div>

                <h3>No hay grupos disponibles</h3>

                <p>
                    Registre un grupo para comenzar a organizar
                    horarios y docentes.
                </p>

                <a
                    href="{{ route('portal.grupos.create') }}"
                    class="btn portal-btn-primary mt-3"
                >
                    <i class="bi bi-plus-circle"></i>
                    Registrar grupo
                </a>

            </div>

        @endif

    </section>

@endsection