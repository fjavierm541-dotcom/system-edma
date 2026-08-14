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

            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-easel"></i>
                    </div>

                    <div>
                        <h2>Docentes</h2>

                        <p>
                            Docentes asignados al grupo.
                        </p>
                    </div>

                </div>

                <div class="portal-empty-state portal-empty-state-documents">

                    <div class="portal-empty-icon">
                        <i class="bi bi-easel"></i>
                    </div>

                    <h3>Asignación docente pendiente</h3>

                    <p>
                        Una vez configurados los horarios,
                        podrá asignarse el docente responsable.
                    </p>

                </div>

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




@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', () => {

        /*
        |--------------------------------------------------------------------------
        | Editar asignación
        |--------------------------------------------------------------------------
        */

        const editModal = document.getElementById(
            'editGroupScheduleModal'
        );

        editModal?.addEventListener(
            'show.bs.modal',
            event => {
                const button = event.relatedTarget;

                if (!button) {
                    return;
                }

                const form = document.getElementById(
                    'editGroupScheduleForm'
                );

                form.action =
                    button.dataset.action;

                document.getElementById(
                    'edit_dia_semana'
                ).value =
                    button.dataset.dia || '';

                document.getElementById(
                    'edit_horario_id'
                ).value =
                    button.dataset.horario || '';
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Quitar asignación
        |--------------------------------------------------------------------------
        */

        const removeModal = document.getElementById(
            'removeGroupScheduleModal'
        );

        removeModal?.addEventListener(
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

                form.action =
                    button.dataset.action;

                day.textContent =
                    button.dataset.dia || '';

                schedule.textContent =
                    button.dataset.horario || '';
            }
        );
    });
</script>

@endpush

@endsection