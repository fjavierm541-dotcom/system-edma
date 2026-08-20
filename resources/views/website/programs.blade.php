@extends('layouts.web')

@section(
    'title',
    'Programas de inglés | Edumerican Academy Honduras'
)

@section(
    'description',
    'Conoce los programas de inglés de Edumerican Academy Honduras y explora los niveles que conforman cada recorrido académico.'
)

@section('content')

{{-- =========================================================
     HERO
========================================================= --}}
<section class="edma-programs-hero">

    <div class="edma-container">

        <div class="edma-programs-hero__grid">

            <div class="edma-programs-hero__content">

                <span class="edma-programs-hero__eyebrow">
                    Oferta académica
                </span>

                <h1 class="edma-programs-hero__title">
                    Una formación
                    <span>para cada etapa.</span>
                </h1>

                <p class="edma-programs-hero__description">
                    Explora nuestros programas de inglés y conoce una formación
                    organizada en niveles progresivos, diseñada para acompañarte
                    en cada etapa de tu aprendizaje.
                </p>

                <div class="edma-programs-hero__meta">

                    <div>
                        <strong>{{ $programas->count() }}</strong>

                        <span>
                            {{ $programas->count() === 1
                                ? 'Programa disponible'
                                : 'Programas disponibles' }}
                        </span>
                    </div>

                    <span class="edma-programs-hero__divider"></span>

                    <div>
                        <strong>100%</strong>
                        <span>Formación virtual</span>
                    </div>

                </div>

            </div>


            <div class="edma-programs-hero__visual">

                <div class="edma-programs-hero__glass">

                    <span class="edma-programs-hero__glass-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </span>

                    <small>Formación progresiva</small>

                    <strong>
                        Avanza nivel a nivel
                    </strong>

                    <p>
                        Cada programa organiza tu aprendizaje mediante
                        una ruta académica clara y estructurada.
                    </p>

                    <div class="edma-programs-hero__progress">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

                </div>


                <div class="edma-programs-hero__floating">

                    <i class="bi bi-laptop"></i>

                    <div>
                        <small>Modalidad</small>
                        <strong>Desde donde estés</strong>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     PROGRAMAS DISPONIBLES
========================================================= --}}
<section
    class="edma-programs-catalog"
    id="programas-disponibles"
>

    <div class="edma-container">

        <div class="edma-programs-catalog__header">

            <div>

                <span class="edma-section-eyebrow">
                    Programas disponibles
                </span>

                <h2>
                    Encuentra tu
                    <span>ruta de aprendizaje.</span>
                </h2>

            </div>

            <p>
                Conoce nuestros programas y explora los niveles que
                conforman cada recorrido académico.
            </p>

        </div>


        @forelse($programas as $programa)

            <article class="edma-program-card">

                {{-- Identidad --}}
                <div class="edma-program-card__identity">

                    <div class="edma-program-card__number">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="edma-program-card__icon">
                        <i class="bi bi-book-fill"></i>
                    </div>

                    <span class="edma-program-card__segment">
                        {{ $programa->segmento }}
                    </span>

                </div>


                {{-- Información --}}
                <div class="edma-program-card__content">

                    <span class="edma-program-card__code">
                        {{ $programa->codigo }}
                    </span>

                    <h3>
                        {{ $programa->nombre }}
                    </h3>

                    @if($programa->descripcion)

                        <p>
                            {{ $programa->descripcion }}
                        </p>

                    @endif


                    <div class="edma-program-card__facts">

                        <div>
                            <i class="bi bi-diagram-3"></i>

                            <span>
                                <strong>
                                    {{ $programa->niveles->count() }}
                                </strong>

                                {{ $programa->niveles->count() === 1
                                    ? 'nivel'
                                    : 'niveles' }}
                            </span>
                        </div>


                        <div>
                            <i class="bi bi-camera-video"></i>

                            <span>
                                Formación
                                <strong>virtual</strong>
                            </span>
                        </div>

                    </div>


                    @if($programa->niveles->isNotEmpty())

                        <button
                            type="button"
                            class="edma-program-card__action"
                            data-bs-toggle="modal"
                            data-bs-target="#programModal{{ $programa->id }}"
                        >
                            Conocer más

                            <i class="bi bi-arrow-up-right"></i>
                        </button>

                    @endif

                </div>


                {{-- Vista rápida --}}
                <div class="edma-program-card__preview">

                    <span>
                        Recorrido académico
                    </span>

                    <div class="edma-program-card__levels">

                        @foreach($programa->niveles->take(6) as $nivel)

                            <div title="{{ $nivel->nombre }}">
                                {{ $nivel->codigo }}
                            </div>

                        @endforeach


                        @if($programa->niveles->count() > 6)

                            <div class="edma-program-card__levels-more">
                                +{{ $programa->niveles->count() - 6 }}
                            </div>

                        @endif

                    </div>


                    @if($programa->niveles->isNotEmpty())

                        <small>
                            Desde
                            <strong>
                                {{ $programa->niveles->first()->nombre }}
                            </strong>

                            hasta

                            <strong>
                                {{ $programa->niveles->last()->nombre }}
                            </strong>
                        </small>

                    @endif

                </div>

            </article>


            {{-- =====================================================
                 MODAL DEL PROGRAMA
            ====================================================== --}}
            <div
                class="modal fade edma-program-modal"
                id="programModal{{ $programa->id }}"
                tabindex="-1"
                aria-labelledby="programModalLabel{{ $programa->id }}"
                aria-hidden="true"
            >

                <div
                    class="modal-dialog
                           modal-xl
                           modal-dialog-centered
                           modal-dialog-scrollable"
                >

                    <div class="modal-content">

                        {{-- Cabecera --}}
                        <div class="edma-program-modal__header">

                            <div>

                                <span class="edma-program-modal__eyebrow">
                                    {{ $programa->codigo }}
                                </span>

                                <h2 id="programModalLabel{{ $programa->id }}">
                                    {{ $programa->nombre }}
                                </h2>

                                <p>
                                    Explora el recorrido académico y conoce
                                    cada uno de los niveles disponibles.
                                </p>

                            </div>


                            <button
                                type="button"
                                class="edma-program-modal__close"
                                data-bs-dismiss="modal"
                                aria-label="Cerrar"
                            >
                                <i class="bi bi-x-lg"></i>
                            </button>

                        </div>


                        {{-- Resumen --}}
                        <div class="edma-program-modal__meta">

                            <div>
                                <i class="bi bi-diagram-3"></i>

                                <span>
                                    <small>Recorrido</small>

                                    <strong>
                                        {{ $programa->niveles->count() }}
                                        {{ $programa->niveles->count() === 1
                                            ? 'nivel'
                                            : 'niveles' }}
                                    </strong>
                                </span>
                            </div>


                            <div>
                                <i class="bi bi-camera-video"></i>

                                <span>
                                    <small>Modalidad</small>
                                    <strong>100% virtual</strong>
                                </span>
                            </div>


                            <div>
                                <i class="bi bi-people"></i>

                                <span>
                                    <small>Programa</small>
                                    <strong>{{ $programa->segmento }}</strong>
                                </span>
                            </div>

                        </div>


                        {{-- Ruta de niveles --}}
                        <div class="edma-modal-level-path">

                            <span class="edma-program-modal__section-label">
                                Selecciona un nivel
                            </span>


                            <div class="edma-level-path__route">

                                <div class="edma-level-path__line"></div>

                                @foreach($programa->niveles as $nivel)

                                    <button
                                        type="button"
                                        class="edma-level-node
                                               {{ $loop->first ? 'is-active' : '' }}"
                                        data-level-target="modal-nivel-{{ $nivel->id }}"
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                    >

                                        <span class="edma-level-node__position">
                                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>

                                        <span class="edma-level-node__point">
                                            <i class="bi bi-check-lg"></i>
                                        </span>

                                        <span class="edma-level-node__code">
                                            {{ $nivel->codigo }}
                                        </span>

                                        <strong>
                                            {{ $nivel->nombre }}
                                        </strong>

                                    </button>

                                @endforeach

                            </div>


                            {{-- Detalles --}}
                            <div class="edma-level-details">

                                @foreach($programa->niveles as $nivel)

                                    <article
                                        class="edma-level-detail
                                               {{ $loop->first ? 'is-active' : '' }}"
                                        id="modal-nivel-{{ $nivel->id }}"
                                    >

                                        <div class="edma-level-detail__main">

                                            <div class="edma-level-detail__number">
                                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                            </div>


                                            <div>

                                                <span class="edma-level-detail__label">
                                                    Nivel académico
                                                </span>

                                                <h3>
                                                    {{ $nivel->nombre }}
                                                </h3>


                                                @if($nivel->descripcion)

                                                    <p>
                                                        {{ $nivel->descripcion }}
                                                    </p>

                                                @else

                                                    <p>
                                                        Continúa desarrollando
                                                        tus habilidades mediante
                                                        una formación progresiva
                                                        y organizada.
                                                    </p>

                                                @endif

                                            </div>

                                        </div>


                                        <div class="edma-level-detail__info">

                                            <div class="edma-level-detail__fact">

                                                <span class="edma-level-detail__fact-icon">
                                                    <i class="bi bi-calendar3"></i>
                                                </span>

                                                <div>
                                                    <small>Duración</small>

                                                    <strong>
                                                        {{ $nivel->duracion_semanas }}
                                                        {{ $nivel->duracion_semanas == 1
                                                            ? 'semana'
                                                            : 'semanas' }}
                                                    </strong>
                                                </div>

                                            </div>


                                            <div class="edma-level-detail__fact">

                                                <span class="edma-level-detail__fact-icon">
                                                    <i class="bi bi-laptop"></i>
                                                </span>

                                                <div>
                                                    <small>Modalidad</small>
                                                    <strong>100% virtual</strong>
                                                </div>

                                            </div>


                                            <div class="edma-level-detail__fact">

                                                <span class="edma-level-detail__fact-icon">
                                                    <i class="bi bi-award"></i>
                                                </span>

                                                <div>
                                                    <small>Al completar</small>
                                                    <strong>Diplomado por nivel</strong>
                                                </div>

                                            </div>

                                        </div>

                                    </article>

                                @endforeach

                            </div>

                        </div>


                        {{-- Footer del modal --}}
                        <div class="edma-program-modal__footer">

                            <div>
                                <i class="bi bi-info-circle"></i>

                                <span>
                                    ¿Quieres comenzar tu proceso de ingreso?
                                </span>
                            </div>

                            <a
                                href="{{ route('inscripciones.solicitud') }}"
                                class="edma-level-detail__cta"
                            >
                                Solicitar inscripción

                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="edma-programs-empty">

                <i class="bi bi-journal-bookmark"></i>

                <h3>
                    Programas próximamente
                </h3>

                <p>
                    Actualmente no hay programas académicos
                    disponibles para mostrar.
                </p>

            </div>

        @endforelse

    </div>

</section>


{{-- =========================================================
     EXPERIENCIA ACADÉMICA
========================================================= --}}
<section class="edma-program-experience">

    <div class="edma-container">

        <div class="edma-program-experience__grid">

            <div class="edma-program-experience__content">

                <span class="edma-section-eyebrow">
                    Experiencia académica
                </span>

                <h2>
                    Aprende inglés
                    <span>para comunicarte.</span>
                </h2>

                <p>
                    Nuestra formación integra las principales habilidades
                    del idioma mediante una experiencia virtual,
                    práctica y progresiva.
                </p>


                <div class="edma-program-experience__skills">

                    <span>
                        <i class="bi bi-book"></i>
                        Lectura
                    </span>

                    <span>
                        <i class="bi bi-pencil-square"></i>
                        Escritura
                    </span>

                    <span>
                        <i class="bi bi-headphones"></i>
                        Escucha
                    </span>

                    <span>
                        <i class="bi bi-chat-dots"></i>
                        Habla
                    </span>

                </div>

            </div>


            <div class="edma-program-experience__facts">

                <article class="edma-program-experience__fact">

                    <span class="edma-program-experience__fact-number">
                        01
                    </span>

                    <div>
                        <i class="bi bi-camera-video"></i>

                        <h3>Formación virtual</h3>

                        <p>
                            Aprende desde cualquier lugar mediante
                            una experiencia académica completamente
                            en línea.
                        </p>
                    </div>

                </article>


                <article class="edma-program-experience__fact">

                    <span class="edma-program-experience__fact-number">
                        02
                    </span>

                    <div>
                        <i class="bi bi-calendar3"></i>

                        <h3>Formación progresiva</h3>

                        <p>
                            Cada programa organiza el aprendizaje
                            mediante niveles estructurados y
                            secuenciales.
                        </p>
                    </div>

                </article>


                <article class="edma-program-experience__fact">

                    <span class="edma-program-experience__fact-number">
                        03
                    </span>

                    <div>
                        <i class="bi bi-award"></i>

                        <h3>Diplomado por nivel</h3>

                        <p>
                            Al completar satisfactoriamente cada nivel,
                            recibes el diplomado correspondiente.
                        </p>
                    </div>

                </article>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     Cierre / Solicitud de inscripción
========================================================= --}}

<section class="edma-programs-cta">

    <div class="edma-container">

        <div
            class="edma-programs-cta__panel edma-reveal"
            data-reveal="left"
        >

            <div class="edma-programs-cta__content">

                <span class="edma-programs-cta__eyebrow">
                    Comienza tu formación
                </span>

                <h2>
                    ¿Encontraste el programa
                    <span>ideal para ti?</span>
                </h2>

                <p>
                    Envía tu solicitud de inscripción en línea y nuestro equipo
                    podrá revisar tu información para continuar con tu proceso
                    de ingreso a Edumerican Academy.
                </p>

                <div class="edma-programs-cta__actions">

                    <a
                        href="{{ route('inscripciones.solicitud') }}"
                        class="edma-programs-cta__primary"
                    >
                        Solicitar inscripción

                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                    <a
                        href="{{ route('website.contact') }}"
                        class="edma-programs-cta__secondary"
                    >
                        Tengo una consulta

                        <i class="bi bi-chat-dots"></i>
                    </a>

                </div>

            </div>


            <div class="edma-programs-cta__facts">

                <article>

                    <span>
                        <i class="bi bi-check2-circle"></i>
                    </span>

                    <div>
                        <small>Matrícula</small>

                        <strong>
                            Gratuita
                        </strong>
                    </div>

                </article>


                <article>

                    <span>
                        <i class="bi bi-wallet2"></i>
                    </span>

                    <div>
                        <small>Primera mensualidad</small>

                        <strong>
                            L700
                        </strong>
                    </div>

                </article>


                <div class="edma-programs-cta__note">

                    <i class="bi bi-info-circle-fill"></i>

                    <p>
                        El pago inicial corresponde a la primera mensualidad.
                        No es un cobro por matrícula.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection