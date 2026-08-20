<section class="edma-methodology-home" id="metodologia">

    <div class="edma-container">

        {{-- Encabezado --}}
        <div class="edma-methodology-home__header">

            <div>
                <span class="edma-methodology-home__eyebrow">
                    Nuestra metodología
                </span>

                <h2 class="edma-methodology-home__title">
                    Aprende inglés
                    <span>usándolo desde el primer día.</span>
                </h2>
            </div>

            <p class="edma-methodology-home__intro">
                Nuestra experiencia académica integra las principales habilidades
                del idioma para que el estudiante no se limite a memorizar
                contenidos, sino que aprenda a comprender, expresarse y
                comunicarse con mayor seguridad.
            </p>

        </div>


        {{-- Composición principal --}}
        <div class="edma-methodology-home__layout">

            {{-- Fotografía --}}
            <div
                class="edma-methodology-home__media edma-reveal"
                data-reveal="left"
            >

                <img
                    src="{{ asset('images/website/methodology-class.jpg') }}"
                    alt="Estudiantes practicando inglés durante una clase"
                    class="edma-methodology-home__image"
                >

                <div class="edma-methodology-home__overlay"></div>

                {{-- Glass puntual --}}
                <div class="edma-methodology-home__glass">

                    <span class="edma-methodology-home__glass-icon">
                        <i
                            class="bi bi-chat-quote-fill"
                            aria-hidden="true"
                        ></i>
                    </span>

                    <div>
                        <small>Enfoque comunicativo</small>

                        <strong>
                            El idioma se aprende practicándolo.
                        </strong>
                    </div>

                </div>

            </div>


            {{-- Habilidades --}}
            <div class="edma-methodology-home__skills">

                <article
                    class="edma-methodology-skill edma-reveal"
                    data-reveal="right"
                >

                    <span class="edma-methodology-skill__number">
                        01
                    </span>

                    <span class="edma-methodology-skill__icon">
                        <i
                            class="bi bi-headphones"
                            aria-hidden="true"
                        ></i>
                    </span>

                    <div>
                        <h3>Escucha</h3>

                        <p>
                            Desarrolla la comprensión del idioma mediante
                            diferentes contextos, voces y situaciones.
                        </p>
                    </div>

                </article>


                <article
                    class="edma-methodology-skill edma-reveal"
                    data-reveal="right"
                    style="--reveal-delay: 90ms;"
                >

                    <span class="edma-methodology-skill__number">
                        02
                    </span>

                    <span class="edma-methodology-skill__icon">
                        <i
                            class="bi bi-mic-fill"
                            aria-hidden="true"
                        ></i>
                    </span>

                    <div>
                        <h3>Habla</h3>

                        <p>
                            Practica la expresión oral para comunicar ideas
                            con mayor naturalidad, seguridad y fluidez.
                        </p>
                    </div>

                </article>


                <article
                    class="edma-methodology-skill edma-reveal"
                    data-reveal="right"
                    style="--reveal-delay: 180ms;"
                >

                    <span class="edma-methodology-skill__number">
                        03
                    </span>

                    <span class="edma-methodology-skill__icon">
                        <i
                            class="bi bi-book-half"
                            aria-hidden="true"
                        ></i>
                    </span>

                    <div>
                        <h3>Lectura</h3>

                        <p>
                            Fortalece la comprensión de textos y amplía
                            vocabulario dentro de situaciones significativas.
                        </p>
                    </div>

                </article>


                <article
                    class="edma-methodology-skill edma-reveal"
                    data-reveal="right"
                    style="--reveal-delay: 270ms;"
                >

                    <span class="edma-methodology-skill__number">
                        04
                    </span>

                    <span class="edma-methodology-skill__icon">
                        <i
                            class="bi bi-pencil-square"
                            aria-hidden="true"
                        ></i>
                    </span>

                    <div>
                        <h3>Escritura</h3>

                        <p>
                            Aprende a organizar y comunicar ideas por escrito
                            utilizando estructuras cada vez más completas.
                        </p>
                    </div>

                </article>

            </div>

        </div>


        {{-- Cierre de sección --}}
        <div class="edma-methodology-home__footer">

            <p>
                Las cuatro habilidades se desarrollan de forma integrada
                durante el proceso de aprendizaje.
            </p>

            <a
                href="{{ route('website.courses') }}"
                class="edma-methodology-home__link"
            >
                Conocer nuestra formación

                <i
                    class="bi bi-arrow-right"
                    aria-hidden="true"
                ></i>
            </a>

        </div>

    </div>

</section>