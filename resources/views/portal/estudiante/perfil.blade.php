@extends('layouts.portal')

@section(
    'title',
    'Mi perfil | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Mi cuenta
            </span>

            <h1>
                Mi perfil
            </h1>

            <p>
                Consulta la información personal
                y académica registrada en EDMA.
            </p>

        </div>

    </div>

@endsection


@section('content')

    @php

        $tipoDocumento =
            match (
                $persona->tipo_documento
            ) {
                'dni' =>
                    'DNI',

                'pasaporte' =>
                    'Pasaporte',

                'partida_nacimiento' =>
                    'Partida de nacimiento',

                default =>
                    str(
                        $persona
                            ->tipo_documento
                            ?? 'Documento'
                    )
                    ->replace(
                        '_',
                        ' '
                    )
                    ->title(),
            };

        $programa =
            $estudiante
                ->nivelAutorizado
                ?->programa;

        $nivel =
            $estudiante
                ->nivelAutorizado;

    @endphp


    {{-- ============================================================
        IDENTIDAD
    ============================================================ --}}

    <section class="portal-card mb-4">

        <div class="p-4">

            <div
                class="
                    d-flex
                    align-items-center
                    gap-4
                    flex-wrap
                "
            >

                <div>

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
                            class="
                                rounded-circle
                                border
                            "
                            style="
                                width: 100px;
                                height: 100px;
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
                                width: 100px;
                                height: 100px;
                            "
                        >

                            <i
                                class="
                                    bi
                                    bi-person
                                    fs-2
                                "
                            ></i>

                        </div>

                    @endif

                </div>


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

                    <h2 class="mb-1">

                        {{
                            $persona
                                ->nombre_completo
                        }}

                    </h2>

                    <strong>

                        {{
                            $estudiante
                                ->codigo_estudiante
                        }}

                    </strong>

                </div>


                <div>

                    <span
                        class="
                            badge
                            text-bg-success
                        "
                    >
                        Expediente activo
                    </span>

                </div>

            </div>

        </div>

    </section>


    <div class="row g-4">

        {{-- ========================================================
            INFORMACIÓN PERSONAL
        ======================================================== --}}

        <div class="col-12 col-xl-7">

            <section class="portal-card h-100">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Información personal
                        </h2>

                        <p>
                            Datos de identificación
                            registrados en tu expediente.
                        </p>

                    </div>

                </div>


                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>
                            Nombre completo
                        </span>

                        <strong>
                            {{
                                $persona
                                    ->nombre_completo
                            }}
                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            {{ $tipoDocumento }}
                        </span>

                        <strong>
                            {{
                                $persona
                                    ->numero_documento
                                ?: 'No registrado'
                            }}
                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Fecha de nacimiento
                        </span>

                        <strong>

                            @if (
                                $persona
                                    ->fecha_nacimiento
                            )

                                {{
                                    $persona
                                        ->fecha_nacimiento
                                        ->format(
                                            'd/m/Y'
                                        )
                                }}

                            @else

                                No registrada

                            @endif

                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Nacionalidad
                        </span>

                        <strong>
                            {{
                                $persona
                                    ->nacionalidad
                                ?: 'No registrada'
                            }}
                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Correo electrónico
                        </span>

                        <strong>
                            {{
                                $persona
                                    ->correo_personal
                                ?: 'No registrado'
                            }}
                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Teléfono móvil
                        </span>

                        <strong>
                            {{
                                $persona
                                    ->telefono_movil
                                ?: 'No registrado'
                            }}
                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Ciudad
                        </span>

                        <strong>
                            {{
                                $persona
                                    ->ciudad
                                ?: 'No registrada'
                            }}
                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Departamento
                        </span>

                        <strong>
                            {{
                                $persona
                                    ->departamento
                                ?: 'No registrado'
                            }}
                        </strong>

                    </div>

                </div>

            </section>

        </div>


        {{-- ========================================================
            INFORMACIÓN ACADÉMICA
        ======================================================== --}}

        <div class="col-12 col-xl-5">

            <section class="portal-card h-100">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Información académica
                        </h2>

                        <p>
                            Datos principales de tu
                            expediente estudiantil.
                        </p>

                    </div>

                </div>


                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

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


                    <div class="portal-detail-item">

                        <span>
                            Programa
                        </span>

                        <strong>
                            {{
                                $programa
                                    ?->nombre
                                ?: 'Por definir'
                            }}
                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Nivel actual
                        </span>

                        <strong>
                            {{
                                $nivel
                                    ?->nombre
                                ?: 'Por definir'
                            }}
                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Fecha de ingreso
                        </span>

                        <strong>

                            @if (
                                $estudiante
                                    ->fecha_ingreso
                            )

                                {{
                                    $estudiante
                                        ->fecha_ingreso
                                        ->format(
                                            'd/m/Y'
                                        )
                                }}

                            @else

                                No registrada

                            @endif

                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Estado
                        </span>

                        <strong>
                            {{
                                $estudiante->estado
                                === 'activo'
                                    ? 'Activo'
                                    : str(
                                        $estudiante
                                            ->estado
                                    )->title()
                            }}
                        </strong>

                    </div>

                </div>

            </section>

        </div>

    </div>


    {{-- ============================================================
        AYUDA
    ============================================================ --}}

    <section class="portal-card mt-4">

        <div class="p-4">

            <div
                class="
                    d-flex
                    align-items-start
                    gap-3
                "
            >

                <i
                    class="
                        bi
                        bi-info-circle
                        text-primary
                        mt-1
                    "
                ></i>

                <div>

                    <strong class="d-block mb-1">
                        ¿Necesitas actualizar algún dato?
                    </strong>

                    <span class="text-muted">
                        Si encuentras información incorrecta
                        en tu expediente, comunícate con
                        Administración de Edumerican Academy
                        para solicitar la actualización.
                    </span>

                </div>

            </div>

        </div>

    </section>

@endsection