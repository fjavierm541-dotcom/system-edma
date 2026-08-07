<aside
    class="portal-sidebar"
    id="portalSidebar"
    aria-label="Navegación principal"
>
    <div class="portal-sidebar-header">

        <a
            href="{{ route('portal.dashboard') }}"
            class="portal-brand"
        >
            <div class="portal-brand-icon">
                <span>ED</span>
            </div>

            <div class="portal-brand-text">
                <span class="portal-brand-name">EDMA</span>
                <small>Sistema Académico</small>
            </div>
        </a>

    </div>

    <nav class="portal-navigation">

        <div class="portal-menu-section">

            <span class="portal-menu-label">
                Principal
            </span>

            <a
                href="{{ route('portal.dashboard') }}"
                class="portal-menu-link
                    {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}"
            >
                <i class="bi bi-grid-1x2"></i>

                <span>Dashboard</span>
            </a>

        </div>

        <div class="portal-menu-section">

            <span class="portal-menu-label">
                Gestión de personas
            </span>

            <a
                href="{{ route('portal.personas.index') }}"
                class="portal-menu-link
                    {{ request()->routeIs('portal.personas.*') ? 'active' : '' }}"
            >
                <i class="bi bi-person-vcard"></i>

                <span>Personas</span>
            </a>

            <a
                href="{{ route('portal.estudiantes.index') }}"
                class="portal-menu-link
                    {{ request()->routeIs('portal.estudiantes.*')
                        ? 'active'
                        : '' }}"
            >
                <i class="bi bi-mortarboard"></i>

                <span>Estudiantes</span>
            </a>

            <a
                href="#"
                class="portal-menu-link"
            >
                <i class="bi bi-briefcase"></i>

                <span>Empleados</span>
            </a>

            <a
                href="#"
                class="portal-menu-link"
            >
                <i class="bi bi-person-workspace"></i>

                <span>Docentes</span>
            </a>

        </div>

        <div class="portal-menu-section">

            <span class="portal-menu-label">
                Gestión académica
            </span>

            <a
                href="#"
                class="portal-menu-link"
            >
                <i class="bi bi-journal-bookmark"></i>

                <span>Programas y niveles</span>
            </a>

            <a
                href="#"
                class="portal-menu-link"
            >
                <i class="bi bi-people"></i>

                <span>Grupos</span>
            </a>

            <a
                href="#"
                class="portal-menu-link"
            >
                <i class="bi bi-calendar3"></i>

                <span>Horarios y períodos</span>
            </a>

        </div>

        <div class="portal-menu-section">

            <span class="portal-menu-label">
                Administración
            </span>

            <a
                href="#"
                class="portal-menu-link"
            >
                <i class="bi bi-file-earmark-text"></i>

                <span>Matrículas</span>

                <span class="portal-menu-badge">
                    0
                </span>
            </a>

            <a
                href="#"
                class="portal-menu-link"
            >
                <i class="bi bi-wallet2"></i>

                <span>Pagos</span>
            </a>

            <a
                href="#"
                class="portal-menu-link"
            >
                <i class="bi bi-bar-chart"></i>

                <span>Reportes</span>
            </a>

        </div>

        <div class="portal-menu-section">

            <span class="portal-menu-label">
                Sistema
            </span>

            <a
                href="#"
                class="portal-menu-link"
            >
                <i class="bi bi-gear"></i>

                <span>Configuración</span>
            </a>

        </div>

    </nav>

    <div class="portal-sidebar-footer">

        <div class="portal-sidebar-status">

            <span class="portal-status-indicator"></span>

            <div>
                <strong>Sistema disponible</strong>
                <small>EDMA Portal</small>
            </div>

        </div>

    </div>
</aside>