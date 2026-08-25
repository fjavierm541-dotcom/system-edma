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
                Consulta tu información personal,
                docente y académica registrada en EDMA.
            </p>

        </div>

    </div>

@endsection


@section('content')

    @php

        /*
        |--------------------------------------------------------------------------
        | Tipo de documento
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Estado docente
        |--------------------------------------------------------------------------
        */

        $estadoDocente =
            match (
                $docente->estado
            ) {
                'activo' =>
                    'Activo',

                'inactivo' =>
                    'Inactivo',

                default =>
                    str(
                        $docente->estado
                    )
                    ->replace(
                        '_',
                        ' '
                    )
                    ->title(),
            };


        /*
        |--------------------------------------------------------------------------
        | Nivel académico principal
        |--------------------------------------------------------------------------
        */

        $nivelAcademicoPrincipal =
            $formacionPrincipal
                ?->nivel_academico
                ? str(
                    $formacionPrincipal
                        ->nivel_academico
                )
                    ->replace(
                        '_',
                        ' '
                    )
                    ->title()
                    ->toString()
                : null;

    @endphp


    {{-- ============================================================
        IDENTIDAD DEL DOCENTE
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

                {{-- Fotografía --}}
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
                            alt="Fotografía de perfil"
                            class="
                                rounded-circle
                                border
                            "
                            style="
                                width: 110px;
                                height: 110px;
                                object-fit: cover;
                            "
                        >

                    @else

                        <div
                            class="
                                d-flex
                                align-items-center
                                justify-content-center
                                rounded-circle
                                border
                                bg-light
                            "
                            style="
                                width: 110px;
                                height: 110px;
                            "
                        >

                            <i
                                class="
                                    bi
                                    bi-person
                                    fs-1
                                    text-muted
                                "
                            ></i>

                        </div>

                    @endif

                </div>


                {{-- Identidad --}}
                <div class="flex-grow-1">

                    <span
                        class="
                            text-muted
                            d-block
                            mb-1
                        "
                    >
                        Docente
                    </span>

                    <h2 class="mb-1">

                        {{
                            $persona
                                ->nombre_completo
                        }}

                    </h2>


                    {{-- Formación principal --}}
                    @if (
                        $formacionPrincipal
                        &&
                        $formacionPrincipal
                            ->titulo_obtenido
                    )

                        <div
                            class="
                                text-muted
                                mb-2
                            "
                        >
                            <i
                                class="
                                    bi
                                    bi-mortarboard
                                    me-1
                                "
                            ></i>

                            {{
                                $formacionPrincipal
                                    ->titulo_obtenido
                            }}
                        </div>

                    @elseif (
                        $nivelAcademicoPrincipal
                    )

                        <div
                            class="
                                text-muted
                                mb-2
                            "
                        >
                            <i
                                class="
                                    bi
                                    bi-mortarboard
                                    me-1
                                "
                            ></i>

                            {{
                                $nivelAcademicoPrincipal
                            }}
                        </div>

                    @endif


                    <div
                        class="
                            d-flex
                            align-items-center
                            gap-2
                            flex-wrap
                        "
                    >

                        <strong>

                            {{
                                $docente
                                    ->codigo_docente
                            }}

                        </strong>


                        <span
                            class="
                                badge
                                text-bg-{{
                                    $docente->estado
                                    === 'activo'
                                        ? 'success'
                                        : 'secondary'
                                }}
                            "
                        >
                            {{ $estadoDocente }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
        INFORMACIÓN PERSONAL / INFORMACIÓN DOCENTE
    ============================================================ --}}

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
                            Datos de identificación y
                            contacto registrados en tu perfil.
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
                            Teléfono fijo
                        </span>

                        <strong>

                            {{
                                $persona
                                    ->telefono_fijo
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


                    <div class="portal-detail-item">

                        <span>
                            Dirección
                        </span>

                        <strong>

                            {{
                                $persona
                                    ->direccion
                                ?: 'No registrada'
                            }}

                        </strong>

                    </div>

                </div>

            </section>

        </div>


        {{-- ========================================================
            INFORMACIÓN DOCENTE
        ======================================================== --}}

        <div class="col-12 col-xl-5">

            <section class="portal-card h-100">

                <div class="portal-card-header">

                    <div>

                        <h2>
                            Información docente
                        </h2>

                        <p>
                            Datos principales de tu
                            expediente institucional.
                        </p>

                    </div>

                </div>


                <div class="portal-detail-grid">

                    <div class="portal-detail-item">

                        <span>
                            Código docente
                        </span>

                        <strong>

                            {{
                                $docente
                                    ->codigo_docente
                                ?: 'No registrado'
                            }}

                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Especialidad
                        </span>

                        <strong>

                            {{
                                $docente
                                    ->especialidad
                                ?: 'No registrada'
                            }}

                        </strong>

                    </div>


                    <div class="portal-detail-item">

                        <span>
                            Inicio de docencia
                        </span>

                        <strong>

                            @if (
                                $docente
                                    ->fecha_inicio_docencia
                            )

                                {{
                                    $docente
                                        ->fecha_inicio_docencia
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
                            {{ $estadoDocente }}
                        </strong>

                    </div>


                    @if (
                        $empleado
                        &&
                        $empleado->codigo_empleado
                    )

                        <div class="portal-detail-item">

                            <span>
                                Código de empleado
                            </span>

                            <strong>

                                {{
                                    $empleado
                                        ->codigo_empleado
                                }}

                            </strong>

                        </div>

                    @endif

                </div>

            </section>

        </div>

    </div>


    {{-- ============================================================
        FORMACIÓN ACADÉMICA
    ============================================================ --}}

    <section class="portal-card mt-4">

        <div class="portal-card-header">

            <div>

                <h2>
                    Formación académica
                </h2>

                <p>
                    Estudios y títulos registrados
                    en tu expediente institucional.
                </p>

            </div>


            @if ($formaciones->isNotEmpty())

                <span
                    class="
                        badge
                        text-bg-light
                        border
                        text-dark
                    "
                >
                    {{
                        $formaciones
                            ->count()
                    }}

                    {{
                        $formaciones
                            ->count() === 1
                            ? 'registro'
                            : 'registros'
                    }}
                </span>

            @endif

        </div>


        @if ($formaciones->isEmpty())

            <div class="text-center py-5 px-4">

                <i
                    class="
                        bi
                        bi-mortarboard
                        fs-2
                        text-muted
                    "
                ></i>

                <h5 class="mt-3">
                    No hay formación académica registrada
                </h5>

                <p class="text-muted mb-0">
                    Cuando Administración registre
                    información académica en tu expediente,
                    aparecerá en esta sección.
                </p>

            </div>

        @else

            <div class="p-4">

                <div class="row g-4">

                    @foreach (
                        $formaciones
                        as $formacion
                    )

                        @php

                            $nivelAcademico =
                                $formacion
                                    ->nivel_academico
                                    ? str(
                                        $formacion
                                            ->nivel_academico
                                    )
                                        ->replace(
                                            '_',
                                            ' '
                                        )
                                        ->title()
                                        ->toString()
                                    : 'Nivel académico no registrado';

                        @endphp


                        <div
                            class="
                                col-12
                                col-lg-6
                            "
                        >

                            <article
                                class="
                                    border
                                    rounded-3
                                    p-4
                                    h-100
                                "
                            >

                                {{-- ====================================
                                    TÍTULO
                                ==================================== --}}

                                <div
                                    class="
                                        d-flex
                                        justify-content-between
                                        align-items-start
                                        gap-3
                                        mb-4
                                    "
                                >

                                    <div>

                                        <span
                                            class="
                                                text-muted
                                                d-block
                                                mb-1
                                            "
                                        >
                                            {{ $nivelAcademico }}
                                        </span>


                                        <h3 class="mb-0">

                                            {{
                                                $formacion
                                                    ->titulo_obtenido
                                                ?: 'Título no registrado'
                                            }}

                                        </h3>

                                    </div>


                                    @if (
                                        $formacion
                                            ->es_principal
                                    )

                                        <span
                                            class="
                                                badge
                                                text-bg-primary
                                            "
                                        >
                                            <i
                                                class="
                                                    bi
                                                    bi-star-fill
                                                    me-1
                                                "
                                            ></i>

                                            Principal
                                        </span>

                                    @endif

                                </div>


                                {{-- ====================================
                                    DETALLE
                                ==================================== --}}

                                <div class="row g-3">

                                    <div class="col-12">

                                        <span
                                            class="
                                                text-muted
                                                d-block
                                                mb-1
                                            "
                                        >
                                            Institución educativa
                                        </span>

                                        <strong>

                                            {{
                                                $formacion
                                                    ->institucion_educativa
                                                ?: 'No registrada'
                                            }}

                                        </strong>

                                    </div>


                                    <div class="col-6">

                                        <span
                                            class="
                                                text-muted
                                                d-block
                                                mb-1
                                            "
                                        >
                                            Año de graduación
                                        </span>

                                        <strong>

                                            {{
                                                $formacion
                                                    ->anio_graduacion
                                                ?: 'No registrado'
                                            }}

                                        </strong>

                                    </div>


                                    <div class="col-6">

                                        <span
                                            class="
                                                text-muted
                                                d-block
                                                mb-1
                                            "
                                        >
                                            País
                                        </span>

                                        <strong>

                                            {{
                                                $formacion
                                                    ->pais
                                                    ?->nombre
                                                ?: 'No registrado'
                                            }}

                                        </strong>

                                    </div>

                                </div>

                            </article>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </section>


    {{-- ============================================================
        INFORMACIÓN / AYUDA
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
                        o necesitas actualizar tu formación
                        académica, comunícate con Administración
                        de Edumerican Academy.
                    </span>

                </div>

            </div>

        </div>

    </section>

@endsection