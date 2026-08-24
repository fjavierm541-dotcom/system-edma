@extends('layouts.portal')

@section(
    'title',
    'Comprobante de matrícula | Portal EDMA'
)

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión académica
            </span>

            <h1>
                Comprobante de matrícula
            </h1>

            <p>
                Consulta e imprime el comprobante correspondiente
                a tu matrícula académica vigente.
            </p>
        </div>

        @if ($matricula)

            <button
                type="button"
                class="btn portal-btn-primary edma-no-print"
                onclick="window.print()"
            >
                <i class="bi bi-printer me-2"></i>
                Imprimir comprobante
            </button>

        @endif

    </div>

@endsection


@section('content')

    @if (!$matricula)

        <section class="portal-card">

            <div class="edma-comprobante-empty">

                <div class="edma-comprobante-empty__icon">
                    <i class="bi bi-file-earmark-x"></i>
                </div>

                <h2>
                    Sin matrícula activa en este período
                </h2>

                <p>
                    Actualmente no tienes una matrícula activa
                    disponible para generar este comprobante.
                </p>

            </div>

        </section>

    @else

        @php

            $persona =
                $matricula
                    ->estudiante
                    ->persona;

            $grupo =
                $matricula
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

            /*
             * Docente activo del grupo.
             */
            $asignacionDocente =
                $grupo
                    ->docentes
                    ->firstWhere(
                        'activo',
                        true
                    );

            $docente =
                $asignacionDocente
                    ?->docente
                    ?->empleado
                    ?->persona;

            /*
             * Fotografía.
             *
             * En BD se almacena, por ejemplo:
             * personas/fotografias/archivo.jpg
             *
             * Y físicamente está en:
             * storage/app/public/personas/fotografias
             */
            $fotoUrl = null;

            if (!empty($persona->foto_perfil)) {

                $fotoUrl =
                    asset(
                        'storage/'
                        . ltrim(
                            $persona->foto_perfil,
                            '/'
                        )
                    );
            }

            /*
             * Iniciales como respaldo.
             */
            $iniciales =
                collect([
                    $persona->nombres ?? null,
                    $persona->apellidos ?? null,
                ])
                ->filter()
                ->map(
                    fn ($texto) =>
                        mb_strtoupper(
                            mb_substr(
                                trim($texto),
                                0,
                                1
                            )
                        )
                )
                ->implode('');

            if (!$iniciales) {
                $iniciales = 'ED';
            }

            /*
             * Nombre visible del documento.
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

        @endphp


        <article
            class="edma-comprobante"
            id="comprobanteMatricula"
        >

            {{-- ====================================================
                MARCA DE AGUA
                Solo aparece al imprimir.
            ==================================================== --}}

            <div
                class="edma-comprobante__watermark"
                aria-hidden="true"
            >
                <img
                    src="{{
                        asset(
                            'images/brand/logo-edma.png'
                        )
                    }}"
                    alt=""
                >
            </div>


            {{-- ====================================================
                ENCABEZADO
            ==================================================== --}}

            <header class="edma-comprobante__header">

                <div class="edma-comprobante__brand">

                    <div class="edma-comprobante__logo-box">

                        <img
                            src="{{
                                asset(
                                    'images/brand/logo-edma.png'
                                )
                            }}"
                            alt="Edumerican Academy Honduras"
                        >

                    </div>


                    <div>

                        <span class="edma-comprobante__academy">
                            EDUMERICAN ACADEMY HONDURAS
                        </span>

                        <h2>
                            Comprobante de matrícula
                        </h2>

                        <p>
                            Documento de registro académico vigente
                        </p>

                    </div>

                </div>


                <div class="edma-comprobante__status">

                    <span
                        class="edma-comprobante__status-dot"
                    ></span>

                    Matrícula activa

                </div>

            </header>


            {{-- ====================================================
                DATOS GENERALES
            ==================================================== --}}

            <section class="edma-comprobante__section">

                <div class="edma-comprobante__section-heading">

                    <div>
                        <span>
                            Información general
                        </span>

                        <h3>
                            Datos del estudiante
                        </h3>
                    </div>

                    <div class="edma-comprobante__matricula-code">

                        <span>
                            Código de matrícula
                        </span>

                        <strong>
                            {{
                                $matricula
                                    ->codigo_matricula
                            }}
                        </strong>

                    </div>

                </div>


                <div class="edma-student">

                    {{-- Foto --}}
                    <div class="edma-student__photo">

                        @if ($fotoUrl)

                            <img
                                src="{{ $fotoUrl }}"
                                alt="Fotografía de {{ $persona->nombre_completo }}"
                            >

                        @else

                            <div class="edma-student__photo-placeholder">
                                {{ $iniciales }}
                            </div>

                        @endif

                    </div>


                    {{-- Datos --}}
                    <div class="edma-student__data">

                        <div class="edma-data-item">

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


                        <div class="edma-data-item">

                            <span>
                                Código de estudiante
                            </span>

                            <strong>
                                {{
                                    $estudiante
                                        ->codigo_estudiante
                                }}
                            </strong>

                        </div>


                        <div class="edma-data-item">

                            <span>
                                {{ $tipoDocumento }}
                            </span>

                            <strong>
                                {{
                                    $persona
                                        ->numero_documento
                                    ?? 'No registrado'
                                }}
                            </strong>

                        </div>


                        <div class="edma-data-item">

                            <span>
                                Programa
                            </span>

                            <strong>
                                {{
                                    $programa
                                        ->nombre
                                }}
                            </strong>

                        </div>


                        <div class="edma-data-item">

                            <span>
                                Período académico
                            </span>

                            <strong>
                                {{
                                    $periodo
                                        ->nombre
                                }}
                            </strong>

                        </div>


                        <div class="edma-data-item">

                            <span>
                                Año
                            </span>

                            <strong>
                                {{
                                    $periodo
                                        ->fecha_inicio
                                        ?->format('Y')
                                    ??
                                    now()->format('Y')
                                }}
                            </strong>

                        </div>

                    </div>

                </div>

            </section>


            {{-- ====================================================
                MATRÍCULA REALIZADA
            ==================================================== --}}

            <section class="edma-comprobante__section">

                <div class="edma-comprobante__section-heading">

                    <div>
                        <span>
                            Registro académico
                        </span>

                        <h3>
                            Matrícula realizada
                        </h3>
                    </div>

                </div>


                <div class="table-responsive">

                    <table class="edma-comprobante-table">

                        <thead>

                            <tr>
                                <th>
                                    Código nivel
                                </th>

                                <th>
                                    Nivel
                                </th>

                                <th>
                                    Grupo
                                </th>

                                <th>
                                    Sección / Horario
                                </th>

                                <th>
                                    HI
                                </th>

                                <th>
                                    HF
                                </th>

                                <th>
                                    Modalidad
                                </th>

                                <th>
                                    Período
                                </th>
                            </tr>

                        </thead>


                        <tbody>

                            @forelse (
                                $grupo
                                    ->horarios
                                    ->sortBy(
                                        fn ($asignacion) =>
                                            $asignacion
                                                ->horario
                                                ?->hora_inicio
                                    )
                                as $asignacion
                            )

                                <tr>

                                    <td>
                                        {{
                                            $nivel
                                                ->codigo
                                            ?? '—'
                                        }}
                                    </td>


                                    <td>
                                        {{
                                            $nivel
                                                ->nombre
                                        }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{
                                                $grupo
                                                    ->nombre
                                            }}
                                        </strong>

                                        <small>
                                            {{
                                                $grupo
                                                    ->codigo
                                            }}
                                        </small>

                                    </td>


                                    <td>

                                        {{
                                            $asignacion
                                                ->horario
                                                ?->nombre
                                            ??
                                            'Por definir'
                                        }}

                                    </td>


                                    <td>

                                        @if (
                                            $asignacion
                                                ->horario
                                        )

                                            {{
                                                substr(
                                                    $asignacion
                                                        ->horario
                                                        ->hora_inicio,
                                                    0,
                                                    5
                                                )
                                            }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if (
                                            $asignacion
                                                ->horario
                                        )

                                            {{
                                                substr(
                                                    $asignacion
                                                        ->horario
                                                        ->hora_fin,
                                                    0,
                                                    5
                                                )
                                            }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>
                                        {{
                                            ucfirst(
                                                $grupo
                                                    ->modalidad
                                            )
                                        }}
                                    </td>


                                    <td>
                                        {{
                                            $periodo
                                                ->nombre
                                        }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td>
                                        {{
                                            $nivel
                                                ->codigo
                                            ?? '—'
                                        }}
                                    </td>

                                    <td>
                                        {{
                                            $nivel
                                                ->nombre
                                        }}
                                    </td>

                                    <td>

                                        <strong>
                                            {{
                                                $grupo
                                                    ->nombre
                                            }}
                                        </strong>

                                        <small>
                                            {{
                                                $grupo
                                                    ->codigo
                                            }}
                                        </small>

                                    </td>

                                    <td>
                                        Por definir
                                    </td>

                                    <td>
                                        —
                                    </td>

                                    <td>
                                        —
                                    </td>

                                    <td>
                                        {{
                                            ucfirst(
                                                $grupo
                                                    ->modalidad
                                            )
                                        }}
                                    </td>

                                    <td>
                                        {{
                                            $periodo
                                                ->nombre
                                        }}
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </section>


            {{-- ====================================================
                DOCENTE
            ==================================================== --}}

            <section class="edma-comprobante__teacher">

                <div>

                    <span>
                        Docente asignado
                    </span>

                    <strong>
                        {{
                            $docente
                                ?->nombre_completo
                            ??
                            'Por asignar'
                        }}
                    </strong>

                </div>

            </section>


            {{-- ====================================================
                PIE
            ==================================================== --}}

            <footer class="edma-comprobante__footer">

                <div>

                    <strong>
                        Edumerican Academy Honduras
                    </strong>

                    <span>
                        EDMA Portal · Sistema de Gestión Académica
                    </span>

                </div>


                <div>

                    <span>
                        Generado el
                    </span>

                    <strong>
                        {{
                            now()
                                ->format(
                                    'd/m/Y'
                                )
                        }}
                    </strong>

                </div>

            </footer>

        </article>

    @endif

@endsection


@push('styles')

<style>

/*
|--------------------------------------------------------------------------
| Vista normal
|--------------------------------------------------------------------------
| Más adelante moveremos estos estilos al archivo CSS global del Portal.
|--------------------------------------------------------------------------
*/

.edma-comprobante {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--portal-border);
    border-radius: var(--portal-radius-lg);
    background: #fff;
    box-shadow: var(--portal-shadow);
}

.edma-comprobante__watermark {
    display: none;
}


/* Header */

.edma-comprobante__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 20px 24px;
    background:
        linear-gradient(
            135deg,
            var(--portal-primary-dark),
            var(--portal-primary)
        );
    color: #fff;
}

.edma-comprobante__brand {
    display: flex;
    align-items: center;
    gap: 14px;
}

.edma-comprobante__logo-box {
    display: grid;
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    place-items: center;
    overflow: hidden;
    border-radius: 50%;
    background: #fff;
}

.edma-comprobante__logo-box img {
    width: 38px;
    height: 38px;
    object-fit: contain;
}

.edma-comprobante__academy {
    display: block;
    margin-bottom: 2px;
    color: var(--portal-accent);
    font-size: .63rem;
    font-weight: 800;
    letter-spacing: .08em;
}

.edma-comprobante__brand h2 {
    margin: 0;
    font-size: 1.2rem;
}

.edma-comprobante__brand p {
    margin: 2px 0 0;
    color: rgba(255,255,255,.72);
    font-size: .65rem;
}

.edma-comprobante__status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 999px;
    background: rgba(255,255,255,.10);
    font-size: .68rem;
    font-weight: 700;
}

.edma-comprobante__status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #4bd692;
    box-shadow:
        0 0 0 4px
        rgba(75,214,146,.14);
}


/* Secciones */

.edma-comprobante__section {
    padding: 18px 24px;
    border-bottom: 1px solid var(--portal-border);
}

.edma-comprobante__section-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 14px;
}

.edma-comprobante__section-heading span,
.edma-comprobante__matricula-code span {
    display: block;
    color: var(--portal-text-muted);
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
}

.edma-comprobante__section-heading h3 {
    margin: 2px 0 0;
    color: var(--portal-primary-dark);
    font-size: .88rem;
}

.edma-comprobante__matricula-code {
    text-align: right;
}

.edma-comprobante__matricula-code strong {
    display: block;
    margin-top: 2px;
    color: var(--portal-primary);
    font-size: .7rem;
}


/* Estudiante */

.edma-student {
    display: grid;
    grid-template-columns: 92px 1fr;
    gap: 20px;
    align-items: center;
}

.edma-student__photo {
    display: grid;
    width: 82px;
    height: 100px;
    place-items: center;
    overflow: hidden;
    border: 1px solid var(--portal-border);
    border-radius: 10px;
    background: var(--portal-surface-soft);
}

.edma-student__photo img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.edma-student__photo-placeholder {
    display: grid;
    width: 100%;
    height: 100%;
    place-items: center;
    color: var(--portal-primary);
    font-size: 1.35rem;
    font-weight: 800;
}

.edma-student__data {
    display: grid;
    grid-template-columns:
        repeat(3, minmax(0, 1fr));
    gap: 13px 20px;
}

.edma-data-item {
    display: flex;
    min-width: 0;
    flex-direction: column;
}

.edma-data-item span,
.edma-comprobante__teacher span,
.edma-comprobante__footer span {
    color: var(--portal-text-muted);
    font-size: .59rem;
}

.edma-data-item strong,
.edma-comprobante__teacher strong {
    margin-top: 2px;
    color: var(--portal-primary-dark);
    font-size: .7rem;
}


/* Tabla */

.edma-comprobante-table {
    width: 100%;
    border-collapse: collapse;
}

.edma-comprobante-table th {
    padding: 8px 7px;
    border-bottom: 2px solid var(--portal-primary);
    background: var(--portal-primary-soft);
    color: var(--portal-primary-dark);
    font-size: .57rem;
    font-weight: 750;
    text-align: left;
    white-space: nowrap;
}

.edma-comprobante-table td {
    padding: 9px 7px;
    border-bottom: 1px solid var(--portal-border);
    color: #405161;
    font-size: .62rem;
    vertical-align: top;
}

.edma-comprobante-table td strong {
    display: block;
    color: var(--portal-primary-dark);
    font-size: .63rem;
}

.edma-comprobante-table td small {
    display: block;
    margin-top: 1px;
    color: var(--portal-text-muted);
    font-size: .54rem;
}


/* Docente */

.edma-comprobante__teacher {
    padding: 13px 24px;
    border-bottom: 1px solid var(--portal-border);
    background: var(--portal-surface-soft);
}

.edma-comprobante__teacher > div {
    display: flex;
    flex-direction: column;
}


/* Footer */

.edma-comprobante__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 13px 24px;
}

.edma-comprobante__footer > div {
    display: flex;
    flex-direction: column;
}

.edma-comprobante__footer strong {
    color: var(--portal-primary-dark);
    font-size: .61rem;
}


/* Sin matrícula */

.edma-comprobante-empty {
    padding: 60px 20px;
    text-align: center;
}

.edma-comprobante-empty__icon {
    display: grid;
    width: 58px;
    height: 58px;
    margin: 0 auto 15px;
    place-items: center;
    border-radius: 14px;
    background: var(--portal-primary-soft);
    color: var(--portal-primary);
    font-size: 1.35rem;
}

.edma-comprobante-empty h2 {
    color: var(--portal-primary-dark);
    font-size: 1rem;
}

.edma-comprobante-empty p {
    margin: 0 auto;
    max-width: 480px;
    color: var(--portal-text-muted);
}


/* Responsive */

@media (max-width: 767.98px) {

    .edma-comprobante__header {
        align-items: flex-start;
        flex-direction: column;
    }

    .edma-student {
        grid-template-columns: 1fr;
    }

    .edma-student__data {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 575.98px) {

    .edma-student__data {
        grid-template-columns: 1fr;
    }

}


/*
|--------------------------------------------------------------------------
| Impresión
|--------------------------------------------------------------------------
*/

@media print {

    @page {
        size: A4 landscape;
        margin: 7mm;
    }

    html,
    body {
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    .portal-sidebar,
    .portal-navbar,
    .portal-footer,
    .portal-page-heading,
    .edma-no-print,
    nav,
    aside {
        display: none !important;
    }

    .portal-main,
    .portal-content,
    main,
    .container,
    .container-fluid {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .edma-comprobante {
        position: relative;
        width: 100%;
        overflow: visible;
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;

        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    /*
    | Marca de agua solo al imprimir.
    */
    .edma-comprobante__watermark {
        position: absolute;
        top: 52%;
        left: 50%;
        z-index: 0;
        display: block;
        transform:
            translate(-50%, -50%);
        opacity: .035;
        pointer-events: none;
    }

    .edma-comprobante__watermark img {
        width: 280px;
        height: auto;
    }

    .edma-comprobante__header,
    .edma-comprobante__section,
    .edma-comprobante__teacher,
    .edma-comprobante__footer {
        position: relative;
        z-index: 1;
    }

    /*
    | Compactamos especialmente para que
    | todo entre en una sola hoja.
    */
    .edma-comprobante__header {
        padding: 12px 16px !important;
        background:
            linear-gradient(
                135deg,
                #0b2746,
                #123d6a
            ) !important;
    }

    .edma-comprobante__logo-box {
        width: 38px;
        height: 38px;
    }

    .edma-comprobante__logo-box img {
        width: 31px;
        height: 31px;
    }

    .edma-comprobante__brand h2 {
        font-size: 1rem;
    }

    .edma-comprobante__brand p {
        font-size: .56rem;
    }

    .edma-comprobante__status {
        padding: 6px 10px;
        font-size: .58rem;
    }

    .edma-comprobante__section {
        padding: 11px 16px !important;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .edma-comprobante__section-heading {
        margin-bottom: 8px;
    }

    .edma-student {
        grid-template-columns: 68px 1fr;
        gap: 14px;
    }

    .edma-student__photo {
        width: 62px;
        height: 76px;
    }

    .edma-student__data {
        gap: 7px 16px;
    }

    .edma-data-item span {
        font-size: .5rem;
    }

    .edma-data-item strong {
        font-size: .6rem;
    }

    .edma-comprobante-table {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .edma-comprobante-table th {
        padding: 6px;
        background: #eaf1f8 !important;
        font-size: .49rem;
    }

    .edma-comprobante-table td {
        padding: 7px 6px;
        font-size: .54rem;
    }

    .edma-comprobante__teacher {
        padding: 8px 16px;
    }

    .edma-comprobante__footer {
        padding: 8px 16px;
    }

}

</style>

@endpush