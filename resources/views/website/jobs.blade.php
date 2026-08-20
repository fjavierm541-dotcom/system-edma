@extends('layouts.web')
@section(
    'title',
    'Trabaja con nosotros | Edumerican Academy Honduras'
)

@section(
    'description',
    'Conoce las oportunidades para formar parte del equipo de Edumerican Academy Honduras y contribuir a la formación de nuestros estudiantes.'
)

@section('content')

{{-- =========================================================
     HERO EMPLEOS
========================================================= --}}
<section class="edma-jobs-hero">

    <div class="edma-container">

        <div class="edma-jobs-hero__layout">

            <div class="edma-jobs-hero__content">

                <span class="edma-jobs-hero__eyebrow">
                    Trabaja con nosotros
                </span>

                <h1 class="edma-jobs-hero__title">
                    Comparte tu experiencia.
                    <span>Inspira nuevas oportunidades.</span>
                </h1>

                <p class="edma-jobs-hero__description">
                    Forma parte de un equipo comprometido con la enseñanza,
                    la innovación y el desarrollo académico de estudiantes
                    que buscan fortalecer sus habilidades en inglés.
                </p>

                <div class="edma-jobs-hero__actions">

                    <a
                        href="#vacantes"
                        class="edma-jobs-hero__primary"
                    >
                        Ver vacantes

                        <i class="bi bi-arrow-down"></i>
                    </a>

                    <a
                        href="{{ route('website.about') }}"
                        class="edma-jobs-hero__secondary"
                    >
                        Conocer EDMA

                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            <div class="edma-jobs-hero__visual">

                <div class="edma-jobs-hero__image">

                    <img
                        src="{{ asset('images/website/jobs/jobs-hero.jpg') }}"
                        alt="Docente impartiendo una clase virtual"
                        loading="lazy"
                        decoding="async"
                    >

                    <div class="edma-jobs-hero__overlay"></div>

                </div>


                <div class="edma-jobs-hero__glass">

                    <span>
                        <i class="bi bi-person-video3"></i>
                    </span>

                    <div>
                        <small>Equipo EDMA</small>

                        <strong>
                            Enseña desde donde estés.
                        </strong>
                    </div>

                </div>


                <div class="edma-jobs-hero__badge">

                    <i class="bi bi-wifi"></i>

                    <span>
                        Oportunidades<br>
                        100% virtuales
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     POR QUÉ TRABAJAR CON EDMA
========================================================= --}}
<section class="edma-jobs-benefits">

    <div class="edma-container">

        <div class="edma-jobs-benefits__header">

            <div
                class="edma-reveal"
                data-reveal="left"
            >

                <span class="edma-section-eyebrow">
                    Experiencia profesional
                </span>

                <h2>
                    Un espacio para enseñar,
                    <span>crecer y aportar.</span>
                </h2>

            </div>

            <p
                class="edma-reveal"
                data-reveal="right"
            >
                En EDMA buscamos crear un entorno de trabajo donde nuestros
                docentes puedan acompañar a los estudiantes, fortalecer sus
                habilidades profesionales y contribuir a una experiencia
                educativa de calidad.
            </p>

        </div>


        <div class="edma-jobs-benefits__grid">

            <article class="edma-job-benefit edma-reveal">

                <span>
                    <i class="bi bi-house-laptop-fill"></i>
                </span>

                <div>
                    <small>01</small>

                    <h3>
                        Trabajo remoto
                    </h3>

                    <p>
                        Desarrolla tu labor docente desde casa o desde
                        cualquier lugar con conexión a Internet.
                    </p>
                </div>

            </article>


            <article
                class="edma-job-benefit edma-reveal"
                style="--reveal-delay: 90ms;"
            >

                <span>
                    <i class="bi bi-people-fill"></i>
                </span>

                <div>
                    <small>02</small>

                    <h3>
                        Comunidad académica
                    </h3>

                    <p>
                        Forma parte de un equipo orientado al aprendizaje,
                        colaboración y acompañamiento de estudiantes.
                    </p>
                </div>

            </article>


            <article
                class="edma-job-benefit edma-reveal"
                style="--reveal-delay: 180ms;"
            >

                <span>
                    <i class="bi bi-graph-up-arrow"></i>
                </span>

                <div>
                    <small>03</small>

                    <h3>
                        Desarrollo profesional
                    </h3>

                    <p>
                        Continúa fortaleciendo tus habilidades y experiencia
                        dentro de un entorno educativo dinámico.
                    </p>
                </div>

            </article>

        </div>

    </div>

</section>


{{-- =========================================================
     VACANTES
========================================================= --}}
<section
    class="edma-jobs-openings"
    id="vacantes"
>

    <div class="edma-container">

        <div class="edma-jobs-openings__header">

            <div>

                <span class="edma-section-eyebrow">
                    Oportunidades profesionales
                </span>

                <h2>
                    Forma parte de nuestro
                    <span>banco de talento.</span>
                </h2>

            </div>

            <p>
                Si tu perfil coincide con nuestras áreas de interés, puedes
                compartir tu información para que EDMA pueda considerarte
                cuando surjan futuras oportunidades.
            </p>

        </div>


        <article class="edma-job-opening edma-reveal">

            {{-- Identidad --}}
            <div class="edma-job-opening__identity">

                <span class="edma-job-opening__status">
                    Perfil de interés
                </span>

                <span class="edma-job-opening__icon">
                    <i class="bi bi-person-video3"></i>
                </span>

                <div>
                    <small>Área académica</small>

                    <strong>
                        Profesor de inglés virtual
                    </strong>
                </div>

            </div>


            {{-- Descripción --}}
            <div class="edma-job-opening__content">

                <h3>
                    Profesor de inglés virtual
                </h3>

                <p>
                    Buscamos docentes comprometidos con la enseñanza del
                    inglés y con interés en formar parte de una experiencia
                    educativa completamente virtual.
                </p>

                <p>
                    Desde EDMA podrás acompañar a estudiantes en su proceso
                    de aprendizaje dentro de un entorno dinámico, práctico
                    y orientado al desarrollo de habilidades comunicativas.
                </p>


                <div class="edma-job-opening__conditions">

                    <div>
                        <i class="bi bi-house-fill"></i>

                        <span>
                            <small>Modalidad</small>
                            <strong>Trabajo desde casa</strong>
                        </span>
                    </div>

                    <div>
                        <i class="bi bi-clock-fill"></i>

                        <span>
                            <small>Horario</small>
                            <strong>16:00 a 21:00</strong>
                        </span>
                    </div>

                    <div>
                        <i class="bi bi-calendar2-check-fill"></i>

                        <span>
                            <small>Disponibilidad</small>
                            <strong>En horarios mencionados</strong>
                        </span>
                    </div>

                </div>

            </div>


            {{-- Requisitos --}}
            <div class="edma-job-opening__requirements">

                <span class="edma-job-opening__requirements-label">
                    Requisitos
                </span>

                <ul>

                    <li>
                        <i class="bi bi-check2"></i>

                        <span>
                            Nivel avanzado de inglés.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check2"></i>

                        <span>
                            Nacionalidad hondureña.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check2"></i>

                        <span>
                            Experiencia o interés en enseñanza virtual.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check2"></i>

                        <span>
                            Conexión estable a Internet.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check2"></i>

                        <span>
                            Manejo de herramientas digitales para
                            clases en línea.
                        </span>
                    </li>

                </ul>


                <a
                    href="#"
                    class="edma-job-opening__button"
                >
                    POSTULARME

                    <i class="bi bi-arrow-up-right"></i>
                </a>


            </div>

        </article>

    </div>

</section>


{{-- =========================================================
     PROCESO DE POSTULACIÓN
========================================================= --}}
<section class="edma-jobs-process">

    <div class="edma-container">

        <div class="edma-jobs-process__header">

            <div
                class="edma-reveal"
                data-reveal="left"
            >
                <span class="edma-section-eyebrow">
                    Banco de talento
                </span>

                <h2>
                    Comparte tu perfil
                    <span>para futuras oportunidades.</span>
                </h2>
            </div>

            <p
                class="edma-reveal"
                data-reveal="right"
            >
                Puedes registrar tu información profesional aunque no exista
                una plaza abierta en este momento. EDMA podrá consultar tu perfil
                cuando surjan oportunidades compatibles con tu experiencia.
            </p>

        </div>


        <div class="edma-jobs-process__steps">

            <article
                class="edma-job-process-step edma-reveal"
                data-reveal="left"
            >
                <span class="edma-job-process-step__number">
                    01
                </span>

                <span class="edma-job-process-step__icon">
                    <i class="bi bi-person-lines-fill"></i>
                </span>

                <div>
                    <h3>Registra tu información</h3>

                    <p>
                        Completa tus datos personales, experiencia,
                        preparación académica y áreas de interés.
                    </p>
                </div>
            </article>


            <article
                class="edma-job-process-step edma-reveal"
                style="--reveal-delay: 90ms;"
            >
                <span class="edma-job-process-step__number">
                    02
                </span>

                <span class="edma-job-process-step__icon">
                    <i class="bi bi-file-earmark-person-fill"></i>
                </span>

                <div>
                    <h3>Adjunta tu hoja de vida</h3>

                    <p>
                        Comparte tu CV para que el equipo de EDMA pueda
                        conocer con mayor detalle tu perfil profesional.
                    </p>
                </div>
            </article>


            <article
                class="edma-job-process-step edma-reveal"
                style="--reveal-delay: 180ms;"
            >
                <span class="edma-job-process-step__number">
                    03
                </span>

                <span class="edma-job-process-step__icon">
                    <i class="bi bi-database-check"></i>
                </span>

                <div>
                    <h3>Tu perfil queda disponible</h3>

                    <p>
                        La información podrá ser consultada por EDMA
                        cuando exista una necesidad de contratación.
                    </p>
                </div>
            </article>


            <article
                class="edma-job-process-step edma-reveal"
                style="--reveal-delay: 270ms;"
            >
                <span class="edma-job-process-step__number">
                    04
                </span>

                <span class="edma-job-process-step__icon">
                    <i class="bi bi-chat-square-text-fill"></i>
                </span>

                <div>
                    <h3>EDMA podrá contactarte</h3>

                    <p>
                        Si tu perfil coincide con una futura oportunidad,
                        el equipo podrá comunicarse contigo para continuar
                        el proceso.
                    </p>
                </div>
            </article>

        </div>

    </div>

</section>


{{-- =========================================================
     CTA FINAL
========================================================= --}}
<section class="edma-jobs-cta">

    <div class="edma-container">

        <div
            class="edma-jobs-cta__panel edma-reveal"
            data-reveal="left"
        >

            <div class="edma-jobs-cta__content">

                <span class="edma-jobs-cta__eyebrow">
                    Talento EDMA
                </span>

                <h2>
                    Tu experiencia puede formar parte
                    <span>de futuras oportunidades.</span>
                </h2>

                <p>
                    Registra tu perfil profesional y comparte tu hoja de vida
                    para que podamos considerarte cuando exista una oportunidad
                    compatible con tu experiencia.
                </p>

                <div class="edma-jobs-cta__actions">

                    <a
                        href="#"
                        class="edma-jobs-cta__primary"
                    >
                        Compartir mi perfil

                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                    <a
                        href="{{ route('website.contact') }}"
                        class="edma-jobs-cta__secondary"
                    >
                        Contactar a EDMA

                        <i class="bi bi-chat-dots"></i>
                    </a>

                </div>

                <small class="edma-jobs-cta__note">
                    El formulario de registro de talento se habilitará próximamente.
                </small>

            </div>


            <div class="edma-jobs-cta__glass">

                <span>
                    <i class="bi bi-briefcase-fill"></i>
                </span>

                <div>
                    <small>Banco de talento</small>

                    <strong>
                        Tu perfil puede ser considerado en futuras oportunidades.
                    </strong>
                </div>

            </div>

        </div>

    </div>

</section>

@endsection