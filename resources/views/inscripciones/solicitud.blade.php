@extends('layouts.web')

@section('title', 'Solicitud de inscripción | Edumerican Academy')

@section('content')

@php
    $puedeSolicitar = $periodosDisponibles->count() === 1;

    $periodoActual = $puedeSolicitar
        ? $periodosDisponibles->first()
        : null;
@endphp

<style>
    .edma-application {
        min-height: 100vh;
        padding: 10rem 0 5rem;
        background:
            radial-gradient(
                circle at top right,
                rgba(47, 103, 173, .12),
                transparent 30%
            ),
            linear-gradient(
                135deg,
                #f7faff 0%,
                #eef5ff 48%,
                #fbfcff 100%
            );
    }

    .edma-application-container {
        width: min(1180px, calc(100% - 32px));
        margin: 0 auto;
    }

    .edma-application-header {
        max-width: 760px;
        margin: 0 auto 2.5rem;
        text-align: center;
    }

    .edma-application-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: 1rem;
        padding: .5rem .85rem;
        border: 1px solid rgba(18, 59, 117, .1);
        border-radius: 999px;
        color: #2f67ad;
        background: rgba(255, 255, 255, .68);
        font-size: .78rem;
        font-weight: 750;
        backdrop-filter: blur(14px);
    }

    .edma-application-eyebrow span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #d6aa4a;
        box-shadow:
            0 0 0 5px rgba(214, 170, 74, .14);
    }

    .edma-application-header h1 {
        margin: 0;
        color: #123b75;
        font-size: clamp(2rem, 5vw, 3.3rem);
        font-weight: 850;
        letter-spacing: -.04em;
    }

    .edma-application-header p {
        max-width: 650px;
        margin: 1rem auto 0;
        color: #68788d;
        font-size: 1rem;
        line-height: 1.7;
    }

    .edma-application-shell {
        overflow: hidden;
        border: 1px solid rgba(18, 59, 117, .1);
        border-radius: 1.6rem;
        background: rgba(255, 255, 255, .88);
        box-shadow:
            0 28px 70px rgba(18, 59, 117, .11);
        backdrop-filter: blur(20px);
    }

    .edma-application-progress {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(18, 59, 117, .08);
        background: rgba(247, 250, 255, .75);
    }

    .edma-step-list {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: .6rem;
    }

    .edma-step-indicator {
        position: relative;
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: .7rem;
        border-radius: .85rem;
        color: #8592a2;
        font-size: .72rem;
        font-weight: 700;
        transition: .2s ease;
    }

    .edma-step-indicator-number {
        display: grid;
        width: 30px;
        height: 30px;
        flex-shrink: 0;
        place-items: center;
        border-radius: 50%;
        background: #eef3f9;
        font-size: .72rem;
    }

    .edma-step-indicator.active {
        color: #123b75;
        background: rgba(47, 103, 173, .08);
    }

    .edma-step-indicator.active
    .edma-step-indicator-number {
        color: white;
        background:
            linear-gradient(
                135deg,
                #2f67ad,
                #123b75
            );
    }

    .edma-step-indicator.completed
    .edma-step-indicator-number {
        color: white;
        background: #23865e;
    }

    .edma-application-body {
        padding: 2rem;
    }

    .edma-form-step {
        display: none;
        animation: edmaStepIn .25s ease;
    }

    .edma-form-step.active {
        display: block;
    }

    @keyframes edmaStepIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .edma-form-heading {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.8rem;
    }

    .edma-form-heading-icon {
        display: grid;
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        place-items: center;
        border-radius: 1rem;
        color: #123b75;
        background: rgba(47, 103, 173, .1);
        font-size: 1.2rem;
    }

    .edma-form-heading h2 {
        margin: 0;
        color: #123b75;
        font-size: 1.25rem;
        font-weight: 800;
    }

    .edma-form-heading p {
        margin: .3rem 0 0;
        color: #7a8898;
        font-size: .83rem;
        line-height: 1.5;
    }

    .edma-form-label {
        margin-bottom: .45rem;
        color: #17375e;
        font-size: .82rem;
        font-weight: 700;
    }

    .edma-required {
        color: #c33f4d;
    }

    .edma-form-control {
        min-height: 48px;
        border: 1px solid #dce4ee;
        border-radius: .8rem;
        background: rgba(255, 255, 255, .92);
        font-size: .88rem;
    }

    textarea.edma-form-control {
        min-height: auto;
    }

    .edma-form-control:focus {
        border-color: #2f67ad;
        box-shadow:
            0 0 0 .2rem rgba(47, 103, 173, .1);
    }

    .edma-form-help {
        margin-top: .4rem;
        color: #8a96a4;
        font-size: .72rem;
        line-height: 1.5;
    }

    .edma-info-panel {
        padding: 1rem 1.1rem;
        border: 1px solid rgba(47, 103, 173, .12);
        border-radius: 1rem;
        color: #44566d;
        background: rgba(47, 103, 173, .055);
        font-size: .8rem;
        line-height: 1.6;
    }

    .edma-info-panel strong {
        color: #123b75;
    }

    .edma-segment-result {
        display: none;
        margin-top: 1rem;
    }

    .edma-responsible-section {
        display: none;
    }

    .edma-responsible-section.visible {
        display: block;
    }

    .edma-payment-upload {
        position: relative;
        padding: 2rem;
        border: 2px dashed #ccd8e5;
        border-radius: 1rem;
        text-align: center;
        background: #fafcff;
    }

    .edma-payment-upload i {
        display: block;
        margin-bottom: .7rem;
        color: #2f67ad;
        font-size: 2rem;
    }

    .edma-payment-upload strong {
        display: block;
        color: #123b75;
    }

    .edma-payment-upload small {
        display: block;
        margin-top: .4rem;
        color: #8794a2;
    }

    .edma-application-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.4rem;
        border-top: 1px solid #edf0f4;
    }

    .edma-btn {
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        padding: .7rem 1.25rem;
        border-radius: .8rem;
        font-size: .84rem;
        font-weight: 750;
    }

    .edma-btn-primary {
        border: 1px solid #123b75;
        color: white;
        background:
            linear-gradient(
                135deg,
                #2f67ad,
                #123b75
            );
    }

    .edma-btn-primary:hover {
        color: white;
        transform: translateY(-1px);
    }

    .edma-btn-secondary {
        border: 1px solid #d9e1ea;
        color: #123b75;
        background: white;
    }

    .edma-review-card {
        height: 100%;
        padding: 1rem;
        border: 1px solid #e4e9ef;
        border-radius: 1rem;
        background: #fbfcfe;
    }

    .edma-review-card span {
        display: block;
        margin-bottom: .3rem;
        color: #8a96a4;
        font-size: .7rem;
    }

    .edma-review-card strong {
        color: #17375e;
        font-size: .85rem;
    }

    @media (max-width: 991.98px) {
        .edma-step-list {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 575.98px) {
        .edma-application {
            padding-top: 8rem;
        }

        .edma-step-list {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .edma-step-indicator {
            font-size: .65rem;
        }

        .edma-application-body {
            padding: 1.25rem;
        }

        .edma-application-actions {
            align-items: stretch;
            flex-direction: column-reverse;
        }

        .edma-btn {
            width: 100%;
        }
    }
</style>


<section class="edma-application">

    <div class="edma-application-container">

        <header class="edma-application-header">

            <div class="edma-application-eyebrow">
                <span></span>
                Admisiones EDMA
            </div>

            <h1>
                Solicitud de inscripción
            </h1>

            <p>
                Complete la información solicitada para iniciar
                su proceso de ingreso a Edumerican Academy Honduras.
                El envío de esta solicitud no constituye todavía
                una matrícula.
            </p>

        </header>

        @if (session('error'))

            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>

        @endif

        @if ($errors->any())

            <div class="alert alert-danger mb-4">

                <strong>
                    Revise la información ingresada.
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

        @if (!$puedeSolicitar)

            <div class="edma-application-shell">

                <div class="edma-application-body">

                    <div class="text-center py-5">

                        <div class="edma-form-heading-icon mx-auto mb-3">
                            <i class="bi bi-calendar-x"></i>
                        </div>

                        @if ($periodosDisponibles->isEmpty())

                            <h2>
                                Solicitudes temporalmente no disponibles
                            </h2>

                            <p class="text-muted">
                                Actualmente no existe un período habilitado
                                para recibir nuevas solicitudes de inscripción.
                            </p>

                        @else

                            <h2>
                                Configuración académica pendiente
                            </h2>

                            <p class="text-muted">
                                Existen varios períodos disponibles.
                                El equipo administrativo debe definir
                                el período correspondiente antes de
                                recibir nuevas solicitudes.
                            </p>

                        @endif

                        <a
                            href="{{ route('website.admissions') }}"
                            class="btn edma-btn edma-btn-secondary mt-3"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Volver a inscripciones
                        </a>

                    </div>

                </div>

            </div>

        @else

            <div class="edma-application-shell">

                <div class="edma-application-progress">

                    <div class="edma-step-list">

                        @foreach ([
                            'Datos personales',
                            'Contacto',
                            'Información académica',
                            'Responsable',
                            'Pago',
                            'Revisión'
                        ] as $index => $titulo)

                            <div
                                class="edma-step-indicator
                                    {{ $index === 0 ? 'active' : '' }}"
                                data-step-indicator="{{ $index + 1 }}"
                            >

                                <span class="edma-step-indicator-number">
                                    {{ $index + 1 }}
                                </span>

                                <span>
                                    {{ $titulo }}
                                </span>

                            </div>

                        @endforeach

                    </div>

                </div>

                <form
                    action="{{ route('inscripciones.solicitud.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="applicationForm"
                    novalidate
                >
                    @csrf

                    <div class="edma-application-body">

                        {{-- ===================================================
                             PASO 1
                             =================================================== --}}
                        <section
                            class="edma-form-step active"
                            data-step="1"
                        >

                            <div class="edma-form-heading">

                                <div class="edma-form-heading-icon">
                                    <i class="bi bi-person-vcard"></i>
                                </div>

                                <div>
                                    <h2>
                                        Datos personales
                                    </h2>

                                    <p>
                                        Ingrese la información de la persona
                                        que desea formar parte de EDMA.
                                    </p>
                                </div>

                            </div>

                            <div class="row g-3">

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Primer nombre
                                        <span class="edma-required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="primer_nombre"
                                        value="{{ old('primer_nombre') }}"
                                        class="form-control edma-form-control"
                                        maxlength="80"
                                        required
                                    >

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Segundo nombre
                                    </label>

                                    <input
                                        type="text"
                                        name="segundo_nombre"
                                        value="{{ old('segundo_nombre') }}"
                                        class="form-control edma-form-control"
                                        maxlength="80"
                                    >

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Primer apellido
                                        <span class="edma-required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="primer_apellido"
                                        value="{{ old('primer_apellido') }}"
                                        class="form-control edma-form-control"
                                        maxlength="80"
                                        required
                                    >

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Segundo apellido
                                    </label>

                                    <input
                                        type="text"
                                        name="segundo_apellido"
                                        value="{{ old('segundo_apellido') }}"
                                        class="form-control edma-form-control"
                                        maxlength="80"
                                    >

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Fecha de nacimiento
                                        <span class="edma-required">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        name="fecha_nacimiento"
                                        id="fechaNacimiento"
                                        value="{{ old('fecha_nacimiento') }}"
                                        max="{{ now()->subYears(7)->format('Y-m-d') }}"
                                        class="form-control edma-form-control"
                                        required
                                    >

                                    <div class="edma-form-help">
                                        EDMA recibe estudiantes a partir
                                        de los 7 años.
                                    </div>

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Sexo
                                        <span class="edma-required">*</span>
                                    </label>

                                    <select
                                        name="sexo"
                                        class="form-select edma-form-control"
                                        required
                                    >

                                        <option value="">
                                            Seleccione una opción
                                        </option>

                                        <option
                                            value="masculino"
                                            @selected(
                                                old('sexo')
                                                === 'masculino'
                                            )
                                        >
                                            Masculino
                                        </option>

                                        <option
                                            value="femenino"
                                            @selected(
                                                old('sexo')
                                                === 'femenino'
                                            )
                                        >
                                            Femenino
                                        </option>

                                    </select>

                                </div>

                                <div class="col-12">

                                    <div
                                        class="edma-info-panel edma-segment-result"
                                        id="segmentResult"
                                    >
                                        <strong id="segmentTitle"></strong>

                                        <div id="segmentDescription"></div>
                                    </div>

                                    <input
                                        type="hidden"
                                        name="segmento_solicitado"
                                        id="segmentoSolicitado"
                                        value="{{ old('segmento_solicitado') }}"
                                    >

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Tipo de documento
                                        <span class="edma-required">*</span>
                                    </label>

                                    <select
                                        name="tipo_documento"
                                        class="form-select edma-form-control"
                                        required
                                    >

                                        <option value="">
                                            Seleccione
                                        </option>

                                        <option
                                            value="dni"
                                            @selected(
                                                old('tipo_documento')
                                                === 'dni'
                                            )
                                        >
                                            DNI
                                        </option>

                                        <option
                                            value="identidad_menor"
                                            @selected(
                                                old('tipo_documento')
                                                === 'identidad_menor'
                                            )
                                        >
                                            Identificación de menor
                                        </option>

                                        <option
                                            value="pasaporte"
                                            @selected(
                                                old('tipo_documento')
                                                === 'pasaporte'
                                            )
                                        >
                                            Pasaporte
                                        </option>

                                    </select>

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Número de documento
                                        <span class="edma-required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="numero_documento"
                                        value="{{ old('numero_documento') }}"
                                        class="form-control edma-form-control"
                                        maxlength="50"
                                        required
                                    >

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Nacionalidad
                                    </label>

                                    <input
                                        type="text"
                                        name="nacionalidad"
                                        value="{{ old('nacionalidad') }}"
                                        class="form-control edma-form-control"
                                        maxlength="100"
                                    >

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        País de residencia
                                        <span class="edma-required">*</span>
                                    </label>

                                    <select
                                        name="pais_residencia_id"
                                        class="form-select edma-form-control"
                                        required
                                    >

                                        <option value="">
                                            Seleccione
                                        </option>

                                        @foreach ($paises as $pais)

                                            <option
                                                value="{{ $pais->id }}"
                                                @selected(
                                                    (string) old(
                                                        'pais_residencia_id'
                                                    )
                                                    ===
                                                    (string) $pais->id
                                                )
                                            >
                                                {{ $pais->nombre }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </section>

                        {{-- ===================================================
                             PASO 2
                             =================================================== --}}
                        <section
                            class="edma-form-step"
                            data-step="2"
                        >

                            <div class="edma-form-heading">

                                <div class="edma-form-heading-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>

                                <div>

                                    <h2 id="contactStepTitle">
                                        Información de contacto
                                    </h2>

                                    <p id="contactStepDescription">
                                        Estos datos permitirán a EDMA
                                        comunicarse durante el proceso.
                                    </p>

                                </div>

                            </div>

                            <div
                                class="edma-info-panel mb-4"
                                id="minorContactNotice"
                                style="display:none;"
                            >
                                Si el aspirante es menor de edad, puede
                                registrar su propio correo y teléfono si
                                dispone de ellos. Los datos obligatorios
                                de contacto se solicitarán en el apartado
                                del responsable.
                            </div>

                            <div class="row g-3">

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">

                                        Correo electrónico

                                        <span
                                            class="edma-required"
                                            id="aspirantEmailRequired"
                                        >
                                            *
                                        </span>

                                    </label>

                                    <input
                                        type="email"
                                        name="correo_personal"
                                        id="aspirantEmail"
                                        value="{{ old('correo_personal') }}"
                                        class="form-control edma-form-control"
                                        maxlength="150"
                                    >

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">

                                        Teléfono móvil

                                        <span
                                            class="edma-required"
                                            id="aspirantPhoneRequired"
                                        >
                                            *
                                        </span>

                                    </label>

                                    <input
                                        type="tel"
                                        name="telefono_movil"
                                        id="aspirantPhone"
                                        value="{{ old('telefono_movil') }}"
                                        class="form-control edma-form-control"
                                        maxlength="30"
                                    >

                                </div>

                                <div class="col-12">

                                    <div class="form-check form-switch">

                                        <input
                                            type="checkbox"
                                            name="telefono_movil_whatsapp"
                                            value="1"
                                            id="telefonoWhatsapp"
                                            class="form-check-input"
                                            @checked(
                                                old(
                                                    'telefono_movil_whatsapp'
                                                )
                                            )
                                        >

                                        <label
                                            for="telefonoWhatsapp"
                                            class="form-check-label"
                                        >
                                            Este número también está
                                            disponible en WhatsApp.
                                        </label>

                                    </div>

                                </div>

                                <div class="col-12">

                                    <label class="edma-form-label">
                                        Dirección de residencia
                                        <span class="edma-required">*</span>
                                    </label>

                                    <textarea
                                        name="direccion"
                                        rows="3"
                                        maxlength="500"
                                        class="form-control edma-form-control"
                                        required
                                    >{{ old('direccion') }}</textarea>

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Ciudad o municipio
                                        <span class="edma-required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="ciudad_municipio"
                                        value="{{ old('ciudad_municipio') }}"
                                        class="form-control edma-form-control"
                                        maxlength="120"
                                        required
                                    >

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Departamento o estado
                                        <span class="edma-required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="departamento_estado"
                                        value="{{ old('departamento_estado') }}"
                                        class="form-control edma-form-control"
                                        maxlength="120"
                                        required
                                    >

                                </div>

                            </div>

                        </section>

                        {{-- ===================================================
                             PASO 3
                             =================================================== --}}
                        <section
                            class="edma-form-step"
                            data-step="3"
                        >

                            <div class="edma-form-heading">

                                <div class="edma-form-heading-icon">
                                    <i class="bi bi-mortarboard"></i>
                                </div>

                                <div>

                                    <h2>
                                        Información académica
                                    </h2>

                                    <p>
                                        Indique el programa y el nivel al que
                                        considera que podría ingresar.
                                    </p>

                                </div>

                            </div>

                            <div class="edma-info-panel mb-4">

                                <strong>
                                    Importante:
                                </strong>

                                todos los estudiantes nuevos ingresan
                                inicialmente con nivel autorizado A0.
                                Si selecciona un nivel superior, EDMA podrá
                                solicitar posteriormente una prueba de ubicación.

                            </div>

                            <div class="row g-3">

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Programa
                                        <span class="edma-required">*</span>
                                    </label>

                                    <select
                                        name="programa_id"
                                        id="programaSelect"
                                        class="form-select edma-form-control"
                                        required
                                    >

                                        <option value="">
                                            Seleccione un programa
                                        </option>

                                        @foreach ($programas as $programa)

                                            <option
                                                value="{{ $programa->id }}"
                                                data-segmento="{{ $programa->segmento }}"
                                                @selected(
                                                    (string) old(
                                                        'programa_id'
                                                    )
                                                    ===
                                                    (string) $programa->id
                                                )
                                            >
                                                {{ $programa->nombre }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-12 col-md-6">

                                    <label class="edma-form-label">
                                        Nivel que considera adecuado
                                        <span class="edma-required">*</span>
                                    </label>

                                    <select
                                        name="nivel_solicitado_id"
                                        id="nivelSelect"
                                        class="form-select edma-form-control"
                                        required
                                    >

                                        <option value="">
                                            Seleccione un nivel
                                        </option>

                                        @foreach ($niveles as $nivel)

                                            <option
                                                value="{{ $nivel->id }}"
                                                data-programa="{{ $nivel->programa_id }}"
                                                data-codigo="{{ $nivel->codigo }}"
                                                @selected(
                                                    (string) old(
                                                        'nivel_solicitado_id'
                                                    )
                                                    ===
                                                    (string) $nivel->id
                                                )
                                            >
                                                {{ $nivel->nombre }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-12">

                                    <div
                                        class="edma-info-panel"
                                        id="placementNotice"
                                        style="display:none;"
                                    ></div>

                                </div>

                                <div class="col-12">

                                    <label class="edma-form-label">
                                        ¿Cómo conoció EDMA?
                                    </label>

                                    <select
                                        name="fuente_referencia_tipo"
                                        id="referenceType"
                                        class="form-select edma-form-control"
                                    >

                                        <option value="">
                                            Seleccione una opción
                                        </option>

                                        <option
                                            value="redes_sociales"
                                            @selected(
                                                old('fuente_referencia_tipo')
                                                === 'redes_sociales'
                                            )
                                        >
                                            Redes sociales
                                        </option>

                                        <option
                                            value="recomendacion_estudiante"
                                            @selected(
                                                old('fuente_referencia_tipo')
                                                === 'recomendacion_estudiante'
                                            )
                                        >
                                            Recomendación de un estudiante de EDMA
                                        </option>

                                        <option
                                            value="recomendacion_familiar"
                                            @selected(
                                                old('fuente_referencia_tipo')
                                                === 'recomendacion_familiar'
                                            )
                                        >
                                            Recomendación de un familiar o conocido
                                        </option>

                                        <option
                                            value="publicidad_internet"
                                            @selected(
                                                old('fuente_referencia_tipo')
                                                === 'publicidad_internet'
                                            )
                                        >
                                            Publicidad en internet
                                        </option>

                                        <option
                                            value="volante"
                                            @selected(
                                                old('fuente_referencia_tipo')
                                                === 'volante'
                                            )
                                        >
                                            Volante o material impreso
                                        </option>

                                        <option
                                            value="evento_edma"
                                            @selected(
                                                old('fuente_referencia_tipo')
                                                === 'evento_edma'
                                            )
                                        >
                                            Evento o actividad de EDMA
                                        </option>

                                        <option
                                            value="busqueda_internet"
                                            @selected(
                                                old('fuente_referencia_tipo')
                                                === 'busqueda_internet'
                                            )
                                        >
                                            Búsqueda en internet
                                        </option>

                                        <option
                                            value="otro"
                                            @selected(
                                                old('fuente_referencia_tipo')
                                                === 'otro'
                                            )
                                        >
                                            Otro
                                        </option>

                                    </select>

                                    <input
                                        type="hidden"
                                        name="fuente_referencia_id"
                                        value=""
                                    >

                                </div>

                                <div
                                    class="col-12"
                                    id="otherReferenceContainer"
                                    style="display:none;"
                                >

                                    <label class="edma-form-label">
                                        Especifique cómo conoció EDMA
                                        <span class="edma-required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="fuente_referencia_otro"
                                        id="otherReference"
                                        value="{{ old('fuente_referencia_otro') }}"
                                        class="form-control edma-form-control"
                                        maxlength="150"
                                    >

                                </div>

                                <div class="col-12">

                                    <label class="edma-form-label">
                                        Observaciones
                                    </label>

                                    <textarea
                                        name="observaciones_solicitante"
                                        rows="3"
                                        maxlength="2000"
                                        class="form-control edma-form-control"
                                        placeholder="Puede agregar información que considere importante..."
                                    >{{ old('observaciones_solicitante') }}</textarea>

                                </div>

                            </div>

                        </section>

                        {{-- ===================================================
                             PASO 4
                             =================================================== --}}
                        <section
                            class="edma-form-step"
                            data-step="4"
                        >

                            <div class="edma-form-heading">

                                <div class="edma-form-heading-icon">
                                    <i class="bi bi-people"></i>
                                </div>

                                <div>

                                    <h2>
                                        Responsable
                                    </h2>

                                    <p>
                                        Esta información se solicitará
                                        únicamente cuando corresponda.
                                    </p>

                                </div>

                            </div>

                            <div
                                class="edma-info-panel"
                                id="adultNotice"
                            >
                                Según la fecha de nacimiento registrada,
                                el aspirante es mayor de edad y no necesita
                                registrar un responsable obligatorio.
                            </div>

                            <div
                                class="edma-responsible-section"
                                id="responsibleFields"
                            >

                                <div class="edma-info-panel mb-4">
                                    El aspirante es menor de 18 años.
                                    Debe registrar los datos de su responsable.
                                </div>

                                <div class="row g-3">

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            Primer nombre
                                            <span class="edma-required">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            name="responsable_primer_nombre"
                                            value="{{ old('responsable_primer_nombre') }}"
                                            class="form-control edma-form-control responsible-required"
                                            maxlength="80"
                                        >

                                    </div>

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            Segundo nombre
                                        </label>

                                        <input
                                            type="text"
                                            name="responsable_segundo_nombre"
                                            value="{{ old('responsable_segundo_nombre') }}"
                                            class="form-control edma-form-control"
                                            maxlength="80"
                                        >

                                    </div>

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            Primer apellido
                                            <span class="edma-required">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            name="responsable_primer_apellido"
                                            value="{{ old('responsable_primer_apellido') }}"
                                            class="form-control edma-form-control responsible-required"
                                            maxlength="80"
                                        >

                                    </div>

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            Segundo apellido
                                        </label>

                                        <input
                                            type="text"
                                            name="responsable_segundo_apellido"
                                            value="{{ old('responsable_segundo_apellido') }}"
                                            class="form-control edma-form-control"
                                            maxlength="80"
                                        >

                                    </div>

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            Tipo de documento
                                            <span class="edma-required">*</span>
                                        </label>

                                        <select
                                            name="responsable_tipo_documento"
                                            class="form-select edma-form-control responsible-required"
                                        >

                                            <option value="">
                                                Seleccione
                                            </option>

                                            <option
                                                value="dni"
                                                @selected(
                                                    old(
                                                        'responsable_tipo_documento'
                                                    ) === 'dni'
                                                )
                                            >
                                                DNI
                                            </option>

                                            <option
                                                value="pasaporte"
                                                @selected(
                                                    old(
                                                        'responsable_tipo_documento'
                                                    ) === 'pasaporte'
                                                )
                                            >
                                                Pasaporte
                                            </option>

                                        </select>

                                    </div>

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            Número de documento
                                            <span class="edma-required">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            name="responsable_numero_documento"
                                            value="{{ old('responsable_numero_documento') }}"
                                            class="form-control edma-form-control responsible-required"
                                            maxlength="50"
                                        >

                                    </div>

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            Correo electrónico
                                            <span class="edma-required">*</span>
                                        </label>

                                        <input
                                            type="email"
                                            name="responsable_correo"
                                            value="{{ old('responsable_correo') }}"
                                            class="form-control edma-form-control responsible-required"
                                            maxlength="150"
                                        >

                                    </div>

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            Teléfono
                                            <span class="edma-required">*</span>
                                        </label>

                                        <input
                                            type="tel"
                                            name="responsable_telefono"
                                            value="{{ old('responsable_telefono') }}"
                                            class="form-control edma-form-control responsible-required"
                                            maxlength="30"
                                        >

                                    </div>

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            País de residencia
                                            <span class="edma-required">*</span>
                                        </label>

                                        <select
                                            name="responsable_pais_residencia_id"
                                            class="form-select edma-form-control responsible-required"
                                        >

                                            <option value="">
                                                Seleccione
                                            </option>

                                            @foreach ($paises as $pais)

                                                <option
                                                    value="{{ $pais->id }}"
                                                    @selected(
                                                        (string) old(
                                                            'responsable_pais_residencia_id'
                                                        )
                                                        ===
                                                        (string) $pais->id
                                                    )
                                                >
                                                    {{ $pais->nombre }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="col-12 col-md-6">

                                        <label class="edma-form-label">
                                            Parentesco
                                            <span class="edma-required">*</span>
                                        </label>

                                        <select
                                            name="parentesco"
                                            class="form-select edma-form-control responsible-required"
                                        >

                                            <option value="">
                                                Seleccione
                                            </option>

                                            @foreach ([
                                                'Madre',
                                                'Padre',
                                                'Tutor legal',
                                                'Abuela',
                                                'Abuelo',
                                                'Hermana',
                                                'Hermano',
                                                'Otro'
                                            ] as $parentesco)

                                                <option
                                                    value="{{ $parentesco }}"
                                                    @selected(
                                                        old('parentesco')
                                                        === $parentesco
                                                    )
                                                >
                                                    {{ $parentesco }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="col-12">

                                        <div class="form-check form-switch">

                                            <input
                                                type="checkbox"
                                                name="responsable_recibe_notificaciones"
                                                value="1"
                                                id="responsableNotificaciones"
                                                class="form-check-input"
                                                @checked(
                                                    old(
                                                        'responsable_recibe_notificaciones',
                                                        true
                                                    )
                                                )
                                            >

                                            <label
                                                for="responsableNotificaciones"
                                                class="form-check-label"
                                            >
                                                El responsable recibirá
                                                notificaciones relacionadas
                                                con el estudiante.
                                            </label>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </section>

                        {{-- ===================================================
                             PASO 5
                             =================================================== --}}
                        <section
                            class="edma-form-step"
                            data-step="5"
                        >

                            <div class="edma-form-heading">

                                <div class="edma-form-heading-icon">
                                    <i class="bi bi-receipt"></i>
                                </div>

                                <div>

                                    <h2>
                                        Pago y comprobante
                                    </h2>

                                    <p>
                                        Registre el primer pago realizado
                                        y adjunte el comprobante correspondiente.
                                    </p>

                                </div>

                            </div>

                            <div class="edma-info-panel mb-4">

                                <strong>
                                    Período correspondiente:
                                </strong>

                                {{ $periodoActual->nombre }}

                                <br>

                                El primer pago debe ser de al menos
                                <strong>
                                    L 700.00
                                </strong>.

                            </div>

                            <div class="row g-3">

                                <div class="col-12 col-md-4">

                                    <label class="edma-form-label">
                                        Monto pagado
                                        <span class="edma-required">*</span>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            L
                                        </span>

                                        <input
                                            type="number"
                                            name="monto_total"
                                            value="{{ old('monto_total', '700.00') }}"
                                            min="700"
                                            step="0.01"
                                            class="form-control edma-form-control"
                                            required
                                        >

                                    </div>

                                </div>

                                <div class="col-12 col-md-4">

                                    <label class="edma-form-label">
                                        Método de pago
                                        <span class="edma-required">*</span>
                                    </label>

                                    <select
                                        name="metodo_pago"
                                        class="form-select edma-form-control"
                                        required
                                    >

                                        <option value="">
                                            Seleccione
                                        </option>

                                        <option
                                            value="transferencia_bancaria"
                                            @selected(
                                                old('metodo_pago')
                                                === 'transferencia_bancaria'
                                            )
                                        >
                                            Transferencia bancaria
                                        </option>

                                        <option
                                            value="deposito_bancario"
                                            @selected(
                                                old('metodo_pago')
                                                === 'deposito_bancario'
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
                                            value="efectivo"
                                            @selected(
                                                old('metodo_pago')
                                                === 'efectivo'
                                            )
                                        >
                                            Efectivo
                                        </option>

                                    </select>

                                </div>

                                <div class="col-12 col-md-4">

                                    <label class="edma-form-label">
                                        Fecha del pago
                                        <span class="edma-required">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        name="fecha_pago"
                                        value="{{ old(
                                            'fecha_pago',
                                            now()->format('Y-m-d')
                                        ) }}"
                                        max="{{ now()->format('Y-m-d') }}"
                                        class="form-control edma-form-control"
                                        required
                                    >

                                </div>

                                <div class="col-12">

                                    <label class="edma-form-label">
                                        Número de referencia
                                    </label>

                                    <input
                                        type="text"
                                        name="numero_referencia"
                                        value="{{ old('numero_referencia') }}"
                                        class="form-control edma-form-control"
                                        maxlength="100"
                                        placeholder="Número de transacción, depósito o referencia"
                                    >

                                </div>

                                <div class="col-12">

                                    <label class="edma-form-label">
                                        Comprobante
                                        <span class="edma-required">*</span>
                                    </label>

                                    <div class="edma-payment-upload">

                                        <i class="bi bi-cloud-arrow-up"></i>

                                        <strong>
                                            Adjunte su comprobante de pago
                                        </strong>

                                        <small>
                                            JPG, PNG, WEBP o PDF · máximo 5 MB
                                        </small>

                                        <input
                                            type="file"
                                            name="comprobante_pago"
                                            id="paymentFile"
                                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                                            class="form-control mt-3"
                                            required
                                        >

                                        <div
                                            class="edma-form-help"
                                            id="paymentFileName"
                                        ></div>

                                    </div>

                                </div>

                            </div>

                        </section>

                        {{-- ===================================================
                             PASO 6
                             =================================================== --}}
                        <section
                            class="edma-form-step"
                            data-step="6"
                        >

                            <div class="edma-form-heading">

                                <div class="edma-form-heading-icon">
                                    <i class="bi bi-check2-circle"></i>
                                </div>

                                <div>

                                    <h2>
                                        Revisar y enviar
                                    </h2>

                                    <p>
                                        Confirme los datos principales
                                        antes de enviar la solicitud.
                                    </p>

                                </div>

                            </div>

                            <div class="row g-3 mb-4">

                                <div class="col-12 col-md-6">

                                    <div class="edma-review-card">

                                        <span>
                                            Aspirante
                                        </span>

                                        <strong id="reviewName">
                                            —
                                        </strong>

                                    </div>

                                </div>

                                <div class="col-12 col-md-6">

                                    <div class="edma-review-card">

                                        <span>
                                            Segmento
                                        </span>

                                        <strong id="reviewSegment">
                                            —
                                        </strong>

                                    </div>

                                </div>

                                <div class="col-12 col-md-6">

                                    <div class="edma-review-card">

                                        <span>
                                            Programa
                                        </span>

                                        <strong id="reviewProgram">
                                            —
                                        </strong>

                                    </div>

                                </div>

                                <div class="col-12 col-md-6">

                                    <div class="edma-review-card">

                                        <span>
                                            Nivel solicitado
                                        </span>

                                        <strong id="reviewLevel">
                                            —
                                        </strong>

                                    </div>

                                </div>

                                <div class="col-12 col-md-6">

                                    <div class="edma-review-card">

                                        <span>
                                            Período
                                        </span>

                                        <strong>
                                            {{ $periodoActual->nombre }}
                                        </strong>

                                    </div>

                                </div>

                                <div class="col-12 col-md-6">

                                    <div class="edma-review-card">

                                        <span>
                                            Pago registrado
                                        </span>

                                        <strong id="reviewPayment">
                                            —
                                        </strong>

                                    </div>

                                </div>

                            </div>

                            <div class="edma-info-panel mb-4">

                                Al enviar la solicitud, EDMA revisará
                                la información y el comprobante de pago.
                                Si la solicitud es aprobada, se crearán
                                posteriormente sus credenciales como estudiante.

                            </div>

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="acepta_declaracion"
                                    value="1"
                                    id="aceptaDeclaracion"
                                    class="form-check-input"
                                    required
                                >

                                <label
                                    for="aceptaDeclaracion"
                                    class="form-check-label"
                                >
                                    Confirmo que la información proporcionada
                                    es correcta y que el comprobante adjunto
                                    corresponde al pago realizado.
                                </label>

                            </div>

                        </section>

                        <div class="edma-application-actions">

                            <button
                                type="button"
                                class="btn edma-btn edma-btn-secondary"
                                id="previousStep"
                                style="visibility:hidden;"
                            >
                                <i class="bi bi-arrow-left"></i>
                                Anterior
                            </button>

                            <button
                                type="button"
                                class="btn edma-btn edma-btn-primary"
                                id="nextStep"
                            >
                                Continuar
                                <i class="bi bi-arrow-right"></i>
                            </button>

                            <button
                                type="submit"
                                class="btn edma-btn edma-btn-primary"
                                id="submitApplication"
                                style="display:none;"
                            >
                                <i class="bi bi-send"></i>
                                Enviar solicitud
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        @endif

    </div>

</section>

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {

    const form =
        document.getElementById(
            'applicationForm'
        );

    if (!form) {
        return;
    }

    const steps = Array.from(
        document.querySelectorAll(
            '.edma-form-step'
        )
    );

    const indicators = Array.from(
        document.querySelectorAll(
            '.edma-step-indicator'
        )
    );

    const previousButton =
        document.getElementById(
            'previousStep'
        );

    const nextButton =
        document.getElementById(
            'nextStep'
        );

    const submitButton =
        document.getElementById(
            'submitApplication'
        );

    let currentStep = 1;

    /*
    |--------------------------------------------------------------------------
    | Edad y segmento
    |--------------------------------------------------------------------------
    */

    const birthDate =
        document.getElementById(
            'fechaNacimiento'
        );

    const segmentInput =
        document.getElementById(
            'segmentoSolicitado'
        );

    const segmentResult =
        document.getElementById(
            'segmentResult'
        );

    const segmentTitle =
        document.getElementById(
            'segmentTitle'
        );

    const segmentDescription =
        document.getElementById(
            'segmentDescription'
        );

    const responsibleFields =
        document.getElementById(
            'responsibleFields'
        );

    const adultNotice =
        document.getElementById(
            'adultNotice'
        );

    const responsibleRequired =
        Array.from(
            document.querySelectorAll(
                '.responsible-required'
            )
        );

    const aspirantEmail =
        document.getElementById(
            'aspirantEmail'
        );

    const aspirantPhone =
        document.getElementById(
            'aspirantPhone'
        );

    const aspirantEmailRequired =
        document.getElementById(
            'aspirantEmailRequired'
        );

    const aspirantPhoneRequired =
        document.getElementById(
            'aspirantPhoneRequired'
        );

    const minorContactNotice =
        document.getElementById(
            'minorContactNotice'
        );

    const contactStepTitle =
        document.getElementById(
            'contactStepTitle'
        );

    const contactStepDescription =
        document.getElementById(
            'contactStepDescription'
        );

    const calculateAge = value => {
        if (!value) {
            return null;
        }

        const birth =
            new Date(
                `${value}T00:00:00`
            );

        const today =
            new Date();

        let age =
            today.getFullYear()
            - birth.getFullYear();

        const month =
            today.getMonth()
            - birth.getMonth();

        if (
            month < 0 ||
            (
                month === 0 &&
                today.getDate()
                < birth.getDate()
            )
        ) {
            age--;
        }

        return age;
    };

    const updateAgeRules = () => {
        const age =
            calculateAge(
                birthDate?.value
            );

        if (
            age === null ||
            age < 7
        ) {
            segmentResult.style.display =
                'none';

            segmentInput.value = '';

            return;
        }

        const isChild =
            age <= 13;

        const isMinor =
            age < 18;

        segmentInput.value =
            isChild
                ? 'niños'
                : 'jóvenes_adultos';

        segmentTitle.textContent =
            isChild
                ? 'Programa para Niños'
                : 'Programa para Jóvenes y adultos';

        segmentDescription.textContent =
            `${age} años · El segmento se determina automáticamente según la edad.`;

        segmentResult.style.display =
            'block';

        responsibleFields.classList.toggle(
            'visible',
            isMinor
        );

        adultNotice.style.display =
            isMinor
                ? 'none'
                : 'block';

        responsibleRequired.forEach(
            element => {
                element.required =
                    isMinor;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Contacto del aspirante
        |--------------------------------------------------------------------------
        */

        aspirantEmail.required =
            !isMinor;

        aspirantPhone.required =
            !isMinor;

        aspirantEmailRequired.style.display =
            isMinor
                ? 'none'
                : 'inline';

        aspirantPhoneRequired.style.display =
            isMinor
                ? 'none'
                : 'inline';

        minorContactNotice.style.display =
            isMinor
                ? 'block'
                : 'none';

        contactStepTitle.textContent =
            isMinor
                ? 'Contacto del aspirante'
                : 'Información de contacto';

        contactStepDescription.textContent =
            isMinor
                ? 'Puede registrar los datos de contacto del aspirante si dispone de ellos.'
                : 'Estos datos permitirán a EDMA comunicarse durante el proceso.';

        filterPrograms();
    };

    /*
    |--------------------------------------------------------------------------
    | Programa y niveles
    |--------------------------------------------------------------------------
    */

    const programSelect =
        document.getElementById(
            'programaSelect'
        );

    const levelSelect =
        document.getElementById(
            'nivelSelect'
        );

    const placementNotice =
        document.getElementById(
            'placementNotice'
        );

    const filterPrograms = () => {
        const segment =
            segmentInput.value;

        Array.from(
            programSelect.options
        ).forEach(option => {

            if (!option.value) {
                return;
            }

            const visible =
                option.dataset.segmento
                === segment;

            option.hidden = !visible;
            option.disabled = !visible;
        });

        const selected =
            programSelect.options[
                programSelect.selectedIndex
            ];

        if (
            selected &&
            selected.value &&
            selected.disabled
        ) {
            programSelect.value = '';
            levelSelect.value = '';
        }

        filterLevels();
    };

    const filterLevels = () => {
        const programId =
            programSelect.value;

        Array.from(
            levelSelect.options
        ).forEach(option => {

            if (!option.value) {
                return;
            }

            const visible =
                option.dataset.programa
                === programId;

            option.hidden = !visible;
            option.disabled = !visible;
        });

        const selected =
            levelSelect.options[
                levelSelect.selectedIndex
            ];

        if (
            selected &&
            selected.value &&
            selected.disabled
        ) {
            levelSelect.value = '';
        }

        updatePlacementNotice();
    };

    const updatePlacementNotice = () => {
        const option =
            levelSelect.options[
                levelSelect.selectedIndex
            ];

        if (
            !option ||
            !option.value
        ) {
            placementNotice.style.display =
                'none';

            return;
        }

        const code =
            (
                option.dataset.codigo ||
                option.textContent
            )
                .trim()
                .toUpperCase();

        if (code === 'A0') {

            placementNotice.innerHTML =
                '<strong>Nivel A0.</strong> No se requerirá prueba de ubicación para iniciar en este nivel.';

        } else {

            placementNotice.innerHTML =
                '<strong>Nivel superior a A0.</strong> Esta selección expresa su preferencia. EDMA determinará el nivel autorizado y podrá solicitar una prueba de ubicación.';

        }

        placementNotice.style.display =
            'block';
    };

    /*
    |--------------------------------------------------------------------------
    | Fuente de referencia
    |--------------------------------------------------------------------------
    */

    const referenceType =
        document.getElementById(
            'referenceType'
        );

    const otherReferenceContainer =
        document.getElementById(
            'otherReferenceContainer'
        );

    const otherReference =
        document.getElementById(
            'otherReference'
        );

    const updateReference = () => {
        const isOther =
            referenceType.value
            === 'otro';

        otherReferenceContainer.style.display =
            isOther
                ? 'block'
                : 'none';

        otherReference.required =
            isOther;

        if (!isOther) {
            otherReference.value = '';
        }
    };

    /*
    |--------------------------------------------------------------------------
    | Archivo
    |--------------------------------------------------------------------------
    */

    const paymentFile =
        document.getElementById(
            'paymentFile'
        );

    const paymentFileName =
        document.getElementById(
            'paymentFileName'
        );

    paymentFile?.addEventListener(
        'change',
        () => {
            paymentFileName.textContent =
                paymentFile.files.length
                    ? paymentFile.files[0].name
                    : '';
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Navegación
    |--------------------------------------------------------------------------
    */

    const validateCurrentStep = () => {
        const current =
            steps[
                currentStep - 1
            ];

        const fields =
            Array.from(
                current.querySelectorAll(
                    'input, select, textarea'
                )
            ).filter(
                field =>
                    !field.disabled &&
                    field.offsetParent !== null
            );

        for (const field of fields) {

            if (!field.checkValidity()) {

                field.reportValidity();
                field.focus();

                return false;
            }
        }

        return true;
    };

    const renderStep = () => {

        steps.forEach(
            (step, index) => {

                step.classList.toggle(
                    'active',
                    index + 1 === currentStep
                );
            }
        );

        indicators.forEach(
            (indicator, index) => {

                indicator.classList.toggle(
                    'active',
                    index + 1 === currentStep
                );

                indicator.classList.toggle(
                    'completed',
                    index + 1 < currentStep
                );
            }
        );

        previousButton.style.visibility =
            currentStep === 1
                ? 'hidden'
                : 'visible';

        nextButton.style.display =
            currentStep === steps.length
                ? 'none'
                : 'inline-flex';

        submitButton.style.display =
            currentStep === steps.length
                ? 'inline-flex'
                : 'none';

        if (
            currentStep
            === steps.length
        ) {
            updateReview();
        }

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };

    nextButton.addEventListener(
        'click',
        () => {

            if (!validateCurrentStep()) {
                return;
            }

            if (
                currentStep <
                steps.length
            ) {
                currentStep++;
                renderStep();
            }
        }
    );

    previousButton.addEventListener(
        'click',
        () => {

            if (currentStep > 1) {
                currentStep--;
                renderStep();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Resumen
    |--------------------------------------------------------------------------
    */

    const value = name =>
        form.querySelector(
            `[name="${name}"]`
        )?.value?.trim() || '';

    const selectedText = id => {

        const select =
            document.getElementById(id);

        const option =
            select?.options[
                select.selectedIndex
            ];

        return option?.value
            ? option.textContent.trim()
            : '—';
    };

    const updateReview = () => {

        document.getElementById(
            'reviewName'
        ).textContent =
            [
                value('primer_nombre'),
                value('segundo_nombre'),
                value('primer_apellido'),
                value('segundo_apellido')
            ]
                .filter(Boolean)
                .join(' ')
                || '—';

        document.getElementById(
            'reviewSegment'
        ).textContent =
            segmentInput.value === 'niños'
                ? 'Niños'
                : (
                    segmentInput.value
                    === 'jóvenes_adultos'
                        ? 'Jóvenes y adultos'
                        : '—'
                );

        document.getElementById(
            'reviewProgram'
        ).textContent =
            selectedText(
                'programaSelect'
            );

        document.getElementById(
            'reviewLevel'
        ).textContent =
            selectedText(
                'nivelSelect'
            );

        const amount =
            value(
                'monto_total'
            );

        document.getElementById(
            'reviewPayment'
        ).textContent =
            amount
                ? `L ${Number(amount).toFixed(2)}`
                : '—';
    };

    /*
    |--------------------------------------------------------------------------
    | Eventos
    |--------------------------------------------------------------------------
    */

    birthDate?.addEventListener(
        'change',
        updateAgeRules
    );

    programSelect?.addEventListener(
        'change',
        filterLevels
    );

    levelSelect?.addEventListener(
        'change',
        updatePlacementNotice
    );

    referenceType?.addEventListener(
        'change',
        updateReference
    );

    /*
    |--------------------------------------------------------------------------
    | Inicialización
    |--------------------------------------------------------------------------
    */

    updateAgeRules();
    filterPrograms();
    filterLevels();
    updateReference();
    renderStep();
});
</script>

@endpush