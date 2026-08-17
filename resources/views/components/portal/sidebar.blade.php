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

        {{-- =====================================================
             PRINCIPAL
             ===================================================== --}}
        <div class="portal-menu-section">

            <span class="portal-menu-label">
                Principal
            </span>

            <a
                href="{{ route('portal.dashboard') }}"
                class="portal-menu-link
                    {{ request()->routeIs('portal.dashboard')
                        ? 'active'
                        : '' }}"
            >
                <i class="bi bi-grid-1x2"></i>

                <span>
                    Dashboard
                </span>
            </a>

        </div>

        {{-- =====================================================
             GESTIÓN DE PERSONAS
             ===================================================== --}}
        <div class="portal-menu-section">

            <span class="portal-menu-label">
                Gestión de personas
            </span>

            <a
                href="{{ route('portal.personas.index') }}"
                class="portal-menu-link
                    {{ request()->routeIs('portal.personas.*')
                        ? 'active'
                        : '' }}"
            >
                <i class="bi bi-person-vcard"></i>

                <span>
                    Personas
                </span>
            </a>

            <a
                href="{{ route('portal.estudiantes.index') }}"
                class="portal-menu-link
                    {{ request()->routeIs('portal.estudiantes.*')
                        ? 'active'
                        : '' }}"
            >
                <i class="bi bi-mortarboard"></i>

                <span>
                    Estudiantes
                </span>
            </a>

            <a
                href="{{ route('portal.empleados.index') }}"
                class="portal-menu-link
                    {{ request()->routeIs('portal.empleados.*')
                        ? 'active'
                        : '' }}"
            >
                <i class="bi bi-briefcase"></i>

                <span>
                    Empleados
                </span>
            </a>

            <a
                href="{{ route('portal.docentes.index') }}"
                class="portal-menu-link
                    {{ request()->routeIs('portal.docentes.*')
                        ? 'active'
                        : '' }}"
            >
                <i class="bi bi-easel"></i>

                <span>
                    Docentes
                </span>
            </a>

        </div>

        {{-- =====================================================
             GESTIÓN ACADÉMICA
             ===================================================== --}}
        <div class="portal-menu-section">

    <span class="portal-menu-label">
        Gestión académica
    </span>

    <a
        href="{{ route('portal.programas.index') }}"
        class="portal-menu-link
            {{
                request()->routeIs('portal.programas.*')
                || request()->routeIs('portal.niveles.*')
                    ? 'active'
                    : ''
            }}"
    >
        <i class="bi bi-journal-bookmark"></i>

        <span>
            Programas y niveles
        </span>
    </a>

    <a
        href="{{ route('portal.grupos.index') }}"
        class="portal-menu-link
            {{ request()->routeIs('portal.grupos.*')
                ? 'active'
                : '' }}"
    >
        <i class="bi bi-people"></i>

        <span>
            Grupos
        </span>
    </a>

    <a
        href="{{ route('portal.horarios.index') }}"
        class="portal-menu-link
            {{ request()->routeIs('portal.horarios.*')
                ? 'active'
                : '' }}"
    >
        <i class="bi bi-clock"></i>

        <span>
            Horarios
        </span>
    </a>

    @if (
        Route::has(
            'portal.periodos-academicos.index'
        )
    )

        <a
            href="{{ route(
                'portal.periodos-academicos.index'
            ) }}"
            class="portal-menu-link
                {{
                    request()->routeIs(
                        'portal.periodos-academicos.*'
                    )
                        ? 'active'
                        : ''
                }}"
        >
            <i class="bi bi-calendar-range"></i>

            <span>
                Períodos académicos
            </span>
        </a>

    @elseif (
        Route::has(
            'portal.periodos.index'
        )
    )

        <a
            href="{{ route(
                'portal.periodos.index'
            ) }}"
            class="portal-menu-link
                {{
                    request()->routeIs(
                        'portal.periodos.*'
                    )
                        ? 'active'
                        : ''
                }}"
        >
            <i class="bi bi-calendar-range"></i>

            <span>
                Períodos académicos
            </span>
        </a>

    @else

        <div class="portal-menu-link">

            <i class="bi bi-calendar-range"></i>

            <span>
                Períodos académicos
            </span>

        </div>

    @endif

</div>

        {{-- =====================================================
             ADMINISTRACIÓN
             ===================================================== --}}
        <div class="portal-menu-section">

            <span class="portal-menu-label">
                Administración
            </span>

            {{-- Solicitudes de inscripción --}}
            @if (
                Route::has(
                    'portal.solicitudes-inscripcion.index'
                )
            )

                <a
                    href="{{ route(
                        'portal.solicitudes-inscripcion.index'
                    ) }}"
                    class="portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.solicitudes-inscripcion.*'
                            )
                                ? 'active'
                                : ''
                        }}"
                >
                    <i class="bi bi-file-earmark-check"></i>

                    <span>
                        Solicitudes
                    </span>
                </a>

            @else

                <div class="portal-menu-link">

                    <i class="bi bi-file-earmark-check"></i>

                    <span>
                        Solicitudes
                    </span>

                </div>

            @endif

            {{-- Matrículas --}}
            @if (
                Route::has(
                    'portal.matriculas.index'
                )
            )

                <a
                    href="{{ route(
                        'portal.matriculas.index'
                    ) }}"
                    class="portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.matriculas.*'
                            )
                                ? 'active'
                                : ''
                        }}"
                >
                    <i class="bi bi-file-earmark-text"></i>

                    <span>
                        Matrículas
                    </span>
                </a>

            @else

                <div class="portal-menu-link">

                    <i class="bi bi-file-earmark-text"></i>

                    <span>
                        Matrículas
                    </span>

                </div>

            @endif

            {{-- Pagos --}}
            @if (
                Route::has(
                    'portal.pagos.index'
                )
            )

                <a
                    href="{{ route(
                        'portal.pagos.index'
                    ) }}"
                    class="portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.pagos.*'
                            )
                                ? 'active'
                                : ''
                        }}"
                >
                    <i class="bi bi-wallet2"></i>

                    <span>
                        Pagos
                    </span>
                </a>

            @else

                <div class="portal-menu-link">

                    <i class="bi bi-wallet2"></i>

                    <span>
                        Pagos
                    </span>

                </div>

            @endif

            {{-- Reportes --}}
            @if (
                Route::has(
                    'portal.reportes.index'
                )
            )

                <a
                    href="{{ route(
                        'portal.reportes.index'
                    ) }}"
                    class="portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.reportes.*'
                            )
                                ? 'active'
                                : ''
                        }}"
                >
                    <i class="bi bi-bar-chart"></i>

                    <span>
                        Reportes
                    </span>
                </a>

            @else

                <div class="portal-menu-link">

                    <i class="bi bi-bar-chart"></i>

                    <span>
                        Reportes
                    </span>

                </div>

            @endif

        </div>

        {{-- =====================================================
             SISTEMA
             ===================================================== --}}
        <div class="portal-menu-section">

            <span class="portal-menu-label">
                Sistema
            </span>

            @if (
                Route::has(
                    'portal.configuracion.index'
                )
            )

                <a
                    href="{{ route(
                        'portal.configuracion.index'
                    ) }}"
                    class="portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.configuracion.*'
                            )
                                ? 'active'
                                : ''
                        }}"
                >
                    <i class="bi bi-gear"></i>

                    <span>
                        Configuración
                    </span>
                </a>

            @else

                <div class="portal-menu-link">

                    <i class="bi bi-gear"></i>

                    <span>
                        Configuración
                    </span>

                </div>

            @endif

        </div>

    </nav>

    <div class="portal-sidebar-footer">

        <div class="portal-sidebar-status">

            <span class="portal-status-indicator"></span>

            <div>

                <strong>
                    Sistema disponible
                </strong>

                <small>
                    EDMA Portal
                </small>

            </div>

        </div>

    </div>

</aside>