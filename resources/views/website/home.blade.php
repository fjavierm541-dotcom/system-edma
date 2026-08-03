@extends('layouts.web')

@section('title', 'Inicio | Edumerican Academy Honduras')

@section(
    'description',
    'Aprende inglés con una metodología moderna, práctica y orientada a resultados en Edumerican Academy Honduras.'
)

@section('content')

    <section class="edma-hero">

        {{-- Decoración ambiental --}}
        <div
            class="edma-hero__glow edma-hero__glow--left"
            aria-hidden="true"
        ></div>

        <div
            class="edma-hero__glow edma-hero__glow--right"
            aria-hidden="true"
        ></div>

        <div
            class="edma-hero__grid"
            aria-hidden="true"
        ></div>

        <div class="edma-container">

            {{-- Contenido principal --}}
            <div class="edma-hero__main">

                <div class="edma-hero__content">

                    <div class="edma-hero__eyebrow">
                        <span
                            class="edma-hero__eyebrow-dot"
                            aria-hidden="true"
                        ></span>

                        Educación diseñada para avanzar
                    </div>

                    <h1 class="edma-hero__title">
                        El inglés que transforma
                        <span>tu futuro.</span>
                    </h1>

                    <p class="edma-hero__description">
                        Desarrolla las habilidades que necesitas para comunicarte
                        con confianza mediante una experiencia académica moderna,
                        práctica y cercana.
                    </p>

                    <div class="edma-hero__actions">

                        <a
                            href="{{ route('website.admissions') }}"
                            class="edma-hero__button edma-hero__button--primary"
                        >
                            <span>Solicitar inscripción</span>

                            <i
                                class="bi bi-arrow-up-right"
                                aria-hidden="true"
                            ></i>
                        </a>

                        <a
                            href="{{ route('website.courses') }}"
                            class="edma-hero__button edma-hero__button--secondary"
                        >
                            <span>Explorar programas</span>

                            <i
                                class="bi bi-arrow-right"
                                aria-hidden="true"
                            ></i>
                        </a>

                    </div>

                    <div class="edma-hero__support">

                        <div class="edma-hero__support-icon">
                            <i
                                class="bi bi-check2"
                                aria-hidden="true"
                            ></i>
                        </div>

                        <p>
                            Programas para niños, jóvenes y adultos.
                        </p>

                    </div>

                </div>

                {{-- Fotografía principal --}}
                <div class="edma-hero__visual">

                    <div
                        class="edma-hero__visual-shape"
                        aria-hidden="true"
                    ></div>

                    <figure class="edma-hero__image-card">

                        <img
                            src="{{ asset('images/website/hero-students.jpg') }}"
                            alt="Estudiantes aprendiendo inglés en Edumerican Academy"
                            class="edma-hero__image"
                        >

                        <div
                            class="edma-hero__image-overlay"
                            aria-hidden="true"
                        ></div>

                        <figcaption class="edma-hero__image-caption">

                            <span class="edma-hero__caption-icon">
                                <i
                                    class="bi bi-chat-dots-fill"
                                    aria-hidden="true"
                                ></i>
                            </span>

                            <span class="edma-hero__caption-content">
                                <small>Metodología comunicativa</small>
                                <strong>Aprende usando el idioma</strong>
                            </span>

                        </figcaption>

                        <div class="edma-hero__badge">

                            <span class="edma-hero__badge-number">
                                7
                            </span>

                            <span class="edma-hero__badge-text">
                                Niveles<br>
                                formativos
                            </span>

                        </div>

                    </figure>

                </div>

            </div>

            {{-- Tarjetas de beneficios --}}
            <div class="edma-hero__features">

                <article class="edma-feature-card">

                    <span class="edma-feature-card__number">
                        01
                    </span>

                    <span class="edma-feature-card__icon">
                        <i
                            class="bi bi-mortarboard-fill"
                            aria-hidden="true"
                        ></i>
                    </span>

                    <div class="edma-feature-card__content">
                        <h2>Programa completo</h2>

                        <p>
                            Formación progresiva organizada en niveles.
                        </p>
                    </div>

                </article>

                <article class="edma-feature-card">

                    <span class="edma-feature-card__number">
                        02
                    </span>

                    <span class="edma-feature-card__icon">
                        <i
                            class="bi bi-people-fill"
                            aria-hidden="true"
                        ></i>
                    </span>

                    <div class="edma-feature-card__content">
                        <h2>Aprendizaje práctico</h2>

                        <p>
                            Clases orientadas a la comunicación real.
                        </p>
                    </div>

                </article>

                <article class="edma-feature-card">

                    <span class="edma-feature-card__number">
                        03
                    </span>

                    <span class="edma-feature-card__icon">
                        <i
                            class="bi bi-laptop-fill"
                            aria-hidden="true"
                        ></i>
                    </span>

                    <div class="edma-feature-card__content">
                        <h2>EDMA Campus</h2>

                        <p>
                            Recursos académicos disponibles en línea.
                        </p>
                    </div>

                </article>

                <article class="edma-feature-card">

                    <span class="edma-feature-card__number">
                        04
                    </span>

                    <span class="edma-feature-card__icon">
                        <i
                            class="bi bi-calendar2-check-fill"
                            aria-hidden="true"
                        ></i>
                    </span>

                    <div class="edma-feature-card__content">
                        <h2>Horarios accesibles</h2>

                        <p>
                            Opciones para distintas edades y rutinas.
                        </p>
                    </div>

                </article>

            </div>

        </div>

    </section>

@endsection