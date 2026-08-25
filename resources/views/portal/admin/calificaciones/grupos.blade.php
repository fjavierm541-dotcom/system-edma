@extends('layouts.portal')

@section(
    'title',
    'Grupos | Calificaciones | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Calificaciones
            </span>

            <h1>
                {{ $periodo->nombre }}
            </h1>

            <p>
                Selecciona un grupo para consultar
                sus calificaciones finales.
            </p>

        </div>


        <div class="portal-page-actions">

            <a
                href="{{
                    route(
                        'portal.admin.calificaciones.index'
                    )
                }}"
                class="btn portal-btn-secondary"
            >
                <i
                    class="
                        bi
                        bi-arrow-left
                        me-2
                    "
                ></i>

                Volver a períodos
            </a>

        </div>

    </div>

@endsection


@section('content')

    {{-- ============================================================
        INFORMACIÓN DEL PERÍODO
    ============================================================ --}}

    <section class="portal-card mb-4">

        <div class="p-4">

            <div class="row g-4">

                <div class="col-12 col-md-4">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Período académico
                    </span>

                    <strong>
                        {{ $periodo->nombre }}
                    </strong>

                </div>


                <div class="col-12 col-md-4">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Código
                    </span>

                    <strong>
                        {{ $periodo->codigo }}
                    </strong>

                </div>


                <div class="col-12 col-md-4">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Grupos registrados
                    </span>

                    <strong>
                        {{ $grupos->count() }}
                    </strong>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
        GRUPOS
    ============================================================ --}}

    @if ($grupos->isEmpty())

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
                    No hay grupos registrados
                </h5>

                <p class="text-muted mb-0">
                    Este período académico todavía
                    no tiene grupos disponibles.
                </p>

            </div>

        </section>

    @else

        @php

            /*
            |--------------------------------------------------------------------------
            | Agrupar por programa
            |--------------------------------------------------------------------------
            */

            $gruposPorPrograma =
                $grupos->groupBy(
                    function ($grupo) {
                        return $grupo
                            ->nivel
                            ?->programa
                            ?->id
                            ?? 'sin-programa';
                    }
                );

        @endphp


        @foreach (
            $gruposPorPrograma
            as $gruposPrograma
        )

            @php

                $programa =
                    $gruposPrograma
                        ->first()
                        ?->nivel
                        ?->programa;

                $segmento =
                    match (
                        $programa?->segmento
                    ) {
                        'niños' =>
                            'Niños',

                        'jóvenes_adultos' =>
                            'Jóvenes y adultos',

                        default =>
                            $programa?->segmento
                                ? str(
                                    $programa
                                        ->segmento
                                )
                                    ->replace(
                                        '_',
                                        ' '
                                    )
                                    ->title()
                                    ->toString()
                                : null,
                    };

            @endphp


            <section class="mb-5">

                {{-- ====================================================
                    ENCABEZADO DEL PROGRAMA
                ==================================================== --}}

                <div
                    class="
                        d-flex
                        align-items-end
                        justify-content-between
                        gap-3
                        flex-wrap
                        mb-3
                    "
                >

                    <div>

                        <span
                            class="
                                portal-page-eyebrow
                                d-block
                                mb-1
                            "
                        >
                            Programa académico
                        </span>

                        <h2 class="mb-1">

                            {{
                                $programa?->nombre
                                ?? 'Programa no disponible'
                            }}

                        </h2>


                        @if ($segmento)

                            <span class="text-muted">
                                {{ $segmento }}
                            </span>

                        @endif

                    </div>


                    <span
                        class="
                            badge
                            text-bg-light
                            border
                            text-dark
                        "
                    >
                        {{
                            $gruposPrograma
                                ->count()
                        }}

                        {{
                            $gruposPrograma
                                ->count() === 1
                                    ? 'grupo'
                                    : 'grupos'
                        }}
                    </span>

                </div>


                {{-- ====================================================
                    TARJETAS DE GRUPOS
                ==================================================== --}}

                <div class="row g-4">

                    @foreach (
                        $gruposPrograma
                        as $grupo
                    )

                        @php

                            $nivel =
                                $grupo
                                    ->nivel;

                            $tieneTodas =
                                $grupo
                                    ->estudiantes_count
                                > 0
                                &&
                                $grupo
                                    ->calificaciones_count
                                ===
                                $grupo
                                    ->estudiantes_count;

                        @endphp


                        <div
                            class="
                                col-12
                                col-md-6
                                col-xl-4
                            "
                        >

                            <article
                                class="
                                    portal-card
                                    h-100
                                "
                            >

                                {{-- ====================================
                                    ENCABEZADO
                                ==================================== --}}

                                <div class="portal-card-header">

                                    <div>

                                        <span
                                            class="
                                                text-muted
                                                d-block
                                                mb-1
                                            "
                                        >
                                            {{ $grupo->codigo }}
                                        </span>

                                        <h2>
                                            {{ $grupo->nombre }}
                                        </h2>

                                        <p class="mb-0">

                                            {{
                                                $nivel?->nombre
                                                ?? 'Nivel no disponible'
                                            }}

                                        </p>

                                    </div>

                                </div>


                                {{-- ====================================
                                    INFORMACIÓN
                                ==================================== --}}

                                <div class="p-4">

                                    <div class="row g-3 mb-4">

                                        <div class="col-6">

                                            <span
                                                class="
                                                    text-muted
                                                    d-block
                                                    mb-1
                                                "
                                            >
                                                Estudiantes
                                            </span>

                                            <strong class="fs-4">

                                                {{
                                                    $grupo
                                                        ->estudiantes_count
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
                                                Definitivas
                                            </span>

                                            <strong class="fs-4">

                                                {{
                                                    $grupo
                                                        ->calificaciones_count
                                                }}

                                            </strong>

                                        </div>

                                    </div>


                                    {{-- ====================================
                                        ESTADO DE CALIFICACIONES
                                    ==================================== --}}

                                    <div class="mb-4">

                                        @if (
                                            $grupo
                                                ->estudiantes_count
                                            === 0
                                        )

                                            <span
                                                class="
                                                    badge
                                                    text-bg-light
                                                    border
                                                    text-dark
                                                "
                                            >
                                                Sin estudiantes
                                            </span>

                                        @elseif (
                                            $tieneTodas
                                        )

                                            <span
                                                class="
                                                    badge
                                                    text-bg-success
                                                "
                                            >
                                                <i
                                                    class="
                                                        bi
                                                        bi-check-circle
                                                        me-1
                                                    "
                                                ></i>

                                                Calificaciones completas
                                            </span>

                                        @elseif (
                                            $grupo
                                                ->calificaciones_count
                                            > 0
                                        )

                                            <span
                                                class="
                                                    badge
                                                    text-bg-warning
                                                "
                                            >
                                                <i
                                                    class="
                                                        bi
                                                        bi-clock
                                                        me-1
                                                    "
                                                ></i>

                                                Proceso incompleto
                                            </span>

                                        @else

                                            <span
                                                class="
                                                    badge
                                                    text-bg-secondary
                                                "
                                            >
                                                Sin calificaciones definitivas
                                            </span>

                                        @endif

                                    </div>


                                    {{-- ====================================
                                        ACCIÓN
                                    ==================================== --}}

                                    <a
                                        href="{{
                                            route(
                                                'portal.admin.calificaciones.grupo',
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
                                                bi-journal-check
                                                me-2
                                            "
                                        ></i>

                                        Ver calificaciones
                                    </a>

                                </div>

                            </article>

                        </div>

                    @endforeach

                </div>

            </section>

        @endforeach

    @endif

@endsection