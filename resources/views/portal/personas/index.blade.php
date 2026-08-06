@extends('layouts.portal')

@section('title', 'Personas | Portal EDMA')

@section('page-title', 'Personas')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de personas
            </span>

            <h1>Personas registradas</h1>

            <p>
                Administre la información personal que será utilizada
                posteriormente en los expedientes de estudiantes,
                empleados, docentes y responsables.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.personas.create') }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-person-plus"></i>
                Nueva persona
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
                <span>Total registradas</span>
                <strong>{{ number_format($resumen['total']) }}</strong>
                <small>Registros generales</small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-person-check"></i>
            </div>

            <div>
                <span>Personas activas</span>
                <strong>{{ number_format($resumen['activas']) }}</strong>
                <small>Disponibles para procesos</small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-muted">
                <i class="bi bi-person-dash"></i>
            </div>

            <div>
                <span>Personas inactivas</span>
                <strong>{{ number_format($resumen['inactivas']) }}</strong>
                <small>Conservan su historial</small>
            </div>

        </article>

    </section>

    {{-- Filtros --}}
    <section class="portal-card portal-personas-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Directorio de personas</h2>

                <p>
                    Busque por nombre, documento, RTN, correo o teléfono.
                </p>
            </div>

            <span class="portal-results-count">
                {{ $personas->total() }}
                {{ $personas->total() === 1 ? 'resultado' : 'resultados' }}
            </span>

        </div>

        <div class="portal-filter-area">

            <form
                action="{{ route('portal.personas.index') }}"
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
                        placeholder="Buscar por nombre, documento, correo..."
                        aria-label="Buscar personas"
                    >

                </div>

                <div class="portal-filter-select">

                    <label
                        for="estado"
                        class="visually-hidden"
                    >
                        Filtrar por estado
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
                            @selected($estadoSeleccionado === 'activo')
                        >
                            Activas
                        </option>

                        <option
                            value="inactivo"
                            @selected($estadoSeleccionado === 'inactivo')
                        >
                            Inactivas
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

                @if ($termino !== '' || $estadoSeleccionado)
                    <a
                        href="{{ route('portal.personas.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>
                @endif

            </form>

        </div>

        {{-- Tabla --}}
        @if ($personas->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Persona</th>
                            <th>Documento</th>
                            <th>Contacto</th>
                            <th>Residencia</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($personas as $persona)

                            <tr>

                                <td>

                                    <div class="portal-person-cell">

                                        @if ($persona->foto_perfil)

                                            <img
                                                src="{{ asset('storage/' . $persona->foto_perfil) }}"
                                                alt="Fotografía de {{ $persona->nombre_completo }}"
                                                class="portal-person-avatar"
                                            >

                                        @else

                                            <span class="portal-person-avatar portal-person-avatar-placeholder">
                                                {{ $persona->iniciales ?: 'PE' }}
                                            </span>

                                        @endif

                                        <div class="portal-person-data">

                                            <a
                                                href="{{ route('portal.personas.show', $persona) }}"
                                                class="portal-person-name"
                                            >
                                                {{ $persona->nombre_completo }}
                                            </a>

                                            <small>
                                                Registro #{{ str_pad($persona->id, 5, '0', STR_PAD_LEFT) }}
                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    @if ($persona->numero_documento)

                                        <div class="portal-table-primary">
                                            {{ $persona->numero_documento }}
                                        </div>

                                        <small class="portal-table-secondary">
                                            {{ str($persona->tipo_documento)->replace('_', ' ')->title() }}
                                        </small>

                                    @else

                                        <span class="portal-no-data">
                                            Sin documento
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if ($persona->correo_personal)

                                        <div class="portal-table-primary portal-text-ellipsis">
                                            {{ $persona->correo_personal }}
                                        </div>

                                    @endif

                                    @if ($persona->telefono_movil)

                                        <small class="portal-table-secondary">
                                            {{ $persona->telefono_movil }}

                                            @if ($persona->telefono_movil_whatsapp)
                                                <i
                                                    class="bi bi-whatsapp portal-whatsapp-icon"
                                                    title="Disponible en WhatsApp"
                                                ></i>
                                            @endif
                                        </small>

                                    @endif

                                    @if (!$persona->correo_personal && !$persona->telefono_movil)

                                        <span class="portal-no-data">
                                            Sin contacto
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if (
                                        $persona->ciudad_municipio ||
                                        $persona->departamento_estado ||
                                        $persona->paisResidencia
                                    )

                                        <div class="portal-table-primary">
                                            {{ $persona->ciudad_municipio ?: 'No especificada' }}
                                        </div>

                                        <small class="portal-table-secondary">

                                            {{ collect([
                                                $persona->departamento_estado,
                                                $persona->paisResidencia?->nombre,
                                            ])->filter()->implode(', ') }}

                                        </small>

                                    @else

                                        <span class="portal-no-data">
                                            Sin residencia
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if ($persona->estado === 'activo')

                                        <span class="portal-status-badge portal-status-active">
                                            <span></span>
                                            Activa
                                        </span>

                                    @else

                                        <span class="portal-status-badge portal-status-inactive">
                                            <span></span>
                                            Inactiva
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
                                            aria-label="Opciones para {{ $persona->nombre_completo }}"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                            <li>
                                                <a
                                                    href="{{ route('portal.personas.show', $persona) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Ver expediente
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    href="{{ route('portal.personas.edit', $persona) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                    Editar información
                                                </a>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item
                                                        {{ $persona->estado === 'activo'
                                                            ? 'text-warning-emphasis'
                                                            : 'text-success' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#changeStatusModal"
                                                    data-person-id="{{ $persona->id }}"
                                                    data-person-name="{{ $persona->nombre_completo }}"
                                                    data-person-status="{{ $persona->estado }}"
                                                    data-action="{{ route('portal.personas.cambiar-estado', $persona) }}"
                                                >
                                                    <i class="bi
                                                        {{ $persona->estado === 'activo'
                                                            ? 'bi-person-dash'
                                                            : 'bi-person-check' }}">
                                                    </i>

                                                    {{ $persona->estado === 'activo'
                                                        ? 'Desactivar persona'
                                                        : 'Activar persona' }}
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
                    <strong>{{ $personas->firstItem() }}</strong>
                    a
                    <strong>{{ $personas->lastItem() }}</strong>
                    de
                    <strong>{{ $personas->total() }}</strong>
                    registros
                </div>

                <div>
                    {{ $personas->links() }}
                </div>

            </div>

        @else

            <div class="portal-empty-state portal-empty-state-large">

                <div class="portal-empty-icon">
                    <i class="bi bi-person-search"></i>
                </div>

                @if ($termino !== '' || $estadoSeleccionado)

                    <h3>No se encontraron coincidencias</h3>

                    <p>
                        No hay personas que coincidan con los criterios
                        de búsqueda seleccionados.
                    </p>

                    <a
                        href="{{ route('portal.personas.index') }}"
                        class="btn portal-btn-secondary mt-3"
                    >
                        Limpiar filtros
                    </a>

                @else

                    <h3>Todavía no hay personas registradas</h3>

                    <p>
                        Registre la primera persona para comenzar a construir
                        los expedientes del sistema.
                    </p>

                    <a
                        href="{{ route('portal.personas.create') }}"
                        class="btn portal-btn-primary mt-3"
                    >
                        <i class="bi bi-person-plus"></i>
                        Registrar primera persona
                    </a>

                @endif

            </div>

        @endif

    </section>

    {{-- Modal de cambio de estado --}}
    <div
        class="modal fade"
        id="changeStatusModal"
        tabindex="-1"
        aria-labelledby="changeStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    method="POST"
                    id="changeStatusForm"
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
                                id="changeStatusModalLabel"
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
                            <i class="bi bi-person-gear"></i>
                        </div>

                        <p id="changeStatusMessage" class="mb-0"></p>

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
                            id="changeStatusSubmit"
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
            const modal = document.getElementById('changeStatusModal');
            const form = document.getElementById('changeStatusForm');
            const message = document.getElementById('changeStatusMessage');
            const submitButton = document.getElementById('changeStatusSubmit');

            if (!modal || !form || !message || !submitButton) {
                return;
            }

            modal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;

                const personName = button.dataset.personName;
                const personStatus = button.dataset.personStatus;
                const action = button.dataset.action;

                const willDeactivate = personStatus === 'activo';

                form.action = action;

                message.textContent = willDeactivate
                    ? `¿Desea desactivar a ${personName}? La información histórica se conservará.`
                    : `¿Desea activar nuevamente a ${personName}?`;

                submitButton.textContent = willDeactivate
                    ? 'Desactivar'
                    : 'Activar';

                submitButton.classList.toggle(
                    'portal-btn-danger',
                    willDeactivate
                );

                submitButton.classList.toggle(
                    'portal-btn-primary',
                    !willDeactivate
                );
            });
        });
    </script>

@endpush