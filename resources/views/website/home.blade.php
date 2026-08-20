@extends('layouts.web')

@section('title', 'Inicio | Edumerican Academy Honduras')

@section(
    'description',
    'Aprende inglés con Edumerican Academy Honduras mediante una formación práctica, progresiva y orientada a la comunicación.'
)

@section('content')

    {{-- =====================================================
        HERO INSTITUCIONAL
    ====================================================== --}}
    <section class="edma-hero">

        <div class="edma-container">

            <div class="edma-hero__panel">

                {{-- Contenido --}}
                <div class="edma-hero__content">

                    <div class="edma-hero__eyebrow">
                        <span></span>
                        Edumerican Academy Honduras
                    </div>

                    <h1 class="edma-hero__title">
                        El inglés que transforma
                        <span class="edma-hero__highlight">
                            tu futuro.
                        </span>
                    </h1>

                    <p class="edma-hero__description">
                        Formación en inglés para niños, jóvenes y adultos,
                        diseñada para desarrollar confianza, comunicación
                        y habilidades aplicables a situaciones reales.
                    </p>

                    <div class="edma-hero__actions">

                        <a
                            href="{{ route('inscripciones.solicitud') }}"
                            class="edma-button edma-button--gold"
                        >
                            Solicitar inscripción
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                        <a
                            href="{{ route('website.courses') }}"
                            class="edma-button edma-button--outline-light"
                        >
                            Conocer programas
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                    <div class="edma-hero__note">
                        <i class="bi bi-check-circle-fill"></i>
                        Programas estructurados en niveles progresivos.
                    </div>

                </div>

                {{-- Fotografía --}}
                <div class="edma-hero__media">

                    <img
                        src="{{ asset('images/website/hero-students.jpg') }}"
                        alt="Estudiante de Edumerican Academy aprendiendo inglés"
                        class="edma-hero__image"
                    >

                    <div class="edma-hero__media-overlay"></div>

                    <div class="edma-hero__media-card">

                        <span class="edma-hero__media-icon">
                            <i class="bi bi-chat-square-text-fill"></i>
                        </span>

                        <div>
                            <small>Metodología comunicativa</small>
                            <strong>Aprende utilizando el idioma</strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        CIFRAS / DATOS CLAVE
    ====================================================== --}}
    <section class="edma-stats">

        <div class="edma-container">

            <div class="edma-stats__grid">

                <article class="edma-stat">
                    <strong>7</strong>
                    <span>Niveles formativos</span>
                </article>

                <article class="edma-stat">
                    <strong>+15</strong>
                    <span>Docentes</span>
                </article>

                <article class="edma-stat">
                    <strong>+500</strong>
                    <span>Padres felices</span>
                </article>

                <article class="edma-stat">
                    <strong>+1500</strong>
                    <span>Estudiantes</span>
                </article>

            </div>

        </div>

    </section>


    {{-- =====================================================
        PRESENTACIÓN INSTITUCIONAL
    ====================================================== --}}
    <section class="edma-about-home">

        <div class="edma-container">

            <div class="edma-about-home__layout">

                {{-- Imagen --}}
                <div class="edma-about-home__media">

                    <img
                        src="{{ asset('images/website/about-students.jpg') }}"
                        alt="Estudiantes de Edumerican Academy"
                        class="edma-about-home__image"
                    >

                    <div class="edma-about-home__glass">

                        <span class="edma-about-home__glass-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </span>

                        <div>
                            <small>Formación progresiva</small>
                            <strong>
                                Aprende. Practica. Avanza.
                            </strong>
                        </div>

                    </div>

                </div>

                {{-- Texto --}}
                <div class="edma-about-home__content">

                    <div class="edma-section-eyebrow">
                        Sobre Edumerican
                    </div>

                    <h2 class="edma-about-home__title">
                        Una formación pensada
                        para avanzar contigo.
                    </h2>

                    <p class="edma-about-home__description">
                        Edumerican Academy Honduras ofrece una experiencia
                        de aprendizaje enfocada en el desarrollo progresivo
                        del idioma inglés, combinando formación académica,
                        práctica y acompañamiento durante cada nivel.
                    </p>

                    <div class="edma-about-home__features">

                        <div class="edma-about-feature">

                            <span>
                                <i class="bi bi-chat-dots-fill"></i>
                            </span>

                            <div>
                                <h3>Comunicación real</h3>

                                <p>
                                    Desarrollo de comprensión, expresión,
                                    escucha y conversación.
                                </p>
                            </div>

                        </div>

                        <div class="edma-about-feature">

                            <span>
                                <i class="bi bi-bar-chart-fill"></i>
                            </span>

                            <div>
                                <h3>Progreso estructurado</h3>

                                <p>
                                    Cada nivel fortalece conocimientos
                                    y habilidades adquiridas previamente.
                                </p>
                            </div>

                        </div>

                    </div>

                    <a
                        href="{{ route('website.about') }}"
                        class="edma-about-home__link"
                    >
                        Conocer más sobre la academia

                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>

    </section>





    @include('website.sections.programs-home')

    @include('website.sections.methodology-home')

    @include('website.sections.schedules-home')

    @include('website.sections.campus-home')

    @include('website.sections.trust-home')
    
    @include('website.sections.gallery-home')

    @include('website.sections.admission-cta-home')

    
@endsection