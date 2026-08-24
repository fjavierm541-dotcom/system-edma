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
                                        old('metodo_pago')
                                        === 'transferencia'
                                    )
                                >
                                    Transferencia bancaria
                                </option>

                                <option
                                    value="deposito"
                                    @selected(
                                        old('metodo_pago')
                                        === 'deposito'
                                    )
                                >
                                    Depósito bancario
                                </option>

                                <option
                                    value="tigo_money"
                                    @selected(
                                        old('metodo_pago')
                                        === 'tigo_money'
                                    )
                                >
                                    Tigo Money
                                </option>

                                <option
                                    value="otro"
                                    @selected(
                                        old('metodo_pago')
                                        === 'otro'
                                    )
                                >
                                    Otro
                                </option>

                            </select>

                        </div>


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
                                        now()->toDateString()
                                    )
                                }}"
                                max="{{
                                    now()->toDateString()
                                }}"
                                required
                            >

                        </div>


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
                                accept=".jpg,.jpeg,.png,.pdf"
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
            HISTORIAL
        ======================================================== --}}

        <div class="col-12 col-xl-8">

            <section class="portal-card">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Historial de pagos
                        </h2>

                        <p>
                            Los pagos pendientes aparecen
                            primero para facilitar su seguimiento.
                        </p>

                    </div>

                </div>


                @if ($pagos->isEmpty())

                    <div class="text-center py-5 px-4">

                        <i
                            class="
                                bi
                                bi-receipt
                                fs-2
                                text-muted
                            "
                        ></i>

                        <h5 class="mt-3">
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

                                @foreach ($pagos as $pago)

                                    @php

                                        $estadoTexto =
                                            match ($pago->estado) {
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
                                                        $pago->estado
                                                    )
                                                    ->replace(
                                                        '_',
                                                        ' '
                                                    )
                                                    ->title(),
                                            };


                                        $estadoClase =
                                            match ($pago->estado) {
                                                'aprobado' =>
                                                    'success',

                                                'rechazado' =>
                                                    'danger',

                                                'anulado' =>
                                                    'secondary',

                                                default =>
                                                    'warning',
                                            };


                                        $anioPeriodo =
                                            $pago
                                                ->periodoAcademico
                                                ?->fecha_inicio
                                                ?->format('Y');


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


                                        if (
                                            $pago->estado
                                            !== 'aprobado'
                                        ) {

                                            $concepto =
                                                'Mensualidad';

                                        } elseif (
                                            $cuotas->count()
                                            === 1
                                        ) {

                                            $numero =
                                                (int)
                                                $cuotas->first();

                                            $concepto =
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

                                        } elseif (
                                            $cuotas->count()
                                            > 1
                                        ) {

                                            $concepto =
                                                'Cuotas '
                                                . $cuotas
                                                    ->implode(
                                                        ', '
                                                    );

                                        } elseif (
                                            $pago
                                                ->solicitud_inscripcion_id
                                        ) {

                                            $concepto =
                                                'Primer pago del período';

                                        } else {

                                            $concepto =
                                                'Mensualidad';
                                        }


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

                                        <td>

                                            <strong
                                                class="text-nowrap"
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


                                        <td>

                                            <div>
                                                {{
                                                    $pago
                                                        ->periodoAcademico
                                                        ?->nombre
                                                    ?? '—'
                                                }}
                                            </div>

                                            @if ($anioPeriodo)

                                                <small
                                                    class="text-muted"
                                                >
                                                    {{ $anioPeriodo }}
                                                </small>

                                            @endif

                                        </td>


                                        <td>
                                            {{ $concepto }}
                                        </td>


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


                                        <td>
                                            {{ $metodoTexto }}
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
                                                        $pago
                                                            ->monto_total,
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


                                            @if (
                                                $pago->estado
                                                === 'rechazado'
                                            )

                                                <div class="mt-2">

                                                    <button
                                                        type="button"
                                                        class="
                                                            btn
                                                            btn-link
                                                            btn-sm
                                                            p-0
                                                            text-danger
                                                        "
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#motivoPagoModal{{ $pago->id }}"
                                                    >
                                                        Ver motivo
                                                    </button>

                                                </div>

                                            @endif

                                        </td>

                                    </tr>


                                    {{-- =====================================
                                        MODAL RECHAZO
                                    ====================================== --}}

                                    @if (
                                        $pago->estado
                                        === 'rechazado'
                                    )

                                        <div
                                            class="modal fade"
                                            id="motivoPagoModal{{ $pago->id }}"
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

                                                            <small
                                                                class="
                                                                    text-muted
                                                                    d-block
                                                                "
                                                            >
                                                                Pago rechazado
                                                            </small>

                                                            <h5
                                                                class="
                                                                    modal-title
                                                                    mb-0
                                                                "
                                                            >
                                                                {{
                                                                    $pago
                                                                        ->codigo_pago
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

                                                        <div
                                                            class="
                                                                alert
                                                                alert-warning
                                                            "
                                                        >

                                                            <div
                                                                class="
                                                                    d-flex
                                                                    gap-2
                                                                "
                                                            >

                                                                <i
                                                                    class="
                                                                        bi
                                                                        bi-exclamation-circle
                                                                        mt-1
                                                                    "
                                                                ></i>

                                                                <div>

                                                                    <strong
                                                                        class="
                                                                            d-block
                                                                            mb-1
                                                                        "
                                                                    >
                                                                        Este pago no fue aprobado
                                                                    </strong>

                                                                    <span>
                                                                        Revisa el motivo indicado
                                                                        por Administración.
                                                                        Si necesitas ayuda,
                                                                        puedes comunicarte con
                                                                        Edumerican Academy.
                                                                    </span>

                                                                </div>

                                                            </div>

                                                        </div>


                                                        <div class="mb-4">

                                                            <small
                                                                class="
                                                                    text-muted
                                                                    d-block
                                                                    mb-1
                                                                "
                                                            >
                                                                Motivo del rechazo
                                                            </small>

                                                            <div
                                                                class="
                                                                    border
                                                                    rounded-3
                                                                    p-3
                                                                    bg-light
                                                                "
                                                            >
                                                                {{
                                                                    $pago
                                                                        ->motivo_rechazo
                                                                    ?:
                                                                    'Administración no registró información adicional.'
                                                                }}
                                                            </div>

                                                        </div>


                                                        <div>

                                                            <small
                                                                class="
                                                                    text-muted
                                                                    d-block
                                                                    mb-2
                                                                "
                                                            >
                                                                Contactar a Administración
                                                            </small>


                                                            <div
                                                                class="
                                                                    d-flex
                                                                    align-items-center
                                                                    gap-2
                                                                    flex-wrap
                                                                "
                                                            >

                                                                <a
                                                                    href="https://wa.me/50496734171"
                                                                    target="_blank"
                                                                    rel="noopener"
                                                                    class="
                                                                        btn
                                                                        portal-btn-secondary
                                                                    "
                                                                    title="Contactar por WhatsApp"
                                                                    aria-label="Contactar a Administración por WhatsApp"
                                                                >
                                                                    <i class="bi bi-whatsapp"></i>
                                                                </a>


                                                                <a
                                                                    href="mailto:edumerican@gmail.com"
                                                                    class="
                                                                        btn
                                                                        portal-btn-secondary
                                                                    "
                                                                >
                                                                    <i class="bi bi-envelope me-2"></i>
                                                                    edumerican@gmail.com
                                                                </a>

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
                                                            Cerrar
                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    @endif

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @endif

            </section>

        </div>

    </div>

@endsection