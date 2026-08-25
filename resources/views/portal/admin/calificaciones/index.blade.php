@extends('layouts.portal')

@section(
    'title',
    'Calificaciones | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>
                Calificaciones
            </h1>

            <p>
                Selecciona un período académico
                para consultar sus grupos y
                calificaciones finales.
            </p>

        </div>

    </div>

@endsection


@section('content')

    <section class="portal-card">

        <div class="portal-card-header">

            <div>

                <h2>
                    Períodos académicos
                </h2>

                <p>
                    Consulta las calificaciones
                    organizadas por período.
                </p>

            </div>

        </div>


        @if ($periodos->isEmpty())

            <div class="text-center py-5 px-4">

                <i
                    class="
                        bi
                        bi-calendar-x
                        fs-2
                        text-muted
                    "
                ></i>

                <h5 class="mt-3">
                    No hay períodos registrados
                </h5>

            </div>

        @else

            <div class="p-4">

                <div class="row g-3">

                    @foreach (
                        $periodos
                        as $periodo
                    )

                        @php

                            $estadoTexto =
                                match (
                                    $periodo->estado
                                ) {
                                    'planificado' =>
                                        'Planificado',

                                    'matricula_abierta' =>
                                        'Matrícula abierta',

                                    'en_curso' =>
                                        'En curso',

                                    'finalizado' =>
                                        'Finalizado',

                                    'cancelado' =>
                                        'Cancelado',

                                    default =>
                                        str(
                                            $periodo->estado
                                        )
                                        ->replace(
                                            '_',
                                            ' '
                                        )
                                        ->title(),
                                };

                        @endphp


                        <div
                            class="
                                col-12
                                col-md-6
                                col-xl-4
                            "
                        >

                            <article class="portal-card h-100">

                                <div class="p-4">

                                    <span
                                        class="
                                            text-muted
                                            d-block
                                            mb-1
                                        "
                                    >
                                        {{
                                            $periodo
                                                ->codigo
                                        }}
                                    </span>

                                    <h3 class="mb-2">

                                        {{
                                            $periodo
                                                ->nombre
                                        }}

                                    </h3>

                                    <div
                                        class="
                                            text-muted
                                            mb-3
                                        "
                                    >

                                        @if (
                                            $periodo
                                                ->fecha_inicio
                                            &&
                                            $periodo
                                                ->fecha_fin
                                        )

                                            {{
                                                $periodo
                                                    ->fecha_inicio
                                                    ->format(
                                                        'd/m/Y'
                                                    )
                                            }}

                                            —

                                            {{
                                                $periodo
                                                    ->fecha_fin
                                                    ->format(
                                                        'd/m/Y'
                                                    )
                                            }}

                                        @endif

                                    </div>


                                    <div class="mb-4">

                                        <span
                                            class="
                                                badge
                                                text-bg-light
                                                border
                                                text-dark
                                            "
                                        >
                                            {{ $estadoTexto }}
                                        </span>

                                    </div>


                                    <a
                                        href="{{
                                            route(
                                                'portal.admin.calificaciones.grupos',
                                                $periodo
                                            )
                                        }}"
                                        class="
                                            btn
                                            portal-btn-primary
                                            w-100
                                        "
                                    >
                                        Ver grupos
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