@extends('layouts.portal')

@section('title', 'Niveles | Portal EDMA')

@section('page-title', 'Niveles académicos')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Niveles académicos</h1>

            <p>
                Administre los niveles que conforman los
                programas académicos de la academia.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.niveles.create') }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-plus-circle"></i>
                Nuevo nivel
            </a>

        </div>

    </div>

@endsection

@section('content')

    {{-- Resumen --}}
    <section class="portal-summary-grid">

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-layers"></i>
            </div>

            <div>
                <span>Total de niveles</span>

                <strong>
                    {{ number_format($resumen['total']) }}
                </strong>

                <small>
                    Niveles registrados
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-check-circle"></i>
            </div>

            <div>
                <span>Niveles activos</span>

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
                <i class="bi bi-dash-circle"></i>
            </div>

            <div>
                <span>Niveles inactivos</span>

                <strong>
                    {{ number_format($resumen['inactivos']) }}
                </strong>

                <small>
                    Conservan su información e historial
                </small>
            </div>

        </article>

    </section>

    {{-- Listado --}}
    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Listado de niveles</h2>

                <p>
                    Consulte los niveles registrados por programa.
                </p>
            </div>

            <span class="portal-results-count">
                {{ $niveles->total() }}

                {{ $niveles->total() === 1
                    ? 'resultado'
                    : 'resultados' }}
            </span>

        </div>

        {{-- Filtros --}}
        <div class="portal-filter-area">

            <form
                action="{{ route('portal.niveles.index') }}"
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
                        placeholder="Buscar por código o nombre..."
                        aria-label="Buscar niveles"
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
                    $programaSeleccionado ||
                    $estadoSeleccionado
                )

                    <a
                        href="{{ route('portal.niveles.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>

        @if ($niveles->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Nivel</th>
                            <th>Programa</th>
                            <th>Orden</th>
                            <th>Duración</th>
                            <th>Nota mínima</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($niveles as $nivel)

                            <tr>

                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="portal-academic-icon">
                                            <i class="bi bi-layers"></i>
                                        </div>

                                        <div>

                                            <div class="portal-table-primary">
                                                {{ $nivel->nombre }}
                                            </div>

                                            <small class="portal-table-secondary">
                                                {{ $nivel->codigo }}
                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <div class="portal-table-primary">
                                        {{ $nivel->programa?->nombre
                                            ?: 'Programa no disponible' }}
                                    </div>

                                    @if ($nivel->programa)

                                        <small class="portal-table-secondary">
                                            {{ $nivel->programa->codigo }}
                                        </small>

                                    @endif

                                </td>

                                <td>
                                    <strong>
                                        {{ $nivel->orden }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $nivel->duracion_semanas }}
                                    semanas
                                </td>

                                <td>

                                    @if (
                                        $nivel->nota_minima_aprobacion
                                        !== null
                                    )

                                        <strong>
                                            {{ number_format(
                                                (float) $nivel
                                                    ->nota_minima_aprobacion,
                                                2
                                            ) }}
                                        </strong>

                                        <small class="portal-table-secondary">
                                            / 100
                                        </small>

                                    @else

                                        <span class="portal-table-secondary">
                                            No definida
                                        </span>

                                    @endif

                                </td>

                                <td>

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

                                </td>

                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="portal-table-action"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            aria-label="Opciones del nivel"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.niveles.show',
                                                        $nivel
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Ver nivel
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.niveles.edit',
                                                        $nivel
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                    Editar nivel
                                                </a>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item
                                                        {{ $nivel->estado === 'activo'
                                                            ? 'text-warning-emphasis'
                                                            : 'text-success' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#changeLevelStatusModal"
                                                    data-name="{{ $nivel->nombre }}"
                                                    data-status="{{ $nivel->estado }}"
                                                    data-action="{{ route(
                                                        'portal.niveles.cambiar-estado',
                                                        $nivel
                                                    ) }}"
                                                >
                                                    <i class="bi
                                                        {{ $nivel->estado === 'activo'
                                                            ? 'bi-toggle-off'
                                                            : 'bi-toggle-on' }}">
                                                    </i>

                                                    {{ $nivel->estado === 'activo'
                                                        ? 'Desactivar nivel'
                                                        : 'Activar nivel' }}
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

                    <strong>{{ $niveles->firstItem() }}</strong>

                    a

                    <strong>{{ $niveles->lastItem() }}</strong>

                    de

                    <strong>{{ $niveles->total() }}</strong>

                    registros
                </div>

                <div>
                    {{ $niveles->links() }}
                </div>

            </div>

        @else

            <div class="portal-empty-state portal-empty-state-large">

                <div class="portal-empty-icon">
                    <i class="bi bi-layers"></i>
                </div>

                @if (
                    $termino !== '' ||
                    $programaSeleccionado ||
                    $estadoSeleccionado
                )

                    <h3>No se encontraron niveles</h3>

                    <p>
                        Pruebe cambiando los filtros o el término
                        utilizado en la búsqueda.
                    </p>

                    <a
                        href="{{ route('portal.niveles.index') }}"
                        class="btn portal-btn-secondary mt-3"
                    >
                        Limpiar filtros
                    </a>

                @else

                    <h3>No hay niveles registrados</h3>

                    <p>
                        Registre los niveles que conforman
                        los programas académicos.
                    </p>

                    <a
                        href="{{ route('portal.niveles.create') }}"
                        class="btn portal-btn-primary mt-3"
                    >
                        <i class="bi bi-plus-circle"></i>
                        Registrar nivel
                    </a>

                @endif

            </div>

        @endif

    </section>

    {{-- Modal de cambio de estado --}}
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
                    method="POST"
                    id="changeLevelStatusForm"
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
                                Cambiar estado del nivel
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

                        <p
                            id="changeLevelStatusMessage"
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
                            id="changeLevelStatusSubmit"
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
            'changeLevelStatusModal'
        );

        const form = document.getElementById(
            'changeLevelStatusForm'
        );

        const message = document.getElementById(
            'changeLevelStatusMessage'
        );

        const submit = document.getElementById(
            'changeLevelStatusSubmit'
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
                ? `¿Desea desactivar el nivel ${name}? La información registrada se conservará.`
                : `¿Desea activar nuevamente el nivel ${name}?`;

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