@extends('layouts.portal')

@section(
    'title',
    'Calificaciones | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Calificaciones
            </span>

            <h1>
                {{ $grupo->nombre }}
            </h1>

            <p>
                Registra las calificaciones finales
                de los estudiantes matriculados.
            </p>

        </div>


        <div class="portal-page-actions">

            <a
                href="{{
                    route(
                        'portal.docente.mis-grupos.show',
                        $grupo
                    )
                }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left me-2"></i>
                Volver al grupo
            </a>

        </div>

    </div>


    @if ($puedeConfirmar)

    <div
        class="modal fade"
        id="confirmarCalificacionesModal"
        tabindex="-1"
        aria-labelledby="confirmarCalificacionesModalLabel"
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

                        <span
                            class="
                                text-muted
                                d-block
                                mb-1
                            "
                        >
                            Calificaciones finales
                        </span>

                        <h5
                            class="modal-title"
                            id="confirmarCalificacionesModalLabel"
                        >
                            Confirmar calificaciones
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
                            mb-4
                        "
                    >

                        <div class="d-flex gap-2">

                            <i
                                class="
                                    bi
                                    bi-exclamation-triangle
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
                                    Revisa las calificaciones antes de continuar
                                </strong>

                                <span>
                                    Después de confirmar,
                                    las calificaciones dejarán
                                    de estar disponibles para
                                    edición mediante el flujo normal.
                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="mb-3">

                        <span
                            class="
                                text-muted
                                d-block
                                mb-1
                            "
                        >
                            Grupo
                        </span>

                        <strong>
                            {{ $grupo->nombre }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <span
                            class="
                                text-muted
                                d-block
                                mb-1
                            "
                        >
                            Estudiantes
                        </span>

                        <strong>
                            {{ $totalEstudiantes }}
                        </strong>

                    </div>


                    <p class="mb-0">
                        Confirma únicamente cuando hayas
                        verificado que las notas y resultados
                        académicos son correctos.
                    </p>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn portal-btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Volver y revisar
                    </button>


                    <form
                        method="POST"
                        action="{{
                            route(
                                'portal.docente.calificaciones.confirmar',
                                $grupo
                            )
                        }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn portal-btn-primary"
                        >
                            <i
                                class="
                                    bi
                                    bi-check2-circle
                                    me-2
                                "
                            ></i>

                            Sí, confirmar
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endif



@endsection


@section('content')

    @if (session('success'))

        <div
            class="
                alert
                alert-success
                portal-alert
                mb-4
            "
        >

            <i class="bi bi-check-circle"></i>

            <div>

                <strong>
                    Cambios guardados
                </strong>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        </div>

    @endif


    @if (session('error'))

        <div
            class="
                alert
                alert-danger
                portal-alert
                mb-4
            "
        >

            <i class="bi bi-exclamation-triangle"></i>

            <div>

                <strong>
                    No fue posible guardar
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
                Revisa las calificaciones ingresadas.
            </strong>

            <ul class="mb-0 mt-2">

                @foreach (
                    $errors->all()
                    as $error
                )

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
        INFORMACIÓN DEL GRUPO
    ============================================================ --}}

    <section class="portal-card mb-4">

        <div class="portal-card-header">

            <div>

                <h2>
                    Calificaciones finales
                </h2>

                <p>

                    {{
                        $periodo->nombre
                    }}

                    ·

                    {{
                        $grupo
                            ->nivel
                            ->codigo
                    }}

                </p>

            </div>


            @if ($puedeEditar)

                <span class="badge text-bg-success">
                    Carga habilitada
                </span>

            @else

                <span class="badge text-bg-secondary">
                    Carga cerrada
                </span>

            @endif

        </div>


        <div class="p-4">

            <p class="text-muted mb-0">

                Para una calificación ordinaria,
                ingresa la nota final.

                El sistema determinará automáticamente
                si el resultado corresponde a
                aprobación o reprobación según la
                nota mínima configurada para el nivel.

            </p>

        </div>

    </section>


    {{-- ============================================================
        FORMULARIO
    ============================================================ --}}

    <form
        method="POST"
        action="{{
            route(
                'portal.docente.calificaciones.update',
                $grupo
            )
        }}"
    >

        @csrf
        @method('PUT')


        <section class="portal-card">

            <div class="portal-card-header">

                <div>

                    <h2>
                        Estudiantes
                    </h2>

                    <p>
                        Guarda tus avances como borrador.
                    </p>

                </div>

            </div>


            @if (
                $grupo
                    ->matriculas
                    ->isEmpty()
            )

                <div
                    class="
                        text-center
                        py-5
                        px-4
                    "
                >

                    <h5>
                        No hay estudiantes matriculados
                    </h5>

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
                                    Resultado
                                </th>

                                <th style="width: 140px;">
                                    Nota final
                                </th>

                                

                            </tr>

                        </thead>


                        <tbody>

                            @foreach (
                                $grupo->matriculas
                                as $indice => $matricula
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


                                    $tipoResultadoActual =
                                        match (
                                            $calificacion
                                                ?->resultado
                                        ) {
                                            'incompleto' =>
                                                'incompleto',

                                            'retirado' =>
                                                'retirado',

                                            default =>
                                                'normal',
                                        };

                                @endphp


                                <tr>

                                    <td>

                                        <strong>
                                            {{
                                                $estudiante
                                                    ->codigo_estudiante
                                            }}
                                        </strong>

                                        <input
                                            type="hidden"
                                            name="calificaciones[{{ $indice }}][matricula_id]"
                                            value="{{
                                                $matricula->id
                                            }}"
                                        >

                                    </td>


                                    <td>

                                        {{
                                            $persona
                                                ->nombre_completo
                                        }}

                                    </td>


                                    <td>

                                        <select
                                            name="calificaciones[{{ $indice }}][tipo_resultado]"
                                            class="
                                                form-select
                                                resultado-calificacion
                                            "
                                            data-nota-target="nota-{{ $matricula->id }}"
                                            @disabled(
                                                !$puedeEditar
                                                ||
                                                in_array(
                                                    $calificacion
                                                        ?->estado,
                                                    [
                                                        'confirmada',
                                                        'bloqueada',
                                                    ],
                                                    true
                                                )
                                            )
                                        >

                                            <option
                                                value="normal"
                                                @selected(
                                                    old(
                                                        "calificaciones.{$indice}.tipo_resultado",
                                                        $tipoResultadoActual
                                                    )
                                                    ===
                                                    'normal'
                                                )
                                            >
                                                Nota ordinaria
                                            </option>


                                            <option
                                                value="incompleto"
                                                @selected(
                                                    old(
                                                        "calificaciones.{$indice}.tipo_resultado",
                                                        $tipoResultadoActual
                                                    )
                                                    ===
                                                    'incompleto'
                                                )
                                            >
                                                NSP
                                            </option>


                                            <option
                                                value="retirado"
                                                @selected(
                                                    old(
                                                        "calificaciones.{$indice}.tipo_resultado",
                                                        $tipoResultadoActual
                                                    )
                                                    ===
                                                    'retirado'
                                                )
                                            >
                                                ABD
                                            </option>

                                        </select>

                                    </td>


                                    <td>

                                        <input
                                            type="integer"
                                            id="nota-{{ $matricula->id }}"
                                            name="calificaciones[{{ $indice }}][nota_final]"
                                            class="form-control"
                                            min="0"
                                            max="100"
                                            step="1"
                                            value="{{
                                                old(
                                                    "calificaciones.{$indice}.nota_final",
                                                    $calificacion
                                                        ?->nota_final
                                                )
                                            }}"
                                            @disabled(
                                                !$puedeEditar
                                                ||
                                                $tipoResultadoActual
                                                    !==
                                                    'normal'
                                                ||
                                                in_array(
                                                    $calificacion
                                                        ?->estado,
                                                    [
                                                        'confirmada',
                                                        'bloqueada',
                                                    ],
                                                    true
                                                )
                                            )
                                        >

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                @if ($puedeEditar)

    <div
        class="
            border-top
            p-4
            d-flex
            justify-content-between
            align-items-center
            gap-3
            flex-wrap
        "
    >

        <div>

            @if ($todasConfirmadas)

                <span class="text-success">

                    <i
                        class="
                            bi
                            bi-check-circle
                            me-1
                        "
                    ></i>

                    Las calificaciones ya fueron confirmadas.

                </span>

            @elseif (
                $totalConCalificacion
                <
                $totalEstudiantes
            )

                <span class="text-muted">

                    {{
                        $totalConCalificacion
                    }}

                    de

                    {{
                        $totalEstudiantes
                    }}

                    estudiantes tienen una calificación registrada.

                </span>

            @else

                <span class="text-muted">
                    Todas las calificaciones están listas para revisión.
                </span>

            @endif

        </div>


        <div
            class="
                d-flex
                gap-2
                flex-wrap
            "
        >

            @if (!$todasConfirmadas)

                <button
                    type="submit"
                    class="btn portal-btn-secondary"
                >
                    <i class="bi bi-save me-2"></i>
                    Guardar borrador
                </button>

            @endif


            @if ($puedeConfirmar)

                <button
                    type="button"
                    class="btn portal-btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#confirmarCalificacionesModal"
                >
                    <i
                        class="
                            bi
                            bi-check2-circle
                            me-2
                        "
                    ></i>

                    Confirmar calificaciones
                </button>

            @endif

        </div>

    </div>

@endif

            @endif

        </section>

    </form>

@endsection


@push('scripts')

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {
        const selects =
            document.querySelectorAll(
                '.resultado-calificacion'
            );

        selects.forEach(
            function (select) {
                const actualizarNota =
                    function () {
                        const input =
                            document.getElementById(
                                select.dataset.notaTarget
                            );

                        if (!input) {
                            return;
                        }

                        if (
                            select.value === 'normal'
                        ) {
                            input.disabled = false;
                        } else {
                            input.value = '';
                            input.disabled = true;
                        }
                    };

                select.addEventListener(
                    'change',
                    actualizarNota
                );

                actualizarNota();
            }
        );
    }
);
</script>

@endpush