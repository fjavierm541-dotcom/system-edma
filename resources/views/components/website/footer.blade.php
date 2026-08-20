<footer class="edma-footer">

    <div class="edma-container">

        <div class="edma-footer__main">

            {{-- Marca --}}
            <div class="edma-footer__brand">

                <img
                    src="{{ asset('images/brand/logo-edma.png') }}"
                    alt="Edumerican Academy Honduras"
                    class="edma-footer__logo"
                >

                <p class="edma-footer__description">
                    Formación en inglés para niños, jóvenes y adultos mediante
                    una experiencia académica virtual, práctica y progresiva.
                </p>

                <div class="edma-footer__social">

                    <a
                        href="https://www.facebook.com/edumerican.hn"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Facebook de Edumerican Academy"
                        title="Facebook"
                    >
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a
                        href="https://www.instagram.com/edumerican.hn/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram de Edumerican Academy"
                        title="Instagram"
                    >
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a
                        href="https://api.whatsapp.com/send?phone=50496734171"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="WhatsApp de Edumerican Academy"
                        title="WhatsApp"
                    >
                        <i class="bi bi-whatsapp"></i>
                    </a>

                    <a
                        href="https://youtube.com/@EdumericanAcademy"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="YouTube de Edumerican Academy"
                        title="YouTube"
                    >
                        <i class="bi bi-youtube"></i>
                    </a>

                </div>

            </div>


            {{-- Navegación --}}
            <div class="edma-footer__column">

                <h2>Navegación</h2>

                <ul>
                    <li>
                        <a href="{{ route('website.home') }}">
                            Inicio
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('website.courses') }}">
                            Programas
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('website.about') }}">
                            Nosotros
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('website.jobs') }}">
                            Empleos
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('website.contact') }}">
                            Contacto
                        </a>
                    </li>
                </ul>

            </div>


            {{-- Accesos --}}
            <div class="edma-footer__column">

                <h2>Accesos</h2>

                <ul>
                    <li>
                        <a href="#">
                            Campus EDMA
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Portal EDMA
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('inscripciones.solicitud') }}">
                            Solicitar inscripción
                        </a>
                    </li>
                </ul>

            </div>


            {{-- Contacto --}}
            <div class="edma-footer__column edma-footer__contact">

                <h2>Contacto</h2>

                <a href="mailto:edumerican@gmail.com">
                    <i class="bi bi-envelope-fill"></i>
                    <span>edumerican@gmail.com</span>
                </a>

                <a href="tel:+50431470921">
                    <i class="bi bi-telephone-fill"></i>
                    <span>3147-0921</span>
                </a>

                <a href="tel:+50496734171">
                    <i class="bi bi-phone-fill"></i>
                    <span>9673-4171</span>
                </a>

            </div>

        </div>


        {{-- Parte inferior --}}
        <div class="edma-footer__bottom">

            <p>
                © {{ date('Y') }} Edumerican Academy Honduras.
                Todos los derechos reservados.
            </p>

            <div class="edma-footer__legal">

                <a href="#">
                    Política de privacidad
                </a>

                <span></span>

                <a href="#">
                    Términos y condiciones
                </a>

            </div>

        </div>

    </div>

</footer>