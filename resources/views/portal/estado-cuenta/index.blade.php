@extends('layouts.portal')

@section(
    'title',
    'Estado de cuenta | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Gestión financiera
            </span>

            <h1>
                Estado de cuenta
            </h1>

            <p>
                Consulta tus mensualidades,
                pagos aprobados y saldos pendientes.
            </p>

        </div>


        @if ($matriculaSeleccionada)

            <button
                type="button"
                class="
                    btn
                    portal-btn-secondary
                    edma-no-print
                "
                onclick="window.print()"
            >
                <i class="bi bi-printer me-2"></i>
                Imprimir
            </button>

        @endif

    </div>

@endsection


@section('content')

<div class="edma-account-page">

    @if (!$matriculaSeleccionada)

        <section class="portal-card">

            <div class="text-center py-5 px-4">

                <i
                    class="
                        bi
                        bi-wallet2
                        fs-2
                        text-muted
                    "
                ></i>

                <h5 class="mt-3">
                    Sin estados de cuenta
                </h5>

                <p class="text-muted mb-0">
                    Aún no tienes matrículas registradas
                    para consultar información financiera.
                </p>

            </div>

        </section>

    @else

        @php

            $grupo =
                $matriculaSeleccionada
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

        @endphp


        {{-- ========================================================
            CABECERA EXCLUSIVA DE IMPRESIÓN
        ======================================================== --}}

        <div class="estado-cuenta-print-only">

            <div class="estado-cuenta-print-header">

                <div>

                    <strong>
                        EDUMERICAN ACADEMY HONDURAS
                    </strong>

                    <span>
                        Estado de cuenta académico
                    </span>

                </div>


                <div class="text-end">

                    <strong>
                        {{
                            $estudiante
                                ->persona
                                ->nombre_completo
                        }}
                    </strong>

                    <span>
                        {{
                            $estudiante
                                ->codigo_estudiante
                        }}
                    </span>

                </div>

            </div>


            <div class="estado-cuenta-print-periodo">

                <div>

                    <span>
                        Período académico
                    </span>

                    <strong>
                        {{ $periodo->nombre }}

                        @if ($periodo->fecha_inicio)
                            ·
                            {{
                                $periodo
                                    ->fecha_inicio
                                    ->format('Y')
                            }}
                        @endif
                    </strong>

                </div>


                <div>

                    <span>
                        Nivel
                    </span>

                    <strong>
                        {{ $nivel->nombre }}
                    </strong>

                </div>


                <div>

                    <span>
                        Programa
                    </span>

                    <strong>
                        {{ $programa->nombre }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- ========================================================
            SELECTORES
        ======================================================== --}}

        <section
            class="
                portal-card
                mb-4
                edma-no-print
            "
        >

            <div class="p-4">

                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-4">

                        <label
                            for="anioCuenta"
                            class="form-label"
                        >
                            Año
                        </label>

                        <select
                            id="anioCuenta"
                            class="form-select"
                            onchange="
                                window.location.href =
                                this.value
                            "
                        >

                            <option
                                value="{{
                                    route(
                                        'portal.estado-cuenta.index'
                                    )
                                }}"
                            >
                                Todos los años
                            </option>


                            @foreach (
                                $aniosDisponibles
                                as $anio
                            )

                                <option
                                    value="{{
                                        route(
                                            'portal.estado-cuenta.index',
                                            [
                                                'anio' => $anio
                                            ]
                                        )
                                    }}"
                                    @selected(
                                        $anioSeleccionado
                                        ===
                                        (string) $anio
                                    )
                                >
                                    {{ $anio }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-12 col-md-8">

                        <label
                            for="cuentaSeleccionada"
                            class="form-label"
                        >
                            Período y nivel
                        </label>

                        <select
                            id="cuentaSeleccionada"
                            class="form-select"
                            onchange="
                                window.location.href =
                                this.value
                            "
                        >

                            @foreach (
                                $estadosCuenta
                                as $estadoCuenta
                            )

                                @php

                                    $cuenta =
                                        $estadoCuenta[
                                            'matricula'
                                        ];

                                    $periodoCuenta =
                                        $cuenta
                                            ->grupo
                                            ->periodoAcademico;

                                    $nivelCuenta =
                                        $cuenta
                                            ->grupo
                                            ->nivel;

                                @endphp


                                <option
                                    value="{{
                                        route(
                                            'portal.estado-cuenta.index',
                                            [
                                                'matricula' =>
                                                    $cuenta->id,

                                                'anio' =>
                                                    $anioSeleccionado,
                                            ]
                                        )
                                    }}"
                                    @selected(
                                        $cuenta->id
                                        ===
                                        $matriculaSeleccionada
                                            ->id
                                    )
                                >

                                    {{
                                        $periodoCuenta
                                            ->nombre
                                    }}

                                    ·

                                    {{
                                        $nivelCuenta
                                            ->nombre
                                    }}

                                    @if (
                                        $periodoCuenta
                                            ->fecha_inicio
                                    )

                                        ·
                                        {{
                                            $periodoCuenta
                                                ->fecha_inicio
                                                ->format('Y')
                                        }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </section>


        {{-- ========================================================
            CUENTA SELECCIONADA
        ======================================================== --}}

        <section class="portal-card mb-4">

            <div class="portal-card-header">

                <div>

                    <span class="portal-page-eyebrow">
                        Estado de cuenta
                    </span>

                    <h2>
                        {{ $periodo->nombre }}
                    </h2>

                    <p class="mb-0">

                        {{ $programa->nombre }}

                        ·

                        {{ $nivel->nombre }}

                        @if ($periodo->fecha_inicio)

                            ·

                            {{
                                $periodo
                                    ->fecha_inicio
                                    ->format('Y')
                            }}

                        @endif

                    </p>

                </div>


                @if (
                    $matriculaSeleccionada
                        ->estado
                    === 'activa'
                )

                    <span class="badge text-bg-success">
                        Cuenta actual
                    </span>

                @else

                    <span class="badge text-bg-secondary">
                        Histórico
                    </span>

                @endif

            </div>

        </section>


        {{-- ========================================================
            RESUMEN
        ======================================================== --}}

        <div class="row g-4 mb-4 estado-cuenta-resumen">

            <div class="col-12 col-md-6">

                <section
                    class="
                        portal-card
                        estado-cuenta-resumen-card
                        h-100
                    "
                >

                    <div class="p-4">

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-3
                            "
                        >

                            <div
                                class="
                                    estado-cuenta-resumen-icon
                                "
                            >
                                <i class="bi bi-check-circle"></i>
                            </div>


                            <div>

                                <span
                                    class="
                                        text-muted
                                        d-block
                                    "
                                >
                                    Total pagado
                                </span>

                                <strong
                                    class="
                                        estado-cuenta-resumen-valor
                                        text-success
                                    "
                                >
                                    L
                                    {{
                                        number_format(
                                            $totalPagado,
                                            2
                                        )
                                    }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </section>

            </div>


            <div class="col-12 col-md-6">

                <section
                    class="
                        portal-card
                        estado-cuenta-resumen-card
                        h-100
                    "
                >

                    <div class="p-4">

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-3
                            "
                        >

                            <div
                                class="
                                    estado-cuenta-resumen-icon
                                "
                            >
                                <i class="bi bi-wallet2"></i>
                            </div>


                            <div>

                                <span
                                    class="
                                        text-muted
                                        d-block
                                    "
                                >
                                    Saldo pendiente
                                </span>

                                <strong
                                    class="
                                        estado-cuenta-resumen-valor
                                    "
                                >
                                    L
                                    {{
                                        number_format(
                                            $saldoPendiente,
                                            2
                                        )
                                    }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

        </div>


        {{-- ========================================================
            EN REVISIÓN
        ======================================================== --}}

        @if (
            $pagosEnRevision
                ->isNotEmpty()
        )

            <div
                class="
                    alert
                    alert-info
                    mb-4
                    edma-no-print
                "
            >

                <div class="d-flex gap-2">

                    <i
                        class="
                            bi
                            bi-hourglass-split
                            mt-1
                        "
                    ></i>

                    <div>

                        <strong class="d-block">
                            Pago en revisión
                        </strong>

                        <span>
                            Tienes
                            {{
                                $pagosEnRevision
                                    ->count()
                            }}
                            pago{{
                                $pagosEnRevision
                                    ->count() !== 1
                                    ? 's'
                                    : ''
                            }}
                            pendiente{{
                                $pagosEnRevision
                                    ->count() !== 1
                                    ? 's'
                                    : ''
                            }}
                            de revisión.

                            El saldo se actualizará
                            cuando Administración
                            confirme el comprobante.
                        </span>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================
            RECHAZADOS
        ======================================================== --}}

        @if ($tienePagoRechazado)

            <div
                class="
                    alert
                    alert-warning
                    mb-4
                    edma-no-print
                "
            >

                <div class="d-flex gap-2">

                    <i
                        class="
                            bi
                            bi-exclamation-circle
                            mt-1
                        "
                    ></i>

                    <div>

                        <strong class="d-block">
                            Hay un pago que requiere tu atención
                        </strong>

                        <span>
                            Consulta la sección Pagos para
                            revisar el motivo indicado por
                            Administración.
                        </span>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================
            MENSUALIDADES
        ======================================================== --}}

        <section class="portal-card mb-4">

            <div class="portal-card-header">

                <div>

                    <h2>
                        Mensualidades
                    </h2>

                    <p>
                        Consulta lo pagado y el saldo
                        pendiente de cada mensualidad.
                    </p>

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
                        edma-no-print
                    "
                >
                    <i class="bi bi-receipt me-2"></i>
                    Ir a Pagos
                </a>

            </div>


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
                                Mensualidad
                            </th>

                            <th>
                                Vencimiento
                            </th>

                            <th class="text-end">
                                Monto
                            </th>

                            <th class="text-end">
                                Pagado
                            </th>

                            <th class="text-end">
                                Saldo
                            </th>

                            <th>
                                Estado
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach (
                            $cuotas
                            as $cuota
                        )

                            @php

                                $numero =
                                    (int)
                                    $cuota
                                        ->numero_cuota;

                                $nombreMensualidad =
                                    match ($numero) {
                                        1 =>
                                            'Primera mensualidad',

                                        2 =>
                                            'Segunda mensualidad',

                                        3 =>
                                            'Tercera mensualidad',

                                        default =>
                                            "Mensualidad {$numero}",
                                    };


                                $estadoTexto =
                                    match (
                                        $cuota
                                            ->estado_visible
                                    ) {
                                        'pagada' =>
                                            'Pagada',

                                        'parcial' =>
                                            'Pago parcial',

                                        'vencida' =>
                                            'Pago pendiente',

                                        default =>
                                            'Pendiente',
                                    };


                                $estadoClase =
                                    match (
                                        $cuota
                                            ->estado_visible
                                    ) {
                                        'pagada' =>
                                            'success',

                                        'parcial' =>
                                            'info',

                                        'vencida' =>
                                            'warning',

                                        default =>
                                            'secondary',
                                    };

                            @endphp


                            <tr>

                                <td>

                                    <strong>
                                        {{ $nombreMensualidad }}
                                    </strong>

                                </td>


                                <td class="text-nowrap">

                                    {{
                                        $cuota
                                            ->fecha_vencimiento
                                            ?->format(
                                                'd/m/Y'
                                            )
                                        ?? '—'
                                    }}

                                </td>


                                <td
                                    class="
                                        text-end
                                        text-nowrap
                                    "
                                >

                                    L
                                    {{
                                        number_format(
                                            (float)
                                            $cuota->monto,
                                            2
                                        )
                                    }}

                                </td>


                                <td
                                    class="
                                        text-end
                                        text-nowrap
                                    "
                                >

                                    L
                                    {{
                                        number_format(
                                            (float)
                                            $cuota
                                                ->monto_pagado_calculado,
                                            2
                                        )
                                    }}

                                </td>


                                <td
                                    class="
                                        text-end
                                        text-nowrap
                                    "
                                >

                                    <strong>

                                        L
                                        {{
                                            number_format(
                                                (float)
                                                $cuota
                                                    ->saldo_calculado,
                                                2
                                            )
                                        }}

                                    </strong>

                                </td>


                                <td>

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

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </section>


        {{-- ========================================================
            CUENTAS ANTERIORES
        ======================================================== --}}

        @if (
            $estadosCuenta
                ->count()
            > 1
        )

            <section
                class="
                    portal-card
                    edma-no-print
                "
            >

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Estados de cuenta anteriores
                        </h2>

                        <p>
                            Consulta otros períodos
                            académicos registrados.
                        </p>

                    </div>

                </div>


                <div
                    class="
                        list-group
                        list-group-flush
                    "
                >

                    @foreach (
                        $estadosCuenta
                        as $estadoCuenta
                    )

                        @php

                            $cuenta =
                                $estadoCuenta[
                                    'matricula'
                                ];

                            $periodoCuenta =
                                $cuenta
                                    ->grupo
                                    ->periodoAcademico;

                            $nivelCuenta =
                                $cuenta
                                    ->grupo
                                    ->nivel;

                        @endphp


                        @if (
                            $cuenta->id
                            !==
                            $matriculaSeleccionada
                                ->id
                        )

                            <div
                                class="
                                    list-group-item
                                    px-4
                                    py-3
                                "
                            >

                                <div
                                    class="
                                        d-flex
                                        justify-content-between
                                        align-items-center
                                        gap-3
                                        flex-wrap
                                    "
                                >

                                    <div>

                                        <strong
                                            class="
                                                d-block
                                                mb-1
                                            "
                                        >
                                            {{
                                                $periodoCuenta
                                                    ->nombre
                                            }}
                                        </strong>

                                        <span
                                            class="
                                                text-muted
                                                small
                                            "
                                        >

                                            {{
                                                $nivelCuenta
                                                    ->nombre
                                            }}

                                            @if (
                                                $periodoCuenta
                                                    ->fecha_inicio
                                            )

                                                ·

                                                {{
                                                    $periodoCuenta
                                                        ->fecha_inicio
                                                        ->format(
                                                            'Y'
                                                        )
                                                }}

                                            @endif

                                        </span>

                                    </div>


                                    <div
                                        class="
                                            d-flex
                                            align-items-center
                                            gap-4
                                            flex-wrap
                                        "
                                    >

                                        <div>

                                            <small
                                                class="
                                                    text-muted
                                                    d-block
                                                "
                                            >
                                                Pagado
                                            </small>

                                            <strong
                                                class="
                                                    text-success
                                                "
                                            >

                                                L
                                                {{
                                                    number_format(
                                                        $estadoCuenta[
                                                            'pagado'
                                                        ],
                                                        2
                                                    )
                                                }}

                                            </strong>

                                        </div>


                                        <div>

                                            <small
                                                class="
                                                    text-muted
                                                    d-block
                                                "
                                            >
                                                Saldo
                                            </small>

                                            <strong>

                                                L
                                                {{
                                                    number_format(
                                                        $estadoCuenta[
                                                            'saldo'
                                                        ],
                                                        2
                                                    )
                                                }}

                                            </strong>

                                        </div>


                                        <a
                                            href="{{
                                                route(
                                                    'portal.estado-cuenta.index',
                                                    [
                                                        'matricula' =>
                                                            $cuenta
                                                                ->id,

                                                        'anio' =>
                                                            $anioSeleccionado,
                                                    ]
                                                )
                                            }}"
                                            class="
                                                btn
                                                portal-btn-secondary
                                                btn-sm
                                            "
                                        >
                                            Ver
                                        </a>

                                    </div>

                                </div>

                            </div>

                        @endif

                    @endforeach

                </div>

            </section>

        @endif

    @endif
</div>
@endsection