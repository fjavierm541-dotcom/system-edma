<header class="portal-navbar">

    <div class="portal-navbar-left">

        <button
            type="button"
            class="portal-icon-button portal-sidebar-toggle"
            id="portalSidebarToggle"
            aria-label="Mostrar u ocultar menú"
            aria-controls="portalSidebar"
            aria-expanded="true"
        >
            <i class="bi bi-list"></i>
        </button>

        <div class="portal-navbar-title">

            <span class="portal-navbar-context">
                Portal administrativo
            </span>

            <strong>
                @yield('page-title', 'Dashboard')
            </strong>

        </div>

    </div>

    <div class="portal-navbar-right">

        <button
            type="button"
            class="portal-icon-button"
            aria-label="Notificaciones"
        >
            <i class="bi bi-bell"></i>

            <span class="portal-notification-dot"></span>
        </button>

        <div class="dropdown">

            <button
                type="button"
                class="portal-user-button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <span class="portal-user-avatar">
                    JA
                </span>

                <span class="portal-user-information">
                    <strong>Administrador</strong>
                    <small>Equipo administrativo</small>
                </span>

                <i class="bi bi-chevron-down"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end portal-user-menu">

                <li>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        <i class="bi bi-person"></i>
                        Mi perfil
                    </a>
                </li>

                <li>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        <i class="bi bi-key"></i>
                        Cambiar contraseña
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a
                        class="dropdown-item text-danger"
                        href="#"
                    >
                        <i class="bi bi-box-arrow-right"></i>
                        Cerrar sesión
                    </a>
                </li>

            </ul>

        </div>

    </div>

</header>