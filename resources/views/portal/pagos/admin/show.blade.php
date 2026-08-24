@extends('layouts.portal')

@section(
    'title',
    'Revisión de pago | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Gestión financiera
            </span>

            <h1>
                Revisión de pago
            </h1>

            <p>
                Verifica la información y el comprobante
                antes de aprobar o rechazar este pago.
            </p>

        </div>


        <a
            href="{{
                route(
                    'portal.admin.pagos.index'
                )
            }}"
            class="
                btn
                portal-btn-secondary
            "
        >
            <i
                class="
                    bi
                    bi-arrow-left
                    me-2
                "
            ></i>

            Volver
        </a>

    </div>

@endsection


@section('content')

    @php

        $persona =
            $pago
                ->estudiante
                ?->persona;

        $comprobante =
            $pago
                ->comprobantes
                ->first();

        $comprobanteUrl =
            $comprobante
                ? asset(
                    'storage/'
                    . $comprobante
                        ->ruta_archivo
                )
                : null;

        $esImagen =
            $comprobante
            && str_starts_with(
                $comprobante->mime_type,
                'image/'
            );

        $esPdf =
            $comprobante
            && $comprobante->mime_type
            === 'application/pdf';

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

    @endphp


    @if (session('error'))

        <div class="alert alert-danger portal-alert mb-4">

            <i class="bi bi-exclamation-triangle-fill"></i>

            <div>

                <strong>
                    No fue posible completar la acción
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
                Revisa la información.
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
            INFORMACIÓN
        ======================================================== --}}

        <div class="col-12 col-xl-5">

            <section class="portal-card">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Información del pago
                        </h2>

                        <p>
                            Datos reportados por el estudiante.
                        </p>

                    </div>

                </div>


                <div class="p-4">

                    <div class="row g-3">

                        <div class="col-12">

                            <small class="text-muted d-block">
                                Código de pago
                            </small>

                            <strong>
                                {{ $pago->codigo_pago }}
                            </strong>

                        </div>


                        <div class="col-12">

                            <small class="text-muted d-block">
                                Estudiante
                            </small>

                            <strong>
                                {{
                                    $persona
                                        ?->nombre_completo
                                    ?? '—'
                                }}
                            </strong>

                            <div class="small text-muted">
                                {{
                                    $pago
                                        ->estudiante
                                        ?->codigo_estudiante
                                    ?? '—'
                                }}
                            </div>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Período
                            </small>

                            <strong>
                                {{
                                    $pago
                                        ->periodoAcademico
                                        ?->nombre
                                    ?? '—'
                                }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Estado
                            </small>

                            <strong>
                                {{ $estadoTexto }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Fecha del pago
                            </small>

                            <strong>
                                {{
                                    $pago
                                        ->fecha_pago
                                        ?->format(
                                            'd/m/Y'
                                        )
                                    ?? '—'
                                }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Monto reportado
                            </small>

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

                        </div>


                        <div class="col-12">

                            <small class="text-muted d-block">
                                Número de transacción
                                o referencia
                            </small>

                            <strong>
                                {{
                                    $pago
                                        ->numero_referencia
                                    ?: 'No registrado'
                                }}
                            </strong>

                        </div>

                    </div>

                </div>

            </section>


            @if (
                $pago->estado
                === 'pendiente_revision'
            )

                <section class="portal-card mt-4">

                    <div class="portal-card-header">

                        <div>

                            <h2>
                                Resolver pago
                            </h2>

                            <p>
                                Confirma el monto real
                                antes de aprobar.
                            </p>

                        </div>

                    </div>


                    <div class="p-4">

                        {{-- Aprobar --}}
                        <form
                            method="POST"
                            action="{{
                                route(
                                    'portal.admin.pagos.aprobar',
                                    $pago
                                )
                            }}"
                            class="mb-4"
                        >

                            @csrf


                            <div class="mb-3">

                                <label
                                    for="monto_confirmado"
                                    class="form-label"
                                >
                                    Monto confirmado
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        L
                                    </span>

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="monto_confirmado"
                                        name="monto_confirmado"
                                        value="{{
                                            old(
                                                'monto_confirmado',
                                                $pago
                                                    ->monto_total
                                            )
                                        }}"
                                        min="0.01"
                                        step="0.01"
                                        required
                                    >

                                </div>

                                <div class="form-text">
                                    Corrige este monto si
                                    el comprobante muestra
                                    una cantidad diferente
                                    a la reportada.
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
                                        bi-check-circle
                                        me-2
                                    "
                                ></i>

                                Aprobar pago
                            </button>

                        </form>


                        <hr>


                        {{-- Rechazar --}}
                        <button
                            type="button"
                            class="
                                btn
                                btn-outline-danger
                                w-100
                            "
                            data-bs-toggle="modal"
                            data-bs-target="#rechazarPagoModal"
                        >
                            <i
                                class="
                                    bi
                                    bi-x-circle
                                    me-2
                                "
                            ></i>

                            Rechazar pago
                        </button>

                    </div>

                </section>

            @endif

        </div>


        {{-- ========================================================
            COMPROBANTE
        ======================================================== --}}

        <div class="col-12 col-xl-7">

            <section class="portal-card">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Comprobante adjunto
                        </h2>

                        <p>
                            Revisa el documento enviado
                            antes de tomar una decisión.
                        </p>

                    </div>

                </div>


                <div class="p-4">

                    @if (!$comprobante)

                        <div
                            class="
                                alert
                                alert-warning
                                mb-0
                            "
                        >
                            Este pago no tiene
                            comprobante adjunto.
                        </div>


                    @elseif ($esImagen)

                        <div class="text-center">

                            <img
                                src="{{ $comprobanteUrl }}"
                                alt="Comprobante de pago"
                                class="
                                    img-fluid
                                    rounded
                                    border
                                "
                                style="
                                    max-height: 650px;
                                    object-fit: contain;
                                "
                            >

                        </div>


                    @elseif ($esPdf)

                        <div class="ratio ratio-4x3">

                            <iframe
                                src="{{ $comprobanteUrl }}"
                                title="Comprobante de pago"
                                class="border rounded"
                            ></iframe>

                        </div>


                    @else

                        <div class="alert alert-info">

                            El comprobante no puede
                            visualizarse directamente
                            en el navegador.

                        </div>

                    @endif


                    @if ($comprobanteUrl)

                        <div class="mt-3">

                            <a
                                href="{{ $comprobanteUrl }}"
                                target="_blank"
                                rel="noopener"
                                class="
                                    btn
                                    portal-btn-secondary
                                "
                            >
                                <i
                                    class="
                                        bi
                                        bi-box-arrow-up-right
                                        me-2
                                    "
                                ></i>

                                Abrir comprobante
                            </a>

                        </div>

                    @endif

                </div>

            </section>

        </div>

    </div>


    {{-- ============================================================
        MODAL RECHAZO
    ============================================================ --}}

    @if (
        $pago->estado
        === 'pendiente_revision'
    )

        <div
            class="modal fade"
            id="rechazarPagoModal"
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

                        <h5 class="modal-title">
                            Rechazar pago
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"
                        ></button>

                    </div>


                    <form
                        method="POST"
                        action="{{
                            route(
                                'portal.admin.pagos.rechazar',
                                $pago
                            )
                        }}"
                    >

                        @csrf


                        <div class="modal-body">

                            <p class="text-muted">
                                El pago no será aplicado
                                a la cuenta del estudiante.
                            </p>


                            <div>

                                <label
                                    for="motivo_rechazo"
                                    class="form-label"
                                >
                                    Motivo del rechazo
                                </label>

                                <textarea
                                    class="form-control"
                                    name="motivo_rechazo"
                                    id="motivo_rechazo"
                                    rows="4"
                                    maxlength="1000"
                                    required
                                >{{ old('motivo_rechazo') }}</textarea>

                                <div class="form-text">
                                    Registra una explicación
                                    clara para mantener
                                    trazabilidad administrativa.
                                </div>

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
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                class="
                                    btn
                                    btn-danger
                                "
                            >
                                Confirmar rechazo
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif

@endsection