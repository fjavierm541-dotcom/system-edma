@extends('layouts.portal')

@section('title', 'Períodos académicos | Portal EDMA')

@section('page-title', 'Períodos académicos')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Períodos académicos</h1>

            <p>
                Administre las fechas de matrícula y desarrollo
                académico de cada período.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.periodos.create') }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-plus-circle"></i>
                Nuevo período
            </a>

        </div>

    </div>

@endsection

@section('content')

    {{-- Resumen --}}
    <section class="portal-summary-grid">

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div>
                <span>Total de períodos</span>

                <strong>
                    {{ number_format($resumen['total']) }}
                </strong>

                <small>
                    Períodos académicos registrados
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-calendar-check"></i>
            </div>

            <div>
                <span>Períodos activos</span>

                <strong>
                    {{ number_format($resumen['activos']) }}
                </strong>

                <small>
                    Disponibles para la gestión académica
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-muted">
                <i class="bi bi-calendar-x"></i>
            </div>

            <div>
                <span>Períodos inactivos</span>

                <strong>
                    {{ number_format($resumen['inactivos']) }}
                </strong>

                <small>
                    Conservan sus registros e historial
                </small>
            </div>

        </article>

    </section>

    {{-- Listado --}}
    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Períodos registrados</h2>

                <p>
                    Consulte las fechas de matrícula y desarrollo
                    académico de cada período.
                </p>
            </div>

            <span class="portal-results-count">
                {{ $periodos->total() }}

                {{ $periodos->total() === 1
                    ? 'resultado'
                    : 'resultados' }}
            </span>

        </div>

        {{-- Filtros --}}
        <div class="portal-filter-area">

            <form
                action="{{ route('portal.periodos.index') }}"
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
                        placeholder="Código, nombre u observación..."
                        aria-label="Buscar períodos"
                    >

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
                            value="activo"
                            @selected(
                                $estadoSeleccionado === 'activo'
                            )
                        >
                            Activos
                        </option>

                        <option
                            value="inactivo"
                            @selected(
                                $estadoSeleccionado === 'inactivo'
                            )
                        >
                            Inactivos
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
                    $estadoSeleccionado
                )

                    <a
                        href="{{ route('portal.periodos.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>

        @if ($periodos->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Período</th>
                            <th>Matrícula</th>
                            <th>Desarrollo académico</th>
                            <th>Grupos</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($periodos as $periodo)

                            <tr>

                                <td>

                                    <div class="portal-table-primary">
                                        {{ $periodo->nombre }}
                                    </div>

                                    <small class="portal-table-secondary">
                                        {{ $periodo->codigo }}
                                    </small>

                                </td>

                                <td>

                                    <div class="portal-table-primary">
                                        {{ $periodo->fecha_inicio_matricula
                                            ? $periodo->fecha_inicio_matricula
                                                ->translatedFormat('d M Y')
                                            : 'No definida' }}
                                    </div>

                                    <small class="portal-table-secondary">
                                        hasta
                                        {{ $periodo->fecha_fin_matricula
                                            ? $periodo->fecha_fin_matricula
                                                ->translatedFormat('d M Y')
                                            : 'No definida' }}
                                    </small>

                                    @if ($periodo->matricula_abierta)

                                        <span class="portal-status-badge portal-status-active mt-2">
                                            <span></span>
                                            Matrícula abierta
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="portal-table-primary">
                                        {{ $periodo->fecha_inicio
                                            ? $periodo->fecha_inicio
                                                ->translatedFormat('d M Y')
                                            : 'No definida' }}
                                    </div>

                                    <small class="portal-table-secondary">
                                        hasta
                                        {{ $periodo->fecha_fin
                                            ? $periodo->fecha_fin
                                                ->translatedFormat('d M Y')
                                            : 'No definida' }}
                                    </small>

                                    @if ($periodo->en_curso)

                                        <span class="portal-status-badge portal-status-active mt-2">
                                            <span></span>
                                            En curso
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <strong>
                                        {{ $periodo->grupos_count }}
                                    </strong>

                                    <small class="portal-table-secondary">
                                        {{ $periodo->grupos_count === 1
                                            ? 'grupo'
                                            : 'grupos' }}
                                    </small>

                                </td>

                                <td>

                                    @if ($periodo->estado === 'activo')

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

                                </td>

                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="portal-table-action"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            aria-label="Opciones del período"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.periodos.show',
                                                        $periodo
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Ver período
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.periodos.edit',
                                                        $periodo
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                    Editar período
                                                </a>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item
                                                        {{ $periodo->estado === 'activo'
                                                            ? 'text-warning-emphasis'
                                                            : 'text-success' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#changePeriodStatusModal"
                                                    data-name="{{ $periodo->nombre }}"
                                                    data-status="{{ $periodo->estado }}"
                                                    data-action="{{ route(
                                                        'portal.periodos.cambiar-estado',
                                                        $periodo
                                                    ) }}"
                                                >
                                                    <i class="bi
                                                        {{ $periodo->estado === 'activo'
                                                            ? 'bi-toggle-off'
                                                            : 'bi-toggle-on' }}">
                                                    </i>

                                                    {{ $periodo->estado === 'activo'
                                                        ? 'Desactivar período'
                                                        : 'Activar período' }}
                                                </button>

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

                    <strong>{{ $periodos->firstItem() }}</strong>

                    a

                    <strong>{{ $periodos->lastItem() }}</strong>

                    de

                    <strong>{{ $periodos->total() }}</strong>

                    registros
                </div>

                <div>
                    {{ $periodos->links() }}
                </div>

            </div>

        @else

            <div class="portal-empty-state portal-empty-state-large">

                <div class="portal-empty-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                @if (
                    $termino !== '' ||
                    $estadoSeleccionado
                )

                    <h3>No se encontraron períodos</h3>

                    <p>
                        Pruebe cambiando los filtros o el término
                        utilizado en la búsqueda.
                    </p>

                    <a
                        href="{{ route('portal.periodos.index') }}"
                        class="btn portal-btn-secondary mt-3"
                    >
                        Limpiar filtros
                    </a>

                @else

                    <h3>No hay períodos académicos registrados</h3>

                    <p>
                        Registre un período para comenzar a organizar
                        matrículas, grupos y actividades académicas.
                    </p>

                    <a
                        href="{{ route('portal.periodos.create') }}"
                        class="btn portal-btn-primary mt-3"
                    >
                        <i class="bi bi-plus-circle"></i>
                        Registrar período
                    </a>

                @endif

            </div>

        @endif

    </section>

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
                    method="POST"
                    id="changePeriodStatusForm"
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
                                Cambiar estado del período
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

                        <p
                            id="changePeriodStatusMessage"
                            class="mb-0"
                        ></p>

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
                            class="btn portal-btn-primary"
                            id="changePeriodStatusSubmit"
                        >
                            Confirmar
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById(
            'changePeriodStatusModal'
        );

        const form = document.getElementById(
            'changePeriodStatusForm'
        );

        const message = document.getElementById(
            'changePeriodStatusMessage'
        );

        const submit = document.getElementById(
            'changePeriodStatusSubmit'
        );

        if (!modal || !form || !message || !submit) {
            return;
        }

        modal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const name = button.dataset.name;
            const status = button.dataset.status;

            const willDeactivate =
                status === 'activo';

            form.action = button.dataset.action;

            message.textContent = willDeactivate
                ? `¿Desea desactivar ${name}? Los grupos y registros asociados se conservarán.`
                : `¿Desea activar nuevamente ${name}?`;

            submit.textContent = willDeactivate
                ? 'Desactivar'
                : 'Activar';

            submit.classList.toggle(
                'portal-btn-danger',
                willDeactivate
            );

            submit.classList.toggle(
                'portal-btn-primary',
                !willDeactivate
            );
        });
    });
</script>

@endpush