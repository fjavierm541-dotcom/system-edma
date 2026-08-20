<section class="edma-programs-home" id="programas">

    <div class="edma-container">

        {{-- Encabezado --}}
        <div class="edma-programs-home__header">

            <div class="edma-programs-home__heading">

                <span class="edma-section-eyebrow">
                    Formación EDMA
                </span>

                <h2>
                    Un programa para cada
                    <span>etapa de aprendizaje.</span>
                </h2>

            </div>

            <p class="edma-programs-home__intro">
                Nuestra formación está organizada para acompañar el desarrollo
                del estudiante de manera progresiva, con contenidos y experiencias
                adaptadas a cada etapa.
            </p>

        </div>


        {{-- Selector interactivo --}}
        <div
            class="edma-program-selector"
            role="tablist"
            aria-label="Seleccionar programa"
        >

            <button
                type="button"
                class="edma-program-selector__button is-active"
                data-program="kids"
                role="tab"
                aria-selected="true"
            >
                <span class="edma-program-selector__icon">
                    <i class="bi bi-stars"></i>
                </span>

                <span>
                    <small>Programa</small>
                    Niños
                </span>
            </button>

            <button
                type="button"
                class="edma-program-selector__button"
                data-program="adults"
                role="tab"
                aria-selected="false"
            >
                <span class="edma-program-selector__icon">
                    <i class="bi bi-people-fill"></i>
                </span>

                <span>
                    <small>Programa</small>
                    Jóvenes y adultos
                </span>
            </button>

        </div>


        {{-- Contenido dinámico --}}
        <div class="edma-program-showcase">

            {{-- Información --}}
            <div class="edma-program-showcase__content">

                <div class="edma-program-showcase__number">
                    <span>01</span>
                    Diplomado de Inglés
                </div>

                <h3 id="edmaProgramTitle">
                    Inglés para niños
                </h3>

                <p
                    class="edma-program-showcase__description"
                    id="edmaProgramDescription"
                >
                    Una experiencia de aprendizaje diseñada para desarrollar
                    habilidades comunicativas desde edades tempranas mediante
                    actividades prácticas, progresivas y apropiadas para su etapa.
                </p>

                <div class="edma-program-showcase__facts">

                    <div class="edma-program-fact">
                        <span>
                            <i class="bi bi-layers-fill"></i>
                        </span>

                        <div>
                            <strong>7 niveles</strong>
                            <small>Formación progresiva</small>
                        </div>
                    </div>

                    <div class="edma-program-fact">
                        <span>
                            <i class="bi bi-calendar3"></i>
                        </span>

                        <div>
                            <strong>12 semanas</strong>
                            <small>Por nivel</small>
                        </div>
                    </div>

                    <div class="edma-program-fact">
                        <span>
                            <i class="bi bi-chat-square-text-fill"></i>
                        </span>

                        <div>
                            <strong>Enfoque práctico</strong>
                            <small>Comunicación real</small>
                        </div>
                    </div>

                </div>

                <div class="edma-program-showcase__actions">

                    <a
                        href="{{ route('website.courses') }}"
                        class="edma-program-showcase__primary"
                    >
                        Conocer el programa

                        <i class="bi bi-arrow-right"></i>
                    </a>

                    <a
                        href="{{ route('inscripciones.solicitud') }}"
                        class="edma-program-showcase__secondary"
                    >
                        Solicitar inscripción
                    </a>

                </div>

            </div>


            {{-- Visual --}}
            <div class="edma-program-showcase__media">

                <div class="edma-program-showcase__image-wrap">

                    <img
                        id="edmaProgramImage"
                        src="{{ asset('images/website/program-kids.jpg') }}"
                        data-kids-image="{{ asset('images/website/program-kids.jpg') }}"
                        data-adults-image="{{ asset('images/website/program-adults.jpg') }}"
                        alt="Programa de inglés de Edumerican Academy"
                    >

                    <div class="edma-program-showcase__image-overlay"></div>

                </div>


                {{-- Glass puntual --}}
                <div class="edma-program-showcase__glass">

                    <span>
                        <i class="bi bi-mortarboard-fill"></i>
                    </span>

                    <div>
                        <small>Formación EDMA</small>
                        <strong id="edmaProgramGlassText">
                            Aprende mientras desarrollas confianza
                        </strong>
                    </div>

                </div>


                {{-- Indicador decorativo --}}
                <div
                    class="edma-program-showcase__index"
                    aria-hidden="true"
                >
                    <span id="edmaProgramIndex">01</span>
                    <small>EDMA</small>
                </div>

            </div>

        </div>

    </div>

</section>