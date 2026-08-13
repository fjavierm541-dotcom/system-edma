@extends('layouts.portal')

@section('title', 'Horarios | Portal EDMA')

@section('page-title', 'Horarios')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Horarios</h1>

            <p>
                Administre las franjas horarias que podrán
                asignarse a los grupos académicos.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.horarios.create') }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-plus-circle"></i>
                Nuevo horario
            </a>

        </div>

    </div>

@endsection

@section('content')

    {{-- Resumen --}}
    <section class="portal-summary-grid">

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-clock"></i>
            </div>

            <div>
                <span>Total de horarios</span>

                <strong>
                    {{ number_format($resumen['total']) }}
                </strong>

                <small>
                    Franjas horarias registradas
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-clock-history"></i>
            </div>

            <div>
                <span>Horarios activos</span>

                <strong>
                    {{ number_format($resumen['activos']) }}
                </strong>

                <small>
                    Disponibles para asignar a grupos
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-muted">
                <i class="bi bi-clock-fill"></i>
            </div>

            <div>
                <span>Horarios inactivos</span>

                <strong>
                    {{ number_format($resumen['inactivos']) }}
                </strong>

                <small>
                    Conservan sus asignaciones anteriores
                </small>
            </div>

        </article>

    </section>

    {{-- Listado --}}
    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Franjas horarias</h2>

                <p>
                    Consulte los horarios disponibles para
                    organizar las clases.
                </p>
            </div>

            <span class="portal-results-count">
                {{ $horarios->total() }}

                {{ $horarios->total() === 1
                    ? 'resultado'
                    : 'resultados' }}
            </span>

        </div>

        <div class="portal-filter-area">

            <form
                action="{{ route('portal.horarios.index') }}"
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
                        placeholder="Buscar horario..."
                        aria-label="Buscar horarios"
                    >

                </div>

                <div class="portal-filter-select">

                    <select
                        name="estado"
                        class="form-select"
                        aria-label="Filtrar por disponibilidad"
                    >
                        <option value="">
                            Todos
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
                        href="{{ route('portal.horarios.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>

        @if ($horarios->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Horario</th>
                            <th>Hora de inicio</th>
                            <th>Hora de finalización</th>
                            <th>Zona horaria</th>
                            <th>Asignaciones</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($horarios as $horario)

                            @php
                                $inicio = \Carbon\Carbon::createFromFormat(
                                    'H:i:s',
                                    $horario->hora_inicio
                                );

                                $fin = \Carbon\Carbon::createFromFormat(
                                    'H:i:s',
                                    $horario->hora_fin
                                );
                            @endphp

                            <tr>

                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="portal-academic-icon">
                                            <i class="bi bi-clock"></i>
                                        </div>

                                        <div>
                                            <div class="portal-table-primary">
                                                {{ $horario->nombre }}
                                            </div>

                                            <small class="portal-table-secondary">
                                                {{ $inicio->format('g:i A') }}
                                                -
                                                {{ $fin->format('g:i A') }}
                                            </small>
                                        </div>

                                    </div>

                                </td>

                                <td>
                                    <strong>
                                        {{ $inicio->format('g:i A') }}
                                    </strong>
                                </td>

                                <td>
                                    <strong>
                                        {{ $fin->format('g:i A') }}
                                    </strong>
                                </td>

                                <td>
                                    Honduras (UTC-6)
                                </td>

                                <td>

                                    <strong>
                                        {{ $horario->grupo_horarios_count }}
                                    </strong>

                                    <small class="portal-table-secondary">
                                        {{ $horario->grupo_horarios_count === 1
                                            ? 'asignación'
                                            : 'asignaciones' }}
                                    </small>

                                </td>

                                <td>

                                    @if ($horario->activo)

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
                                            aria-label="Opciones del horario"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.horarios.show',
                                                        $horario
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Ver horario
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.horarios.edit',
                                                        $horario
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                    Editar horario
                                                </a>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item
                                                        {{ $horario->activo
                                                            ? 'text-warning-emphasis'
                                                            : 'text-success' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#changeScheduleStatusModal"
                                                    data-name="{{ $horario->nombre }}"
                                                    data-status="{{ $horario->activo ? '1' : '0' }}"
                                                    data-action="{{ route(
                                                        'portal.horarios.cambiar-estado',
                                                        $horario
                                                    ) }}"
                                                >
                                                    <i class="bi
                                                        {{ $horario->activo
                                                            ? 'bi-toggle-off'
                                                            : 'bi-toggle-on' }}">
                                                    </i>

                                                    {{ $horario->activo
                                                        ? 'Desactivar horario'
                                                        : 'Activar horario' }}
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
                    <strong>{{ $horarios->firstItem() }}</strong>
                    a
                    <strong>{{ $horarios->lastItem() }}</strong>
                    de
                    <strong>{{ $horarios->total() }}</strong>
                    registros
                </div>

                <div>
                    {{ $horarios->links() }}
                </div>

            </div>

        @else

            <div class="portal-empty-state portal-empty-state-large">

                <div class="portal-empty-icon">
                    <i class="bi bi-clock"></i>
                </div>

                @if (
                    $termino !== '' ||
                    $estadoSeleccionado
                )

                    <h3>No se encontraron horarios</h3>

                    <p>
                        Pruebe cambiando los filtros o el término
                        utilizado en la búsqueda.
                    </p>

                    <a
                        href="{{ route('portal.horarios.index') }}"
                        class="btn portal-btn-secondary mt-3"
                    >
                        Limpiar filtros
                    </a>

                @else

                    <h3>No hay horarios registrados</h3>

                    <p>
                        Registre las franjas horarias que utilizará
                        la academia para organizar sus grupos.
                    </p>

                    <a
                        href="{{ route('portal.horarios.create') }}"
                        class="btn portal-btn-primary mt-3"
                    >
                        <i class="bi bi-plus-circle"></i>
                        Registrar horario
                    </a>

                @endif

            </div>

        @endif

    </section>

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
                    method="POST"
                    id="changeScheduleStatusForm"
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
                                Cambiar disponibilidad
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

                        <p
                            id="changeScheduleStatusMessage"
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
                            id="changeScheduleStatusSubmit"
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
            'changeScheduleStatusModal'
        );

        const form = document.getElementById(
            'changeScheduleStatusForm'
        );

        const message = document.getElementById(
            'changeScheduleStatusMessage'
        );

        const submit = document.getElementById(
            'changeScheduleStatusSubmit'
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

            const active =
                button.dataset.status === '1';

            form.action = button.dataset.action;

            message.textContent = active
                ? `¿Desea desactivar ${name}? Las asignaciones existentes se conservarán.`
                : `¿Desea activar nuevamente ${name}?`;

            submit.textContent = active
                ? 'Desactivar'
                : 'Activar';

            submit.classList.toggle(
                'portal-btn-danger',
                active
            );

            submit.classList.toggle(
                'portal-btn-primary',
                !active
            );
        });
    });
</script>

@endpush