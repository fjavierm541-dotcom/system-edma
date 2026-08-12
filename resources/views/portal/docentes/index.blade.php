@extends('layouts.portal')

@section('title', 'Docentes | Portal EDMA')

@section('page-title', 'Docentes')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>Directorio de docentes</h1>

            <p>
                Consulte y administre los perfiles docentes
                registrados en Edumerican Academy Honduras.
            </p>
        </div>

        <div class="portal-page-actions">

            <a
                href="{{ route('portal.docentes.create') }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-person-video3"></i>
                Registrar docente
            </a>

        </div>

    </div>

@endsection

@section('content')

    {{-- Resumen --}}
    <section class="portal-summary-grid">

        <article class="portal-summary-card">

            <div class="portal-summary-icon">
                <i class="bi bi-easel"></i>
            </div>

            <div>
                <span>Total de docentes</span>

                <strong>
                    {{ number_format($resumen['total']) }}
                </strong>

                <small>
                    Perfiles docentes registrados
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-success">
                <i class="bi bi-person-check"></i>
            </div>

            <div>
                <span>Docentes activos</span>

                <strong>
                    {{ number_format($resumen['activos']) }}
                </strong>

                <small>
                    Disponibles para asignación académica
                </small>
            </div>

        </article>

        <article class="portal-summary-card">

            <div class="portal-summary-icon portal-summary-icon-muted">
                <i class="bi bi-person-dash"></i>
            </div>

            <div>
                <span>Docentes inactivos</span>

                <strong>
                    {{ number_format($resumen['inactivos']) }}
                </strong>

                <small>
                    Conservan su historial docente
                </small>
            </div>

        </article>

    </section>

    {{-- Listado --}}
    <section class="portal-card">

        <div class="portal-card-header portal-card-header-responsive">

            <div>
                <h2>Docentes registrados</h2>

                <p>
                    Busque por código docente, nombre, documento,
                    correo electrónico o especialidad.
                </p>
            </div>

            <span class="portal-results-count">
                {{ $docentes->total() }}

                {{ $docentes->total() === 1
                    ? 'resultado'
                    : 'resultados' }}
            </span>

        </div>

        {{-- Filtros --}}
        <div class="portal-filter-area">

            <form
                action="{{ route('portal.docentes.index') }}"
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
                        placeholder="Código, nombre, DNI, especialidad..."
                        aria-label="Buscar docentes"
                    >

                </div>

                <div class="portal-filter-select">

                    <label
                        for="estado"
                        class="visually-hidden"
                    >
                        Estado del docente
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
                        href="{{ route('portal.docentes.index') }}"
                        class="btn portal-btn-secondary"
                    >
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>

                @endif

            </form>

        </div>

        @if ($docentes->isNotEmpty())

            <div class="portal-table-responsive">

                <table class="table portal-table portal-teacher-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Docente</th>
                            <th>Código docente</th>
                            <th>Documento</th>
                            <th>Especialidad</th>
                            <th>Inicio de docencia</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($docentes as $docente)

                            @php
                                $empleado = $docente->empleado;
                                $persona = $empleado?->persona;
                            @endphp

                            <tr>

                                {{-- Docente --}}
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
                                                {{ $persona?->iniciales ?: 'DO' }}
                                            </span>

                                        @endif

                                        <div class="portal-person-data">

                                            <a
                                                href="{{ route(
                                                    'portal.docentes.show',
                                                    $docente
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

                                    <span class="portal-teacher-code">
                                        {{ $docente->codigo_docente }}
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

                                {{-- Especialidad --}}
                                <td>

                                    @if ($docente->especialidad)

                                        <div class="portal-table-primary">
                                            {{ $docente->especialidad }}
                                        </div>

                                    @else

                                        <span class="portal-no-data">
                                            No especificada
                                        </span>

                                    @endif

                                    @if ($empleado?->codigo_empleado)

                                        <small class="portal-table-secondary">
                                            {{ $empleado->codigo_empleado }}
                                        </small>

                                    @endif

                                </td>

                                {{-- Inicio --}}
                                <td>

                                    <div class="portal-table-primary">
                                        {{ $docente->fecha_inicio_docencia
                                            ? $docente->fecha_inicio_docencia
                                                ->translatedFormat('d M Y')
                                            : 'No registrada' }}
                                    </div>

                                    @if ($docente->fecha_inicio_docencia)

                                        <small class="portal-table-secondary">
                                            {{ $docente->fecha_inicio_docencia
                                                ->diffForHumans() }}
                                        </small>

                                    @endif

                                </td>

                                {{-- Estado --}}
                                <td>

                                    @if ($docente->estado === 'activo')

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
                                            aria-label="Opciones del docente"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.docentes.show',
                                                        $docente
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Ver perfil docente
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    href="{{ route(
                                                        'portal.docentes.edit',
                                                        $docente
                                                    ) }}"
                                                    class="dropdown-item"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                    Editar perfil
                                                </a>
                                            </li>

                                            @if ($empleado)

                                                <li>
                                                    <a
                                                        href="{{ route(
                                                            'portal.empleados.show',
                                                            $empleado
                                                        ) }}"
                                                        class="dropdown-item"
                                                    >
                                                        <i class="bi bi-briefcase"></i>
                                                        Ver expediente laboral
                                                    </a>
                                                </li>

                                            @endif

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
                                                        {{ $docente->estado === 'activo'
                                                            ? 'text-warning-emphasis'
                                                            : 'text-success' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#changeTeacherStatusModal"
                                                    data-teacher-name="{{ $persona?->nombre_completo }}"
                                                    data-teacher-status="{{ $docente->estado }}"
                                                    data-action="{{ route(
                                                        'portal.docentes.cambiar-estado',
                                                        $docente
                                                    ) }}"
                                                >
                                                    <i class="bi
                                                        {{ $docente->estado === 'activo'
                                                            ? 'bi-person-dash'
                                                            : 'bi-person-check' }}">
                                                    </i>

                                                    {{ $docente->estado === 'activo'
                                                        ? 'Desactivar docente'
                                                        : 'Activar docente' }}
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
                        {{ $docentes->firstItem() }}
                    </strong>

                    a

                    <strong>
                        {{ $docentes->lastItem() }}
                    </strong>

                    de

                    <strong>
                        {{ $docentes->total() }}
                    </strong>

                    registros
                </div>

                <div>
                    {{ $docentes->links() }}
                </div>

            </div>

        @else

            <div class="portal-empty-state portal-empty-state-large">

                <div class="portal-empty-icon">
                    <i class="bi bi-easel"></i>
                </div>

                @if (
                    $termino !== '' ||
                    $estadoSeleccionado
                )

                    <h3>No se encontraron docentes</h3>

                    <p>
                        No existen perfiles docentes que coincidan
                        con los criterios seleccionados.
                    </p>

                    <a
                        href="{{ route('portal.docentes.index') }}"
                        class="btn portal-btn-secondary mt-3"
                    >
                        Limpiar filtros
                    </a>

                @else

                    <h3>Todavía no hay docentes registrados</h3>

                    <p>
                        Seleccione un empleado activo para crear
                        su perfil docente.
                    </p>

                    <a
                        href="{{ route('portal.docentes.create') }}"
                        class="btn portal-btn-primary mt-3"
                    >
                        <i class="bi bi-person-video3"></i>
                        Registrar docente
                    </a>

                @endif

            </div>

        @endif

    </section>

    {{-- Modal de estado --}}
    <div
        class="modal fade"
        id="changeTeacherStatusModal"
        tabindex="-1"
        aria-labelledby="changeTeacherStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">

                <form
                    method="POST"
                    id="changeTeacherStatusForm"
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
                                id="changeTeacherStatusModalLabel"
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
                            id="changeTeacherStatusMessage"
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
                            id="changeTeacherStatusSubmit"
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
            'changeTeacherStatusModal'
        );

        const form = document.getElementById(
            'changeTeacherStatusForm'
        );

        const message = document.getElementById(
            'changeTeacherStatusMessage'
        );

        const submitButton = document.getElementById(
            'changeTeacherStatusSubmit'
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

            const teacherName =
                button.dataset.teacherName ||
                'este docente';

            const teacherStatus =
                button.dataset.teacherStatus;

            const action =
                button.dataset.action;

            const willDeactivate =
                teacherStatus === 'activo';

            form.action = action;

            message.textContent = willDeactivate
                ? `¿Desea desactivar a ${teacherName} como docente? Su historial se conservará.`
                : `¿Desea activar nuevamente a ${teacherName} como docente?`;

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