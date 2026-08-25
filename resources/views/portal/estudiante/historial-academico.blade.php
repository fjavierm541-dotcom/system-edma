@extends('layouts.portal')

@section(
    'title',
    'Historial académico | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Mi formación
            </span>

            <h1>
                Historial académico
            </h1>

            <p>
                Consulta los niveles cursados
                y tus resultados académicos.
            </p>

        </div>


        @if ($historialPorAnio->isNotEmpty())

            <div class="portal-page-actions edma-no-print">

                <button
                    type="button"
                    class="btn portal-btn-secondary"
                    onclick="window.print()"
                >
                    <i class="bi bi-printer"></i>

                    Imprimir historial
                </button>

            </div>

        @endif

    </div>

@endsection


@section('content')

    @php

        $nivelActual =
            $estudiante
                ->nivelAutorizado;

        $programaActual =
            $nivelActual
                ?->programa;

    @endphp


    {{-- ============================================================
        ENCABEZADO PARA IMPRESIÓN
    ============================================================ --}}

    <section class="edma-history-print-header">

        <div class="edma-history-print-brand">

            <div>

                <span class="edma-history-print-brand-name">
                    EDUMERICAN ACADEMY HONDURAS
                </span>

                <h1>
                    Historial académico
                </h1>

                <p>
                    Registro académico del estudiante
                </p>

            </div>

            <div class="edma-history-print-code">

                <span>
                    Código EDMA
                </span>

                <strong>
                    {{
                        $estudiante
                            ->codigo_estudiante
                    }}
                </strong>

            </div>

        </div>


        <div class="edma-history-print-student">

            <div>

                <span>
                    Estudiante
                </span>

                <strong>
                    {{
                        $persona
                            ->nombre_completo
                    }}
                </strong>

            </div>


            <div>

                <span>
                    Programa
                </span>

                <strong>
                    {{
                        $programaActual
                            ?->nombre
                        ?? 'Por definir'
                    }}
                </strong>

            </div>


            <div>

                <span>
                    Nivel actual
                </span>

                <strong>
                    {{
                        $nivelActual
                            ?->nombre
                        ?? 'Por definir'
                    }}
                </strong>

            </div>

        </div>

    </section>


    {{-- ============================================================
        PERFIL RESUMIDO
    ============================================================ --}}

    <section
        class="
            portal-card
            edma-history-profile
            edma-no-print
            mb-4
        "
    >

        <div class="edma-history-profile-body">

            <div class="edma-history-profile-person">

                <div class="edma-history-avatar">

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
                            alt="{{
                                $persona
                                    ->nombre_completo
                            }}"
                        >

                    @else

                        <div class="edma-history-avatar-placeholder">

                            <i class="bi bi-person"></i>

                        </div>

                    @endif

                </div>


                <div>

                    <span class="edma-history-label">
                        Estudiante
                    </span>

                    <h2>
                        {{
                            $persona
                                ->nombre_completo
                        }}
                    </h2>

                    <strong class="edma-history-code">

                        {{
                            $estudiante
                                ->codigo_estudiante
                        }}

                    </strong>

                </div>

            </div>


            <div class="edma-history-profile-data">

                <div>

                    <span>
                        Programa
                    </span>

                    <strong>
                        {{
                            $programaActual
                                ?->nombre
                            ?? 'Por definir'
                        }}
                    </strong>

                </div>


                <div>

                    <span>
                        Nivel actual
                    </span>

                    <strong>
                        {{
                            $nivelActual
                                ?->nombre
                            ?? 'Por definir'
                        }}
                    </strong>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
        SIN HISTORIAL
    ============================================================ --}}

    @if ($historialPorAnio->isEmpty())

        <section class="portal-card">

            <div class="text-center py-5 px-4">

                <i
                    class="
                        bi
                        bi-journal-text
                        fs-2
                        text-muted
                    "
                ></i>

                <h5 class="mt-3">
                    Aún no hay historial académico
                </h5>

                <p class="text-muted mb-0">
                    Los niveles que curses aparecerán
                    aquí conforme avances en tu formación.
                </p>

            </div>

        </section>

    @else

        {{-- ========================================================
            HISTORIAL EN PANTALLA
        ======================================================== --}}

        <div class="edma-history-screen">

            @foreach (
                $historialPorAnio
                as $anio => $matriculas
            )

                <section class="edma-history-year">

                    <div class="edma-history-year-heading">

                        <span>
                            {{ $anio }}
                        </span>

                        <div></div>

                    </div>


                    <div class="edma-history-list">

                        @foreach (
                            $matriculas
                            as $matricula
                        )

                            @php

                                $grupo =
                                    $matricula
                                        ->grupo;

                                $nivel =
                                    $grupo
                                        ->nivel;

                                $periodo =
                                    $grupo
                                        ->periodoAcademico;

                                $calificacion =
                                    $matricula
                                        ->calificacionFinal;


                                if (
                                    $calificacion
                                    &&
                                    $calificacion->estado
                                    === 'bloqueada'
                                ) {

                                    $observacionAcademica =
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

                                } elseif (
                                    $matricula->estado
                                    === 'activa'
                                    &&
                                    in_array(
                                        $periodo->estado,
                                        [
                                            'matricula_abierta',
                                            'en_curso',
                                        ],
                                        true
                                    )
                                ) {

                                    $observacionAcademica =
                                        'En curso';

                                } elseif (
                                    $matricula->estado
                                    === 'retirada'
                                ) {

                                    $observacionAcademica =
                                        'ABD';

                                } else {

                                    $observacionAcademica =
                                        'Pendiente';
                                }

                            @endphp


                            <article class="portal-card edma-history-card">

                                {{-- ================================
                                    CABECERA
                                ================================= --}}

                                <header class="edma-history-card-header">

                                    <div>

                                        <span class="edma-history-period">

                                            {{
                                                $periodo
                                                    ->nombre
                                            }}

                                        </span>

                                        <div class="edma-history-level">

                                            <span>
                                                Nivel
                                            </span>

                                            <strong>

                                                {{
                                                    $nivel
                                                        ->codigo
                                                }}

                                            </strong>

                                            @if (
                                                $nivel->nombre
                                                !==
                                                $nivel->codigo
                                            )

                                                <span>
                                                    ·
                                                    {{
                                                        $nivel
                                                            ->nombre
                                                    }}
                                                </span>

                                            @endif

                                        </div>

                                    </div>


                                    @if (
                                        $periodo
                                            ->fecha_inicio
                                    )

                                        <span class="edma-history-period-year">

                                            {{
                                                $periodo
                                                    ->fecha_inicio
                                                    ->format('Y')
                                            }}

                                        </span>

                                    @endif

                                </header>


                                {{-- ================================
                                    DATOS
                                ================================= --}}

                                <div class="edma-history-card-data">

                                    <div class="edma-history-data-item">

                                        <span>
                                            Grupo
                                        </span>

                                        <strong>
                                            {{
                                                $grupo
                                                    ->nombre
                                            }}
                                        </strong>

                                        <small>
                                            {{
                                                $grupo
                                                    ->codigo
                                            }}
                                        </small>

                                    </div>


                                    <div class="edma-history-data-item">

                                        <span>
                                            Nota final
                                        </span>

                                        <strong
                                            class="
                                                edma-history-grade
                                            "
                                        >

                                            @if (
                                                $calificacion
                                                &&
                                                $calificacion
                                                    ->estado
                                                === 'bloqueada'
                                                &&
                                                !is_null(
                                                    $calificacion
                                                        ->nota_final
                                                )
                                            )

                                                {{
                                                    number_format(
                                                        (float)
                                                        $calificacion
                                                            ->nota_final,
                                                        2
                                                    )
                                                }}

                                            @else

                                                —

                                            @endif

                                        </strong>

                                    </div>


                                    <div class="edma-history-data-item">

                                        <span>
                                            Observación
                                        </span>

                                        <strong
                                            class="
                                                edma-history-result
                                                edma-history-result-{{
                                                    strtolower(
                                                        str_replace(
                                                            ' ',
                                                            '-',
                                                            $observacionAcademica
                                                        )
                                                    )
                                                }}
                                            "
                                        >

                                            {{
                                                $observacionAcademica
                                            }}

                                        </strong>

                                    </div>

                                </div>


                                {{-- ================================
                                    OBSERVACIÓN FINAL
                                ================================= --}}

                                @if (
                                    $calificacion
                                    &&
                                    $calificacion->estado
                                    === 'bloqueada'
                                    &&
                                    $calificacion
                                        ->observacion_docente
                                )

                                    <div class="edma-history-note">

                                        <span>
                                            Observación final
                                        </span>

                                        <p>
                                            {{
                                                $calificacion
                                                    ->observacion_docente
                                            }}
                                        </p>

                                    </div>

                                @endif

                            </article>

                        @endforeach

                    </div>

                </section>

            @endforeach

        </div>


        {{-- ========================================================
            DOCUMENTO DE IMPRESIÓN
        ======================================================== --}}

        <section class="edma-history-print">

            <div class="edma-history-print-title">

                <h2>
                    Registro de niveles cursados
                </h2>

                <span>
                    Detalle académico
                </span>

            </div>


            <table class="edma-history-print-table">

                <thead>

                    <tr>

                        <th>
                            Año
                        </th>

                        <th>
                            Período académico
                        </th>

                        <th>
                            Nivel
                        </th>

                        <th>
                            Grupo
                        </th>

                        <th class="text-center">
                            Nota final
                        </th>

                        <th class="text-center">
                            Obs.
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach (
                        $historialPorAnio
                        as $anio => $matriculas
                    )

                        @foreach (
                            $matriculas
                            as $matricula
                        )

                            @php

                                $grupo =
                                    $matricula
                                        ->grupo;

                                $nivel =
                                    $grupo
                                        ->nivel;

                                $periodo =
                                    $grupo
                                        ->periodoAcademico;

                                $calificacion =
                                    $matricula
                                        ->calificacionFinal;


                                if (
                                    $calificacion
                                    &&
                                    $calificacion
                                        ->estado
                                    === 'bloqueada'
                                ) {

                                    $observacionImpresion =
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

                                } elseif (
                                    $matricula
                                        ->estado
                                    === 'activa'
                                    &&
                                    in_array(
                                        $periodo->estado,
                                        [
                                            'matricula_abierta',
                                            'en_curso',
                                        ],
                                        true
                                    )
                                ) {

                                    $observacionImpresion =
                                        'En curso';

                                } elseif (
                                    $matricula
                                        ->estado
                                    === 'retirada'
                                ) {

                                    $observacionImpresion =
                                        'ABD';

                                } else {

                                    $observacionImpresion =
                                        'Pendiente';
                                }

                            @endphp


                            <tr>

                                <td>
                                    {{ $anio }}
                                </td>


                                <td>
                                    {{
                                        $periodo
                                            ->nombre
                                    }}
                                </td>


                                <td>

                                    {{
                                        $nivel
                                            ->codigo
                                    }}

                                </td>


                                <td>

                                    {{
                                        $grupo
                                            ->nombre
                                    }}

                                    <small>

                                        {{
                                            $grupo
                                                ->codigo
                                        }}

                                    </small>

                                </td>


                                <td class="text-center">

                                    @if (
                                        $calificacion
                                        &&
                                        $calificacion->estado
                                        === 'bloqueada'
                                        &&
                                        !is_null(
                                            $calificacion
                                                ->nota_final
                                        )
                                    )

                                        {{
                                            number_format(
                                                (float)
                                                $calificacion
                                                    ->nota_final,
                                                2
                                            )
                                        }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td class="text-center">

                                    {{
                                        $observacionImpresion
                                    }}

                                </td>

                            </tr>

                        @endforeach

                    @endforeach

                </tbody>

            </table>


            <div class="edma-history-print-legend">

                <span>
                    <strong>APR</strong>
                    Aprobado
                </span>

                <span>
                    <strong>REP</strong>
                    Reprobado
                </span>

                <span>
                    <strong>NSP</strong>
                    No se presentó
                </span>

                <span>
                    <strong>ABD</strong>
                    Abandono
                </span>

            </div>


            <div class="edma-history-print-note">

                <p>
                    Este documento presenta el historial
                    académico registrado en el Sistema de
                    Gestión Académica EDMA.
                </p>

            </div>


            <footer class="edma-history-print-footer">

                <div>

                    <strong>
                        Edumerican Academy Honduras
                    </strong>

                    <span>
                        Sistema de Gestión Académica EDMA
                    </span>

                </div>


                <div>

                    <span>
                        Fecha de emisión
                    </span>

                    <strong>

                        {{
                            now()
                                ->format(
                                    'd/m/Y'
                                )
                        }}

                    </strong>

                </div>

            </footer>

        </section>

    @endif

@endsection