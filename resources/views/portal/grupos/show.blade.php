@extends('layouts.portal')

@section('title', $grupo->etiqueta_completa . ' | Portal EDMA')

@section('page-title', 'Grupo académico')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>{{ $grupo->etiqueta_completa }}</h1>

            <p>
                Consulte la configuración, horarios y docentes
                asociados al grupo.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.grupos.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route(
                    'portal.grupos.edit',
                    $grupo
                ) }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-pencil-square"></i>
                Editar grupo
            </a>

        </div>

    </div>

@endsection

@section('content')

    <div class="row g-4">

        <div class="col-12 col-xl-4">

            <section class="portal-card portal-profile-card">

                <div class="portal-profile-cover"></div>

                <div class="portal-profile-content">

                    <div class="portal-profile-photo">
                        <span>
                            <i class="bi bi-people"></i>
                        </span>
                    </div>

                    <h2>{{ $grupo->nombre }}</h2>

                    <span class="portal-employee-code mt-2">
                        {{ $grupo->codigo }}
                    </span>

                    <div class="mt-3">

                        @if ($grupo->estado === 'activo')

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Grupo activo
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Grupo inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Modalidad</span>
                        <strong>Virtual</strong>
                    </div>

                    <div>
                        <span>Cupo máximo</span>
                        <strong>{{ $grupo->cupo_maximo }}</strong>
                    </div>

                </div>

            </section>

        </div>

        <div class="col-12 col-xl-8">

            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h2>Información general</h2>

                        <p>
                            Configuración académica del grupo.
                        </p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>Nombre</span>
                        <strong>{{ $grupo->nombre }}</strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Código institucional</span>
                        <strong>{{ $grupo->codigo }}</strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Programa</span>
                        <strong>
                            {{ $grupo->nivel?->programa?->nombre }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Nivel</span>
                        <strong>
                            {{ $grupo->nivel?->nombre }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Segmento</span>

                        <strong>
                            {{ $grupo->nivel?->programa?->segmento === 'niños'
                                ? 'Niños'
                                : 'Jóvenes y adultos' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Período académico</span>

                        <strong>
                            {{ $grupo->periodoAcademico?->nombre }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Fecha de inicio</span>

                        <strong>
                            {{ $grupo->fecha_inicio
                                ? $grupo->fecha_inicio
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No definida' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Fecha de finalización</span>

                        <strong>
                            {{ $grupo->fecha_fin
                                ? $grupo->fecha_fin
                                    ->translatedFormat(
                                        'd \d\e F \d\e Y'
                                    )
                                : 'No definida' }}
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Cupo</span>

                        <strong>
                            {{ $grupo->cupo_minimo }}
                            a
                            {{ $grupo->cupo_maximo }}
                            estudiantes
                        </strong>

                    </div>

                    <div class="portal-detail-item">

                        <span>Modalidad</span>
                        <strong>Virtual</strong>

                    </div>

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Observaciones</span>

                        <strong>
                            {{ $grupo->observaciones
                                ?: 'Sin observaciones registradas' }}
                        </strong>

                    </div>

                </div>

            </section>

           {{-- Días y horarios --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header portal-section-header-actions">

                    <div class="d-flex align-items-center gap-3">

                        <div class="portal-form-section-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <div>
                            <h2>Días y horarios</h2>

                            <p>
                                Configure los días y horas en que
                                este grupo recibirá clases.
                            </p>
                        </div>

                    </div>

                    @if (
                        $grupo->estado === 'activo' &&
                        $horariosDisponibles->isNotEmpty()
                    )

                        <button
                            type="button"
                            class="btn portal-btn-secondary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#addGroupScheduleModal"
                        >
                            <i class="bi bi-plus-circle"></i>
                            Agregar horario
                        </button>

                    @endif

                </div>

                @if ($grupo->horarios->isNotEmpty())

                    <div class="portal-academic-list">

                        @foreach ($grupo->horarios as $asignacion)

                            @php
                                $horario = $asignacion->horario;

                                $inicio = $horario
                                    ? \Carbon\Carbon::createFromFormat(
                                        'H:i:s',
                                        $horario->hora_inicio
                                    )
                                    : null;

                                $fin = $horario
                                    ? \Carbon\Carbon::createFromFormat(
                                        'H:i:s',
                                        $horario->hora_fin
                                    )
                                    : null;
                            @endphp

                            <article class="portal-academic-item">

                                <div class="portal-academic-icon">
                                    <i class="bi bi-clock"></i>
                                </div>

                                <div class="portal-academic-info">

                                    <strong>
                                        {{ str(
                                            $asignacion->dia_semana
                                        )->title() }}
                                    </strong>

                                    <span>
                                        {{ $horario?->nombre
                                            ?: 'Horario no disponible' }}
                                    </span>

                                    @if ($inicio && $fin)

                                        <small>
                                            {{ $inicio->format('g:i A') }}
                                            -
                                            {{ $fin->format('g:i A') }}
                                        </small>

                                    @endif

                                </div>

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

                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editGroupScheduleModal"
                                                data-action="{{ route(
                                                    'portal.grupos.horarios.update',
                                                    [
                                                        $grupo,
                                                        $asignacion
                                                    ]
                                                ) }}"
                                                data-dia="{{ $asignacion->dia_semana }}"
                                                data-horario="{{ $asignacion->horario_id }}"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                Editar asignación
                                            </button>

                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>

                                            <button
                                                type="button"
                                                class="dropdown-item text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#removeGroupScheduleModal"
                                                data-action="{{ route(
                                                    'portal.grupos.horarios.destroy',
                                                    [
                                                        $grupo,
                                                        $asignacion
                                                    ]
                                                ) }}"
                                                data-dia="{{ str(
                                                    $asignacion->dia_semana
                                                )->title() }}"
                                                data-horario="{{ $asignacion->horario?->nombre }}"
                                            >
                                                <i class="bi bi-x-circle"></i>
                                                Quitar del grupo
                                            </button>

                                        </li>

                                    </ul>

                                </div>

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <h3>No hay horarios asignados</h3>

                        <p>
                            Seleccione los días y horas en los que
                            este grupo recibirá clases.
                        </p>

                        @if (
                            $grupo->estado === 'activo' &&
                            $horariosDisponibles->isNotEmpty()
                        )

                            <button
                                type="button"
                                class="btn portal-btn-primary mt-3"
                                data-bs-toggle="modal"
                                data-bs-target="#addGroupScheduleModal"
                            >
                                <i class="bi bi-plus-circle"></i>
                                Agregar primer horario
                            </button>

                        @endif

                    </div>

                @endif

            </section>

            {{-- Docente responsable --}}
            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header portal-section-header-actions">

                    <div class="d-flex align-items-center gap-3">

                        <div class="portal-form-section-icon">
                            <i class="bi bi-easel"></i>
                        </div>

                        <div>
                            <h2>Docente responsable</h2>

                            <p>
                                Consulte la asignación docente actual
                                y el historial del grupo.
                            </p>
                        </div>

                    </div>

                    @php
                        $tieneDocenteActivo =
                            $grupo->docentes
                                ->contains(
                                    fn ($asignacion) =>
                                        $asignacion->activo
                                );
                    @endphp

                    @if (
                        !$tieneDocenteActivo &&
                        $grupo->estado === 'activo' &&
                        $docentesDisponibles->isNotEmpty()
                    )

                        <button
                            type="button"
                            class="btn portal-btn-secondary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#addGroupTeacherModal"
                        >
                            <i class="bi bi-person-plus"></i>
                            Asignar docente
                        </button>

                    @endif

                </div>

                @if ($grupo->docentes->isNotEmpty())

                    <div class="portal-academic-list">

                        @foreach ($grupo->docentes as $asignacion)

                            @php
                                $docente =
                                    $asignacion->docente;

                                $persona =
                                    $docente?->empleado?->persona;
                            @endphp

                            <article
                                class="portal-academic-item
                                    {{ !$asignacion->activo
                                        ? 'portal-academic-item-inactive'
                                        : '' }}"
                            >

                                <div class="portal-academic-icon">
                                    <i class="bi bi-person-video3"></i>
                                </div>

                                <div class="portal-academic-info">

                                    <div class="d-flex align-items-center flex-wrap gap-2">

                                        <strong>
                                            {{ $persona?->nombre_completo
                                                ?: 'Docente no disponible' }}
                                        </strong>

                                        @if ($asignacion->activo)

                                            <span class="portal-small-badge">
                                                Docente actual
                                            </span>

                                        @else

                                            <span class="portal-status-badge portal-status-inactive">
                                                Historial
                                            </span>

                                        @endif

                                    </div>

                                    <span>
                                        {{ $docente?->codigo_docente
                                            ?: 'Sin código docente' }}
                                    </span>

                                    <small>

                                        Desde

                                        {{ $asignacion->fecha_inicio
                                            ? $asignacion
                                                ->fecha_inicio
                                                ->translatedFormat(
                                                    'd M Y'
                                                )
                                            : 'fecha no registrada' }}

                                        @if ($asignacion->fecha_fin)

                                            · Hasta

                                            {{ $asignacion
                                                ->fecha_fin
                                                ->translatedFormat(
                                                    'd M Y'
                                                ) }}

                                        @endif

                                    </small>

                                    @if ($asignacion->observaciones)

                                        <small>
                                            {{ $asignacion->observaciones }}
                                        </small>

                                    @endif

                                </div>

                                <div class="dropdown">

                                    <button
                                        type="button"
                                        class="portal-table-action"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        aria-label="Opciones de la asignación docente"
                                    >
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end portal-actions-menu">

                                        <li>

                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editGroupTeacherModal"

                                                data-action="{{ route(
                                                    'portal.grupos.docentes.update',
                                                    [
                                                        $grupo,
                                                        $asignacion
                                                    ]
                                                ) }}"

                                                data-docente="{{ $asignacion->docente_id }}"

                                                data-inicio="{{ $asignacion
                                                    ->fecha_inicio
                                                    ?->format('Y-m-d') }}"

                                                data-fin="{{ $asignacion
                                                    ->fecha_fin
                                                    ?->format('Y-m-d') }}"

                                                data-activo="{{ $asignacion->activo
                                                    ? '1'
                                                    : '0' }}"

                                                data-observaciones="{{ $asignacion->observaciones }}"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                Editar asignación
                                            </button>

                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>

                                            <button
                                                type="button"
                                                class="dropdown-item
                                                    {{ $asignacion->activo
                                                        ? 'text-warning-emphasis'
                                                        : 'text-success' }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#changeGroupTeacherStatusModal"

                                                data-action="{{ route(
                                                    'portal.grupos.docentes.cambiar-estado',
                                                    [
                                                        $grupo,
                                                        $asignacion
                                                    ]
                                                ) }}"

                                                data-name="{{ $persona?->nombre_completo
                                                    ?: 'este docente' }}"

                                                data-active="{{ $asignacion->activo
                                                    ? '1'
                                                    : '0' }}"
                                            >
                                                <i class="bi
                                                    {{ $asignacion->activo
                                                        ? 'bi-person-dash'
                                                        : 'bi-person-check' }}">
                                                </i>

                                                {{ $asignacion->activo
                                                    ? 'Finalizar asignación'
                                                    : 'Reactivar asignación' }}
                                            </button>

                                        </li>

                                    </ul>

                                </div>

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-easel"></i>
                        </div>

                        <h3>No hay docente asignado</h3>

                        <p>
                            Asigne al docente que estará a cargo
                            de este grupo académico.
                        </p>

                        @if (
                            $grupo->estado === 'activo' &&
                            $docentesDisponibles->isNotEmpty()
                        )

                            <button
                                type="button"
                                class="btn portal-btn-primary mt-3"
                                data-bs-toggle="modal"
                                data-bs-target="#addGroupTeacherModal"
                            >
                                <i class="bi bi-person-plus"></i>
                                Asignar docente
                            </button>

                        @endif

                    </div>

                @endif

            </section>

        </div>

    </div>



    {{-- Modal: agregar horario --}}
<div
    class="modal fade"
    id="addGroupScheduleModal"
    tabindex="-1"
    aria-labelledby="addGroupScheduleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content portal-modal">

            <form
                action="{{ route(
                    'portal.grupos.horarios.store',
                    $grupo
                ) }}"
                method="POST"
            >
                @csrf

                <div class="modal-header">

                    <div>
                        <span class="portal-modal-eyebrow">
                            Organización del grupo
                        </span>

                        <h2
                            class="modal-title"
                            id="addGroupScheduleModalLabel"
                        >
                            Agregar día y horario
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

                    <div class="row g-3">

                        <div class="col-12">

                            <label
                                for="dia_semana"
                                class="form-label portal-form-label"
                            >
                                Día de clase
                                <span class="portal-required">*</span>
                            </label>

                            <select
                                name="dia_semana"
                                id="dia_semana"
                                class="form-select portal-form-control"
                                required
                            >
                                <option value="">
                                    Seleccione un día
                                </option>

                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miércoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sábado">Sábado</option>
                                <option value="domingo">Domingo</option>

                            </select>

                        </div>

                        <div class="col-12">

                            <label
                                for="horario_id"
                                class="form-label portal-form-label"
                            >
                                Horario
                                <span class="portal-required">*</span>
                            </label>

                            <select
                                name="horario_id"
                                id="horario_id"
                                class="form-select portal-form-control"
                                required
                            >
                                <option value="">
                                    Seleccione un horario
                                </option>

                                @foreach (
                                    $horariosDisponibles
                                    as $horario
                                )

                                    @php
                                        $inicio =
                                            \Carbon\Carbon::createFromFormat(
                                                'H:i:s',
                                                $horario->hora_inicio
                                            );

                                        $fin =
                                            \Carbon\Carbon::createFromFormat(
                                                'H:i:s',
                                                $horario->hora_fin
                                            );
                                    @endphp

                                    <option value="{{ $horario->id }}">
                                        {{ $horario->nombre }}
                                        ·
                                        {{ $inicio->format('g:i A') }}
                                        -
                                        {{ $fin->format('g:i A') }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

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
                    >
                        <i class="bi bi-check2-circle"></i>
                        Agregar horario
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>



{{-- Modal: editar horario --}}
<div
    class="modal fade"
    id="editGroupScheduleModal"
    tabindex="-1"
    aria-labelledby="editGroupScheduleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content portal-modal">

            <form
                method="POST"
                id="editGroupScheduleForm"
            >
                @csrf
                @method('PUT')

                <div class="modal-header">

                    <div>
                        <span class="portal-modal-eyebrow">
                            Organización del grupo
                        </span>

                        <h2
                            class="modal-title"
                            id="editGroupScheduleModalLabel"
                        >
                            Editar día y horario
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

                    <div class="row g-3">

                        <div class="col-12">

                            <label
                                for="edit_dia_semana"
                                class="form-label portal-form-label"
                            >
                                Día de clase
                            </label>

                            <select
                                name="dia_semana"
                                id="edit_dia_semana"
                                class="form-select portal-form-control"
                                required
                            >
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miércoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sábado">Sábado</option>
                                <option value="domingo">Domingo</option>
                            </select>

                        </div>

                        <div class="col-12">

                            <label
                                for="edit_horario_id"
                                class="form-label portal-form-label"
                            >
                                Horario
                            </label>

                            <select
                                name="horario_id"
                                id="edit_horario_id"
                                class="form-select portal-form-control"
                                required
                            >

                                @foreach (
                                    $horariosDisponibles
                                    as $horario
                                )

                                    <option value="{{ $horario->id }}">
                                        {{ $horario->nombre }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

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
                    >
                        <i class="bi bi-check2-circle"></i>
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

{{-- Modal: quitar horario del grupo --}}
<div
    class="modal fade"
    id="removeGroupScheduleModal"
    tabindex="-1"
    aria-labelledby="removeGroupScheduleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content portal-modal">

            <form
                method="POST"
                id="removeGroupScheduleForm"
            >
                @csrf
                @method('DELETE')

                <div class="modal-header">

                    <div>

                        <span class="portal-modal-eyebrow">
                            Confirmación
                        </span>

                        <h2
                            class="modal-title"
                            id="removeGroupScheduleModalLabel"
                        >
                            Quitar horario del grupo
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
                        <i class="bi bi-calendar-x"></i>
                    </div>

                    <p class="mb-0">

                        ¿Desea retirar la asignación de

                        <strong id="removeGroupScheduleDay"></strong>

                        con el horario

                        <strong id="removeGroupScheduleName"></strong>

                        de este grupo?

                    </p>

                    <div class="portal-form-help mt-3">
                        El horario continuará disponible para otros
                        grupos. Solamente se eliminará esta asignación.
                    </div>

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
                        class="btn portal-btn-danger"
                    >
                        <i class="bi bi-x-circle"></i>
                        Quitar horario
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>



{{-- Modal: asignar docente --}}
<div
    class="modal fade"
    id="addGroupTeacherModal"
    tabindex="-1"
    aria-labelledby="addGroupTeacherModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content portal-modal">

            <form
                action="{{ route(
                    'portal.grupos.docentes.store',
                    $grupo
                ) }}"
                method="POST"
            >
                @csrf

                <div class="modal-header">

                    <div>

                        <span class="portal-modal-eyebrow">
                            Organización del grupo
                        </span>

                        <h2
                            class="modal-title"
                            id="addGroupTeacherModalLabel"
                        >
                            Asignar docente responsable
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

                    <div class="row g-3">

                        <div class="col-12">

                            <label
                                for="docente_id"
                                class="form-label portal-form-label"
                            >
                                Docente
                                <span class="portal-required">*</span>
                            </label>

                            <select
                                name="docente_id"
                                id="docente_id"
                                class="form-select portal-form-control"
                                required
                            >
                                <option value="">
                                    Seleccione un docente
                                </option>

                                @foreach (
                                    $docentesDisponibles
                                    as $docente
                                )

                                    <option value="{{ $docente->id }}">

                                        {{ $docente
                                            ->empleado
                                            ?->persona
                                            ?->nombre_completo }}

                                        ·

                                        {{ $docente->codigo_docente }}

                                    </option>

                                @endforeach

                            </select>

                            <div class="portal-form-help">
                                Se muestran los docentes activos
                                disponibles en el sistema.
                            </div>

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="teacher_fecha_inicio"
                                class="form-label portal-form-label"
                            >
                                Inicio de la asignación
                                <span class="portal-required">*</span>
                            </label>

                            <input
                                type="date"
                                name="fecha_inicio"
                                id="teacher_fecha_inicio"
                                value="{{ $grupo
                                    ->fecha_inicio
                                    ?->format('Y-m-d') }}"
                                min="{{ $grupo
                                    ->fecha_inicio
                                    ?->format('Y-m-d') }}"
                                max="{{ $grupo
                                    ->fecha_fin
                                    ?->format('Y-m-d') }}"
                                class="form-control portal-form-control"
                                required
                            >

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="teacher_fecha_fin"
                                class="form-label portal-form-label"
                            >
                                Fecha de finalización
                            </label>

                            <input
                                type="date"
                                name="fecha_fin"
                                id="teacher_fecha_fin"
                                min="{{ $grupo
                                    ->fecha_inicio
                                    ?->format('Y-m-d') }}"
                                max="{{ $grupo
                                    ->fecha_fin
                                    ?->format('Y-m-d') }}"
                                class="form-control portal-form-control"
                            >

                            <div class="portal-form-help">
                                Puede dejarse vacía mientras el docente
                                continúe a cargo del grupo.
                            </div>

                        </div>

                        <div class="col-12">

                            <label
                                for="teacher_observaciones"
                                class="form-label portal-form-label"
                            >
                                Observaciones
                            </label>

                            <textarea
                                name="observaciones"
                                id="teacher_observaciones"
                                rows="3"
                                maxlength="2000"
                                class="form-control portal-form-control"
                                placeholder="Información adicional sobre la asignación..."
                            ></textarea>

                        </div>

                    </div>

                    <input
                        type="hidden"
                        name="tipo_asignacion"
                        value="principal"
                    >

                    <input
                        type="hidden"
                        name="activo"
                        value="1"
                    >

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
                    >
                        <i class="bi bi-person-check"></i>
                        Asignar docente
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>



{{-- Modal: editar asignación docente --}}
<div
    class="modal fade"
    id="editGroupTeacherModal"
    tabindex="-1"
    aria-labelledby="editGroupTeacherModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content portal-modal">

            <form
                method="POST"
                id="editGroupTeacherForm"
            >
                @csrf
                @method('PUT')

                <div class="modal-header">

                    <div>

                        <span class="portal-modal-eyebrow">
                            Asignación docente
                        </span>

                        <h2
                            class="modal-title"
                            id="editGroupTeacherModalLabel"
                        >
                            Editar asignación
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

                    <div class="row g-3">

                        <div class="col-12">

                            <label
                                for="edit_teacher_docente_id"
                                class="form-label portal-form-label"
                            >
                                Docente
                            </label>

                            <select
                                name="docente_id"
                                id="edit_teacher_docente_id"
                                class="form-select portal-form-control"
                                required
                            >

                                @foreach (
                                    $docentesDisponibles
                                    as $docente
                                )

                                    <option value="{{ $docente->id }}">

                                        {{ $docente
                                            ->empleado
                                            ?->persona
                                            ?->nombre_completo }}

                                        ·

                                        {{ $docente->codigo_docente }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_teacher_fecha_inicio"
                                class="form-label portal-form-label"
                            >
                                Inicio
                            </label>

                            <input
                                type="date"
                                name="fecha_inicio"
                                id="edit_teacher_fecha_inicio"
                                min="{{ $grupo
                                    ->fecha_inicio
                                    ?->format('Y-m-d') }}"
                                max="{{ $grupo
                                    ->fecha_fin
                                    ?->format('Y-m-d') }}"
                                class="form-control portal-form-control"
                                required
                            >

                        </div>

                        <div class="col-12 col-md-6">

                            <label
                                for="edit_teacher_fecha_fin"
                                class="form-label portal-form-label"
                            >
                                Finalización
                            </label>

                            <input
                                type="date"
                                name="fecha_fin"
                                id="edit_teacher_fecha_fin"
                                min="{{ $grupo
                                    ->fecha_inicio
                                    ?->format('Y-m-d') }}"
                                max="{{ $grupo
                                    ->fecha_fin
                                    ?->format('Y-m-d') }}"
                                class="form-control portal-form-control"
                            >

                        </div>

                        <div class="col-12">

                            <label
                                for="edit_teacher_observaciones"
                                class="form-label portal-form-label"
                            >
                                Observaciones
                            </label>

                            <textarea
                                name="observaciones"
                                id="edit_teacher_observaciones"
                                rows="3"
                                maxlength="2000"
                                class="form-control portal-form-control"
                            ></textarea>

                        </div>

                    </div>

                    <input
                        type="hidden"
                        name="tipo_asignacion"
                        value="principal"
                    >

                    <input
                        type="hidden"
                        name="activo"
                        id="edit_teacher_activo"
                        value="1"
                    >

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
                    >
                        <i class="bi bi-check2-circle"></i>
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>


{{-- Modal: cambiar estado de asignación docente --}}
<div
    class="modal fade"
    id="changeGroupTeacherStatusModal"
    tabindex="-1"
    aria-labelledby="changeGroupTeacherStatusModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content portal-modal">

            <form
                method="POST"
                id="changeGroupTeacherStatusForm"
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
                            id="changeGroupTeacherStatusModalLabel"
                        >
                            Cambiar asignación docente
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
                        id="changeGroupTeacherStatusMessage"
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
                        id="changeGroupTeacherStatusSubmit"
                    >
                        Confirmar
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', () => {

        /*
        |--------------------------------------------------------------------------
        | Editar asignación de horario
        |--------------------------------------------------------------------------
        */

        const editScheduleModal = document.getElementById(
            'editGroupScheduleModal'
        );

        editScheduleModal?.addEventListener(
            'show.bs.modal',
            event => {
                const button = event.relatedTarget;

                if (!button) {
                    return;
                }

                const form = document.getElementById(
                    'editGroupScheduleForm'
                );

                const day = document.getElementById(
                    'edit_dia_semana'
                );

                const schedule = document.getElementById(
                    'edit_horario_id'
                );

                if (form) {
                    form.action =
                        button.dataset.action || '';
                }

                if (day) {
                    day.value =
                        button.dataset.dia || '';
                }

                if (schedule) {
                    schedule.value =
                        button.dataset.horario || '';
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Quitar horario del grupo
        |--------------------------------------------------------------------------
        */

        const removeScheduleModal = document.getElementById(
            'removeGroupScheduleModal'
        );

        removeScheduleModal?.addEventListener(
            'show.bs.modal',
            event => {
                const button = event.relatedTarget;

                if (!button) {
                    return;
                }

                const form = document.getElementById(
                    'removeGroupScheduleForm'
                );

                const day = document.getElementById(
                    'removeGroupScheduleDay'
                );

                const schedule = document.getElementById(
                    'removeGroupScheduleName'
                );

                if (form) {
                    form.action =
                        button.dataset.action || '';
                }

                if (day) {
                    day.textContent =
                        button.dataset.dia || '';
                }

                if (schedule) {
                    schedule.textContent =
                        button.dataset.horario || '';
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Editar asignación docente
        |--------------------------------------------------------------------------
        */

        const editTeacherModal = document.getElementById(
            'editGroupTeacherModal'
        );

        editTeacherModal?.addEventListener(
            'show.bs.modal',
            event => {
                const button = event.relatedTarget;

                if (!button) {
                    return;
                }

                const form = document.getElementById(
                    'editGroupTeacherForm'
                );

                const teacher = document.getElementById(
                    'edit_teacher_docente_id'
                );

                const startDate = document.getElementById(
                    'edit_teacher_fecha_inicio'
                );

                const endDate = document.getElementById(
                    'edit_teacher_fecha_fin'
                );

                const observations = document.getElementById(
                    'edit_teacher_observaciones'
                );

                const active = document.getElementById(
                    'edit_teacher_activo'
                );

                if (form) {
                    form.action =
                        button.dataset.action || '';
                }

                if (teacher) {
                    teacher.value =
                        button.dataset.docente || '';
                }

                if (startDate) {
                    startDate.value =
                        button.dataset.inicio || '';
                }

                if (endDate) {
                    endDate.value =
                        button.dataset.fin || '';
                }

                if (observations) {
                    observations.value =
                        button.dataset.observaciones || '';
                }

                if (active) {
                    active.value =
                        button.dataset.activo || '0';
                }

                /*
                 * La fecha final nunca podrá ser
                 * anterior a la fecha inicial.
                 */
                if (startDate && endDate) {
                    endDate.min =
                        startDate.value || '';

                    startDate.onchange = () => {
                        endDate.min =
                            startDate.value || '';

                        if (
                            endDate.value &&
                            startDate.value &&
                            endDate.value <
                            startDate.value
                        ) {
                            endDate.value = '';
                        }
                    };
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Finalizar / reactivar asignación docente
        |--------------------------------------------------------------------------
        */

        const teacherStatusModal = document.getElementById(
            'changeGroupTeacherStatusModal'
        );

        teacherStatusModal?.addEventListener(
            'show.bs.modal',
            event => {
                const button = event.relatedTarget;

                if (!button) {
                    return;
                }

                const isActive =
                    button.dataset.active === '1';

                const teacherName =
                    button.dataset.name ||
                    'este docente';

                const form = document.getElementById(
                    'changeGroupTeacherStatusForm'
                );

                const message = document.getElementById(
                    'changeGroupTeacherStatusMessage'
                );

                const submit = document.getElementById(
                    'changeGroupTeacherStatusSubmit'
                );

                if (form) {
                    form.action =
                        button.dataset.action || '';
                }

                if (message) {
                    message.textContent = isActive
                        ? `¿Desea finalizar la asignación de ${teacherName} en este grupo? El historial permanecerá disponible.`
                        : `¿Desea reactivar la asignación de ${teacherName} en este grupo?`;
                }

                if (submit) {
                    submit.textContent = isActive
                        ? 'Finalizar asignación'
                        : 'Reactivar asignación';

                    submit.classList.toggle(
                        'portal-btn-danger',
                        isActive
                    );

                    submit.classList.toggle(
                        'portal-btn-primary',
                        !isActive
                    );
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Fechas al crear una asignación docente
        |--------------------------------------------------------------------------
        */

        const createTeacherStartDate =
            document.getElementById(
                'teacher_fecha_inicio'
            );

        const createTeacherEndDate =
            document.getElementById(
                'teacher_fecha_fin'
            );

        const updateTeacherEndMinimum = () => {
            if (
                !createTeacherStartDate ||
                !createTeacherEndDate
            ) {
                return;
            }

            createTeacherEndDate.min =
                createTeacherStartDate.value || '';

            if (
                createTeacherEndDate.value &&
                createTeacherStartDate.value &&
                createTeacherEndDate.value <
                createTeacherStartDate.value
            ) {
                createTeacherEndDate.value = '';
            }
        };

        createTeacherStartDate?.addEventListener(
            'change',
            updateTeacherEndMinimum
        );

        updateTeacherEndMinimum();
    });
</script>

@endpush

@endsection