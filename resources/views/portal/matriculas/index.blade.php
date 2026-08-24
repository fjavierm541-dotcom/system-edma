@extends('layouts.portal')

@section('title', 'Mi matrícula | Portal EDMA')

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>
                Mi matrícula
            </h1>

            <p>
                Consulta la información de tu matrícula
                y administra tu grupo durante el período habilitado.
            </p>

        </div>

    </div>

@endsection


@section('content')

    {{-- ============================================================
        MENSAJES
    ============================================================ --}}

    @if (session('success'))

        <div
            class="alert alert-success portal-alert mb-4"
        >

            <i class="bi bi-check-circle-fill"></i>

            <div>
                <strong>
                    Proceso completado
                </strong>

                <span>
                    {{ session('success') }}
                </span>
            </div>

        </div>

    @endif


    @if (session('error'))

        <div
            class="alert alert-danger portal-alert mb-4"
        >

            <i class="bi bi-exclamation-triangle-fill"></i>

            <div>
                <strong>
                    No fue posible completar la acción
                </strong>

                <span>
                    {{ session('error') }}
                </span>
            </div>

        </div>

    @endif


    {{-- ============================================================
        BLOQUEO
    ============================================================ --}}

    @if ($mensajeBloqueo)

        <section class="portal-card">

            <div class="edma-empty-matricula">

                <div class="edma-empty-matricula__icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <h2>
                    Matrícula no disponible
                </h2>

                <p>
                    {{ $mensajeBloqueo }}
                </p>

            </div>

        </section>


    {{-- ============================================================
        MATRÍCULA ACTIVA
    ============================================================ --}}

    @elseif ($matriculaActiva)

        @php

            $asignacionDocente =
                $matriculaActiva
                    ->grupo
                    ->docentes
                    ->firstWhere(
                        'activo',
                        true
                    );

            $docente =
                $asignacionDocente
                    ?->docente
                    ?->empleado
                    ?->persona;

        @endphp


        <section class="edma-current-enrollment">

            {{-- Encabezado --}}
            <div class="edma-current-enrollment__header">

                <div>

                    <span class="edma-current-enrollment__eyebrow">
                        Matrícula actual
                    </span>

                    <h2>
                        {{
                            $matriculaActiva
                                ->grupo
                                ->nivel
                                ->nombre
                        }}
                        ·
                        {{
                            $matriculaActiva
                                ->grupo
                                ->nombre
                        }}
                    </h2>

                    <div class="edma-current-enrollment__code">

                        <i class="bi bi-hash"></i>

                        {{
                            $matriculaActiva
                                ->codigo_matricula
                        }}

                    </div>

                </div>


                <div class="edma-current-enrollment__status">

                    <span></span>

                    Matrícula activa

                </div>

            </div>


            {{-- Información --}}
            <div class="edma-current-enrollment__body">

                <div class="edma-enrollment-grid">

                    {{-- Programa --}}
                    <div class="edma-enrollment-item">

                        <div class="edma-enrollment-item__icon">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>

                        <div>
                            <span>
                                Programa
                            </span>

                            <strong>
                                {{
                                    $matriculaActiva
                                        ->grupo
                                        ->nivel
                                        ->programa
                                        ->nombre
                                }}
                            </strong>
                        </div>

                    </div>


                    {{-- Nivel --}}
                    <div class="edma-enrollment-item">

                        <div class="edma-enrollment-item__icon">
                            <i class="bi bi-bar-chart-steps"></i>
                        </div>

                        <div>
                            <span>
                                Nivel
                            </span>

                            <strong>
                                {{
                                    $matriculaActiva
                                        ->grupo
                                        ->nivel
                                        ->nombre
                                }}
                            </strong>
                        </div>

                    </div>


                    {{-- Grupo --}}
                    <div class="edma-enrollment-item">

                        <div class="edma-enrollment-item__icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <div>
                            <span>
                                Grupo
                            </span>

                            <strong>
                                {{
                                    $matriculaActiva
                                        ->grupo
                                        ->nombre
                                }}
                            </strong>

                            <small>
                                {{
                                    $matriculaActiva
                                        ->grupo
                                        ->codigo
                                }}
                            </small>
                        </div>

                    </div>


                    {{-- Docente --}}
                    <div class="edma-enrollment-item">

                        <div class="edma-enrollment-item__icon">
                            <i class="bi bi-person-video3"></i>
                        </div>

                        <div>
                            <span>
                                Docente
                            </span>

                            <strong>
                                {{
                                    $docente
                                        ?->nombre_completo
                                    ?? 'Por asignar'
                                }}
                            </strong>
                        </div>

                    </div>


                    {{-- Período --}}
                    <div class="edma-enrollment-item">

                        <div class="edma-enrollment-item__icon">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div>
                            <span>
                                Período académico
                            </span>

                            <strong>
                                {{
                                    $matriculaActiva
                                        ->grupo
                                        ->periodoAcademico
                                        ->nombre
                                }}
                            </strong>
                        </div>

                    </div>


                    {{-- Modalidad --}}
                    <div class="edma-enrollment-item">

                        <div class="edma-enrollment-item__icon">
                            <i class="bi bi-laptop"></i>
                        </div>

                        <div>
                            <span>
                                Modalidad
                            </span>

                            <strong>
                                {{
                                    ucfirst(
                                        $matriculaActiva
                                            ->grupo
                                            ->modalidad
                                    )
                                }}
                            </strong>
                        </div>

                    </div>

                </div>


                {{-- Horarios --}}
                <div class="edma-enrollment-schedule">

                    <div class="edma-enrollment-schedule__title">

                        <i class="bi bi-clock"></i>

                        <div>
                            <strong>
                                Horario
                            </strong>

                            <span>
                                Horario asignado a tu grupo
                            </span>
                        </div>

                    </div>


                    <div class="edma-enrollment-schedule__list">

                        @forelse (
                            $matriculaActiva
                                ->grupo
                                ->horarios
                                ->sortBy(
                                    fn ($asignacion) =>
                                        $asignacion
                                            ->horario
                                            ?->hora_inicio
                                )
                            as $asignacion
                        )

                            <div class="edma-enrollment-schedule__row">

                                <div>

                                    <strong>
                                        {{
                                            $asignacion
                                                ->horario
                                                ?->nombre
                                            ?? 'Horario por definir'
                                        }}
                                    </strong>

                                    <span>
                                        {{
                                            ucfirst(
                                                $asignacion
                                                    ->dia_semana
                                            )
                                        }}
                                    </span>

                                </div>


                                @if ($asignacion->horario)

                                    <div class="edma-enrollment-hours">

                                        {{
                                            substr(
                                                $asignacion
                                                    ->horario
                                                    ->hora_inicio,
                                                0,
                                                5
                                            )
                                        }}

                                        <span>
                                            –
                                        </span>

                                        {{
                                            substr(
                                                $asignacion
                                                    ->horario
                                                    ->hora_fin,
                                                0,
                                                5
                                            )
                                        }}

                                    </div>

                                @endif

                            </div>

                        @empty

                            <div class="text-muted">
                                El horario de este grupo
                                aún no ha sido definido.
                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- Acciones --}}
            <div class="edma-current-enrollment__footer">

                <div>

                    <span>
                        Fecha de matrícula
                    </span>

                    <strong>
                        {{
                            $matriculaActiva
                                ->fecha_matricula
                                ?->format(
                                    'd/m/Y'
                                )
                        }}
                    </strong>

                </div>


                <button
                    type="button"
                    class="btn portal-btn-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#cambiarGrupoModal"
                >
                    <i class="bi bi-arrow-repeat me-2"></i>

                    Cambiar de grupo
                </button>

            </div>

        </section>


        {{-- ========================================================
            MODAL CAMBIAR GRUPO
        ======================================================== --}}

        <div
            class="modal fade"
            id="cambiarGrupoModal"
            tabindex="-1"
            aria-hidden="true"
        >

            <div
                class="modal-dialog
                       modal-xl
                       modal-dialog-centered
                       modal-dialog-scrollable"
            >

                <div class="modal-content edma-change-modal">

                    <div class="modal-header">

                        <div>

                            <span class="edma-modal-eyebrow">
                                Mi matrícula
                            </span>

                            <h5 class="modal-title">
                                Cambiar de grupo
                            </h5>

                            <p class="mb-0">
                                Consulta los grupos disponibles
                                para tu nivel y selecciona
                                el horario que prefieras.
                            </p>

                        </div>


                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"
                        ></button>

                    </div>


                    <div class="modal-body">

                        <div class="edma-change-groups">

                            @foreach (
                                $gruposCambio
                                as $grupo
                            )

                                @php

                                    $esActual =
                                        $grupo->id
                                        ===
                                        $matriculaActiva
                                            ->grupo_id;

                                    $cupos =
                                        max(
                                            0,
                                            $grupo
                                                ->cupo_maximo
                                            -
                                            $grupo
                                                ->matriculas_activas_count
                                        );

                                    $asignacionDocenteGrupo =
                                        $grupo
                                            ->docentes
                                            ->firstWhere(
                                                'activo',
                                                true
                                            );

                                    $docenteGrupo =
                                        $asignacionDocenteGrupo
                                            ?->docente
                                            ?->empleado
                                            ?->persona;

                                @endphp


                                <article
                                    class="
                                        edma-change-group-card
                                        {{
                                            $esActual
                                                ? 'is-current'
                                                : ''
                                        }}
                                    "
                                >

                                    <div class="edma-change-group-card__top">

                                        <div>

                                            <span>
                                                {{
                                                    $esActual
                                                        ? 'Clase matriculada'
                                                        : 'Grupo disponible'
                                                }}
                                            </span>

                                            <h3>
                                                {{ $grupo->nombre }}
                                            </h3>

                                            <small>
                                                {{ $grupo->codigo }}
                                            </small>

                                        </div>


                                        @if ($esActual)

                                            <span
                                                class="
                                                    edma-current-badge
                                                "
                                            >
                                                Actual
                                            </span>

                                        @endif

                                    </div>


                                    <div class="edma-change-group-card__info">

                                        <div>

                                            <span>
                                                Docente
                                            </span>

                                            <strong>
                                                {{
                                                    $docenteGrupo
                                                        ?->nombre_completo
                                                    ?? 'Por asignar'
                                                }}
                                            </strong>

                                        </div>


                                        <div>

                                            <span>
                                                Cupos disponibles
                                            </span>

                                            <strong>
                                                {{ $cupos }}
                                            </strong>

                                        </div>

                                    </div>


                                    <div class="edma-change-group-card__schedules">

                                        @forelse (
                                            $grupo
                                                ->horarios
                                                ->sortBy(
                                                    fn ($asignacion) =>
                                                        $asignacion
                                                            ->horario
                                                            ?->hora_inicio
                                                )
                                            as $asignacion
                                        )

                                            <div>

                                                <span>
                                                    {{
                                                        ucfirst(
                                                            $asignacion
                                                                ->dia_semana
                                                        )
                                                    }}
                                                </span>

                                                <strong>
                                                    {{
                                                        $asignacion
                                                            ->horario
                                                            ?->nombre
                                                        ??
                                                        'Horario por definir'
                                                    }}
                                                </strong>


                                                @if (
                                                    $asignacion
                                                        ->horario
                                                )

                                                    <small>

                                                        {{
                                                            substr(
                                                                $asignacion
                                                                    ->horario
                                                                    ->hora_inicio,
                                                                0,
                                                                5
                                                            )
                                                        }}

                                                        –

                                                        {{
                                                            substr(
                                                                $asignacion
                                                                    ->horario
                                                                    ->hora_fin,
                                                                0,
                                                                5
                                                            )
                                                        }}

                                                    </small>

                                                @endif

                                            </div>

                                        @empty

                                            <span class="text-muted">
                                                Horario por definir
                                            </span>

                                        @endforelse

                                    </div>


                                    @if ($esActual)

                                        <button
                                            type="button"
                                            class="btn
                                                   portal-btn-secondary
                                                   w-100"
                                            disabled
                                        >
                                            <i
                                                class="
                                                    bi
                                                    bi-check-circle
                                                    me-2
                                                "
                                            ></i>

                                            Clase matriculada
                                        </button>

                                    @else

                                        <button
                                            type="button"
                                            class="btn
                                                   btn-outline-primary
                                                   w-100
                                                   btn-elegir-cambio"
                                            data-grupo-id="{{
                                                $grupo->id
                                            }}"
                                            data-grupo-nombre="{{
                                                $grupo->nombre
                                            }}"
                                        >
                                            Seleccionar este grupo
                                        </button>

                                    @endif

                                </article>

                            @endforeach

                        </div>


                        <div
                            id="confirmarCambioPanel"
                            class="
                                edma-change-confirmation
                                d-none
                            "
                        >

                            <div>

                                <span>
                                    Nuevo grupo seleccionado
                                </span>

                                <strong
                                    id="nuevoGrupoNombre"
                                ></strong>

                            </div>


                            <form
                                method="POST"
                                action="{{
                                    route(
                                        'portal.mi-matricula.cambiar-grupo'
                                    )
                                }}"
                            >

                                @csrf

                                <input
                                    type="hidden"
                                    name="grupo_id"
                                    id="nuevoGrupoId"
                                >


                    
                                <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                                >
                                    <i
                                        class="
                                            bi
                                            bi-arrow-left-right
                                            me-2
                                        "
                                    ></i>

                                    Cambiar a este grupo
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>


    {{-- ============================================================
        SIN MATRÍCULA
    ============================================================ --}}

    @else

        <section class="portal-card">

            <div class="portal-card-header">

                <div>

                    <h2>
                        Selecciona tu grupo
                    </h2>

                    <p>
                        Estos son los grupos disponibles
                        para tu programa y nivel autorizado.
                    </p>

                </div>

            </div>


            <div class="edma-change-groups">

                @foreach ($grupos as $grupo)

                    @php

                        $cupos =
                            max(
                                0,
                                $grupo->cupo_maximo
                                -
                                $grupo
                                    ->matriculas_activas_count
                            );

                    @endphp


                    <article class="edma-change-group-card">

                        <div class="edma-change-group-card__top">

                            <div>

                                <span>
                                    Grupo disponible
                                </span>

                                <h3>
                                    {{ $grupo->nombre }}
                                </h3>

                                <small>
                                    {{ $grupo->codigo }}
                                </small>

                            </div>

                        </div>


                        <div class="edma-change-group-card__info">

                            <div>
                                <span>
                                    Nivel
                                </span>

                                <strong>
                                    {{ $grupo->nivel->nombre }}
                                </strong>
                            </div>

                            <div>
                                <span>
                                    Cupos disponibles
                                </span>

                                <strong>
                                    {{ $cupos }}
                                </strong>
                            </div>

                        </div>


                        <div class="edma-change-group-card__schedules">

                            @foreach (
                                $grupo->horarios
                                as $asignacion
                            )

                                <div>

                                    <span>
                                        {{
                                            ucfirst(
                                                $asignacion
                                                    ->dia_semana
                                            )
                                        }}
                                    </span>

                                    <strong>
                                        {{
                                            $asignacion
                                                ->horario
                                                ?->nombre
                                            ??
                                            'Horario por definir'
                                        }}
                                    </strong>

                                    @if (
                                        $asignacion->horario
                                    )

                                        <small>
                                            {{
                                                substr(
                                                    $asignacion
                                                        ->horario
                                                        ->hora_inicio,
                                                    0,
                                                    5
                                                )
                                            }}

                                            –

                                            {{
                                                substr(
                                                    $asignacion
                                                        ->horario
                                                        ->hora_fin,
                                                    0,
                                                    5
                                                )
                                            }}
                                        </small>

                                    @endif

                                </div>

                            @endforeach

                        </div>


                        <form
                            method="POST"
                            action="{{
                                route(
                                    'portal.mi-matricula.store'
                                )
                            }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="grupo_id"
                                value="{{ $grupo->id }}"
                            >

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Matricularme en este grupo
                            </button>

                        </form>

                    </article>

                @endforeach

            </div>

        </section>

    @endif

@endsection


@push('styles')

<style>

.edma-current-enrollment {
    overflow: hidden;
    border: 1px solid var(--portal-border);
    border-radius: var(--portal-radius-lg);
    background: var(--portal-surface);
    box-shadow: var(--portal-shadow);
}

.edma-current-enrollment__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 25px 28px;
    background:
        linear-gradient(
            135deg,
            var(--portal-primary-dark),
            var(--portal-primary)
        );
    color: #fff;
}

.edma-current-enrollment__eyebrow,
.edma-modal-eyebrow {
    display: block;
    margin-bottom: 5px;
    color: var(--portal-accent);
    font-size: .68rem;
    font-weight: 750;
    letter-spacing: .07em;
    text-transform: uppercase;
}

.edma-current-enrollment__header h2 {
    margin: 0;
    font-size: 1.35rem;
}

.edma-current-enrollment__code {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 6px;
    color: rgba(255,255,255,.72);
    font-size: .72rem;
}

.edma-current-enrollment__status {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 13px;
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 999px;
    background: rgba(255,255,255,.10);
    font-size: .72rem;
    font-weight: 700;
}

.edma-current-enrollment__status span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #4ed995;
    box-shadow: 0 0 0 4px rgba(78,217,149,.15);
}

.edma-current-enrollment__body {
    padding: 28px;
}

.edma-enrollment-grid {
    display: grid;
    grid-template-columns:
        repeat(3, minmax(0,1fr));
    gap: 16px;
}

.edma-enrollment-item {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    padding: 14px;
    border: 1px solid var(--portal-border);
    border-radius: var(--portal-radius-md);
    background: var(--portal-surface-soft);
}

.edma-enrollment-item__icon {
    display: grid;
    width: 39px;
    height: 39px;
    flex-shrink: 0;
    place-items: center;
    border-radius: 10px;
    background: var(--portal-primary-soft);
    color: var(--portal-primary);
}

.edma-enrollment-item > div:last-child {
    display: flex;
    min-width: 0;
    flex-direction: column;
}

.edma-enrollment-item span,
.edma-change-group-card span {
    color: var(--portal-text-muted);
    font-size: .63rem;
}

.edma-enrollment-item strong {
    color: var(--portal-primary-dark);
    font-size: .76rem;
}

.edma-enrollment-item small {
    color: var(--portal-text-muted);
    font-size: .62rem;
}

.edma-enrollment-schedule {
    margin-top: 22px;
    padding: 19px;
    border: 1px solid var(--portal-border);
    border-radius: var(--portal-radius-md);
}

.edma-enrollment-schedule__title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 13px;
}

.edma-enrollment-schedule__title i {
    color: var(--portal-primary);
}

.edma-enrollment-schedule__title div {
    display: flex;
    flex-direction: column;
}

.edma-enrollment-schedule__title strong {
    color: var(--portal-primary-dark);
    font-size: .8rem;
}

.edma-enrollment-schedule__title span {
    color: var(--portal-text-muted);
    font-size: .64rem;
}

.edma-enrollment-schedule__row {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 11px 0;
    border-top: 1px solid var(--portal-border);
}

.edma-enrollment-schedule__row > div:first-child {
    display: flex;
    flex-direction: column;
}

.edma-enrollment-schedule__row strong {
    color: var(--portal-primary-dark);
    font-size: .74rem;
}

.edma-enrollment-schedule__row span {
    color: var(--portal-text-muted);
    font-size: .63rem;
}

.edma-enrollment-hours {
    align-self: center;
    color: var(--portal-primary);
    font-size: .76rem;
    font-weight: 700;
}

.edma-current-enrollment__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 18px 28px;
    border-top: 1px solid var(--portal-border);
    background: var(--portal-surface-soft);
}

.edma-current-enrollment__footer > div {
    display: flex;
    flex-direction: column;
}

.edma-current-enrollment__footer span {
    color: var(--portal-text-muted);
    font-size: .62rem;
}

.edma-current-enrollment__footer strong {
    color: var(--portal-primary-dark);
    font-size: .73rem;
}

.edma-change-modal {
    border: 0;
    border-radius: var(--portal-radius-lg);
}

.edma-change-modal .modal-header {
    align-items: flex-start;
    padding: 22px 24px;
    border-color: var(--portal-border);
}

.edma-change-modal .modal-header p {
    margin-top: 4px;
    color: var(--portal-text-muted);
    font-size: .68rem;
}

.edma-change-groups {
    display: grid;
    grid-template-columns:
        repeat(3, minmax(0,1fr));
    gap: 16px;
    padding: 4px;
}

.edma-change-group-card {
    display: flex;
    flex-direction: column;
    gap: 15px;
    padding: 18px;
    border: 1px solid var(--portal-border);
    border-radius: var(--portal-radius-md);
    background: var(--portal-surface);
}

.edma-change-group-card.is-current {
    border-color: rgba(18,61,106,.25);
    background: var(--portal-primary-soft);
}

.edma-change-group-card__top {
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

.edma-change-group-card__top h3 {
    margin: 2px 0;
    color: var(--portal-primary-dark);
    font-size: .9rem;
}

.edma-change-group-card__top small {
    color: var(--portal-text-muted);
    font-size: .63rem;
}

.edma-current-badge {
    height: fit-content;
    padding: 6px 9px;
    border-radius: 999px;
    background: var(--portal-primary);
    color: #fff !important;
    font-weight: 700;
}

.edma-change-group-card__info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 9px;
}

.edma-change-group-card__info > div {
    display: flex;
    flex-direction: column;
    padding: 10px;
    border-radius: 9px;
    background: var(--portal-surface-soft);
}

.edma-change-group-card__info strong {
    margin-top: 2px;
    color: var(--portal-primary-dark);
    font-size: .7rem;
}

.edma-change-group-card__schedules {
    flex: 1;
}

.edma-change-group-card__schedules > div {
    display: flex;
    flex-direction: column;
    padding: 9px 0;
    border-top: 1px dashed var(--portal-border);
}

.edma-change-group-card__schedules strong {
    color: var(--portal-primary-dark);
    font-size: .72rem;
}

.edma-change-group-card__schedules small {
    color: var(--portal-text-muted);
    font-size: .63rem;
}

.edma-change-confirmation {
    margin-top: 20px;
    padding: 18px;
    border: 1px solid rgba(18,61,106,.12);
    border-radius: var(--portal-radius-md);
    background: var(--portal-surface-soft);
}

.edma-change-confirmation > div:first-child {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.edma-change-confirmation span {
    color: var(--portal-text-muted);
    font-size: .64rem;
}

.edma-change-confirmation strong {
    color: var(--portal-primary-dark);
    font-size: .84rem;
}

.edma-empty-matricula {
    padding: 65px 25px;
    text-align: center;
}

.edma-empty-matricula__icon {
    display: grid;
    width: 58px;
    height: 58px;
    margin: 0 auto 15px;
    place-items: center;
    border-radius: 14px;
    background: var(--portal-primary-soft);
    color: var(--portal-primary);
    font-size: 1.35rem;
}

.edma-empty-matricula h2 {
    color: var(--portal-primary-dark);
    font-size: 1rem;
}

.edma-empty-matricula p {
    margin: 0;
    color: var(--portal-text-muted);
}

@media (max-width: 991.98px) {

    .edma-enrollment-grid,
    .edma-change-groups {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }

}

@media (max-width: 575.98px) {

    .edma-current-enrollment__header,
    .edma-current-enrollment__footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .edma-enrollment-grid,
    .edma-change-groups {
        grid-template-columns: 1fr;
    }

    .edma-enrollment-schedule__row {
        flex-direction: column;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const botones =
            document.querySelectorAll(
                '.btn-elegir-cambio'
            );

        const panel =
            document.getElementById(
                'confirmarCambioPanel'
            );

        const input =
            document.getElementById(
                'nuevoGrupoId'
            );

        const nombre =
            document.getElementById(
                'nuevoGrupoNombre'
            );


        botones.forEach(
            function (boton) {

                boton.addEventListener(
                    'click',
                    function () {

                        botones.forEach(
                            function (item) {
                                item.textContent =
                                    'Seleccionar este grupo';
                            }
                        );


                        boton.textContent =
                            'Grupo seleccionado';


                        input.value =
                            boton.dataset.grupoId;


                        nombre.textContent =
                            boton.dataset.grupoNombre;


                        panel.classList.remove(
                            'd-none'
                        );


                        panel.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }
                );

            }
        );

    }
);

</script>

@endpush