@extends('layouts.portal')

@section(
    'title',
    'Calificaciones del grupo | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Calificaciones
            </span>

            <h1>
                {{ $grupo->nombre }}
            </h1>

            <p>
                {{ $grupo->periodoAcademico->nombre }}

                ·

                {{ $grupo->nivel->nombre }}

                ·

                {{ $grupo->nivel->programa->nombre }}
            </p>

        </div>


        <div class="portal-page-actions">

            <a
                href="{{
                    route(
                        'portal.admin.calificaciones.grupos',
                        $grupo->periodoAcademico
                    )
                }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left me-2"></i>

                Volver a grupos
            </a>

        </div>

    </div>

@endsection


@section('content')

    {{-- ============================================================
        MENSAJES
    ============================================================ --}}

    @if (session('success'))

        <div
            class="
                alert
                alert-success
                portal-alert
                mb-4
            "
        >

            <i class="bi bi-check-circle-fill"></i>

            <div>

                <strong>
                    Calificación actualizada
                </strong>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        </div>

    @endif


    @if (session('error'))

        <div
            class="
                alert
                alert-danger
                portal-alert
                mb-4
            "
        >

            <i
                class="
                    bi
                    bi-exclamation-triangle-fill
                "
            ></i>

            <div>

                <strong>
                    No fue posible realizar la rectificación
                </strong>

                <span>
                    {{ session('error') }}
                </span>

            </div>

        </div>

    @endif


    {{-- ============================================================
        INFORMACIÓN DEL GRUPO
    ============================================================ --}}

    <section class="portal-card mb-4">

        <div class="p-4">

            <div class="row g-4">

                <div class="col-12 col-md-3">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Período
                    </span>

                    <strong>
                        {{ $grupo->periodoAcademico->nombre }}
                    </strong>

                </div>


                <div class="col-12 col-md-3">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Programa
                    </span>

                    <strong>
                        {{ $grupo->nivel->programa->nombre }}
                    </strong>

                </div>


                <div class="col-12 col-md-3">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Nivel
                    </span>

                    <strong>
                        {{ $grupo->nivel->nombre }}
                    </strong>

                </div>


                <div class="col-12 col-md-3">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Grupo
                    </span>

                    <strong>
                        {{ $grupo->nombre }}
                    </strong>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
        FILTROS
    ============================================================ --}}

    <section class="portal-card mb-4">

        <div class="portal-card-header">

            <div>

                <h2>
                    Buscar y filtrar
                </h2>

                <p>
                    Localiza una calificación dentro
                    de este grupo.
                </p>

            </div>

        </div>


        <div class="p-4">

            <form
                method="GET"
                action="{{
                    route(
                        'portal.admin.calificaciones.grupo',
                        $grupo
                    )
                }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Buscar --}}
                    <div class="col-12 col-lg-5">

                        <label
                            for="buscar"
                            class="form-label"
                        >
                            Estudiante
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="buscar"
                            name="buscar"
                            value="{{ $buscar }}"
                            placeholder="Nombre o código EDMA"
                        >

                    </div>


                    {{-- Resultado --}}
                    <div
                        class="
                            col-12
                            col-md-6
                            col-lg-3
                        "
                    >

                        <label
                            for="resultado"
                            class="form-label"
                        >
                            Resultado
                        </label>

                        <select
                            id="resultado"
                            name="resultado"
                            class="form-select"
                        >

                            <option value="">
                                Todos
                            </option>

                            <option
                                value="aprobado"
                                @selected(
                                    $resultadoFiltro
                                    === 'aprobado'
                                )
                            >
                                APR · Aprobado
                            </option>

                            <option
                                value="reprobado"
                                @selected(
                                    $resultadoFiltro
                                    === 'reprobado'
                                )
                            >
                                REP · Reprobado
                            </option>

                            <option
                                value="incompleto"
                                @selected(
                                    $resultadoFiltro
                                    === 'incompleto'
                                )
                            >
                                NSP · No se presentó
                            </option>

                            <option
                                value="retirado"
                                @selected(
                                    $resultadoFiltro
                                    === 'retirado'
                                )
                            >
                                ABD · Abandono
                            </option>

                        </select>

                    </div>


                    {{-- Rectificación --}}
                    <div
                        class="
                            col-12
                            col-md-6
                            col-lg-2
                        "
                    >

                        <label
                            for="rectificado"
                            class="form-label"
                        >
                            Rectificación
                        </label>

                        <select
                            id="rectificado"
                            name="rectificado"
                            class="form-select"
                        >

                            <option value="">
                                Todos
                            </option>

                            <option
                                value="si"
                                @selected(
                                    $rectificadoFiltro
                                    === 'si'
                                )
                            >
                                Con cambios
                            </option>

                            <option
                                value="no"
                                @selected(
                                    $rectificadoFiltro
                                    === 'no'
                                )
                            >
                                Sin cambios
                            </option>

                        </select>

                    </div>


                    {{-- Botón --}}
                    <div
                        class="
                            col-12
                            col-lg-2
                        "
                    >

                        <button
                            type="submit"
                            class="
                                btn
                                portal-btn-primary
                                w-100
                            "
                        >
                            <i class="bi bi-funnel me-2"></i>

                            Filtrar
                        </button>

                    </div>

                </div>


                @if (
                    $buscar !== ''
                    ||
                    $resultadoFiltro
                    ||
                    $rectificadoFiltro
                )

                    <div class="mt-3">

                        <a
                            href="{{
                                route(
                                    'portal.admin.calificaciones.grupo',
                                    $grupo
                                )
                            }}"
                            class="
                                btn
                                portal-btn-secondary
                                btn-sm
                            "
                        >
                            <i
                                class="
                                    bi
                                    bi-x-circle
                                    me-1
                                "
                            ></i>

                            Limpiar filtros
                        </a>

                    </div>

                @endif

            </form>

        </div>

    </section>


    {{-- ============================================================
        LISTADO
    ============================================================ --}}

    <section class="portal-card">

        <div class="portal-card-header">

            <div>

                <h2>
                    Calificaciones definitivas
                </h2>

                <p>
                    Consulta los resultados académicos
                    definitivos del grupo.
                </p>

            </div>


            <span
                class="
                    badge
                    text-bg-light
                    border
                    text-dark
                "
            >
                {{ $calificaciones->total() }}

                {{
                    $calificaciones->total() === 1
                        ? 'registro'
                        : 'registros'
                }}
            </span>

        </div>


        @if ($calificaciones->isEmpty())

            <div class="text-center py-5 px-4">

                <i
                    class="
                        bi
                        bi-journal-x
                        fs-2
                        text-muted
                    "
                ></i>

                <h5 class="mt-3">
                    No se encontraron calificaciones
                </h5>

                <p class="text-muted mb-0">

                    @if (
                        $buscar !== ''
                        ||
                        $resultadoFiltro
                        ||
                        $rectificadoFiltro
                    )

                        No hay registros que coincidan
                        con los filtros seleccionados.

                    @else

                        Este grupo todavía no tiene
                        calificaciones definitivas.

                    @endif

                </p>

            </div>

        @else

            <div class="table-responsive">

                <table
                    class="
                        table
                        align-middle
                        mb-0
                    "
                >

                    <thead>

                        <tr>

                            <th>
                                Estudiante
                            </th>

                            <th class="text-center">
                                Nota final
                            </th>

                            <th class="text-center">
                                Resultado
                            </th>

                            <th class="text-center">
                                Historial
                            </th>

                            <th class="text-end">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach (
                            $calificaciones
                            as $calificacion
                        )

                            @php

                                $matricula =
                                    $calificacion
                                        ->matricula;

                                $estudiante =
                                    $matricula
                                        ->estudiante;

                                $persona =
                                    $estudiante
                                        ->persona;


                                $resultado =
                                    match (
                                        $calificacion
                                            ->resultado
                                    ) {
                                        'aprobado' =>
                                            'APR',

                                        'reprobado' =>
                                            'REP',

                                        'incompleto' =>
                                            'NSP',

                                        'retirado' =>
                                            'ABD',

                                        default =>
                                            '—',
                                    };


                                $resultadoTexto =
                                    match (
                                        $calificacion
                                            ->resultado
                                    ) {
                                        'aprobado' =>
                                            'Aprobado',

                                        'reprobado' =>
                                            'Reprobado',

                                        'incompleto' =>
                                            'No se presentó',

                                        'retirado' =>
                                            'Abandono',

                                        default =>
                                            'Sin resultado',
                                    };


                                $resultadoClase =
                                    match (
                                        $calificacion
                                            ->resultado
                                    ) {
                                        'aprobado' =>
                                            'success',

                                        'reprobado' =>
                                            'danger',

                                        'incompleto',
                                        'retirado' =>
                                            'secondary',

                                        default =>
                                            'light',
                                    };


                                $tipoResultadoActual =
                                    match (
                                        $calificacion
                                            ->resultado
                                    ) {
                                        'incompleto' =>
                                            'incompleto',

                                        'retirado' =>
                                            'retirado',

                                        default =>
                                            'normal',
                                    };

                            @endphp


                            <tr>

                                {{-- Estudiante --}}
                                <td>

                                    <strong class="d-block">

                                        {{
                                            $persona
                                                ->nombre_completo
                                        }}

                                    </strong>

                                    <small class="text-muted">

                                        {{
                                            $estudiante
                                                ->codigo_estudiante
                                        }}

                                    </small>

                                </td>


                                {{-- Nota --}}
                                <td
                                    class="
                                        text-center
                                        text-nowrap
                                    "
                                >

                                    @if (
                                        !is_null(
                                            $calificacion
                                                ->nota_final
                                        )
                                    )

                                        <strong>

                                            {{
                                                number_format(
                                                    (float)
                                                    $calificacion
                                                        ->nota_final,
                                                    2
                                                )
                                            }}

                                        </strong>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Resultado --}}
                                <td class="text-center">

                                    <span
                                        class="
                                            badge
                                            text-bg-{{
                                                $resultadoClase
                                            }}
                                        "
                                        title="{{
                                            $resultadoTexto
                                        }}"
                                    >
                                        {{ $resultado }}
                                    </span>

                                </td>


                                {{-- Historial --}}
                                <td class="text-center">

                                    @if (
                                        $calificacion
                                            ->historial
                                            ->isNotEmpty()
                                    )

                                        <button
                                            type="button"
                                            class="
                                                btn
                                                portal-btn-secondary
                                                btn-sm
                                            "
                                            data-bs-toggle="modal"
                                            data-bs-target="#historialCalificacionModal{{ $calificacion->id }}"
                                        >
                                            <i
                                                class="
                                                    bi
                                                    bi-clock-history
                                                    me-1
                                                "
                                            ></i>

                                            {{
                                                $calificacion
                                                    ->historial
                                                    ->count()
                                            }}
                                        </button>

                                    @else

                                        <span class="text-muted">
                                            Sin cambios
                                        </span>

                                    @endif

                                </td>


                                {{-- Acciones --}}
                                <td class="text-end">

                                    <button
                                        type="button"
                                        class="
                                            btn
                                            portal-btn-secondary
                                            btn-sm
                                        "
                                        data-bs-toggle="modal"
                                        data-bs-target="#rectificarCalificacionModal{{ $calificacion->id }}"
                                    >
                                        <i
                                            class="
                                                bi
                                                bi-pencil-square
                                                me-1
                                            "
                                        ></i>

                                        Rectificar
                                    </button>

                                </td>

                            </tr>


                            {{-- ====================================================
                                MODAL RECTIFICAR
                            ==================================================== --}}

                            <div
                                class="modal fade"
                                id="rectificarCalificacionModal{{ $calificacion->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div
                                    class="
                                        modal-dialog
                                        modal-dialog-centered
                                    "
                                >

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <div>

                                                <span
                                                    class="
                                                        text-muted
                                                        d-block
                                                        mb-1
                                                    "
                                                >
                                                    Rectificación administrativa
                                                </span>

                                                <h5 class="modal-title">

                                                    {{
                                                        $persona
                                                            ->nombre_completo
                                                    }}

                                                </h5>

                                            </div>


                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                                aria-label="Cerrar"
                                            ></button>

                                        </div>


                                        <form
                                            method="POST"
                                            action="{{
                                                route(
                                                    'portal.admin.calificaciones.rectificar',
                                                    $calificacion
                                                )
                                            }}"
                                        >

                                            @csrf
                                            @method('PATCH')


                                            <div class="modal-body">

                                                <div
                                                    class="
                                                        alert
                                                        alert-warning
                                                    "
                                                >

                                                    <strong
                                                        class="
                                                            d-block
                                                            mb-1
                                                        "
                                                    >
                                                        Registro académico definitivo
                                                    </strong>

                                                    <span>
                                                        Esta acción modificará la
                                                        calificación oficial. El
                                                        valor anterior y el motivo
                                                        del cambio quedarán
                                                        registrados.
                                                    </span>

                                                </div>


                                                {{-- Actual --}}
                                                <div class="row g-3 mb-4">

                                                    <div class="col-6">

                                                        <span
                                                            class="
                                                                text-muted
                                                                d-block
                                                                mb-1
                                                            "
                                                        >
                                                            Nota actual
                                                        </span>

                                                        <strong>

                                                            {{
                                                                is_null(
                                                                    $calificacion
                                                                        ->nota_final
                                                                )
                                                                    ? '—'
                                                                    : number_format(
                                                                        (float)
                                                                        $calificacion
                                                                            ->nota_final,
                                                                        2
                                                                    )
                                                            }}

                                                        </strong>

                                                    </div>


                                                    <div class="col-6">

                                                        <span
                                                            class="
                                                                text-muted
                                                                d-block
                                                                mb-1
                                                            "
                                                        >
                                                            Resultado actual
                                                        </span>

                                                        <strong>

                                                            {{ $resultado }}

                                                            ·

                                                            {{ $resultadoTexto }}

                                                        </strong>

                                                    </div>

                                                </div>


                                                {{-- Tipo --}}
                                                <div class="mb-3">

                                                    <label
                                                        for="tipo_resultado_{{ $calificacion->id }}"
                                                        class="form-label"
                                                    >
                                                        Nuevo resultado
                                                    </label>

                                                    <select
                                                        id="tipo_resultado_{{ $calificacion->id }}"
                                                        name="tipo_resultado"
                                                        class="
                                                            form-select
                                                            rectificacion-resultado
                                                        "
                                                        data-nota-target="nota_rectificacion_{{ $calificacion->id }}"
                                                        required
                                                    >

                                                        <option
                                                            value="normal"
                                                            @selected(
                                                                $tipoResultadoActual
                                                                === 'normal'
                                                            )
                                                        >
                                                            Nota ordinaria
                                                        </option>

                                                        <option
                                                            value="incompleto"
                                                            @selected(
                                                                $tipoResultadoActual
                                                                === 'incompleto'
                                                            )
                                                        >
                                                            NSP · No se presentó
                                                        </option>

                                                        <option
                                                            value="retirado"
                                                            @selected(
                                                                $tipoResultadoActual
                                                                === 'retirado'
                                                            )
                                                        >
                                                            ABD · Abandono
                                                        </option>

                                                    </select>

                                                </div>


                                                {{-- Nota --}}
                                                <div class="mb-3">

                                                    <label
                                                        for="nota_rectificacion_{{ $calificacion->id }}"
                                                        class="form-label"
                                                    >
                                                        Nueva nota final
                                                    </label>

                                                    <input
                                                        type="number"
                                                        id="nota_rectificacion_{{ $calificacion->id }}"
                                                        name="nota_final"
                                                        class="form-control"
                                                        min="0"
                                                        max="100"
                                                        step="1"
                                                        value="{{
                                                            $calificacion
                                                                ->nota_final
                                                        }}"
                                                    >

                                                    <div class="form-text">
                                                        Para una nota ordinaria,
                                                        APR o REP se calculará
                                                        automáticamente según la
                                                        nota mínima de aprobación
                                                        del nivel.
                                                    </div>

                                                </div>


                                                {{-- Motivo --}}
                                                <div>

                                                    <label
                                                        for="motivo_{{ $calificacion->id }}"
                                                        class="form-label"
                                                    >
                                                        Motivo de la rectificación
                                                    </label>

                                                    <textarea
                                                        id="motivo_{{ $calificacion->id }}"
                                                        name="motivo"
                                                        class="form-control"
                                                        rows="4"
                                                        maxlength="1000"
                                                        required
                                                    ></textarea>

                                                    <div class="form-text">
                                                        Explica claramente por qué
                                                        es necesario modificar la
                                                        calificación oficial.
                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="
                                                        btn
                                                        portal-btn-secondary
                                                    "
                                                    data-bs-dismiss="modal"
                                                >
                                                    Cancelar
                                                </button>


                                                <button
                                                    type="submit"
                                                    class="
                                                        btn
                                                        portal-btn-primary
                                                    "
                                                >
                                                    <i
                                                        class="
                                                            bi
                                                            bi-check2-circle
                                                            me-2
                                                        "
                                                    ></i>

                                                    Guardar rectificación
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            {{-- ====================================================
                                MODAL HISTORIAL
                            ==================================================== --}}

                            @if (
                                $calificacion
                                    ->historial
                                    ->isNotEmpty()
                            )

                                <div
                                    class="modal fade"
                                    id="historialCalificacionModal{{ $calificacion->id }}"
                                    tabindex="-1"
                                    aria-hidden="true"
                                >

                                    <div
                                        class="
                                            modal-dialog
                                            modal-lg
                                            modal-dialog-centered
                                            modal-dialog-scrollable
                                        "
                                    >

                                        <div class="modal-content">

                                            <div class="modal-header">

                                                <div>

                                                    <span
                                                        class="
                                                            text-muted
                                                            d-block
                                                            mb-1
                                                        "
                                                    >
                                                        Historial de rectificaciones
                                                    </span>

                                                    <h5 class="modal-title">

                                                        {{
                                                            $persona
                                                                ->nombre_completo
                                                        }}

                                                    </h5>

                                                </div>


                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Cerrar"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                @foreach (
                                                    $calificacion
                                                        ->historial
                                                    as $cambio
                                                )

                                                    @php

                                                        $resultadoAnterior =
                                                            match (
                                                                $cambio
                                                                    ->resultado_anterior
                                                            ) {
                                                                'aprobado' =>
                                                                    'APR',

                                                                'reprobado' =>
                                                                    'REP',

                                                                'incompleto' =>
                                                                    'NSP',

                                                                'retirado' =>
                                                                    'ABD',

                                                                default =>
                                                                    '—',
                                                            };


                                                        $resultadoNuevo =
                                                            match (
                                                                $cambio
                                                                    ->resultado_nuevo
                                                            ) {
                                                                'aprobado' =>
                                                                    'APR',

                                                                'reprobado' =>
                                                                    'REP',

                                                                'incompleto' =>
                                                                    'NSP',

                                                                'retirado' =>
                                                                    'ABD',

                                                                default =>
                                                                    '—',
                                                            };

                                                    @endphp


                                                    <div
                                                        class="
                                                            border
                                                            rounded-3
                                                            p-3
                                                            mb-3
                                                        "
                                                    >

                                                        <div
                                                            class="
                                                                d-flex
                                                                justify-content-between
                                                                gap-3
                                                                flex-wrap
                                                                mb-3
                                                            "
                                                        >

                                                            <strong>
                                                                Rectificación
                                                            </strong>

                                                            <span class="text-muted">

                                                                {{
                                                                    $cambio
                                                                        ->cambiado_at
                                                                        ?->format(
                                                                            'd/m/Y H:i'
                                                                        )
                                                                }}

                                                            </span>

                                                        </div>


                                                        <div class="row g-3 mb-3">

                                                            <div class="col-6">

                                                                <span
                                                                    class="
                                                                        text-muted
                                                                        d-block
                                                                        mb-1
                                                                    "
                                                                >
                                                                    Anterior
                                                                </span>

                                                                <strong>

                                                                    {{
                                                                        is_null(
                                                                            $cambio
                                                                                ->nota_anterior
                                                                        )
                                                                            ? '—'
                                                                            : number_format(
                                                                                (float)
                                                                                $cambio
                                                                                    ->nota_anterior,
                                                                                2
                                                                            )
                                                                    }}

                                                                    ·

                                                                    {{
                                                                        $resultadoAnterior
                                                                    }}

                                                                </strong>

                                                            </div>


                                                            <div class="col-6">

                                                                <span
                                                                    class="
                                                                        text-muted
                                                                        d-block
                                                                        mb-1
                                                                    "
                                                                >
                                                                    Nuevo
                                                                </span>

                                                                <strong>

                                                                    {{
                                                                        is_null(
                                                                            $cambio
                                                                                ->nota_nueva
                                                                        )
                                                                            ? '—'
                                                                            : number_format(
                                                                                (float)
                                                                                $cambio
                                                                                    ->nota_nueva,
                                                                                2
                                                                            )
                                                                    }}

                                                                    ·

                                                                    {{
                                                                        $resultadoNuevo
                                                                    }}

                                                                </strong>

                                                            </div>

                                                        </div>


                                                        <div>

                                                            <span
                                                                class="
                                                                    text-muted
                                                                    d-block
                                                                    mb-1
                                                                "
                                                            >
                                                                Motivo
                                                            </span>

                                                            <p class="mb-0">

                                                                {{
                                                                    $cambio
                                                                        ->motivo
                                                                }}

                                                            </p>

                                                        </div>

                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endif

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- ====================================================
                PAGINACIÓN
            ==================================================== --}}

            @if (
                $calificaciones
                    ->hasPages()
            )

                <div class="p-3 border-top">

                    {{
                        $calificaciones
                            ->links()
                    }}

                </div>

            @endif

        @endif

    </section>

@endsection


@push('scripts')

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        const selects =
            document.querySelectorAll(
                '.rectificacion-resultado'
            );

        selects.forEach(
            function (select) {

                const actualizarNota =
                    function () {

                        const input =
                            document.getElementById(
                                select.dataset.notaTarget
                            );

                        if (!input) {
                            return;
                        }

                        if (
                            select.value === 'normal'
                        ) {
                            input.disabled = false;

                            return;
                        }

                        input.value = '';
                        input.disabled = true;
                    };


                select.addEventListener(
                    'change',
                    actualizarNota
                );

                actualizarNota();

            }
        );

    }
);
</script>

@endpush