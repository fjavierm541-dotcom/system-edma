<header class="edma-header">

    <nav
        class="navbar navbar-expand-xl edma-navbar"
        aria-label="Navegación principal"
    >

        <div class="edma-navbar__shell">

            {{-- =====================================================
                 Logo institucional
            ====================================================== --}}
            <a
                href="{{ route('website.home') }}"
                class="navbar-brand edma-navbar__brand"
                aria-label="Ir al inicio de Edumerican Academy"
            >
                <img
                    src="{{ asset('images/brand/logo-edma.png') }}"
                    alt="Edumerican Academy Honduras"
                    class="edma-navbar__logo"
                >
            </a>


            {{-- =====================================================
                 Botón móvil
            ====================================================== --}}
            <button
                class="navbar-toggler edma-navbar__toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#edmaMainNavbar"
                aria-controls="edmaMainNavbar"
                aria-expanded="false"
                aria-label="Abrir menú de navegación"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>


            {{-- =====================================================
                 Contenido principal
            ====================================================== --}}
            <div
                class="collapse navbar-collapse edma-navbar__collapse"
                id="edmaMainNavbar"
            >

                {{-- Navegación --}}
                <ul class="navbar-nav edma-navbar__menu">

                    <li class="nav-item">

                        <a
                            href="{{ route('website.home') }}"
                            class="
                                nav-link
                                edma-navbar__link
                                {{ request()->routeIs('website.home') ? 'is-active' : '' }}
                            "
                        >
                            Inicio
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            href="{{ route('website.courses') }}"
                            class="
                                nav-link
                                edma-navbar__link
                                {{ request()->routeIs('website.courses') ? 'is-active' : '' }}
                            "
                        >
                            Programas
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            href="{{ route('website.about') }}"
                            class="
                                nav-link
                                edma-navbar__link
                                {{ request()->routeIs('website.about') ? 'is-active' : '' }}
                            "
                        >
                            Nosotros
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            href="{{ route('website.jobs') }}"
                            class="
                                nav-link
                                edma-navbar__link
                                {{ request()->routeIs('website.jobs') ? 'is-active' : '' }}
                            "
                        >
                            Empleos
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            href="{{ route('website.contact') }}"
                            class="
                                nav-link
                                edma-navbar__link
                                {{ request()->routeIs('website.contact') ? 'is-active' : '' }}
                            "
                        >
                            Contacto
                        </a>

                    </li>

                </ul>


                {{-- =================================================
                        Accesos institucionales
                    ================================================== --}}
                    <div class="edma-navbar__actions">

                        {{-- Campus EDMA --}}
                        <a
                            href="{{ route('website.campus') }}"
                            class="
                                edma-button
                                edma-button--access
                                edma-button--campus
                            "
                            title="Acceder a Campus EDMA"
                        >
                            <i
                                class="bi bi-mortarboard"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Campus EDMA
                            </span>
                        </a>


                        {{-- Portal EDMA --}}
                        <a
                            href="#"
                            class="
                                edma-button
                                edma-button--access
                                edma-button--portal
                            "
                            title="Acceder a Portal EDMA"
                            onclick="return false;"
                        >
                            <i
                                class="bi bi-person-circle"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Portal EDMA
                            </span>
                        </a>


                        {{-- Solicitud de inscripción --}}
                        <a
                            href="{{ route('inscripciones.solicitud') }}"
                            class="
                                edma-button
                                edma-button--admission
                            "
                        >
                            <span>
                                Solicitar inscripción
                            </span>

                            <i
                                class="bi bi-arrow-up-right"
                                aria-hidden="true"
                            ></i>
                        </a>

                    </div>
            </div>

        </div>

    </nav>

</header>