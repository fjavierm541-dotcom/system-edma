@extends('layouts.portal')

@section(
    'title',
    'Inicio | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Mi Portal EDMA
            </span>

            <h1>
                Hola, {{ $persona->nombres }}
            </h1>

            <p>
                Accede rápidamente a tus servicios
                académicos y administrativos.
            </p>

        </div>


        <div class="portal-page-actions">

            <span class="portal-current-date">

                <i class="bi bi-calendar3"></i>

                {{
                    now()->translatedFormat(
                        'd \d\e F \d\e Y'
                    )
                }}

            </span>

        </div>

    </div>

@endsection


@section('content')

    {{-- ============================================================
        AVISOS TEMPORALES DE INICIO
    ============================================================ --}}

    @if (
        $mostrarAvisos
        &&
        (
            $pagosPendientesRevision > 0
            ||
            $pagosRechazados > 0
        )
    )

        <div
            id="avisosInicioEstudiante"
            class="mb-4"
        >

            @if ($pagosRechazados > 0)

                <div
                    class="
                        alert
                        alert-warning
                        portal-alert
                        mb-2
                    "
                >

                    <i
                        class="
                            bi
                            bi-exclamation-circle-fill
                        "
                    ></i>

                    <div>

                        <strong>
                            Hay un pago que requiere tu atención
                        </strong>

                        <span>
                            Consulta la sección Pagos para
                            conocer más información.
                        </span>

                    </div>


                    <a
                        href="{{
                            route(
                                'portal.pagos.index'
                            )
                        }}"
                        class="
                            btn
                            portal-btn-secondary
                            btn-sm
                            ms-auto
                        "
                    >
                        Revisar pagos
                    </a>

                </div>

            @endif


            @if ($pagosPendientesRevision > 0)

                <div
                    class="
                        alert
                        alert-info
                        portal-alert
                        mb-0
                    "
                >

                    <i
                        class="
                            bi
                            bi-hourglass-split
                        "
                    ></i>

                    <div>

                        <strong>
                            Pago en revisión
                        </strong>

                        <span>

                            Tienes

                            {{
                                $pagosPendientesRevision
                            }}

                            pago{{
                                $pagosPendientesRevision
                                !== 1
                                    ? 's'
                                    : ''
                            }}

                            pendiente{{
                                $pagosPendientesRevision
                                !== 1
                                    ? 's'
                                    : ''
                            }}

                            de revisión administrativa.

                        </span>

                    </div>

                </div>

            @endif

        </div>

    @endif


    {{-- ============================================================
        IDENTIFICACIÓN DEL ESTUDIANTE
    ============================================================ --}}

    <section class="portal-card mb-4">

        <div class="p-4">

            <div
                class="
                    d-flex
                    align-items-center
                    gap-3
                    flex-wrap
                "
            >

                {{-- Fotografía --}}
                <div>

                    @if ($persona->foto_perfil)

                        <img
                            src="{{
                                asset(
                                    'storage/'
                                    . $persona
                                        ->foto_perfil
                                )
                            }}"
                            alt="{{
                                $persona
                                    ->nombre_completo
                            }}"
                            class="
                                rounded-circle
                                border
                            "
                            style="
                                width: 76px;
                                height: 76px;
                                object-fit: cover;
                            "
                        >

                    @else

                        <div
                            class="
                                portal-stat-icon
                                rounded-circle
                            "
                            style="
                                width: 76px;
                                height: 76px;
                            "
                        >

                            <i
                                class="
                                    bi
                                    bi-person
                                    fs-3
                                "
                            ></i>

                        </div>

                    @endif

                </div>


                {{-- Datos --}}
                <div class="flex-grow-1">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Estudiante
                    </span>

                    <h3 class="mb-1">

                        {{
                            $persona
                                ->nombre_completo
                        }}

                    </h3>

                    <div class="text-muted">

                        {{
                            $estudiante
                                ->codigo_estudiante
                        }}

                    </div>

                </div>


                {{-- Programa --}}
                @if (
                    $estudiante
                        ->nivelAutorizado
                        ?->programa
                )

                    <div class="text-md-end">

                        <span
                            class="
                                text-muted
                                d-block
                                mb-1
                            "
                        >
                            Programa académico
                        </span>

                        <strong>

                            {{
                                $estudiante
                                    ->nivelAutorizado
                                    ->programa
                                    ->nombre
                            }}

                        </strong>

                    </div>

                @endif

            </div>

        </div>

    </section>


    {{-- ============================================================
        ACCESOS RÁPIDOS
    ============================================================ --}}

    <section class="portal-card">

        <div class="portal-card-header">

            <div>

                <h2>
                    Accesos rápidos
                </h2>

                <p>
                    Selecciona la opción que deseas consultar.
                </p>

            </div>

        </div>


        <div class="portal-quick-actions">

            {{-- Mi matrícula --}}
            <a
                href="{{
                    route(
                        'portal.mi-matricula.index'
                    )
                }}"
                class="portal-quick-action"
            >

                <span>
                    <i class="bi bi-journal-check"></i>
                </span>

                <div>

                    <strong>
                        Mi matrícula
                    </strong>

                    <small>
                        Consulta tu grupo,
                        nivel y horario
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>


            {{-- Comprobante --}}
            <a
                href="{{
                    route(
                        'portal.comprobante-matricula.index'
                    )
                }}"
                class="portal-quick-action"
            >

                <span>
                    <i
                        class="
                            bi
                            bi-file-earmark-check
                        "
                    ></i>
                </span>

                <div>

                    <strong>
                        Comprobante de matrícula
                    </strong>

                    <small>
                        Consulta e imprime
                        tu comprobante vigente
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>


            {{-- Pagos --}}
            <a
                href="{{
                    route(
                        'portal.pagos.index'
                    )
                }}"
                class="portal-quick-action"
            >

                <span>
                    <i class="bi bi-receipt"></i>
                </span>

                <div>

                    <strong>
                        Pagos
                    </strong>

                    <small>
                        Registra mensualidades
                        y consulta su estado
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>


            {{-- Estado de cuenta --}}
            <a
                href="{{
                    route(
                        'portal.estado-cuenta.index'
                    )
                }}"
                class="portal-quick-action"
            >

                <span>
                    <i class="bi bi-wallet2"></i>
                </span>

                <div>

                    <strong>
                        Estado de cuenta
                    </strong>

                    <small>
                        Consulta tus mensualidades
                        y saldos
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>


            {{-- Historial académico --}}
            @if (
                Route::has(
                    'portal.historial-academico.index'
                )
            )

                <a
                    href="{{
                        route(
                            'portal.historial-academico.index'
                        )
                    }}"
                    class="portal-quick-action"
                >

                    <span>
                        <i class="bi bi-journal-text"></i>
                    </span>

                    <div>

                        <strong>
                            Historial académico
                        </strong>

                        <small>
                            Consulta tus niveles
                            cursados y resultados
                        </small>

                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>

            @endif


            {{-- Mi perfil --}}
            @if (
                Route::has(
                    'portal.mi-perfil.index'
                )
            )

                <a
                    href="{{
                        route(
                            'portal.mi-perfil.index'
                        )
                    }}"
                    class="portal-quick-action"
                >

                    <span>
                        <i class="bi bi-person-circle"></i>
                    </span>

                    <div>

                        <strong>
                            Mi perfil
                        </strong>

                        <small>
                            Consulta tu información personal
                        </small>

                    </div>

                    <i class="bi bi-chevron-right"></i>

                </a>

            @endif

        </div>

    </section>

@endsection


@push('scripts')

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {
        const avisos =
            document.getElementById(
                'avisosInicioEstudiante'
            );

        if (!avisos) {
            return;
        }

        /*
         * El aviso permanece unos segundos
         * y desaparece suavemente.
         */
        window.setTimeout(
            function () {
                avisos.style.transition =
                    'opacity .45s ease';

                avisos.style.opacity = '0';

                window.setTimeout(
                    function () {
                        avisos.remove();
                    },
                    450
                );
            },
            5500
        );
    }
);
</script>

@endpush