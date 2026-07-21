@extends('layouts.web')

@section('title', 'Inicio | Edumerican Academy Honduras')

@section(
    'description',
    'Aprende inglés con una metodología moderna, práctica y orientada a resultados.'
)

@section('content')

    <section class="edma-hero">

        <div class="edma-hero__glow edma-hero__glow--one"></div>
        <div class="edma-hero__glow edma-hero__glow--two"></div>
        <div class="edma-hero__grid"></div>

        <div class="edma-container">
            <div class="edma-hero__layout">

                <div class="edma-hero__content">

                    <div class="edma-hero__eyebrow">
                        <span class="edma-hero__eyebrow-dot"></span>
                        Educación diseñada para avanzar
                    </div>

                    <h1 class="edma-hero__title">
                        El inglés que transforma
                        <span>tu futuro.</span>
                    </h1>

                    <p class="edma-hero__description">
                        Aprende con una experiencia académica moderna,
                        práctica y cercana, diseñada para ayudarte a
                        comunicarte con confianza en el mundo real.
                    </p>

                    <div class="edma-hero__actions">

                        <a
                            href="{{ route('website.admissions') }}"
                            class="edma-hero__button edma-hero__button--primary"
                        >
                            Solicitar inscripción
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                        <a
                            href="{{ route('website.courses') }}"
                            class="edma-hero__button edma-hero__button--secondary"
                        >
                            Explorar programas
                            <i class="bi bi-play-circle"></i>
                        </a>

                    </div>

                    <div class="edma-hero__trust">

                        <div class="edma-hero__trust-item">
                            <strong>7</strong>
                            <span>Niveles formativos</span>
                        </div>

                        <div class="edma-hero__trust-divider"></div>

                        <div class="edma-hero__trust-item">
                            <strong>12</strong>
                            <span>Semanas por nivel</span>
                        </div>

                        <div class="edma-hero__trust-divider"></div>

                        <div class="edma-hero__trust-item">
                            <strong>100%</strong>
                            <span>Enfoque comunicativo</span>
                        </div>

                    </div>

                </div>

                <div class="edma-hero__visual">

                    <div class="edma-hero__visual-orbit"></div>

                    <div class="edma-hero__image-card">

                        <div class="edma-hero__image-placeholder">
                            <i class="bi bi-people-fill"></i>
                            <span>Imagen institucional</span>
                        </div>

                        <div class="edma-hero__image-overlay"></div>

                        <div class="edma-hero__image-label">
                            <span class="edma-hero__image-label-icon">
                                <i class="bi bi-translate"></i>
                            </span>

                            <span>
                                <small>Experiencia EDMA</small>
                                Aprende haciendo
                            </span>
                        </div>

                    </div>

                    <article class="edma-floating-card edma-floating-card--level">
                        <div class="edma-floating-card__icon">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>

                        <div>
                            <small>Tu progreso</small>
                            <strong>Nivel B1</strong>
                        </div>
                    </article>

                    <article class="edma-floating-card edma-floating-card--campus">
                        <div class="edma-floating-card__icon">
                            <i class="bi bi-laptop"></i>
                        </div>

                        <div>
                            <small>Aprende donde estés</small>
                            <strong>EDMA Campus</strong>
                        </div>
                    </article>

                    <article class="edma-floating-card edma-floating-card--schedule">
                        <span class="edma-floating-card__status"></span>

                        <div>
                            <small>Próximo inicio</small>
                            <strong>Nuevos grupos</strong>
                        </div>
                    </article>

                </div>

            </div>
        </div>

    </section>

@endsection