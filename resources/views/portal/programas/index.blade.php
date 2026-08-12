@extends('layouts.portal')

@section('title', 'Programas | Portal EDMA')

@section('page-title', 'Programas')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Programas académicos</h1>

            <p>
                Administre la oferta académica y los segmentos
                definidos por Edumerican Academy Honduras.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.programas.create') }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-plus-circle"></i>
                Nuevo programa
            </a>

        </div>

    </div>

@endsection

@section('content')

    {{-- Resumen --}}
    <section class="portal-summary-grid">

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-journal-bookmark"></i>
            </div>

            <div>
                <span>Total de programas</span>

                <strong>
                    {{ number_format($totalProgramas) }}
                </strong>

                <small>
                    Oferta académica registrada
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-check-circle"></i>
            </div>

            <div>
                <span>Programas activos</span>

                <strong>
                    {{ number_format($programasActivos) }}
                </strong>

                <small>
                    Disponibles para procesos académicos
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-muted">
                <i class="bi bi-dash-circle"></i>
            </div>

            <div>
                <span>Programas inactivos</span>

                <strong>
                    {{ number_format($programasInactivos) }}
                </strong>

                <small>
                    Conservan su historial
                </small>
            </div>

        </article>

    </section>

    {{-- Directorio --}}
    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Oferta académica</h2>

                <p>
                    Busque por código, nombre, descripción o segmento.
                </p>
            </div>

            <span class="portal-results-count">
                {{ $programas->total() }}

                {{ $programas->total() === 1
                    ? 'resultado'
                    : 'resultados' }}
            </span>

        </div>

        {{-- Filtros --}}
        <div class="portal-filter-area">

            <form
                action="{{ route('portal.programas.index') }}"
                method="GET"
                class="portal-filter-form"
            >

                <div class="portal-search-field">

                    <i class="bi bi-search"></i>

                    <input
                        type="search"
                        name="buscar"
                        value="{{ $buscar }}"
                        class="form-control"
                        placeholder="Código, nombre o segmento..."
                        aria-label="Buscar programas"
                    >

                </div>

                <div class="portal-filter-select">

                    <label
                        for="estado"
                        class="visually-hidden"
                    >
                        Estado
                    </label>

                    <select
                        name="estado"
                        id="estado"
                        class="form-select"
                    >
                        <option value="">
                            Todos los estados
                        </option>

                        <option
                            value="activo"
                            @selected($estado === 'activo')
                        >
                            Activos
                        </option>

                        <option
                            value="inactivo"
                            @selected($estado === 'inactivo')
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

                @if ($buscar !== '' || $estado)

                    <a
                        href="{{ route('portal.programas.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>

        @if ($programas->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Programa</th>
                            <th>Código</th>
                            <th>Segmento</th>
                            <th>Niveles</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($programas as $programa)

                            <tr>

                                <td>

                                    <div class="portal-table-primary">
                                        {{ $programa->nombre }}
                                    </div>

                                    <small class="portal-table-secondary">
                                        {{ $programa->descripcion
                                            ? str($programa->descripcion)->limit(70)
                                            : 'Sin descripción' }}
                                    </small>

                                </td>

                                <td>
                                    <span class="portal-employee-code">
                                        {{ $programa->codigo }}
                                    </span>
                                </td>

                                <td>

                                    @if ($programa->segmento === 'niños')

                                        <span class="portal-role-badge">
                                            <i class="bi bi-emoji-smile"></i>
                                            Niños
                                        </span>

                                    @elseif ($programa->segmento === 'jóvenes_adultos')

                                        <span class="portal-role-badge portal-role-badge-teacher">
                                            <i class="bi bi-people"></i>
                                            Jóvenes y adultos
                                        </span>

                                    @else

                                        <span class="portal-role-badge">
                                            {{ str($programa->segmento)
                                                ->replace('_', ' ')
                                                ->title() }}
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="portal-table-primary">
                                        {{ $programa->niveles_count }}
                                    </div>

                                    <small class="portal-table-secondary">
                                        {{ $programa->niveles_count === 1
                                            ? 'nivel'
                                            : 'niveles' }}
                                    </small>

                                </td>

                                <td>

                                    @if ($programa->estado === 'activo')

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
                                            aria-label="Opciones del programa"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.programas.show',
                                                        $programa
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Ver programa
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.programas.edit',
                                                        $programa
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                    Editar programa
                                                </a>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item
                                                        {{ $programa->estado === 'activo'
                                                            ? 'text-warning-emphasis'
                                                            : 'text-success' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#changeProgramStatusModal"
                                                    data-name="{{ $programa->nombre }}"
                                                    data-status="{{ $programa->estado }}"
                                                    data-action="{{ route(
                                                        'portal.programas.cambiar-estado',
                                                        $programa
                                                    ) }}"
                                                >
                                                    <i class="bi
                                                        {{ $programa->estado === 'activo'
                                                            ? 'bi-toggle-off'
                                                            : 'bi-toggle-on' }}">
                                                    </i>

                                                    {{ $programa->estado === 'activo'
                                                        ? 'Desactivar programa'
                                                        : 'Activar programa' }}
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

                    <strong>
                        {{ $programas->firstItem() }}
                    </strong>

                    a

                    <strong>
                        {{ $programas->lastItem() }}
                    </strong>

                    de

                    <strong>
                        {{ $programas->total() }}
                    </strong>

                    registros
                </div>

                <div>
                    {{ $programas->links() }}
                </div>

            </div>

        @else

            <div class="portal-empty-state portal-empty-state-large">

                <div class="portal-empty-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </div>

                @if ($buscar !== '' || $estado)

                    <h3>No se encontraron programas</h3>

                    <p>
                        No existen programas que coincidan con
                        los criterios seleccionados.
                    </p>

                    <a
                        href="{{ route('portal.programas.index') }}"
                        class="btn portal-btn-secondary mt-3"
                    >
                        Limpiar filtros
                    </a>

                @else

                    <h3>No hay programas registrados</h3>

                    <p>
                        Registre el primer programa académico
                        para comenzar a configurar sus niveles.
                    </p>

                    <a
                        href="{{ route('portal.programas.create') }}"
                        class="btn portal-btn-primary mt-3"
                    >
                        <i class="bi bi-plus-circle"></i>
                        Registrar programa
                    </a>

                @endif

            </div>

        @endif

    </section>

    {{-- Modal estado --}}
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
                    method="POST"
                    id="changeProgramStatusForm"
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
                                Cambiar estado
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

                        <p
                            id="changeProgramStatusMessage"
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
                            id="changeProgramStatusSubmit"
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
            'changeProgramStatusModal'
        );

        const form = document.getElementById(
            'changeProgramStatusForm'
        );

        const message = document.getElementById(
            'changeProgramStatusMessage'
        );

        const submit = document.getElementById(
            'changeProgramStatusSubmit'
        );

        if (!modal || !form || !message || !submit) {
            return;
        }

        modal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;

            const name = button.dataset.name;
            const status = button.dataset.status;

            const willDeactivate =
                status === 'activo';

            form.action = button.dataset.action;

            message.textContent = willDeactivate
                ? `¿Desea desactivar el programa ${name}? Su historial y niveles asociados se conservarán.`
                : `¿Desea activar nuevamente el programa ${name}?`;

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