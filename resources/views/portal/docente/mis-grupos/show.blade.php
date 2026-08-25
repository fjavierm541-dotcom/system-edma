@extends('layouts.portal')

@section(
    'title',
    'Detalle del grupo | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Mis grupos
            </span>

            <h1>
                {{ $grupo->nombre }}
            </h1>

            <p>
                Consulta la información académica
                y los estudiantes matriculados
                en este grupo.
            </p>

        </div>


        <div class="portal-page-actions">

            <a
                href="{{
                    route(
                        'portal.docente.mis-grupos.index'
                    )
                }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left me-2"></i>

                Volver a Mis grupos
            </a>

        </div>

    </div>

@endsection


@section('content')

    @php

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


        $estadoTexto =
            match ($grupo->estado) {
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
            match ($grupo->estado) {
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
                    'Titular',

                'suplente' =>
                    'Suplente',

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


    {{-- ============================================================
        RESUMEN DEL GRUPO
    ============================================================ --}}

    <section class="portal-card mb-4">

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
                    {{ $grupo->nombre }}
                </h2>

                <p>
                    {{ $grupo->codigo }}
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
                    {{ $tipoAsignacion }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>
                    Modalidad
                </span>

                <strong>

                    {{
                        $grupo
                            ->modalidad
                            ? str(
                                $grupo->modalidad
                            )
                                ->replace(
                                    '_',
                                    ' '
                                )
                                ->title()
                            : 'No definida'
                    }}

                </strong>

            </div>

        </div>


        {{-- Horarios --}}
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

    </section>



    {{-- ============================================================
    CARGA DE CALIFICACIONES
============================================================ --}}

@php

    $estadoCargaTexto =
        match (
            $estadoCargaCalificaciones
        ) {
            'abierta' =>
                'Carga habilitada',

            'programada' =>
                'Carga programada',

            'cerrada' =>
                'Carga finalizada',

            default =>
                'Carga no configurada',
        };


    $estadoCargaClase =
        match (
            $estadoCargaCalificaciones
        ) {
            'abierta' =>
                'success',

            'programada' =>
                'info',

            'cerrada' =>
                'secondary',

            default =>
                'warning',
        };

@endphp


<section class="portal-card mb-4">

    <div class="portal-card-header">

        <div>

            <h2>
                Calificaciones finales
            </h2>

            <p>
                Consulta el período habilitado
                para registrar las calificaciones
                finales del grupo.
            </p>

        </div>


        <span
            class="
                badge
                text-bg-{{
                    $estadoCargaClase
                }}
            "
        >
            {{ $estadoCargaTexto }}
        </span>

    </div>


    <div class="p-4">

        {{-- ========================================================
            FECHAS Y ESTADO
        ======================================================== --}}

        <div class="row g-4">

            <div class="col-12 col-md-4">

                <span
                    class="
                        text-muted
                        d-block
                        mb-1
                    "
                >
                    Apertura
                </span>

                <strong>

                    @if ($calificacionesDesde)

                        {{
                            $calificacionesDesde
                                ->format(
                                    'd/m/Y H:i'
                                )
                        }}

                    @else

                        No definida

                    @endif

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
                    Cierre
                </span>

                <strong>

                    @if ($calificacionesHasta)

                        {{
                            $calificacionesHasta
                                ->format(
                                    'd/m/Y H:i'
                                )
                        }}

                    @else

                        No definido

                    @endif

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
                    Estado
                </span>

                <strong>
                    {{ $estadoCargaTexto }}
                </strong>

            </div>

        </div>


        {{-- ========================================================
            MENSAJE SEGÚN ESTADO
        ======================================================== --}}

        <div class="border-top mt-4 pt-3">

            @if (
                $estadoCargaCalificaciones
                === 'abierta'
            )

                <div
                    class="
                        d-flex
                        align-items-center
                        gap-2
                        text-success
                    "
                >

                    <i class="bi bi-check-circle"></i>

                    <span>
                        Puedes registrar y actualizar
                        las calificaciones finales
                        durante este período.
                    </span>

                </div>

            @elseif (
                $estadoCargaCalificaciones
                === 'programada'
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
                        La carga de calificaciones
                        todavía no se encuentra
                        habilitada.
                    </span>

                </div>

            @elseif (
                $estadoCargaCalificaciones
                === 'cerrada'
            )

                <div
                    class="
                        d-flex
                        align-items-center
                        gap-2
                        text-muted
                    "
                >

                    <i class="bi bi-lock"></i>

                    <span>
                        El período ordinario para
                        registrar calificaciones
                        ya finalizó.
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
                        Administración aún no ha
                        establecido las fechas para
                        la carga de calificaciones.
                    </span>

                </div>

            @endif

        </div>


        {{-- ========================================================
            ACCIÓN
        ======================================================== --}}

        @if ($puedeCargarCalificaciones)

            <div
                class="
                    border-top
                    mt-4
                    pt-3
                    d-flex
                    justify-content-end
                "
            >

                <a
                    href="{{
                        route(
                            'portal.docente.calificaciones.edit',
                            $grupo
                        )
                    }}"
                    class="
                        btn
                        portal-btn-primary
                    "
                >
                    <i
                        class="
                            bi
                            bi-pencil-square
                            me-2
                        "
                    ></i>

                    Registrar calificaciones
                </a>

            </div>

        @endif

    </div>

</section>


    {{-- ============================================================
        INDICADORES
    ============================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-12 col-md-4">

            <section class="portal-card h-100">

                <div class="p-4">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Estudiantes matriculados
                    </span>

                    <strong class="fs-3">
                        {{ $cantidadEstudiantes }}
                    </strong>

                </div>

            </section>

        </div>


        <div class="col-12 col-md-4">

            <section class="portal-card h-100">

                <div class="p-4">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Calificaciones registradas
                    </span>

                    <strong class="fs-3">
                        {{ $cantidadCalificados }}
                    </strong>

                </div>

            </section>

        </div>


        <div class="col-12 col-md-4">

            <section class="portal-card h-100">

                <div class="p-4">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Pendientes de calificar
                    </span>

                    <strong class="fs-3">
                        {{ $cantidadPendientes }}
                    </strong>

                </div>

            </section>

        </div>

    </div>

{{-- ============================================================
    ESTUDIANTES
============================================================ --}}

<section class="portal-card">

    <div class="portal-card-header">

        <div>

            <h2>
                Estudiantes matriculados
            </h2>

            <p>
                Lista de estudiantes que
                pertenecen actualmente a este grupo.
            </p>

        </div>

    </div>


    @if ($grupo->matriculas->isEmpty())

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
                No hay estudiantes matriculados
            </h5>

            <p class="text-muted mb-0">
                Cuando existan matrículas activas,
                aparecerán en este listado.
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
                            Código EDMA
                        </th>

                        <th>
                            Estudiante
                        </th>

                        <th>
                            Matrícula
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Resultado
                        </th>

                        <th class="text-end">
                            Nota final
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach (
                        $grupo->matriculas
                        as $matricula
                    )

                        @php

                            $estudiante =
                                $matricula
                                    ->estudiante;

                            $persona =
                                $estudiante
                                    ->persona;

                            $calificacion =
                                $matricula
                                    ->calificacionFinal;


                            /*
                            |--------------------------------------------------------------------------
                            | Estado de la calificación
                            |--------------------------------------------------------------------------
                            */

                            if (!$calificacion) {

                                $estadoCalificacion =
                                    'Sin calificación';

                                $estadoCalificacionClase =
                                    'secondary';

                            } else {

                                $estadoCalificacion =
                                    match (
                                        $calificacion
                                            ->estado
                                    ) {
                                        'borrador' =>
                                            'Borrador',

                                        'confirmada' =>
                                            'Confirmada',

                                        'bloqueada' =>
                                            'Bloqueada',

                                        default =>
                                            str(
                                                $calificacion
                                                    ->estado
                                            )
                                            ->replace(
                                                '_',
                                                ' '
                                            )
                                            ->title(),
                                    };


                                $estadoCalificacionClase =
                                    match (
                                        $calificacion
                                            ->estado
                                    ) {
                                        'borrador' =>
                                            'warning',

                                        'confirmada' =>
                                            'info',

                                        'bloqueada' =>
                                            'success',

                                        default =>
                                            'secondary',
                                    };
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Resultado académico
                            |--------------------------------------------------------------------------
                            */

                            $resultadoAcademico =
                                match (
                                    $calificacion
                                        ?->resultado
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


                            $resultadoClase =
                                match (
                                    $calificacion
                                        ?->resultado
                                ) {
                                    'aprobado' =>
                                        'success',

                                    'reprobado' =>
                                        'danger',

                                    'incompleto' =>
                                        'secondary',

                                    'retirado' =>
                                        'secondary',

                                    default =>
                                        'secondary',
                                };

                        @endphp


                        <tr>

                            {{-- Código EDMA --}}
                            <td>

                                <strong>
                                    {{
                                        $estudiante
                                            ->codigo_estudiante
                                    }}
                                </strong>

                            </td>


                            {{-- Estudiante --}}
                            <td>

                                {{
                                    $persona
                                        ->nombre_completo
                                }}

                            </td>


                            {{-- Matrícula --}}
                            <td>

                                {{
                                    $matricula
                                        ->codigo_matricula
                                }}

                            </td>


                            {{-- Estado de la calificación --}}
                            <td>

                                <span
                                    class="
                                        badge
                                        text-bg-{{
                                            $estadoCalificacionClase
                                        }}
                                    "
                                >
                                    {{
                                        $estadoCalificacion
                                    }}
                                </span>

                            </td>


                            {{-- Resultado académico --}}
                            <td>

                                @if ($calificacion)

                                    <span
                                        class="
                                            badge
                                            text-bg-{{
                                                $resultadoClase
                                            }}
                                        "
                                    >
                                        {{
                                            $resultadoAcademico
                                        }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Nota final --}}
                            <td
                                class="
                                    text-end
                                    text-nowrap
                                "
                            >

                                @if (
                                    $calificacion
                                    &&
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

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</section>

@endsection
