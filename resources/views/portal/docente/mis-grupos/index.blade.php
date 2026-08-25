@extends('layouts.portal')

@section(
    'title',
    'Mis grupos | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Portal Docente
            </span>

            <h1>
                Mis grupos
            </h1>

            <p>
                Consulta los grupos académicos
                que tienes asignados.
            </p>

        </div>

    </div>

@endsection


@section('content')




    {{-- ============================================================
        SIN GRUPOS
    ============================================================ --}}

    @if ($asignaciones->isEmpty())

        <section class="portal-card">

            <div class="text-center py-5 px-4">

                <i
                    class="
                        bi
                        bi-people
                        fs-2
                        text-muted
                    "
                ></i>

                <h5 class="mt-3">
                    No tienes grupos asignados
                </h5>

                <p
                    class="
                        text-muted
                        mb-0
                        mx-auto
                    "
                >
                    Cuando Administración te asigne
                    un grupo académico, aparecerá
                    disponible en esta sección.
                </p>

            </div>

        </section>

    @else

        {{-- ========================================================
            LISTADO
        ======================================================== --}}

        <div class="row g-4">

            @foreach (
                $asignaciones
                as $asignacion
            )

                @php

                    $grupo =
                        $asignacion
                            ->grupo;

                    $nivel =
                        $grupo
                            ->nivel;

                    $programa =
                        $nivel
                            ->programa;

                    $periodo =
                        $grupo
                            ->periodoAcademico;

                    $horarios =
                        $grupo
                            ->horarios
                            ->pluck('horario')
                            ->filter();

                    $cantidadEstudiantes =
                        $grupo
                            ->matriculas
                            ->count();


                    $estadoTexto =
                        match (
                            $grupo->estado
                        ) {
                            'planificado' =>
                                'Planificado',

                            'activo' =>
                                'Activo',

                            'finalizado' =>
                                'Finalizado',

                            'cancelado' =>
                                'Cancelado',

                            default =>
                                str(
                                    $grupo->estado
                                )
                                ->replace(
                                    '_',
                                    ' '
                                )
                                ->title(),
                        };


                    $estadoClase =
                        match (
                            $grupo->estado
                        ) {
                            'activo' =>
                                'success',

                            'planificado' =>
                                'info',

                            'finalizado' =>
                                'secondary',

                            default =>
                                'secondary',
                        };


                    $tipoAsignacion =
                        match (
                            $asignacion
                                ->tipo_asignacion
                        ) {
                            'titular' =>
                                'Docente titular',

                            'suplente' =>
                                'Docente suplente',

                            default =>
                                str(
                                    $asignacion
                                        ->tipo_asignacion
                                        ?? 'Docente'
                                )
                                ->replace(
                                    '_',
                                    ' '
                                )
                                ->title(),
                        };

                @endphp


                <div class="col-12 col-xl-6">

                    <article
                        class="
                            portal-card
                            h-100
                        "
                    >

                        {{-- =========================================
                            CABECERA
                        ========================================== --}}

                        <div class="portal-card-header">

                            <div>

                                <span
                                    class="
                                        text-muted
                                        d-block
                                        mb-1
                                    "
                                >
                                    {{
                                        $periodo
                                            ->nombre
                                    }}

                                    @if (
                                        $periodo
                                            ->fecha_inicio
                                    )

                                        ·

                                        {{
                                            $periodo
                                                ->fecha_inicio
                                                ->format('Y')
                                        }}

                                    @endif

                                </span>


                                <h2>
                                    {{
                                        $grupo
                                            ->nombre
                                    }}
                                </h2>

                                <p>

                                    {{
                                        $grupo
                                            ->codigo
                                    }}

                                </p>

                            </div>


                            <span
                                class="
                                    badge
                                    text-bg-{{
                                        $estadoClase
                                    }}
                                "
                            >
                                {{ $estadoTexto }}
                            </span>

                        </div>


                        {{-- =========================================
                            INFORMACIÓN
                        ========================================== --}}

                        <div class="portal-detail-grid">

                            <div class="portal-detail-item">

                                <span>
                                    Programa
                                </span>

                                <strong>
                                    {{
                                        $programa
                                            ->nombre
                                    }}
                                </strong>

                            </div>


                            <div class="portal-detail-item">

                                <span>
                                    Nivel
                                </span>

                                <strong>

                                    {{
                                        $nivel
                                            ->codigo
                                    }}

                                    @if (
                                        $nivel->nombre
                                        !==
                                        $nivel->codigo
                                    )

                                        ·

                                        {{
                                            $nivel
                                                ->nombre
                                        }}

                                    @endif

                                </strong>

                            </div>


                            <div class="portal-detail-item">

                                <span>
                                    Asignación
                                </span>

                                <strong>
                                    {{
                                        $tipoAsignacion
                                    }}
                                </strong>

                            </div>


                            <div class="portal-detail-item">

                                <span>
                                    Estudiantes
                                </span>

                                <strong>
                                    {{
                                        $cantidadEstudiantes
                                    }}
                                </strong>

                                <small>
                                    Matriculados actualmente
                                </small>

                            </div>

                        </div>


                        {{-- =========================================
                            HORARIOS
                        ========================================== --}}

                        <div
                            class="
                                border-top
                                px-4
                                py-3
                            "
                        >

                            <span
                                class="
                                    text-muted
                                    d-block
                                    mb-2
                                "
                            >
                                Horario
                            </span>


                            @if ($horarios->isEmpty())

                                <span class="text-muted">
                                    Horario pendiente de asignar
                                </span>

                            @else

                                <div
                                    class="
                                        d-flex
                                        gap-2
                                        flex-wrap
                                    "
                                >

                                    @foreach (
                                        $horarios
                                        as $horario
                                    )

                                        <span
                                            class="
                                                badge
                                                text-bg-light
                                                border
                                                text-dark
                                            "
                                        >

                                            {{
                                                $horario
                                                    ->nombre
                                            }}

                                            @if (
                                                $horario
                                                    ->hora_inicio
                                                &&
                                                $horario
                                                    ->hora_fin
                                            )

                                                ·

                                                {{
                                                    \Carbon\Carbon::parse(
                                                        $horario
                                                            ->hora_inicio
                                                    )
                                                    ->format(
                                                        'H:i'
                                                    )
                                                }}

                                                -

                                                {{
                                                    \Carbon\Carbon::parse(
                                                        $horario
                                                            ->hora_fin
                                                    )
                                                    ->format(
                                                        'H:i'
                                                    )
                                                }}

                                            @endif

                                        </span>

                                    @endforeach

                                </div>

                            @endif

                        </div>


                        {{-- =========================================
                            ACCIÓN
                        ========================================== --}}

                        <div
                            class="
                                border-top
                                p-4
                            "
                        >

                            @if (
                                Route::has(
                                    'portal.docente.mis-grupos.show'
                                )
                            )

                                <a
                                    href="{{
                                        route(
                                            'portal.docente.mis-grupos.show',
                                            $grupo
                                        )
                                    }}"
                                    class="
                                        btn
                                        portal-btn-primary
                                        w-100
                                    "
                                >
                                    <i
                                        class="
                                            bi
                                            bi-eye
                                            me-2
                                        "
                                    ></i>

                                    Ver grupo
                                </a>

                            @else

                                <button
                                    type="button"
                                    class="
                                        btn
                                        portal-btn-primary
                                        w-100
                                    "
                                    disabled
                                >
                                    Ver grupo
                                </button>

                            @endif

                        </div>

                    </article>

                </div>

            @endforeach

        </div>

    @endif

@endsection