@extends('layouts.portal')

@section('title', 'Pagos | Portal EDMA')

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Gestión financiera
            </span>

            <h1>
                Pagos
            </h1>

            <p>
                Registra tus mensualidades y consulta
                el estado de los pagos enviados a
                Edumerican Academy.
            </p>

        </div>

    </div>

@endsection


@section('content')

    {{-- ============================================================
        MENSAJES
    ============================================================ --}}

    @if (session('success'))

        <div class="alert alert-success portal-alert mb-4">

            <i class="bi bi-check-circle-fill"></i>

            <div>

                <strong>
                    Pago registrado
                </strong>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        </div>

    @endif


    @if (session('error'))

        <div class="alert alert-danger portal-alert mb-4">

            <i class="bi bi-exclamation-triangle-fill"></i>

            <div>

                <strong>
                    No fue posible registrar el pago
                </strong>

                <span>
                    {{ session('error') }}
                </span>

            </div>

        </div>

    @endif


    @if ($errors->any())

        <div class="alert alert-danger mb-4">

            <strong>
                Revisa la información ingresada.
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row g-4">

        {{-- ========================================================
            REGISTRAR PAGO
        ======================================================== --}}

        <div class="col-12 col-xl-4">

            <section class="portal-card">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Registrar mensualidad
                        </h2>

                        <p>
                            Adjunta el comprobante del pago
                            que realizaste.
                        </p>

                    </div>

                </div>


                <div class="p-4">

                    <div class="alert alert-info">

                        <div class="d-flex gap-2">

                            <i
                                class="
                                    bi
                                    bi-shield-check
                                    mt-1
                                "
                            ></i>

                            <div>

                                <strong class="d-block mb-1">
                                    Verificación del pago
                                </strong>

                                <span>
                                    El comprobante será revisado
                                    por Administración antes de
                                    aplicarse a tu cuenta.
                                </span>

                            </div>

                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{
                            route(
                                'portal.pagos.store'
                            )
                        }}"
                        enctype="multipart/form-data"
                    >

                        @csrf


                        {{-- Monto --}}
                        <div class="mb-3">

                            <label
                                for="monto_total"
                                class="form-label"
                            >
                                Monto pagado
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    L
                                </span>

                                <input
                                    type="number"
                                    class="form-control"
                                    id="monto_total"
                                    name="monto_total"
                                    value="{{
                                        old(
                                            'monto_total'
                                        )
                                    }}"
                                    min="700"
                                    step="0.01"
                                    required
                                >

                            </div>

                            <div class="form-text">
                                La mensualidad base es de
                                L 700.00. Puedes registrar
                                un monto mayor si pagaste
                                más de una mensualidad.
                            </div>

                        </div>


                        {{-- Método --}}
                        <div class="mb-3">

                            <label
                                for="metodo_pago"
                                class="form-label"
                            >
                                Método de pago
                            </label>

                            <select
                                class="form-select"
                                id="metodo_pago"
                                name="metodo_pago"
                                required
                            >

                                <option value="">
                                    Selecciona una opción
                                </option>


                                <option
                                    value="transferencia"
                                    @selected(
                                        old(
                                            'metodo_pago'
                                        )
                                        ===
                                        'transferencia'
                                    )
                                >
                                    Transferencia bancaria
                                </option>


                                <option
                                    value="deposito"
                                    @selected(
                                        old(
                                            'metodo_pago'
                                        )
                                        ===
                                        'deposito'
                                    )
                                >
                                    Depósito bancario
                                </option>


                                <option
                                    value="tigo_money"
                                    @selected(
                                        old(
                                            'metodo_pago'
                                        )
                                        ===
                                        'tigo_money'
                                    )
                                >
                                    Tigo Money
                                </option>


                                <option
                                    value="otro"
                                    @selected(
                                        old(
                                            'metodo_pago'
                                        )
                                        ===
                                        'otro'
                                    )
                                >
                                    Otro
                                </option>

                            </select>

                        </div>


                        {{-- Fecha --}}
                        <div class="mb-3">

                            <label
                                for="fecha_pago"
                                class="form-label"
                            >
                                Fecha del pago
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="fecha_pago"
                                name="fecha_pago"
                                value="{{
                                    old(
                                        'fecha_pago',
                                        now()
                                            ->toDateString()
                                    )
                                }}"
                                max="{{
                                    now()
                                        ->toDateString()
                                }}"
                                required
                            >

                        </div>


                        {{-- Referencia --}}
                        <div class="mb-3">

                            <label
                                for="numero_referencia"
                                class="form-label"
                            >
                                Número de transacción
                                o referencia
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="numero_referencia"
                                name="numero_referencia"
                                value="{{
                                    old(
                                        'numero_referencia'
                                    )
                                }}"
                                maxlength="100"
                            >

                            <div class="form-text">
                                Ingresa el número de
                                transacción, operación o
                                referencia que aparece en
                                tu comprobante, si está
                                disponible.
                            </div>

                        </div>


                        {{-- Comprobante --}}
                        <div class="mb-4">

                            <label
                                for="comprobante"
                                class="form-label"
                            >
                                Comprobante de pago
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                id="comprobante"
                                name="comprobante"
                                accept="
                                    .jpg,
                                    .jpeg,
                                    .png,
                                    .pdf
                                "
                                required
                            >

                            <div class="form-text">
                                Puedes adjuntar una imagen
                                JPG, PNG o un archivo PDF
                                de hasta 5 MB.
                            </div>

                        </div>


                        <button
                            type="submit"
                            class="
                                btn
                                portal-btn-primary
                                w-100
                            "
                        >
                            <i
                                class="
                                    bi
                                    bi-send-check
                                    me-2
                                "
                            ></i>

                            Enviar pago a revisión
                        </button>

                    </form>

                </div>

            </section>

        </div>


        {{-- ========================================================
            HISTORIAL DE PAGOS
        ======================================================== --}}

        <div class="col-12 col-xl-8">

            <section class="portal-card">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Historial de pagos
                        </h2>

                        <p>
                            Consulta los pagos registrados
                            y su estado de revisión.
                        </p>

                    </div>

                </div>


                @if ($pagos->isEmpty())

                    <div class="text-center py-5 px-4">

                        <div class="mb-3">

                            <i
                                class="
                                    bi
                                    bi-receipt
                                    fs-2
                                    text-muted
                                "
                            ></i>

                        </div>

                        <h5>
                            Aún no hay pagos registrados
                        </h5>

                        <p class="text-muted mb-0">
                            Los pagos que registres
                            aparecerán en este historial.
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
                                        Código
                                    </th>

                                    <th>
                                        Período
                                    </th>

                                    <th>
                                        Concepto
                                    </th>

                                    <th>
                                        Fecha
                                    </th>

                                    <th>
                                        Método
                                    </th>

                                    <th class="text-end">
                                        Monto
                                    </th>

                                    <th>
                                        Estado
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach (
                                    $pagos
                                    as $pago
                                )

                                    @php

                                        /*
                                         * Estado visible.
                                         */
                                        $estadoTexto =
                                            match (
                                                $pago
                                                    ->estado
                                            ) {
                                                'pendiente_revision' =>
                                                    'Pendiente de revisión',

                                                'aprobado' =>
                                                    'Aprobado',

                                                'rechazado' =>
                                                    'Rechazado',

                                                'anulado' =>
                                                    'Anulado',

                                                default =>
                                                    str(
                                                        $pago
                                                            ->estado
                                                    )
                                                    ->replace(
                                                        '_',
                                                        ' '
                                                    )
                                                    ->title(),
                                            };


                                        $estadoClase =
                                            match (
                                                $pago
                                                    ->estado
                                            ) {
                                                'aprobado' =>
                                                    'success',

                                                'rechazado' =>
                                                    'danger',

                                                'anulado' =>
                                                    'secondary',

                                                default =>
                                                    'warning',
                                            };


                                        /*
                                         * Año del período.
                                         */
                                        $anioPeriodo =
                                            $pago
                                                ->periodoAcademico
                                                ?->fecha_inicio
                                                ?->format(
                                                    'Y'
                                                );


                                        /*
                                         * Cuotas a las que fue
                                         * aplicado el pago.
                                         */
                                        $cuotas =
                                            $pago
                                                ->aplicacionesCuotas
                                                ->pluck(
                                                    'cuota.numero_cuota'
                                                )
                                                ->filter()
                                                ->unique()
                                                ->sort()
                                                ->values();


                                        /*
                                         * Texto comprensible
                                         * del concepto.
                                         */
                                        if (
                                            $pago->estado
                                            !== 'aprobado'
                                        ) {

                                            $concepto =
                                                'Mensualidad pendiente de revisión';

                                        } elseif (
                                            $cuotas->count()
                                            === 1
                                        ) {

                                            $numero =
                                                $cuotas
                                                    ->first();

                                            $concepto =
                                                match (
                                                    (int) $numero
                                                ) {
                                                    1 =>
                                                        'Primera mensualidad',

                                                    2 =>
                                                        'Segunda mensualidad',

                                                    3 =>
                                                        'Tercera mensualidad',

                                                    default =>
                                                        'Mensualidad '
                                                        . $numero,
                                                };

                                        } elseif (
                                            $cuotas->count()
                                            > 1
                                        ) {

                                            $nombresCuotas =
                                                $cuotas
                                                    ->map(
                                                        function (
                                                            $numero
                                                        ) {

                                                            return match (
                                                                (int) $numero
                                                            ) {
                                                                1 =>
                                                                    'primera',

                                                                2 =>
                                                                    'segunda',

                                                                3 =>
                                                                    'tercera',

                                                                default =>
                                                                    'cuota '
                                                                    . $numero,
                                                            };
                                                        }
                                                    );

                                            if (
                                                $nombresCuotas
                                                    ->count()
                                                === 2
                                            ) {

                                                $concepto =
                                                    ucfirst(
                                                        $nombresCuotas[0]
                                                    )
                                                    . ' y '
                                                    . $nombresCuotas[1]
                                                    . ' mensualidad';

                                            } else {

                                                $concepto =
                                                    'Pago de '
                                                    . $nombresCuotas
                                                        ->implode(
                                                            ', '
                                                        )
                                                    . ' mensualidad';

                                            }

                                        } else {

                                            /*
                                             * Casos como el pago
                                             * inicial ya aprobado
                                             * que todavía no
                                             * tuviera aplicaciones.
                                             */
                                            $concepto =
                                                $pago
                                                    ->solicitud_inscripcion_id
                                                ? 'Primer pago del período'
                                                : 'Mensualidad';
                                        }


                                        /*
                                         * Método visible.
                                         */
                                        $metodoTexto =
                                            match (
                                                $pago
                                                    ->metodo_pago
                                            ) {
                                                'transferencia' =>
                                                    'Transferencia',

                                                'deposito' =>
                                                    'Depósito',

                                                'tigo_money' =>
                                                    'Tigo Money',

                                                'otro' =>
                                                    'Otro',

                                                default =>
                                                    str(
                                                        $pago
                                                            ->metodo_pago
                                                    )
                                                    ->replace(
                                                        '_',
                                                        ' '
                                                    )
                                                    ->title(),
                                            };

                                    @endphp


                                    <tr>

                                        {{-- Código --}}
                                        <td>

                                            <strong
                                                class="
                                                    text-nowrap
                                                "
                                            >
                                                {{
                                                    $pago
                                                        ->codigo_pago
                                                }}
                                            </strong>


                                            @if (
                                                $pago
                                                    ->numero_referencia
                                            )

                                                <div
                                                    class="
                                                        small
                                                        text-muted
                                                        mt-1
                                                    "
                                                >
                                                    Ref.
                                                    {{
                                                        $pago
                                                            ->numero_referencia
                                                    }}
                                                </div>

                                            @endif

                                        </td>


                                        {{-- Período --}}
                                        <td>

                                            <div>
                                                {{
                                                    $pago
                                                        ->periodoAcademico
                                                        ?->nombre
                                                    ?? '—'
                                                }}
                                            </div>


                                            @if (
                                                $anioPeriodo
                                            )

                                                <small
                                                    class="
                                                        text-muted
                                                    "
                                                >
                                                    {{
                                                        $anioPeriodo
                                                    }}
                                                </small>

                                            @endif

                                        </td>


                                        {{-- Concepto --}}
                                        <td>
                                            {{ $concepto }}
                                        </td>


                                        {{-- Fecha --}}
                                        <td class="text-nowrap">

                                            {{
                                                $pago
                                                    ->fecha_pago
                                                    ?->format(
                                                        'd/m/Y'
                                                    )
                                                ?? '—'
                                            }}

                                        </td>


                                        {{-- Método --}}
                                        <td>
                                            {{ $metodoTexto }}
                                        </td>


                                        {{-- Monto --}}
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
                                                        $pago
                                                            ->monto_total,
                                                        2
                                                    )
                                                }}
                                            </strong>

                                        </td>


                                        {{-- Estado --}}
                                        <td>

                                            <span
                                                class="
                                                    badge
                                                    text-bg-{{
                                                        $estadoClase
                                                    }}
                                                "
                                            >
                                                {{
                                                    $estadoTexto
                                                }}
                                            </span>


                                            @if (
                                                $pago->estado
                                                === 'rechazado'
                                                &&
                                                $pago
                                                    ->motivo_rechazo
                                            )

                                                <div
                                                    class="
                                                        small
                                                        text-danger
                                                        mt-1
                                                    "
                                                    title="{{
                                                        $pago
                                                            ->motivo_rechazo
                                                    }}"
                                                >
                                                    Ver motivo
                                                </div>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @endif

            </section>

        </div>

    </div>

@endsection