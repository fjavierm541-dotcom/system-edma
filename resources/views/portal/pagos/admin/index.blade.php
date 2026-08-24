@extends('layouts.portal')

@section('title', 'Pagos | Portal EDMA')

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Gestión financiera
            </span>

            <h1>
                Revisión de pagos
            </h1>

            <p>
                Revisa los comprobantes enviados por los estudiantes
                y gestiona su aprobación o rechazo.
            </p>

        </div>

    </div>

@endsection


@section('content')

    @if (session('success'))

        <div class="alert alert-success portal-alert mb-4">

            <i class="bi bi-check-circle-fill"></i>

            <div>
                <strong>
                    Proceso completado
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
                    No fue posible completar la acción
                </strong>

                <span>
                    {{ session('error') }}
                </span>
            </div>

        </div>

    @endif


    <section class="portal-card">

        <div class="portal-card-header">

            <div>

                <h2>
                    Pagos registrados
                </h2>

                <p>
                    Los pagos pendientes aparecen primero
                    y se muestran en orden de recepción.
                </p>

            </div>

        </div>


        @if ($pagos->isEmpty())

            <div class="text-center py-5 px-4">

                <i
                    class="bi bi-receipt fs-2 text-muted"
                ></i>

                <h5 class="mt-3">
                    No hay pagos registrados
                </h5>

                <p class="text-muted mb-0">
                    Los pagos enviados por los estudiantes
                    aparecerán en esta bandeja.
                </p>

            </div>

        @else

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead>

                        <tr>
                            <th>
                                Pago
                            </th>

                            <th>
                                Estudiante
                            </th>

                            <th>
                                Período
                            </th>

                            <th>
                                Fecha
                            </th>

                            <th>
                                Método
                            </th>

                            <th class="text-end">
                                Monto reportado
                            </th>

                            <th>
                                Estado
                            </th>

                            <th class="text-end">
                                Acción
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($pagos as $pago)

                            @php

                                $estadoTexto =
                                    match ($pago->estado) {
                                        'pendiente_revision' =>
                                            'Pendiente',

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

                                $persona =
                                    $pago
                                        ->estudiante
                                        ?->persona;

                                $metodo =
                                    match ($pago->metodo_pago) {
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

                                    <strong>
                                        {{ $pago->codigo_pago }}
                                    </strong>

                                    @if ($pago->numero_referencia)

                                        <div class="small text-muted">
                                            Ref.
                                            {{ $pago->numero_referencia }}
                                        </div>

                                    @endif

                                </td>


                                <td>

                                    <strong>
                                        {{
                                            $persona
                                                ?->nombre_completo
                                            ?? 'Sin estudiante'
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

                                </td>


                                <td>

                                    {{
                                        $pago
                                            ->periodoAcademico
                                            ?->nombre
                                        ?? '—'
                                    }}

                                    @if (
                                        $pago
                                            ->periodoAcademico
                                            ?->fecha_inicio
                                    )

                                        <div class="small text-muted">
                                            {{
                                                $pago
                                                    ->periodoAcademico
                                                    ->fecha_inicio
                                                    ->format('Y')
                                            }}
                                        </div>

                                    @endif

                                </td>


                                <td class="text-nowrap">

                                    {{
                                        $pago
                                            ->fecha_pago
                                            ?->format('d/m/Y H:i')
                                        ?? '—'
                                    }}

                                </td>


                                <td>
                                    {{ $metodo }}
                                </td>


                                <td class="text-end text-nowrap">

                                    <strong>
                                        L
                                        {{
                                            number_format(
                                                (float)
                                                $pago->monto_total,
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


                                <td class="text-end">

                                    <a
                                        href="{{
                                            route(
                                                'portal.admin.pagos.show',
                                                $pago
                                            )
                                        }}"
                                        class="
                                            btn
                                            portal-btn-secondary
                                            btn-sm
                                        "
                                    >
                                        <i
                                            class="
                                                bi
                                                bi-eye
                                                me-1
                                            "
                                        ></i>

                                        Revisar
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </section>

@endsection