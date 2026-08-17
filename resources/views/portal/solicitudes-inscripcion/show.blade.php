@extends('layouts.portal')

@section('title', 'Detalle de solicitud')


{{-- =========================================================
    ENCABEZADO DE LA PÁGINA
========================================================= --}}
@section('page-header')

    <div class="portal-page-heading">

        <div>

            <span class="portal-page-eyebrow">
                Admisiones
            </span>

            <h1>
                {{ $solicitud->codigo_solicitud }}
            </h1>

            <p>
                Consulte la información enviada por el aspirante
                y los datos relacionados con su proceso de inscripción.
            </p>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- Volver --}}
            <a
                href="{{ route(
                    'portal.solicitudes-inscripcion.index'
                ) }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver a solicitudes
            </a>


            {{-- Pendiente → Iniciar revisión --}}
            @if ($solicitud->estado === 'pendiente')

                <button
                    type="button"
                    class="btn portal-btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalIniciarRevision"
                >
                    <i class="bi bi-search"></i>
                    Iniciar revisión
                </button>

            @endif


            {{-- En revisión → Aprobar / Rechazar --}}
            @if ($solicitud->estado === 'en_revision')

                <button
                    type="button"
                    class="btn portal-btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAprobarSolicitud"
                >
                    <i class="bi bi-check2-circle"></i>
                    Aprobar solicitud
                </button>


                <button
                    type="button"
                    class="btn btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#modalRechazarSolicitud"
                >
                    <i class="bi bi-x-circle"></i>
                    Rechazar solicitud
                </button>

            @endif

        </div>

    </div>

@endsection



{{-- =========================================================
    CONTENIDO
========================================================= --}}
@section('content')


    {{-- =====================================================
        CREDENCIALES TEMPORALES
        Solo aparecen inmediatamente después de aprobar.
    ====================================================== --}}
    @if (session()->has('credenciales_temporales'))

        @php
            $credenciales =
                session('credenciales_temporales');
        @endphp

        <div class="alert alert-success shadow-sm mb-4">

            <div class="d-flex gap-3">

                <div>
                    <i class="bi bi-key-fill fs-3"></i>
                </div>


                <div class="flex-grow-1">

                    <h5 class="mb-2">
                        Credenciales de acceso creadas
                    </h5>

                    <p class="mb-3">
                        Entregue estas credenciales al estudiante
                        o a su responsable autorizado.
                        La contraseña temporal solo se mostrará
                        en este momento.
                    </p>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <small class="d-block text-muted">
                                Usuario
                            </small>

                            <strong class="fs-5">
                                {{ $credenciales['username'] }}
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="d-block text-muted">
                                Contraseña temporal
                            </small>

                            <strong class="fs-5">
                                {{ $credenciales['password'] }}
                            </strong>

                        </div>

                    </div>


                    <hr>


                    <small>
                        Al iniciar sesión por primera vez,
                        el estudiante deberá establecer
                        una nueva contraseña personal.
                    </small>

                </div>

            </div>

        </div>

    @endif



    {{-- =====================================================
        VARIABLES DE APOYO DE LA VISTA
    ====================================================== --}}
    @php

        $persona =
            $solicitud->persona;

        $edad =
            $persona?->fecha_nacimiento
                ?->age;


        $estadoSolicitud = match (
            $solicitud->estado
        ) {
            'pendiente' =>
                'Pendiente',

            'en_revision' =>
                'En revisión',

            'aprobada' =>
                'Aprobada',

            'rechazada' =>
                'Rechazada',

            default =>
                str(
                    $solicitud->estado
                )
                    ->replace('_', ' ')
                    ->title(),
        };


        $segmento = match (
            $solicitud->segmento_solicitado
        ) {
            'niños' =>
                'Niños',

            'jóvenes_adultos' =>
                'Jóvenes y adultos',

            default =>
                str(
                    $solicitud->segmento_solicitado
                )
                    ->replace('_', ' ')
                    ->title(),
        };

    @endphp



    {{-- =====================================================
        SOLICITUD DE INSCRIPCIÓN
    ====================================================== --}}
    <section class="portal-card portal-detail-card">

        <div class="portal-form-section-header">

            <div class="portal-form-section-icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>


            <div>

                <h2>
                    Solicitud de inscripción
                </h2>

                <p>
                    Información general del proceso de admisión.
                </p>

            </div>

        </div>


        <div class="portal-detail-grid">


            {{-- Código --}}
            <div class="portal-detail-item">

                <span>
                    Código de solicitud
                </span>

                <strong>
                    {{ $solicitud->codigo_solicitud }}
                </strong>

            </div>


            {{-- Estado --}}
            <div class="portal-detail-item">

                <span>
                    Estado
                </span>

                <strong>
                    {{ $estadoSolicitud }}
                </strong>

            </div>


            {{-- Fecha de envío --}}
            <div class="portal-detail-item">

                <span>
                    Fecha de envío
                </span>

                <strong>
                    {{ $solicitud->enviada_at
                        ?->translatedFormat(
                            'd \d\e F \d\e Y'
                        )
                        ?? 'No registrada' }}
                </strong>


                @if ($solicitud->enviada_at)

                    <small>
                        {{ $solicitud
                            ->enviada_at
                            ->format('h:i a') }}
                    </small>

                @endif

            </div>


            {{-- Segmento --}}
            <div class="portal-detail-item">

                <span>
                    Segmento
                </span>

                <strong>
                    {{ $segmento }}
                </strong>

            </div>


            {{-- Fecha de revisión --}}
            <div class="portal-detail-item">

                <span>
                    Fecha de revisión
                </span>

                <strong>
                    {{ $solicitud->revisada_at
                        ?->translatedFormat(
                            'd \d\e F \d\e Y'
                        )
                        ?? 'Aún no revisada' }}
                </strong>

            </div>


            {{-- Fecha de resolución --}}
            <div class="portal-detail-item">

                <span>
                    Fecha de resolución
                </span>

                <strong>
                    {{ $solicitud->resuelta_at
                        ?->translatedFormat(
                            'd \d\e F \d\e Y'
                        )
                        ?? 'Aún no resuelta' }}
                </strong>

            </div>


            {{-- Revisada por --}}
            <div
                class="portal-detail-item
                       portal-detail-item-full"
            >

                <span>
                    Revisada por
                </span>

                <strong>
                    {{ $solicitud
                        ->revisadaPor
                        ?->persona
                        ?->nombre_completo
                        ?? 'Aún no asignada a revisión' }}
                </strong>

            </div>

        </div>

    </section>



    {{-- Aspirante --}}
    <section class="portal-card portal-detail-card">

        <div class="portal-form-section-header">

            <div class="portal-form-section-icon">
                <i class="bi bi-person-vcard"></i>
            </div>

            <div>
                <h2>Datos del aspirante</h2>

                <p>
                    Información personal registrada durante la solicitud.
                </p>
            </div>

        </div>


        <div class="portal-detail-grid">

            <div class="portal-detail-item">

                <span>Nombre completo</span>

                <strong>
                    {{ $persona?->nombre_completo
                        ?: 'No registrado' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Tipo de documento</span>

                <strong>
                    {{ $persona?->tipo_documento
                        ? str(
                            $persona->tipo_documento
                        )
                            ->replace('_', ' ')
                            ->title()
                        : 'No registrado' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Número de documento</span>

                <strong>
                    {{ $persona?->numero_documento
                        ?: 'No registrado' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Fecha de nacimiento</span>

                <strong>
                    {{ $persona?->fecha_nacimiento
                        ?->translatedFormat(
                            'd \d\e F \d\e Y'
                        )
                        ?? 'No registrada' }}
                </strong>

                @if ($edad !== null)

                    <small>
                        {{ $edad }} años
                    </small>

                @endif

            </div>


            <div class="portal-detail-item">

                <span>Sexo</span>

                <strong>
                    {{ $persona?->sexo
                        ? str(
                            $persona->sexo
                        )->title()
                        : 'No especificado' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Nacionalidad</span>

                <strong>
                    {{ $persona?->nacionalidad
                        ?: 'No especificada' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Correo personal</span>

                @if ($persona?->correo_personal)

                    <a
                        href="mailto:{{ $persona->correo_personal }}"
                    >
                        {{ $persona->correo_personal }}
                    </a>

                @else

                    <strong>
                        No registrado
                    </strong>

                @endif

            </div>


            <div class="portal-detail-item">

                <span>Teléfono móvil</span>

                <strong>
                    {{ $persona?->telefono_movil
                        ?: 'No registrado' }}
                </strong>

                @if (
                    $persona?->telefono_movil &&
                    $persona?->telefono_movil_whatsapp
                )

                    <small class="portal-detail-whatsapp">

                        <i class="bi bi-whatsapp"></i>

                        Disponible en WhatsApp

                    </small>

                @endif

            </div>


            <div class="portal-detail-item">

                <span>País de residencia</span>

                <strong>
                    {{ $persona
                        ?->paisResidencia
                        ?->nombre
                        ?: 'No especificado' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Ciudad o municipio</span>

                <strong>
                    {{ $persona?->ciudad_municipio
                        ?: 'No especificado' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Departamento o estado</span>

                <strong>
                    {{ $persona?->departamento_estado
                        ?: 'No especificado' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Condición de contacto</span>

                <strong>
                    {{ $edad !== null && $edad < 18
                        ? 'Menor de edad'
                        : 'Adulto' }}
                </strong>

                @if ($edad !== null && $edad < 18)

                    <small>
                        El contacto principal puede corresponder
                        a su responsable.
                    </small>

                @endif

            </div>


            <div class="portal-detail-item portal-detail-item-full">

                <span>Dirección</span>

                <strong>
                    {{ $persona?->direccion
                        ?: 'No registrada' }}
                </strong>

            </div>

        </div>

    </section>


    {{-- Información académica --}}
    <section class="portal-card portal-detail-card">

        <div class="portal-form-section-header">

            <div class="portal-form-section-icon">
                <i class="bi bi-mortarboard"></i>
            </div>

            <div>
                <h2>Información académica</h2>

                <p>
                    Preferencias académicas indicadas durante la solicitud.
                </p>
            </div>

        </div>


        <div class="portal-detail-grid">

            <div class="portal-detail-item">

                <span>Programa</span>

                <strong>
                    {{ $solicitud
                        ->nivelSolicitado
                        ?->programa
                        ?->nombre
                        ?: 'No disponible' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Nivel solicitado</span>

                <strong>
                    {{ $solicitud
                        ->nivelSolicitado
                        ?->nombre
                        ?: 'No disponible' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Nivel autorizado</span>

                <strong>
                    {{ $solicitud
                        ->nivelAutorizado
                        ?->nombre
                        ?: 'Pendiente de autorización' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Segmento</span>

                <strong>
                    {{ $segmento }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Prueba de ubicación</span>

                <strong>
                    {{ $solicitud
                        ->requiere_examen_ubicacion
                        ? 'Requerida'
                        : 'No requerida' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Estado de autorización</span>

                <strong>
                    {{ $solicitud->nivel_autorizado_id
                        ? 'Nivel autorizado'
                        : 'Pendiente de revisión académica' }}
                </strong>

            </div>

        </div>

    </section>


    {{-- Responsables --}}
    <section class="portal-card portal-detail-card">

        <div class="portal-form-section-header">

            <div class="portal-form-section-icon">
                <i class="bi bi-people"></i>
            </div>

            <div>
                <h2>Responsable</h2>

                <p>
                    Información de la persona responsable
                    declarada en la solicitud.
                </p>
            </div>

        </div>


        @forelse (
            $solicitud->responsables
            as $relacionResponsable
        )

            @php
                $responsable =
                    $relacionResponsable->responsable;
            @endphp


            <div class="portal-detail-grid">

                <div class="portal-detail-item">

                    <span>Nombre completo</span>

                    <strong>
                        {{ $responsable?->nombre_completo
                            ?: 'No registrado' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Parentesco</span>

                    <strong>
                        {{ $relacionResponsable->parentesco
                            ?: 'No especificado' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Responsable principal</span>

                    <strong>
                        {{ $relacionResponsable->es_principal
                            ? 'Sí'
                            : 'No' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Tipo de documento</span>

                    <strong>
                        {{ $responsable?->tipo_documento
                            ? str(
                                $responsable->tipo_documento
                            )
                                ->replace('_', ' ')
                                ->title()
                            : 'No registrado' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Número de documento</span>

                    <strong>
                        {{ $responsable?->numero_documento
                            ?: 'No registrado' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Recibe notificaciones</span>

                    <strong>
                        {{ $relacionResponsable
                            ->recibe_notificaciones
                            ? 'Sí'
                            : 'No' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Correo</span>

                    @if ($responsable?->correo_personal)

                        <a
                            href="mailto:{{ $responsable->correo_personal }}"
                        >
                            {{ $responsable->correo_personal }}
                        </a>

                    @else

                        <strong>
                            No registrado
                        </strong>

                    @endif

                </div>


                <div class="portal-detail-item">

                    <span>Teléfono móvil</span>

                    <strong>
                        {{ $responsable?->telefono_movil
                            ?: 'No registrado' }}
                    </strong>

                    @if (
                        $responsable?->telefono_movil &&
                        $responsable?->telefono_movil_whatsapp
                    )

                        <small class="portal-detail-whatsapp">

                            <i class="bi bi-whatsapp"></i>

                            Disponible en WhatsApp

                        </small>

                    @endif

                </div>


                <div class="portal-detail-item">

                    <span>País de residencia</span>

                    <strong>
                        {{ $responsable
                            ?->paisResidencia
                            ?->nombre
                            ?: 'No especificado' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Ciudad o municipio</span>

                    <strong>
                        {{ $responsable?->ciudad_municipio
                            ?: 'No especificado' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Departamento o estado</span>

                    <strong>
                        {{ $responsable?->departamento_estado
                            ?: 'No especificado' }}
                    </strong>

                </div>


                <div class="portal-detail-item portal-detail-item-full">

                    <span>Dirección</span>

                    <strong>
                        {{ $responsable?->direccion
                            ?: 'No registrada' }}
                    </strong>

                </div>

            </div>


            @if (!$loop->last)

                <hr class="mx-4">

            @endif

        @empty

            <div class="portal-empty-state">

                <div class="portal-empty-icon">
                    <i class="bi bi-person-check"></i>
                </div>

                <h3>
                    No requiere responsable
                </h3>

                <p>
                    Esta solicitud no tiene personas responsables
                    registradas.
                </p>

            </div>

        @endforelse

    </section>


    {{-- Fuente de referencia --}}
    <section class="portal-card portal-detail-card">

        <div class="portal-form-section-header">

            <div class="portal-form-section-icon">
                <i class="bi bi-megaphone"></i>
            </div>

            <div>
                <h2>Referencia</h2>

                <p>
                    Información sobre cómo conoció Edumerican Academy.
                </p>
            </div>

        </div>


        <div class="portal-detail-grid">

            <div class="portal-detail-item">

                <span>¿Cómo conoció EDMA?</span>

                <strong>
                    {{ $solicitud
                        ->fuenteReferencia
                        ?->nombre
                        ?: 'No especificado' }}
                </strong>

            </div>


            <div class="portal-detail-item">

                <span>Recomendación de estudiante</span>

                <strong>
                    {{ $solicitud
                        ->recomienda_otro_estudiante
                        ? 'Sí'
                        : 'No' }}
                </strong>

            </div>


            <div class="portal-detail-item portal-detail-item-full">

                <span>Especificación</span>

                <strong>
                    {{ $solicitud->fuente_referencia_otro
                        ?: 'No aplica' }}
                </strong>

            </div>

        </div>

    </section>


    {{-- Pagos --}}
    <section class="portal-card portal-detail-card">

        <div class="portal-form-section-header">

            <div class="portal-form-section-icon">
                <i class="bi bi-credit-card"></i>
            </div>

            <div>
                <h2>Pago</h2>

                <p>
                    Información del pago presentado
                    junto con la solicitud.
                </p>
            </div>

        </div>


        @forelse ($solicitud->pagos as $pago)

            @php

                $estadoPago = match ($pago->estado) {

                    'pendiente_revision' =>
                        'Pendiente de revisión',

                    'aprobado' =>
                        'Aprobado',

                    'rechazado' =>
                        'Rechazado',

                    'anulado' =>
                        'Anulado',

                    default =>
                        str($pago->estado)
                            ->replace('_', ' ')
                            ->title(),
                };

            @endphp


            <div class="portal-detail-grid">

                <div class="portal-detail-item">

                    <span>Código de pago</span>

                    <strong>
                        {{ $pago->codigo_pago }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Estado</span>

                    <strong>
                        {{ $estadoPago }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Monto</span>

                    <strong>
                        L {{ number_format(
                            (float) $pago->monto_total,
                            2
                        ) }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Método de pago</span>

                    <strong>
                        {{ $pago->metodo_pago
                            ? str(
                                $pago->metodo_pago
                            )
                                ->replace('_', ' ')
                                ->title()
                            : 'No registrado' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Fecha del pago</span>

                    <strong>
                        {{ $pago->fecha_pago
                            ?->translatedFormat(
                                'd \d\e F \d\e Y'
                            )
                            ?? 'No registrada' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Número de referencia</span>

                    <strong>
                        {{ $pago->numero_referencia
                            ?: 'No registrado' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Período académico</span>

                    <strong>
                        {{ $pago
                            ->periodoAcademico
                            ?->nombre
                            ?: 'No disponible' }}
                    </strong>

                    @if (
                        $pago
                            ->periodoAcademico
                            ?->codigo
                    )

                        <small>
                            {{ $pago
                                ->periodoAcademico
                                ->codigo }}
                        </small>

                    @endif

                </div>


                <div class="portal-detail-item">

                    <span>Revisado por</span>

                    <strong>
                        {{ $pago
                            ->revisadoPor
                            ?->persona
                            ?->nombre_completo
                            ?? 'Aún no revisado' }}
                    </strong>

                </div>


                <div class="portal-detail-item">

                    <span>Fecha de revisión</span>

                    <strong>
                        {{ $pago->revisado_at
                            ?->translatedFormat(
                                'd \d\e F \d\e Y'
                            )
                            ?? 'Aún no revisado' }}
                    </strong>

                </div>


                <div class="portal-detail-item portal-detail-item-full">

                    <span>Observaciones del pago</span>

                    <strong>
                        {{ $pago->observaciones
                            ?: 'Sin observaciones registradas' }}
                    </strong>

                </div>


                @if ($pago->motivo_rechazo)

                    <div class="portal-detail-item portal-detail-item-full">

                        <span>Motivo de rechazo del pago</span>

                        <strong>
                            {{ $pago->motivo_rechazo }}
                        </strong>

                    </div>

                @endif

            </div>


            {{-- Comprobantes --}}
            <div class="px-4 pb-4">

                <div class="portal-form-section-header mb-3">

                    <div class="portal-form-section-icon">

                        <i class="bi bi-receipt"></i>

                    </div>

                    <div>

                        <h2>Comprobante de pago</h2>

                        <p>
                            Archivo presentado para respaldar este pago.
                        </p>

                    </div>

                </div>


                @forelse ($pago->comprobantes as $comprobante)

                    <div
                        class="border rounded-3 p-3 d-flex
                        flex-column flex-md-row
                        justify-content-between
                        align-items-md-center gap-3 mb-2"
                    >

                        <div>

                            <strong
                                class="d-block"
                            >
                                {{ $comprobante->nombre_original }}
                            </strong>


                            <small class="text-muted">

                                {{ strtoupper(
                                    $comprobante->extension
                                        ?: 'archivo'
                                ) }}

                                @if ($comprobante->tamano_bytes)

                                    ·

                                    {{ number_format(
                                        $comprobante->tamano_bytes
                                        / 1024,
                                        1
                                    ) }}
                                    KB

                                @endif

                            </small>

                        </div>


                        <a
                            href="{{ route(
                                'portal.solicitudes-inscripcion.comprobantes.show',
                                [
                                    'solicitud' =>
                                        $solicitud,

                                    'comprobante' =>
                                        $comprobante,
                                ]
                            ) }}"
                            target="_blank"
                            rel="noopener"
                            class="btn portal-btn-secondary"
                        >

                            <i class="bi bi-eye"></i>

                            Ver comprobante

                        </a>

                    </div>

                @empty

                    <div class="portal-empty-state">

                        <div class="portal-empty-icon">
                            <i class="bi bi-receipt"></i>
                        </div>

                        <h3>
                            No hay comprobante disponible
                        </h3>

                        <p>
                            No se encontró un archivo asociado
                            a este pago.
                        </p>

                    </div>

                @endforelse

            </div>


            @if (!$loop->last)

                <hr class="mx-4 my-4">

            @endif

        @empty

            <div class="portal-empty-state">

                <div class="portal-empty-icon">
                    <i class="bi bi-credit-card"></i>
                </div>

                <h3>
                    No hay pagos registrados
                </h3>

                <p>
                    Esta solicitud no tiene pagos asociados.
                </p>

            </div>

        @endforelse

    </section>


    {{-- Observaciones --}}
    <section class="portal-card portal-detail-card mb-0">

        <div class="portal-form-section-header">

            <div class="portal-form-section-icon">
                <i class="bi bi-chat-left-text"></i>
            </div>

            <div>
                <h2>Observaciones</h2>

                <p>
                    Comentarios relacionados con la solicitud.
                </p>
            </div>

        </div>


        <div class="portal-detail-grid">

            <div class="portal-detail-item portal-detail-item-full">

                <span>Observaciones del solicitante</span>

                <strong>
                    {{ $solicitud->observaciones_solicitante
                        ?: 'Sin observaciones registradas' }}
                </strong>

            </div>


            <div class="portal-detail-item portal-detail-item-full">

                <span>Observaciones administrativas</span>

                <strong>
                    {{ $solicitud->observaciones_administracion
                        ?: 'Sin observaciones administrativas' }}
                </strong>

            </div>


            @if ($solicitud->motivo_rechazo)

                <div class="portal-detail-item portal-detail-item-full">

                    <span>Motivo de rechazo</span>

                    <strong>
                        {{ $solicitud->motivo_rechazo }}
                    </strong>

                </div>

            @endif

        </div>

    </section>



    @if ($solicitud->estado === 'pendiente')

    <div
        class="modal fade"
        id="modalIniciarRevision"
        tabindex="-1"
        aria-labelledby="modalIniciarRevisionLabel"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <div>

                        <span class="portal-page-eyebrow">
                            Revisión administrativa
                        </span>

                        <h5
                            class="modal-title mt-1"
                            id="modalIniciarRevisionLabel"
                        >
                            Iniciar revisión de solicitud
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

                    <p class="mb-3">
                        La solicitud
                        <strong>
                            {{ $solicitud->codigo_solicitud }}
                        </strong>
                        cambiará a estado
                        <strong>En revisión</strong>.
                    </p>

                    <div class="alert alert-light border mb-0">

                        <i class="bi bi-info-circle me-2"></i>

                        Esto indica que la información del aspirante,
                        responsable, pago y comprobante está siendo
                        evaluada por administración.

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn portal-btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <form
                        action="{{ route(
                            'portal.solicitudes-inscripcion.iniciar-revision',
                            $solicitud
                        ) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="btn portal-btn-primary"
                        >
                            <i class="bi bi-search"></i>
                            Iniciar revisión
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endif


@if ($solicitud->estado === 'en_revision')

    <div
        class="modal fade"
        id="modalAprobarSolicitud"
        tabindex="-1"
        aria-labelledby="modalAprobarSolicitudLabel"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <div>

                        <span class="portal-page-eyebrow">
                            Admisión
                        </span>

                        <h5
                            class="modal-title mt-1"
                            id="modalAprobarSolicitudLabel"
                        >
                            Aprobar solicitud
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

                    <p>
                        Está a punto de aprobar la solicitud
                        <strong>
                            {{ $solicitud->codigo_solicitud }}
                        </strong>.
                    </p>

                    <div class="alert alert-light border">

                        Al confirmar, EDMA realizará
                        automáticamente las siguientes acciones:

                        <ul class="mb-0 mt-2">
                            <li>
                                Creará el expediente oficial del estudiante.
                            </li>

                            <li>
                                Generará su código institucional EDMA.
                            </li>

                            <li>
                                Registrará sus responsables, si corresponde.
                            </li>

                            <li>
                                Aprobará el pago presentado.
                            </li>

                            <li>
                                Creará su cuenta de acceso.
                            </li>

                            <li>
                                Generará una contraseña temporal segura.
                            </li>
                        </ul>

                    </div>

                    <div class="alert alert-warning mb-0">

                        <i class="bi bi-info-circle me-2"></i>

                        Esta acción no creará una matrícula.
                        El estudiante realizará posteriormente
                        ese proceso desde el Portal EDMA.

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn portal-btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <form
                        action="{{ route(
                            'portal.solicitudes-inscripcion.aprobar',
                            $solicitud
                        ) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="btn portal-btn-primary"
                        >
                            <i class="bi bi-check2-circle"></i>
                            Confirmar aprobación
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endif



@if ($solicitud->estado === 'en_revision')

    <div
        class="modal fade"
        id="modalRechazarSolicitud"
        tabindex="-1"
        aria-labelledby="modalRechazarSolicitudLabel"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <form
                    action="{{ route(
                        'portal.solicitudes-inscripcion.rechazar',
                        $solicitud
                    ) }}"
                    method="POST"
                >

                    @csrf
                    @method('PATCH')

                    <div class="modal-header">

                        <div>

                            <span class="portal-page-eyebrow text-danger">
                                Admisión
                            </span>

                            <h5
                                class="modal-title mt-1"
                                id="modalRechazarSolicitudLabel"
                            >
                                Rechazar solicitud
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

                        <p>
                            Está a punto de rechazar la solicitud
                            <strong>
                                {{ $solicitud->codigo_solicitud }}
                            </strong>.
                        </p>


                        <div class="alert alert-warning">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            La persona no será registrada como
                            estudiante y no se creará una cuenta
                            de acceso ni una matrícula.

                        </div>


                        <div>

                            <label
                                for="motivo_rechazo"
                                class="form-label fw-semibold"
                            >
                                Motivo del rechazo
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="motivo_rechazo"
                                id="motivo_rechazo"
                                rows="5"
                                maxlength="2000"
                                class="form-control @error('motivo_rechazo') is-invalid @enderror"
                                required
                                placeholder="Explique de forma clara por qué se rechaza esta solicitud."
                            >{{ old('motivo_rechazo') }}</textarea>

                            <div class="form-text">
                                Esta información quedará registrada
                                como parte del historial de la solicitud.
                            </div>

                            @error('motivo_rechazo')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn portal-btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="btn btn-danger"
                        >
                            <i class="bi bi-x-circle"></i>
                            Confirmar rechazo
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif

@endsection