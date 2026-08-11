@extends('layouts.portal')

@section('title', $persona->nombre_completo . ' | Portal EDMA')

@section('page-title', 'Expediente de persona')

@section('page-header')

    <div class="portal-page-heading">

        <div>
            <span class="portal-page-eyebrow">
                Gestión de personas
            </span>

            <h1>{{ $persona->nombre_completo }}</h1>

            <p>
                Consulte la información general, documentos y estado
                administrativo de la persona.
            </p>
        </div>

        <div class="portal-page-actions portal-page-actions-group">

            <a
                href="{{ route('portal.personas.index') }}"
                class="btn portal-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a
                href="{{ route('portal.personas.edit', $persona) }}"
                class="btn portal-btn-primary"
            >
                <i class="bi bi-pencil-square"></i>
                Editar información
            </a>

        </div>

    </div>

@endsection

@section('content')

    <div class="row g-4">

        {{-- Columna lateral --}}
        <div class="col-12 col-xl-4 col-xxl-3">

            <section class="portal-card portal-profile-card">

                <div class="portal-profile-cover"></div>

                <div class="portal-profile-content">

                    <div class="portal-profile-photo">

                        @if ($persona->foto_perfil)

                            <img
                                src="{{ asset('storage/' . $persona->foto_perfil) }}"
                                alt="Fotografía de {{ $persona->nombre_completo }}"
                            >

                        @else

                            <span>
                                {{ $persona->iniciales ?: 'PE' }}
                            </span>

                        @endif

                    </div>

                    <h2>{{ $persona->nombre_completo }}</h2>

                    <span class="portal-profile-code">
                        Registro #{{ str_pad($persona->id, 5, '0', STR_PAD_LEFT) }}
                    </span>

                    <div class="mt-3">

                        @if ($persona->estado === 'activo')

                            <span class="portal-status-badge portal-status-active">
                                <span></span>
                                Persona activa
                            </span>

                        @else

                            <span class="portal-status-badge portal-status-inactive">
                                <span></span>
                                Persona inactiva
                            </span>

                        @endif

                    </div>

                </div>

                <div class="portal-profile-summary">

                    <div>
                        <span>Registrada</span>

                        <strong>
                            {{ $persona->created_at?->translatedFormat('d M Y') }}
                        </strong>
                    </div>

                    <div>
                        <span>Actualizada</span>

                        <strong>
                            {{ $persona->updated_at?->diffForHumans() }}
                        </strong>
                    </div>

                </div>

            </section>

            {{-- Acciones --}}
            <section class="portal-card portal-profile-actions-card">

                <div class="portal-card-header">

                    <div>
                        <h2>Acciones</h2>
                        <p>Operaciones disponibles.</p>
                    </div>

                </div>

                <div class="portal-profile-actions">

                    <a
                        href="{{ route('portal.personas.edit', $persona) }}"
                        class="portal-profile-action"
                    >
                        <span>
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>
                            <strong>Editar información</strong>
                            <small>Actualizar datos personales</small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </a>

                    @if ($persona->estudiante)

                        <a
                            href="{{ route(
                                'portal.estudiantes.show',
                                $persona->estudiante
                            ) }}"
                            class="portal-profile-action"
                        >
                            <span>
                                <i class="bi bi-mortarboard"></i>
                            </span>

                            <div>
                                <strong>Ver expediente estudiantil</strong>

                                <small>
                                    {{ $persona->estudiante->codigo_estudiante }}
                                </small>
                            </div>

                            <i class="bi bi-chevron-right"></i>
                        </a>

                    @elseif ($persona->estado === 'activo')

                        <a
                            href="{{ route(
                                'portal.estudiantes.create',
                                ['persona' => $persona->id]
                            ) }}"
                            class="portal-profile-action"
                        >
                            <span>
                                <i class="bi bi-person-plus"></i>
                            </span>

                            <div>
                                <strong>Registrar como estudiante</strong>

                                <small>
                                    Crear expediente estudiantil
                                </small>
                            </div>

                            <i class="bi bi-chevron-right"></i>
                        </a>

                    @endif

                    @if ($persona->empleado)

    <a
        href="{{ route(
            'portal.empleados.show',
            $persona->empleado
        ) }}"
        class="portal-profile-action"
    >
        <span>
            <i class="bi bi-briefcase"></i>
        </span>

        <div>
            <strong>Ver expediente laboral</strong>

            <small>
                {{ $persona->empleado->codigo_empleado }}
            </small>
        </div>

        <i class="bi bi-chevron-right"></i>
    </a>

@elseif ($persona->estado === 'activo')

    <a
        href="{{ route(
            'portal.empleados.create',
            ['persona' => $persona->id]
        ) }}"
        class="portal-profile-action"
    >
        <span>
            <i class="bi bi-person-badge"></i>
        </span>

        <div>
            <strong>Registrar como empleado</strong>

            <small>
                Crear expediente laboral
            </small>
        </div>

        <i class="bi bi-chevron-right"></i>
    </a>

@endif

                    @if (
                        $persona->telefono_movil &&
                        $persona->telefono_movil_whatsapp
                    )

                        @php
                            $numeroWhatsapp = preg_replace(
                                '/\D+/',
                                '',
                                $persona->telefono_movil
                            );

                            if (
                                strlen($numeroWhatsapp) === 8 &&
                                $persona->paisResidencia?->codigo_iso2 === 'HN'
                            ) {
                                $numeroWhatsapp = '504' . $numeroWhatsapp;
                            }
                        @endphp

                        <a
                            href="https://wa.me/{{ $numeroWhatsapp }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="portal-profile-action"
                        >
                            <span class="portal-profile-action-success">
                                <i class="bi bi-whatsapp"></i>
                            </span>

                            <div>
                                <strong>Contactar por WhatsApp</strong>
                                <small>{{ $persona->telefono_movil }}</small>
                            </div>

                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>

                    @endif

                    <button
                        type="button"
                        class="portal-profile-action portal-profile-action-button"
                        data-bs-toggle="modal"
                        data-bs-target="#changeStatusModal"
                    >
                        <span>
                            <i class="bi bi-person-gear"></i>
                        </span>

                        <div>
                            <strong>
                                {{ $persona->estado === 'activo'
                                    ? 'Desactivar persona'
                                    : 'Activar persona' }}
                            </strong>

                            <small>
                                {{ $persona->estado === 'activo'
                                    ? 'Conservará su información histórica'
                                    : 'Habilitar nuevamente el registro' }}
                            </small>
                        </div>

                        <i class="bi bi-chevron-right"></i>
                    </button>

                </div>

            </section>

        </div>

        {{-- Contenido principal --}}
        <div class="col-12 col-xl-8 col-xxl-9">

            {{-- Datos personales --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <div>
                        <h2>Datos personales</h2>
                        <p>Información general de la persona.</p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">
                        <span>Nombre completo</span>
                        <strong>{{ $persona->nombre_completo }}</strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Fecha de nacimiento</span>

                        <strong>
                            {{ $persona->fecha_nacimiento
                                ? $persona->fecha_nacimiento->translatedFormat('d \d\e F \d\e Y')
                                : 'No especificada' }}
                        </strong>

                        @if ($persona->fecha_nacimiento)
                            <small>
                                {{ $persona->fecha_nacimiento->age }} años
                            </small>
                        @endif
                    </div>

                    <div class="portal-detail-item">
                        <span>Sexo</span>

                        <strong>
                            {{ $persona->sexo
                                ? str($persona->sexo)->replace('_', ' ')->title()
                                : 'No especificado' }}
                        </strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Estado civil</span>

                        <strong>
                            {{ $persona->estado_civil
                                ? str($persona->estado_civil)->replace('_', ' ')->title()
                                : 'No especificado' }}
                        </strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Nacionalidad</span>
                        <strong>{{ $persona->nacionalidad ?: 'No especificada' }}</strong>
                    </div>

                </div>

            </section>

            {{-- Identificación --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>

                    <div>
                        <h2>Identificación</h2>
                        <p>Documentos oficiales registrados.</p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">
                        <span>Tipo de documento</span>

                        <strong>
                            {{ $persona->tipo_documento
                                ? str($persona->tipo_documento)->replace('_', ' ')->title()
                                : 'Sin documento' }}
                        </strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Número de documento</span>
                        <strong>{{ $persona->numero_documento ?: 'No registrado' }}</strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>RTN</span>
                        <strong>{{ $persona->rtn ?: 'No registrado' }}</strong>
                    </div>

                </div>

            </section>

            {{-- Contacto --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-telephone"></i>
                    </div>

                    <div>
                        <h2>Información de contacto</h2>
                        <p>Correo electrónico y teléfonos.</p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">
                        <span>Correo personal</span>

                        @if ($persona->correo_personal)

                            <a href="mailto:{{ $persona->correo_personal }}">
                                {{ $persona->correo_personal }}
                            </a>

                        @else

                            <strong>No registrado</strong>

                        @endif
                    </div>

                    <div class="portal-detail-item">
                        <span>Teléfono móvil</span>

                        <strong>
                            {{ $persona->telefono_movil ?: 'No registrado' }}
                        </strong>

                        @if (
                            $persona->telefono_movil &&
                            $persona->telefono_movil_whatsapp
                        )
                            <small class="portal-detail-whatsapp">
                                <i class="bi bi-whatsapp"></i>
                                Disponible en WhatsApp
                            </small>
                        @endif
                    </div>

                    <div class="portal-detail-item">
                        <span>Teléfono fijo</span>
                        <strong>{{ $persona->telefono_fijo ?: 'No registrado' }}</strong>
                    </div>

                </div>

            </section>

            {{-- Residencia --}}
            <section class="portal-card portal-detail-card">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div>
                        <h2>Residencia</h2>
                        <p>Ubicación actual de la persona.</p>
                    </div>

                </div>

                <div class="portal-detail-grid">

                    <div class="portal-detail-item">
                        <span>País</span>

                        <strong>
                            {{ $persona->paisResidencia?->nombre
                                ?: 'No especificado' }}
                        </strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Departamento o estado</span>
                        <strong>{{ $persona->departamento_estado ?: 'No especificado' }}</strong>
                    </div>

                    <div class="portal-detail-item">
                        <span>Ciudad o municipio</span>
                        <strong>{{ $persona->ciudad_municipio ?: 'No especificada' }}</strong>
                    </div>

                    <div class="portal-detail-item portal-detail-item-full">
                        <span>Dirección</span>
                        <strong>{{ $persona->direccion ?: 'No registrada' }}</strong>
                    </div>

                </div>

            </section>

            {{-- Documentos adjuntos --}}
            <section class="portal-card portal-detail-card mb-0">

                <div class="portal-form-section-header">

                    <div class="portal-form-section-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>

                    <div>
                        <h2>Documentos adjuntos</h2>
                        <p>Archivos incorporados al expediente.</p>
                    </div>

                </div>

                @if ($persona->documentos->isNotEmpty())

                    <div class="portal-document-list">

                        @foreach ($persona->documentos as $documento)

                            <article class="portal-document-item">

                                <span class="portal-document-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </span>

                                <div>
                                    <strong>
                                        {{ $documento->nombre_original }}
                                    </strong>

                                    <small>
                                        {{ str($documento->tipo_documento)
                                            ->replace('_', ' ')
                                            ->title() }}
                                        ·
                                        {{ $documento->tamano_legible }}
                                    </small>
                                </div>

                                @if ($documento->verificado)

                                    <span class="portal-document-verified">
                                        <i class="bi bi-patch-check-fill"></i>
                                        Verificado
                                    </span>

                                @else

                                    <span class="portal-document-pending">
                                        Pendiente
                                    </span>

                                @endif

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="portal-empty-state portal-empty-state-documents">

                        <div class="portal-empty-icon">
                            <i class="bi bi-folder-x"></i>
                        </div>

                        <h3>No hay documentos adjuntos</h3>

                        <p>
                            Los documentos personales podrán incorporarse
                            posteriormente al expediente.
                        </p>

                    </div>

                @endif

            </section>

        </div>

    </div>

    {{-- Modal de cambio de estado --}}
    <div
        class="modal fade"
        id="changeStatusModal"
        tabindex="-1"
        aria-labelledby="changeStatusModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content portal-modal">
 
                <form
                    action="{{ route('portal.personas.cambiar-estado', $persona) }}"
                    method="POST"
                >
                    @csrf
                    @method('PATCH')

                    <div class="modal-header">

                        <div>
                            <span class="portal-modal-eyebrow">
                                Confirmación
                            </span>

                            <h2
                                class="modal-title"
                                id="changeStatusModalLabel"
                            >
                                {{ $persona->estado === 'activo'
                                    ? 'Desactivar persona'
                                    : 'Activar persona' }}
                            </h2>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"
                        ></button>

                    </div>

                    <div class="modal-body">

                        <div class="portal-modal-warning-icon">
                            <i class="bi bi-person-gear"></i>
                        </div>

                        <p class="mb-0">

                            @if ($persona->estado === 'activo')

                                ¿Desea desactivar a
                                <strong>{{ $persona->nombre_completo }}</strong>?
                                Su información histórica se conservará.

                            @else

                                ¿Desea activar nuevamente a
                                <strong>{{ $persona->nombre_completo }}</strong>?

                            @endif

                        </p>

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
                            class="btn
                                {{ $persona->estado === 'activo'
                                    ? 'portal-btn-danger'
                                    : 'portal-btn-primary' }}"
                        >
                            {{ $persona->estado === 'activo'
                                ? 'Desactivar'
                                : 'Activar' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection