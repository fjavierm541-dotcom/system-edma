@extends('layouts.portal')

@section(
    'title',
    'Inicio | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Portal Docente
            </span>

            <h1>
                Inicio
            </h1>

            <p>
                Consulta tus grupos y la información
                académica importante del período actual.
            </p>

        </div>

    </div>

@endsection


@section('content')

    {{-- ============================================================
        BIENVENIDA
    ============================================================ --}}

    <section class="portal-card mb-4">

        <div class="p-4">

            <div
                class="
                    d-flex
                    align-items-center
                    gap-4
                    flex-wrap
                "
            >

                {{-- Foto --}}
                <div>

                    @if ($persona->foto_perfil)

                        <img
                            src="{{
                                asset(
                                    'storage/'
                                    . ltrim(
                                        $persona
                                            ->foto_perfil,
                                        '/'
                                    )
                                )
                            }}"
                            alt="Fotografía de perfil"
                            class="
                                rounded-circle
                                border
                            "
                            style="
                                width: 78px;
                                height: 78px;
                                object-fit: cover;
                            "
                        >

                    @else

                        <div
                            class="
                                d-flex
                                align-items-center
                                justify-content-center
                                rounded-circle
                                border
                                bg-light
                            "
                            style="
                                width: 78px;
                                height: 78px;
                            "
                        >

                            <i
                                class="
                                    bi
                                    bi-person
                                    fs-3
                                    text-muted
                                "
                            ></i>

                        </div>

                    @endif

                </div>


                <div>

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Bienvenido al Portal Docente
                    </span>

                    <h2 class="mb-1">

                        {{
                            $persona
                                ->nombre_completo
                        }}

                    </h2>

                    <span class="text-muted">

                        {{
                            $docente
                                ->codigo_docente
                        }}

                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
        RESUMEN
    ============================================================ --}}

    <div class="row g-4 mb-4">

        {{-- Grupos asignados --}}
        <div class="col-12 col-md-4">

            <section class="portal-card h-100">

                <div class="p-4">

                    <div
                        class="
                            d-flex
                            align-items-center
                            gap-3
                        "
                    >

                        <div class="portal-stat-icon">

                            <i class="bi bi-people"></i>

                        </div>


                        <div>

                            <span
                                class="
                                    text-muted
                                    d-block
                                    mb-1
                                "
                            >
                                Grupos asignados
                            </span>

                            <strong class="fs-3">
                                {{ $cantidadGrupos }}
                            </strong>

                        </div>

                    </div>

                </div>

            </section>

        </div>


        {{-- Estudiantes --}}
        <div class="col-12 col-md-4">

            <section class="portal-card h-100">

                <div class="p-4">

                    <div
                        class="
                            d-flex
                            align-items-center
                            gap-3
                        "
                    >

                        <div class="portal-stat-icon">

                            <i
                                class="
                                    bi
                                    bi-person-check
                                "
                            ></i>

                        </div>


                        <div>

                            <span
                                class="
                                    text-muted
                                    d-block
                                    mb-1
                                "
                            >
                                Estudiantes
                            </span>

                            <strong class="fs-3">
                                {{ $cantidadEstudiantes }}
                            </strong>

                            <small
                                class="
                                    text-muted
                                    d-block
                                    mt-1
                                "
                            >
                                En tus grupos actuales
                            </small>

                        </div>

                    </div>

                </div>

            </section>

        </div>


        {{-- Período actual --}}
        <div class="col-12 col-md-4">

            <section class="portal-card h-100">

                <div class="p-4">

                    <div
                        class="
                            d-flex
                            align-items-start
                            gap-3
                        "
                    >

                        <div class="portal-stat-icon">

                            <i
                                class="
                                    bi
                                    bi-calendar-range
                                "
                            ></i>

                        </div>


                        <div class="flex-grow-1">

                            <span
                                class="
                                    text-muted
                                    d-block
                                    mb-1
                                "
                            >
                                Período académico actual
                            </span>


                            @if ($periodoActual)

                                <strong
                                    class="
                                        d-block
                                        mb-2
                                    "
                                >
                                    {{
                                        $periodoActual
                                            ->nombre
                                    }}
                                </strong>


                                @if (
                                    $periodoActual
                                        ->fecha_inicio
                                    &&
                                    $periodoActual
                                        ->fecha_fin
                                )

                                    <small
                                        class="
                                            text-muted
                                            d-block
                                        "
                                    >
                                        {{
                                            $periodoActual
                                                ->fecha_inicio
                                                ->format(
                                                    'd/m/Y'
                                                )
                                        }}

                                        —

                                        {{
                                            $periodoActual
                                                ->fecha_fin
                                                ->format(
                                                    'd/m/Y'
                                                )
                                        }}
                                    </small>

                                @endif

                            @else

                                <strong
                                    class="
                                        d-block
                                        text-muted
                                    "
                                >
                                    No hay un período activo
                                </strong>

                            @endif

                        </div>

                    </div>

                </div>

            </section>

        </div>

    </div>


    {{-- ============================================================
        INFORMACIÓN DEL PERÍODO ACTUAL
    ============================================================ --}}

    @if ($periodoActual)

        <section class="portal-card mb-4">

            <div class="portal-card-header">

                <div>

                    <h2>
                        Información del período
                    </h2>

                    <p>
                        Fechas importantes para tus
                        actividades académicas.
                    </p>

                </div>

            </div>


            <div class="p-4">

                <div class="row g-4">

                    {{-- Inicio --}}
                    <div class="col-12 col-md-3">

                        <span
                            class="
                                text-muted
                                d-block
                                mb-1
                            "
                        >
                            Inicio de clases
                        </span>

                        <strong>

                            @if (
                                $periodoActual
                                    ->fecha_inicio
                            )

                                {{
                                    $periodoActual
                                        ->fecha_inicio
                                        ->format(
                                            'd/m/Y'
                                        )
                                }}

                            @else

                                No definida

                            @endif

                        </strong>

                    </div>


                    {{-- Fin --}}
                    <div class="col-12 col-md-3">

                        <span
                            class="
                                text-muted
                                d-block
                                mb-1
                            "
                        >
                            Final de clases
                        </span>

                        <strong>

                            @if (
                                $periodoActual
                                    ->fecha_fin
                            )

                                {{
                                    $periodoActual
                                        ->fecha_fin
                                        ->format(
                                            'd/m/Y'
                                        )
                                }}

                            @else

                                No definida

                            @endif

                        </strong>

                    </div>


                    {{-- Inicio de calificaciones --}}
                    <div class="col-12 col-md-3">

                        <span
                            class="
                                text-muted
                                d-block
                                mb-1
                            "
                        >
                            Desde cuándo puedes
                            registrar calificaciones
                        </span>

                        <strong>

                            @if (
                                $periodoActual
                                    ->calificaciones_desde
                            )

                                {{
                                    $periodoActual
                                        ->calificaciones_desde
                                        ->format(
                                            'd/m/Y H:i'
                                        )
                                }}

                            @else

                                No definida

                            @endif

                        </strong>

                    </div>


                    {{-- Fin de calificaciones --}}
                    <div class="col-12 col-md-3">

                        <span
                            class="
                                text-muted
                                d-block
                                mb-1
                            "
                        >
                            Fecha límite para
                            registrar calificaciones
                        </span>

                        <strong>

                            @if (
                                $periodoActual
                                    ->calificaciones_hasta
                            )

                                {{
                                    $periodoActual
                                        ->calificaciones_hasta
                                        ->format(
                                            'd/m/Y H:i'
                                        )
                                }}

                            @else

                                No definida

                            @endif

                        </strong>

                    </div>

                </div>


                {{-- Estado de las calificaciones --}}
                <div
                    class="
                        border-top
                        mt-4
                        pt-3
                    "
                >

                    @if (
                        $estadoCalificaciones
                        === 'habilitadas'
                    )

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-2
                                text-success
                            "
                        >

                            <i
                                class="
                                    bi
                                    bi-check-circle
                                "
                            ></i>

                            <strong>
                                Ya puedes registrar
                                las calificaciones finales.
                            </strong>

                        </div>

                    @elseif (
                        $estadoCalificaciones
                        === 'programadas'
                    )

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-2
                                text-muted
                            "
                        >

                            <i class="bi bi-clock"></i>

                            <span>
                                Las calificaciones todavía
                                no están habilitadas.
                                Podrás registrarlas a partir
                                de la fecha indicada.
                            </span>

                        </div>

                    @elseif (
                        $estadoCalificaciones
                        === 'finalizadas'
                    )

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-2
                                text-muted
                            "
                        >

                            <i
                                class="
                                    bi
                                    bi-lock
                                "
                            ></i>

                            <span>
                                El plazo para registrar
                                las calificaciones finales
                                de este período ya terminó.
                            </span>

                        </div>

                    @else

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-2
                                text-muted
                            "
                        >

                            <i
                                class="
                                    bi
                                    bi-info-circle
                                "
                            ></i>

                            <span>
                                Administración todavía
                                no ha definido las fechas
                                para registrar las
                                calificaciones finales.
                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </section>

    @endif


    {{-- ============================================================
        ACCESOS RÁPIDOS
    ============================================================ --}}

    <section class="portal-card mb-4">

        <div class="portal-card-header">

            <div>

                <h2>
                    Accesos rápidos
                </h2>

                <p>
                    Ingresa directamente a las
                    funciones principales de tu cuenta.
                </p>

            </div>

        </div>


        <div class="p-4">

            <div class="row g-3">

                {{-- Mis grupos --}}
                <div class="col-12 col-md-6">

                    <a
                        href="{{
                            route(
                                'portal.docente.mis-grupos.index'
                            )
                        }}"
                        class="
                            portal-card
                            d-flex
                            align-items-center
                            gap-3
                            p-4
                            text-decoration-none
                            h-100
                        "
                    >

                        <div class="portal-stat-icon">

                            <i class="bi bi-people"></i>

                        </div>


                        <div>

                            <strong
                                class="
                                    d-block
                                    mb-1
                                "
                            >
                                Mis grupos
                            </strong>

                            <span class="text-muted">
                                Consulta tus grupos,
                                estudiantes y calificaciones.
                            </span>

                        </div>

                    </a>

                </div>


                {{-- Mi perfil --}}
                <div class="col-12 col-md-6">

                    <a
                        href="{{
                            route(
                                'portal.docente.mi-perfil.index'
                            )
                        }}"
                        class="
                            portal-card
                            d-flex
                            align-items-center
                            gap-3
                            p-4
                            text-decoration-none
                            h-100
                        "
                    >

                        <div class="portal-stat-icon">

                            <i
                                class="
                                    bi
                                    bi-person-circle
                                "
                            ></i>

                        </div>


                        <div>

                            <strong
                                class="
                                    d-block
                                    mb-1
                                "
                            >
                                Mi perfil
                            </strong>

                            <span class="text-muted">
                                Consulta tu información
                                personal, docente y académica.
                            </span>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
        MIS GRUPOS ACTUALES
    ============================================================ --}}

    <section class="portal-card">

        <div class="portal-card-header">

            <div>

                <h2>
                    Mis grupos actuales
                </h2>

                <p>
                    Resumen de los grupos que tienes
                    asignados actualmente.
                </p>

            </div>


            @if ($cantidadGrupos > 0)

                <a
                    href="{{
                        route(
                            'portal.docente.mis-grupos.index'
                        )
                    }}"
                    class="
                        btn
                        portal-btn-secondary
                        btn-sm
                    "
                >
                    Ver todos
                </a>

            @endif

        </div>


        @if ($asignaciones->isEmpty())

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
                    No tienes grupos disponibles
                </h5>

                <p class="text-muted mb-0">
                    Cuando Administración te asigne
                    un grupo académico, aparecerá aquí.
                </p>

            </div>

        @else

            <div class="p-4">

                <div class="row g-3">

                    @foreach (
                        $asignaciones
                            ->take(4)
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

                            $cantidadMatriculados =
                                $grupo
                                    ->matriculas
                                    ->count();

                        @endphp


                        <div
                            class="
                                col-12
                                col-lg-6
                            "
                        >

                            <article
                                class="
                                    border
                                    rounded-3
                                    p-4
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        d-flex
                                        justify-content-between
                                        align-items-start
                                        gap-3
                                        mb-3
                                    "
                                >

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
                                        </span>

                                        <h3 class="mb-1">

                                            {{
                                                $grupo
                                                    ->nombre
                                            }}

                                        </h3>

                                        <small class="text-muted">

                                            {{
                                                $grupo
                                                    ->codigo
                                            }}

                                        </small>

                                    </div>


                                    <span
                                        class="
                                            badge
                                            text-bg-{{
                                                $grupo->estado
                                                === 'activo'
                                                    ? 'success'
                                                    : 'info'
                                            }}
                                        "
                                    >

                                        {{
                                            $grupo->estado
                                            === 'activo'
                                                ? 'Activo'
                                                : 'Planificado'
                                        }}

                                    </span>

                                </div>


                                <div class="mb-3">

                                    <span
                                        class="
                                            text-muted
                                            d-block
                                        "
                                    >
                                        {{
                                            $programa
                                                ->nombre
                                        }}
                                    </span>

                                    <strong>

                                        {{
                                            $nivel
                                                ->nombre
                                        }}

                                    </strong>

                                </div>


                                <div
                                    class="
                                        d-flex
                                        justify-content-between
                                        align-items-center
                                        gap-3
                                        border-top
                                        pt-3
                                    "
                                >

                                    <span class="text-muted">

                                        <i
                                            class="
                                                bi
                                                bi-person
                                                me-1
                                            "
                                        ></i>

                                        {{
                                            $cantidadMatriculados
                                        }}

                                        {{
                                            $cantidadMatriculados
                                            === 1
                                                ? 'estudiante'
                                                : 'estudiantes'
                                        }}

                                    </span>


                                    <a
                                        href="{{
                                            route(
                                                'portal.docente.mis-grupos.show',
                                                $grupo
                                            )
                                        }}"
                                        class="
                                            btn
                                            portal-btn-secondary
                                            btn-sm
                                        "
                                    >
                                        Ver grupo
                                    </a>

                                </div>

                            </article>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </section>

@endsection