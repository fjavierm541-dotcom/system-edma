@extends('layouts.portal')

@section('title', 'Empleados | Portal EDMA')

@section('page-title', 'Empleados')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de recursos humanos
            </span>

            <h1>Expedientes de empleados</h1>

            <p>
                Consulte y administre la información laboral
                del personal registrado en Edumerican Academy Honduras.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.empleados.create') }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-person-badge"></i>
                Nuevo empleado
            </a>

        </div>

    </div>

@endsection

@section('content')

    {{-- Resumen --}}
    <section class="portal-summary-grid">

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-briefcase"></i>
            </div>

            <div>
                <span>Total de empleados</span>

                <strong>
                    {{ number_format($resumen['total']) }}
                </strong>

                <small>
                    Expedientes laborales
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-person-check"></i>
            </div>

            <div>
                <span>Empleados activos</span>

                <strong>
                    {{ number_format($resumen['activos']) }}
                </strong>

                <small>
                    Personal actualmente activo
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-muted">
                <i class="bi bi-person-dash"></i>
            </div>

            <div>
                <span>Empleados inactivos</span>

                <strong>
                    {{ number_format($resumen['inactivos']) }}
                </strong>

                <small>
                    Conservan su historial laboral
                </small>
            </div>

        </article>

    </section>

    {{-- Listado --}}
    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Directorio de empleados</h2>

                <p>
                    Busque por código, nombre, documento, correo,
                    teléfono o institución laboral.
                </p>
            </div>

            <span class="portal-results-count">
                {{ $empleados->total() }}

                {{ $empleados->total() === 1
                    ? 'resultado'
                    : 'resultados' }}
            </span>

        </div>

        {{-- Filtros --}}
        <div class="portal-filter-area">

            <form
                action="{{ route('portal.empleados.index') }}"
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
                        placeholder="Código, nombre, DNI, correo..."
                        aria-label="Buscar empleados"
                    >

                </div>

                <div class="portal-filter-select">

                    <label
                        for="estado"
                        class="visually-hidden"
                    >
                        Estado del empleado
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
                        href="{{ route('portal.empleados.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>

        @if ($empleados->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table portal-employee-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>Código</th>
                            <th>Documento</th>
                            <th>Ingreso</th>
                            <th>Situación laboral</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($empleados as $empleado)

                            @php
                                $persona = $empleado->persona;
                            @endphp

                            <tr>

                                {{-- Persona --}}
                                <td>

                                    <div class="portal-person-cell">

                                        @if ($persona?->foto_perfil)

                                            <img
                                                src="{{ asset(
                                                    'storage/' .
                                                    $persona->foto_perfil
                                                ) }}"
                                                alt="Fotografía de {{ $persona->nombre_completo }}"
                                                class="portal-person-avatar"
                                            >

                                        @else

                                            <span class="portal-person-avatar portal-person-avatar-placeholder">
                                                {{ $persona?->iniciales ?: 'EM' }}
                                            </span>

                                        @endif

                                        <div class="portal-person-data">

                                            <a
                                                href="{{ route(
                                                    'portal.empleados.show',
                                                    $empleado
                                                ) }}"
                                                class="portal-person-name"
                                            >
                                                {{ $persona?->nombre_completo
                                                    ?: 'Persona no disponible' }}
                                            </a>

                                            @if ($persona?->correo_personal)

                                                <small class="portal-text-ellipsis">
                                                    {{ $persona->correo_personal }}
                                                </small>

                                            @elseif ($persona?->telefono_movil)

                                                <small>
                                                    {{ $persona->telefono_movil }}
                                                </small>

                                            @else

                                                <small>
                                                    Sin información de contacto
                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>

                                {{-- Código --}}
                                <td>

                                    <span class="portal-employee-code">
                                        {{ $empleado->codigo_empleado }}
                                    </span>

                                </td>

                                {{-- Documento --}}
                                <td>

                                    @if ($persona?->numero_documento)

                                        <div class="portal-table-primary">
                                            {{ $persona->numero_documento }}
                                        </div>

                                        <small class="portal-table-secondary">
                                            {{ str($persona->tipo_documento)
                                                ->replace('_', ' ')
                                                ->title() }}
                                        </small>

                                    @else

                                        <span class="portal-no-data">
                                            Sin documento
                                        </span>

                                    @endif

                                </td>

                                {{-- Ingreso --}}
                                <td>

                                    <div class="portal-table-primary">
                                        {{ $empleado->fecha_ingreso
                                            ? $empleado->fecha_ingreso
                                                ->translatedFormat('d M Y')
                                            : 'No registrada' }}
                                    </div>

                                    @if ($empleado->fecha_ingreso)

                                        <small class="portal-table-secondary">
                                            {{ $empleado->fecha_ingreso
                                                ->diffForHumans() }}
                                        </small>

                                    @endif

                                </td>

                                {{-- Situación laboral --}}
                                <td>

                                    @if ($empleado->institucion_laboral_actual)

                                        <div class="portal-table-primary">
                                            {{ $empleado->institucion_laboral_actual }}
                                        </div>

                                        @if ($empleado->horario_laboral_actual)

                                            <small class="portal-table-secondary">
                                                {{ $empleado->horario_laboral_actual }}
                                            </small>

                                        @endif

                                    @else

                                        <span class="portal-no-data">
                                            No especificada
                                        </span>

                                    @endif

                                </td>

                                {{-- Rol --}}
                                <td>

                                    @if ($empleado->docente)

                                        <span class="portal-role-badge portal-role-badge-teacher">
                                            <i class="bi bi-easel"></i>
                                            Docente
                                        </span>

                                    @else

                                        <span class="portal-role-badge">
                                            <i class="bi bi-briefcase"></i>
                                            Empleado
                                        </span>

                                    @endif

                                </td>

                                {{-- Estado --}}
                                <td>

                                    @if ($empleado->estado === 'activo')

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

                                {{-- Acciones --}}
                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="portal-table-action"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            aria-label="Opciones del empleado"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.empleados.show',
                                                        $empleado
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Ver expediente
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.empleados.edit',
                                                        $empleado
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                    Editar información
                                                </a>
                                            </li>

                                            @if ($persona)

                                                <li>
                                                    <a
                                                        href="{{ route(
                                                            'portal.personas.show',
                                                            $persona
                                                        ) }}"
                                                        class="dropdown-item"
                                                    >
                                                        <i class="bi bi-person-vcard"></i>
                                                        Ver datos personales
                                                    </a>
                                                </li>

                                            @endif

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item
                                                        {{ $empleado->estado === 'activo'
                                                            ? 'text-warning-emphasis'
                                                            : 'text-success' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#changeEmployeeStatusModal"
                                                    data-employee-name="{{ $persona?->nombre_completo }}"
                                                    data-employee-status="{{ $empleado->estado }}"
                                                    data-action="{{ route(
                                                        'portal.empleados.cambiar-estado',
                                                        $empleado
                                                    ) }}"
                                                >
                                                    <i class="bi
                                                        {{ $empleado->estado === 'activo'
                                                            ? 'bi-person-dash'
                                                            : 'bi-person-check' }}">
                                                    </i>

                                                    {{ $empleado->estado === 'activo'
                                                        ? 'Desactivar empleado'
                                                        : 'Activar empleado' }}
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
                        {{ $empleados->firstItem() }}
                    </strong>

                    a

                    <strong>
                        {{ $empleados->lastItem() }}
                    </strong>

                    de

                    <strong>
                        {{ $empleados->total() }}
                    </strong>

                    registros
                </div>

                <div>
                    {{ $empleados->links() }}
                </div>

            </div>

        @else

            <div class="portal-empty-state portal-empty-state-large">

                <div class="portal-empty-icon">
                    <i class="bi bi-briefcase"></i>
                </div>

                @if (
                    $termino !== '' ||
                    $estadoSeleccionado
                )

                    <h3>No se encontraron empleados</h3>

                    <p>
                        No existen expedientes laborales que coincidan
                        con los criterios seleccionados.
                    </p>

                    <a
                        href="{{ route('portal.empleados.index') }}"
                        class="btn portal-btn-secondary mt-3"
                    >
                        Limpiar filtros
                    </a>

                @else

                    <h3>Todavía no hay empleados registrados</h3>

                    <p>
                        Seleccione una persona existente para crear
                        su expediente laboral.
                    </p>

                    <a
                        href="{{ route('portal.empleados.create') }}"
                        class="btn portal-btn-primary mt-3"
                    >
                        <i class="bi bi-person-badge"></i>
                        Registrar empleado
                    </a>

                @endif

            </div>

        @endif

    </section>

    {{-- Modal para cambiar estado --}}
    <div
        class="modal fade"
        id="changeEmployeeStatusModal"
        tabindex="-1"
        aria-labelledby="changeEmployeeStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    method="POST"
                    id="changeEmployeeStatusForm"
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
                                id="changeEmployeeStatusModalLabel"
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

                        <p
                            id="changeEmployeeStatusMessage"
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
                            id="changeEmployeeStatusSubmit"
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
                'changeEmployeeStatusModal'
            );

            const form = document.getElementById(
                'changeEmployeeStatusForm'
            );

            const message = document.getElementById(
                'changeEmployeeStatusMessage'
            );

            const submitButton = document.getElementById(
                'changeEmployeeStatusSubmit'
            );

            if (
                !modal ||
                !form ||
                !message ||
                !submitButton
            ) {
                return;
            }

            modal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;

                const employeeName =
                    button.dataset.employeeName ||
                    'este empleado';

                const employeeStatus =
                    button.dataset.employeeStatus;

                const action =
                    button.dataset.action;

                const willDeactivate =
                    employeeStatus === 'activo';

                form.action = action;

                message.textContent = willDeactivate
                    ? `¿Desea desactivar a ${employeeName}? Su historial laboral se conservará.`
                    : `¿Desea activar nuevamente a ${employeeName}?`;

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