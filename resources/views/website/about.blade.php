@extends('layouts.web')

@section('title', 'Nosotros | Edumerican Academy Honduras')

@section(
    'description',
    'Conoce Edumerican Academy Honduras, nuestra propuesta educativa y la experiencia académica que acompaña a nuestros estudiantes.'
)

@section('content')

{{-- =========================================================
     HERO NOSOTROS
========================================================= --}}
<section class="edma-about-page-hero">

    <div class="edma-container">

        <div class="edma-about-page-hero__layout">

            {{-- Contenido --}}
            <div class="edma-about-page-hero__content">

                <span class="edma-about-page-hero__eyebrow">
                    Edumerican Academy Honduras
                </span>

                <h1 class="edma-about-page-hero__title">
                    Aprender un idioma
                    <span>abre nuevas posibilidades.</span>
                </h1>

                <p class="edma-about-page-hero__description">
                    Acompañamos a niños, jóvenes y adultos en una formación
                    progresiva del idioma inglés, combinando aprendizaje,
                    práctica y herramientas digitales dentro de una
                    experiencia académica completamente virtual.
                </p>

                <div class="edma-about-page-hero__actions">

                    <a
                        href="#quienes-somos"
                        class="edma-about-page-hero__primary"
                    >
                        Conocer EDMA
                        <i class="bi bi-arrow-down"></i>
                    </a>

                    <a
                        href="{{ route('website.courses') }}"
                        class="edma-about-page-hero__secondary"
                    >
                        Ver programas
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- Composición visual --}}
            <div class="edma-about-page-hero__visual">

                <div class="edma-about-page-hero__image">

                    <img
                        src="{{ asset('images/website/about/about-hero.jpg') }}"
                        alt="Experiencia académica en Edumerican Academy Honduras"
                    >

                    <div class="edma-about-page-hero__overlay"></div>

                </div>


                <div class="edma-about-page-hero__glass">

                    <span>
                        <i class="bi bi-mortarboard-fill"></i>
                    </span>

                    <div>
                        <small>Formación EDMA</small>
                        <strong>
                            Aprende. Practica. Avanza.
                        </strong>
                    </div>

                </div>


                <div class="edma-about-page-hero__index">

                    <strong>7</strong>

                    <span>
                        niveles<br>
                        formativos
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     QUIÉNES SOMOS
========================================================= --}}
<section
    class="edma-about-intro"
    id="quienes-somos"
>

    <div class="edma-container">

        <div class="edma-about-intro__layout">

            {{-- Identidad --}}
            <div
                class="edma-about-intro__heading edma-reveal"
                data-reveal="left"
            >

                <span class="edma-section-eyebrow">
                    Quiénes somos
                </span>

                <h2>
                    Una academia construida
                    <span>alrededor del aprendizaje.</span>
                </h2>

            </div>


            {{-- Texto --}}
            <div
                class="edma-about-intro__content edma-reveal"
                data-reveal="right"
            >

                <p class="edma-about-intro__lead">
                    Edumerican Academy Honduras ofrece formación en inglés
                    para estudiantes de diferentes edades mediante programas
                    organizados en niveles progresivos y una experiencia
                    académica completamente virtual.
                </p>

                <p>
                    Nuestro enfoque busca que cada estudiante avance
                    desarrollando las principales habilidades del idioma
                    mientras fortalece su confianza para comprender,
                    expresarse y comunicarse.
                </p>


                <div class="edma-about-intro__signature">

                    <span></span>

                    <div>
                        <small>Nuestra forma de avanzar</small>

                        <strong>
                            Formación progresiva, práctica y accesible.
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Banda visual --}}
        <div class="edma-about-intro__visual">

            <figure
                class="edma-about-intro__photo edma-reveal"
                data-reveal="left"
            >

                <img
                    src="{{ asset('images/website/about/about-class.jpg') }}"
                    alt="Estudiantes participando en una clase de inglés"
                >

            </figure>


            <div class="edma-about-intro__principles">

                <article class="edma-about-principle">

                    <span>
                        <i class="bi bi-chat-dots-fill"></i>
                    </span>

                    <div>
                        <small>01</small>
                        <strong>Comunicación</strong>

                        <p>
                            Aprende utilizando el idioma en situaciones
                            que fortalecen tu comprensión y expresión.
                        </p>
                    </div>

                </article>


                <article class="edma-about-principle">

                    <span>
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>

                    <div>
                        <small>02</small>
                        <strong>Progreso</strong>

                        <p>
                            Cada nivel construye nuevas habilidades sobre
                            los conocimientos adquiridos anteriormente.
                        </p>
                    </div>

                </article>


                <article class="edma-about-principle">

                    <span>
                        <i class="bi bi-laptop-fill"></i>
                    </span>

                    <div>
                        <small>03</small>
                        <strong>Accesibilidad</strong>

                        <p>
                            Continúa tu formación mediante una experiencia
                            académica diseñada para desarrollarse en línea.
                        </p>
                    </div>

                </article>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     MISIÓN, VISIÓN Y VALORES
========================================================= --}}
<section class="edma-about-purpose">

    <div class="edma-container">

        <div class="edma-about-purpose__header edma-reveal">

            <span class="edma-section-eyebrow">
                Nuestra identidad
            </span>

            <h2>
                Lo que nos guía
                <span>como institución.</span>
            </h2>

        </div>


        <div class="edma-about-purpose__grid">

            {{-- Misión --}}
            <article
                class="edma-purpose-card edma-purpose-card--mission edma-reveal"
                data-reveal="left"
            >

                <div class="edma-purpose-card__top">

                    <span class="edma-purpose-card__number">
                        01
                    </span>

                    <span class="edma-purpose-card__icon">
                        <i class="bi bi-compass-fill"></i>
                    </span>

                </div>

                <span class="edma-purpose-card__label">
                    Nuestra Misión
                </span>

                <h3>
                    Educación para afrontar
                    un mundo cada vez más competitivo.
                </h3>

                <p>
                    Promover la enseñanza y aprendizaje del inglés y otras áreas
                    esenciales del conocimiento, como una herramienta fundamental,
                    que permitirá a niños, jóvenes y adultos adquirir capacidades
                    competitivas para afrontar los retos de un mundo globalizado
                    cada vez más exigente.
                </p>

                <span
                    class="edma-purpose-card__accent"
                    aria-hidden="true"
                ></span>

            </article>


            {{-- Visión --}}
            <article
                class="edma-purpose-card edma-purpose-card--vision edma-reveal"
                data-reveal="right"
            >

                <div class="edma-purpose-card__top">

                    <span class="edma-purpose-card__number">
                        02
                    </span>

                    <span class="edma-purpose-card__icon">
                        <i class="bi bi-eye-fill"></i>
                    </span>

                </div>

                <span class="edma-purpose-card__label">
                    Nuestra Visión
                </span>

                <h3>
                    Ser una referencia en
                    oportunidades educativas de calidad.
                </h3>

                <p>
                    Ser la academia educativa de referencia que propicie el
                    acceso a oportunidades educativas de alta calidad y que
                    atienda las necesidades individuales de aprendizaje de
                    nuestra población estudiantil.
                </p>

                <span
                    class="edma-purpose-card__accent"
                    aria-hidden="true"
                ></span>

            </article>

        </div>


        {{-- Valores --}}
        <article
            class="edma-about-values edma-reveal"
            data-reveal="left"
        >

            <div class="edma-about-values__heading">

                <span class="edma-about-values__icon">
                    <i class="bi bi-stars"></i>
                </span>

                <div>
                    <small>Lo que nos representa</small>

                    <h3>
                        Nuestros Valores
                    </h3>
                </div>

            </div>


            <div class="edma-about-values__content">

                <p>
                    Somos una institución altamente comprometida con la educación
                    de nuestro país, y le apostamos a la innovación y calidad en
                    nuestros servicios educativos. Contamos con un equipo de
                    profesores con amplia experiencia en el ámbito de la educación
                    y la docencia. Asimismo, nos mantenemos en constante preparación
                    para ofrecer siempre la mejor atención a nuestros estudiantes.
                </p>


                <div class="edma-about-values__keywords">

                    <span>
                        <i class="bi bi-check2"></i>
                        Compromiso
                    </span>

                    <span>
                        <i class="bi bi-check2"></i>
                        Innovación
                    </span>

                    <span>
                        <i class="bi bi-check2"></i>
                        Calidad
                    </span>

                    <span>
                        <i class="bi bi-check2"></i>
                        Preparación
                    </span>

                </div>

            </div>

        </article>

    </div>

</section>


{{-- =========================================================
     EQUIPO ACADÉMICO / FORMA DE ENSEÑAR
========================================================= --}}
<section class="edma-about-academic">

    <div class="edma-container">

        <div class="edma-about-academic__layout">

            {{-- Imagen --}}
            <div
                class="edma-about-academic__media edma-reveal"
                data-reveal="left"
            >

                <img
                    src="{{ asset('images/website/about/about-teacher.jpg') }}"
                    alt="Docente acompañando una clase de inglés"
                >

                <div class="edma-about-academic__overlay"></div>

                <div class="edma-about-academic__glass">

                    <span>
                        <i class="bi bi-person-badge-fill"></i>
                    </span>

                    <div>
                        <small>Equipo académico</small>
                        <strong>
                            Experiencia y preparación continua
                        </strong>
                    </div>

                </div>

            </div>


            {{-- Contenido --}}
            <div
                class="edma-about-academic__content edma-reveal"
                data-reveal="right"
            >

                <span class="edma-section-eyebrow">
                    Nuestra forma de enseñar
                </span>

                <h2>
                    Docentes que acompañan
                    <span>tu proceso de aprendizaje.</span>
                </h2>

                <p>
                    Contamos con profesores con experiencia en educación y
                    docencia, comprometidos con una formación práctica,
                    cercana y orientada al desarrollo progresivo del idioma.
                </p>


                <div class="edma-about-academic__pillars">

                    <article>

                        <span class="edma-about-academic__pillar-icon">
                            <i class="bi bi-person-check-fill"></i>
                        </span>

                        <div>
                            <small>01</small>

                            <h3>
                                Experiencia docente
                            </h3>

                            <p>
                                Profesores preparados para orientar y acompañar
                                el proceso académico de cada estudiante.
                            </p>
                        </div>

                    </article>


                    <article>

                        <span class="edma-about-academic__pillar-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </span>

                        <div>
                            <small>02</small>

                            <h3>
                                Preparación continua
                            </h3>

                            <p>
                                Nuestro equipo se mantiene en constante
                                actualización para fortalecer la calidad
                                de la experiencia educativa.
                            </p>
                        </div>

                    </article>


                    <article>

                        <span class="edma-about-academic__pillar-icon">
                            <i class="bi bi-chat-square-text-fill"></i>
                        </span>

                        <div>
                            <small>03</small>

                            <h3>
                                Aprendizaje práctico
                            </h3>

                            <p>
                                Las clases buscan que el estudiante participe,
                                practique y utilice el idioma de manera activa.
                            </p>
                        </div>

                    </article>

                </div>


                <a
                    href="{{ route('website.courses') }}"
                    class="edma-about-academic__link"
                >
                    Conocer nuestros programas

                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     CIERRE / CTA
========================================================= --}}
<section class="edma-about-cta">

    <div class="edma-container">

        <div
            class="edma-about-cta__panel edma-reveal"
            data-reveal="left"
        >

            <div class="edma-about-cta__content">

                <span class="edma-about-cta__eyebrow">
                    Continúa con EDMA
                </span>

                <h2>
                    Conoce una formación
                    <span>pensada para avanzar contigo.</span>
                </h2>

                <p>
                    Explora nuestros programas académicos o inicia tu solicitud
                    de inscripción para comenzar tu proceso de ingreso a
                    Edumerican Academy.
                </p>

                <div class="edma-about-cta__actions">

                    <a
                        href="{{ route('website.courses') }}"
                        class="edma-about-cta__secondary"
                    >
                        Ver programas

                        <i class="bi bi-arrow-right"></i>
                    </a>

                    <a
                        href="{{ route('inscripciones.solicitud') }}"
                        class="edma-about-cta__primary"
                    >
                        Solicitar inscripción

                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </div>

            </div>


            <div class="edma-about-cta__visual">

                <span class="edma-about-cta__icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>

                <div>
                    <small>Edumerican Academy Honduras</small>

                    <strong>
                        Aprende. Practica. Avanza.
                    </strong>
                </div>

            </div>

        </div>

    </div>

</section>

@endsection