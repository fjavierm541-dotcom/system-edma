<section class="edma-schedules-home" id="horarios">

    <div class="edma-container">

        <div class="edma-schedules-home__layout">

            {{-- Información --}}
            <div
                class="edma-schedules-home__content edma-reveal"
                data-reveal="left"
            >

                <span class="edma-section-eyebrow">
                    Horarios
                </span>

                <h2 class="edma-schedules-home__title">
                    Aprende en un horario
                    <span>que se adapte a tu ritmo.</span>
                </h2>

                <p class="edma-schedules-home__description">
                    Nuestros grupos cuentan con diferentes horarios de clase
                    para que puedas encontrar una opción compatible con tu
                    disponibilidad y continuar aprendiendo desde donde estés.
                </p>


                {{-- Beneficios --}}
                <div class="edma-schedules-home__modalities">

                    <article class="edma-modality">

                        <span class="edma-modality__icon">
                            <i class="bi bi-clock-fill"></i>
                        </span>

                        <div>
                            <small>Disponibilidad</small>

                            <strong>Horarios accesibles</strong>

                            <p>
                                Consulta las opciones disponibles según
                                tu nivel y período académico.
                            </p>
                        </div>

                    </article>


                    <article class="edma-modality">

                        <span class="edma-modality__icon">
                            <i class="bi bi-laptop-fill"></i>
                        </span>

                        <div>
                            <small>100% virtual</small>

                            <strong>Aprende desde cualquier lugar</strong>

                            <p>
                                Conéctate a tus clases y continúa tu formación
                                sin importar dónde te encuentres.
                            </p>
                        </div>

                    </article>

                </div>


                {{-- Aviso --}}
                <div class="edma-schedules-home__notice">

                    <i class="bi bi-info-circle-fill"></i>

                    <p>
                        Los horarios disponibles pueden variar según el nivel,
                        período académico y grupos habilitados.
                    </p>

                </div>


                <a
                    href="{{ route('inscripciones.solicitud') }}"
                    class="edma-schedules-home__button"
                >
                    Solicitar inscripción

                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </div>


            {{-- Panel visual --}}
            <div
                class="edma-schedule-board edma-reveal"
                data-reveal="right"
            >

                <div class="edma-schedule-board__header">

                    <div>
                        <small>Organiza tu aprendizaje</small>

                        <h3>Tu horario, tu espacio</h3>
                    </div>

                    <span class="edma-schedule-board__calendar">
                        <i class="bi bi-calendar3"></i>
                    </span>

                </div>


                <div class="edma-schedule-board__timeline">

                    {{-- Horarios --}}
                    <article class="edma-schedule-period">

                        <div class="edma-schedule-period__time">

                            <span class="edma-schedule-period__dot"></span>

                            <div>
                                <small>Opciones</small>
                                <strong>Horarios</strong>
                            </div>

                        </div>

                        <div class="edma-schedule-period__detail">

                            <span>
                                <i class="bi bi-clock-history"></i>
                            </span>

                            <div>
                                <strong>Horarios accesibles</strong>

                                <small>
                                    Diferentes opciones según los grupos
                                    habilitados en cada período.
                                </small>
                            </div>

                        </div>

                    </article>


                    {{-- Virtual --}}
                    <article class="edma-schedule-period">

                        <div class="edma-schedule-period__time">

                            <span class="edma-schedule-period__dot"></span>

                            <div>
                                <small>Modalidad</small>
                                <strong>Virtual</strong>
                            </div>

                        </div>

                        <div class="edma-schedule-period__detail">

                            <span>
                                <i class="bi bi-wifi"></i>
                            </span>

                            <div>
                                <strong>Conéctate desde donde estés</strong>

                                <small>
                                    Accede a tu formación mediante una
                                    experiencia académica completamente virtual.
                                </small>
                            </div>

                        </div>

                    </article>


                    {{-- Experiencia --}}
                    <article class="edma-schedule-period">

                        <div class="edma-schedule-period__time">

                            <span class="edma-schedule-period__dot"></span>

                            <div>
                                <small>Experiencia</small>
                                <strong>Interactiva</strong>
                            </div>

                        </div>

                        <div class="edma-schedule-period__detail">

                            <span>
                                <i class="bi bi-chat-square-dots-fill"></i>
                            </span>

                            <div>
                                <strong>Participa y practica</strong>

                                <small>
                                    Clases dinámicas orientadas al uso
                                    práctico del idioma.
                                </small>
                            </div>

                        </div>

                    </article>

                </div>


                {{-- Glass inferior --}}
                <div class="edma-schedule-board__glass">

                    <span>
                        <i class="bi bi-calendar-check-fill"></i>
                    </span>

                    <div>
                        <small>Disponibilidad académica</small>

                        <strong>
                            Elige tu grupo al momento de matricularte.
                        </strong>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>