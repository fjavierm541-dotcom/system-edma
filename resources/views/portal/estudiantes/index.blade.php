@extends('layouts.portal')

@section('title', 'Estudiantes | Portal EDMA')

@section('page-title', 'Estudiantes')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de estudiantes
            </span>

            <h1>Expedientes estudiantiles</h1>

            <p>
                Consulte y administre los estudiantes registrados
                en Edumerican Academy Honduras.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
    href="{{ route('portal.estudiantes.create') }}"
    class="btn portal-btn-primary"
>
    <i class="bi bi-person-plus"></i>
    Registro manual de estudiante
</a>

        </div>

    </div>

@endsection

@section('content')

    {{-- Resumen --}}
    <section class="portal-summary-grid">

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-mortarboard"></i>
            </div>

            <div>
                <span>Total de estudiantes</span>

                <strong>
                    {{ number_format($resumen['total']) }}
                </strong>

                <small>
                    Expedientes registrados
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-person-check"></i>
            </div>

            <div>
                <span>Estudiantes activos</span>

                <strong>
                    {{ number_format($resumen['activos']) }}
                </strong>

                <small>
                    Disponibles para procesos académicos
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-muted">
                <i class="bi bi-person-dash"></i>
            </div>

            <div>
                <span>Estudiantes inactivos</span>

                <strong>
                    {{ number_format($resumen['inactivos']) }}
                </strong>

                <small>
                    Conservan su historial académico
                </small>
            </div>

        </article>

    </section>

    {{-- Listado --}}
    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Directorio de estudiantes</h2>

                <p>
                    Busque por código EDMA, nombre, documento,
                    correo electrónico o teléfono.
                </p>
            </div>

            <span class="portal-results-count">
                {{ $estudiantes->total() }}

                {{ $estudiantes->total() === 1
                    ? 'resultado'
                    : 'resultados' }}
            </span>

        </div>

        {{-- Filtros --}}
        <div class="portal-filter-area">

            <form
                action="{{ route('portal.estudiantes.index') }}"
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
                        placeholder="Código EDMA, nombre, DNI, correo..."
                        aria-label="Buscar estudiantes"
                    >

                </div>

                <div class="portal-filter-select">

                    <label
                        for="estado"
                        class="visually-hidden"
                    >
                        Estado del estudiante
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
                        href="{{ route('portal.estudiantes.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>

        @if ($estudiantes->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table portal-student-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Código EDMA</th>
                            <th>Documento</th>
                            <th>Escolaridad</th>
                            <th>Fecha de ingreso</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($estudiantes as $estudiante)

                            @php
                                $persona = $estudiante->persona;
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
                                                {{ $persona?->iniciales ?: 'ES' }}
                                            </span>

                                        @endif

                                        <div class="portal-person-data">

                                            <a
                                                href="{{ route(
                                                    'portal.estudiantes.show',
                                                    $estudiante
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

                                    <span class="portal-student-code">
                                        {{ $estudiante->codigo_estudiante }}
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

                                {{-- Escolaridad --}}
                                <td>

                                    @if ($estudiante->nivelEscolaridad)

                                        <div class="portal-table-primary">
                                            {{ $estudiante->nivelEscolaridad->nombre }}
                                        </div>

                                    @else

                                        <span class="portal-no-data">
                                            No especificada
                                        </span>

                                    @endif

                                    @if ($estudiante->profesion_ocupacion)

                                        <small class="portal-table-secondary">
                                            {{ $estudiante->profesion_ocupacion }}
                                        </small>

                                    @endif

                                </td>

                                {{-- Fecha de ingreso --}}
                                <td>

                                    <div class="portal-table-primary">
                                        {{ $estudiante->fecha_ingreso
                                            ? $estudiante->fecha_ingreso
                                                ->translatedFormat('d M Y')
                                            : 'No registrada' }}
                                    </div>

                                    @if ($estudiante->fecha_ingreso)

                                        <small class="portal-table-secondary">
                                            {{ $estudiante->fecha_ingreso
                                                ->diffForHumans() }}
                                        </small>

                                    @endif

                                </td>

                                {{-- Estado --}}
                                <td>

                                    @if ($estudiante->estado === 'activo')

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
                                            aria-label="Opciones del estudiante"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.estudiantes.show',
                                                        $estudiante
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
                                                        'portal.estudiantes.edit',
                                                        $estudiante
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
                                                        {{ $estudiante->estado === 'activo'
                                                            ? 'text-warning-emphasis'
                                                            : 'text-success' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#changeStudentStatusModal"
                                                    data-student-name="{{ $persona?->nombre_completo }}"
                                                    data-student-status="{{ $estudiante->estado }}"
                                                    data-action="{{ route(
                                                        'portal.estudiantes.cambiar-estado',
                                                        $estudiante
                                                    ) }}"
                                                >
                                                    <i class="bi
                                                        {{ $estudiante->estado === 'activo'
                                                            ? 'bi-person-dash'
                                                            : 'bi-person-check' }}">
                                                    </i>

                                                    {{ $estudiante->estado === 'activo'
                                                        ? 'Desactivar estudiante'
                                                        : 'Activar estudiante' }}
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
                        {{ $estudiantes->firstItem() }}
                    </strong>

                    a

                    <strong>
                        {{ $estudiantes->lastItem() }}
                    </strong>

                    de

                    <strong>
                        {{ $estudiantes->total() }}
                    </strong>

                    registros
                </div>

                <div>
                    {{ $estudiantes->links() }}
                </div>

            </div>

        @else

            <div class="portal-empty-state portal-empty-state-large">

                <div class="portal-empty-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>

                @if (
                    $termino !== '' ||
                    $estadoSeleccionado
                )

                    <h3>No se encontraron estudiantes</h3>

                    <p>
                        No hay expedientes que coincidan con los criterios
                        de búsqueda seleccionados.
                    </p>

                    <a
                        href="{{ route('portal.estudiantes.index') }}"
                        class="btn portal-btn-secondary mt-3"
                    >
                        Limpiar filtros
                    </a>

                @else

                    <h3>Todavía no hay estudiantes registrados</h3>

                    <p>
                        Seleccione una persona existente para crear
                        su expediente estudiantil.
                    </p>

                    <a
                        href="{{ route('portal.estudiantes.create') }}"
                        class="btn portal-btn-primary mt-3"
                    >
                        <i class="bi bi-mortarboard"></i>
                        Registrar primer estudiante
                    </a>

                @endif

            </div>

        @endif

    </section>

    {{-- Modal para cambiar estado --}}
    <div
        class="modal fade"
        id="changeStudentStatusModal"
        tabindex="-1"
        aria-labelledby="changeStudentStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    method="POST"
                    id="changeStudentStatusForm"
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
                                id="changeStudentStatusModalLabel"
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
                            id="changeStudentStatusMessage"
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
                            id="changeStudentStatusSubmit"
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
                'changeStudentStatusModal'
            );

            const form = document.getElementById(
                'changeStudentStatusForm'
            );

            const message = document.getElementById(
                'changeStudentStatusMessage'
            );

            const submitButton = document.getElementById(
                'changeStudentStatusSubmit'
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

                const studentName =
                    button.dataset.studentName ||
                    'este estudiante';

                const studentStatus =
                    button.dataset.studentStatus;

                const action =
                    button.dataset.action;

                const willDeactivate =
                    studentStatus === 'activo';

                form.action = action;

                message.textContent = willDeactivate
                    ? `¿Desea desactivar a ${studentName}? Su información académica e histórica se conservará.`
                    : `¿Desea activar nuevamente a ${studentName}?`;

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