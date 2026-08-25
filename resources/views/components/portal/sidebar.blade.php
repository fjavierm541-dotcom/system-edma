@php

    $user = auth()->user();

    $esAdministrador =
        $user?->tieneRol('Administrador');

    $esEstudiante =
        $user?->tieneRol('Estudiante');

    $esDocente =
        $user?->tieneRol('Docente');


    /*
    |--------------------------------------------------------------------------
    | Ruta principal según rol
    |--------------------------------------------------------------------------
    */

    if ($esAdministrador) {

        $rutaInicio =
            route(
                'portal.admin.inicio'
            );

    } elseif ($esEstudiante) {

        $rutaInicio =
            route(
                'portal.estudiante.inicio'
            );

    } else {

        $rutaInicio =
            route(
                'portal.inicio'
            );
    }

@endphp


<aside
    class="portal-sidebar"
    id="portalSidebar"
    aria-label="Navegación principal"
>

    {{-- ============================================================
        MARCA
    ============================================================ --}}

    <div class="portal-sidebar-header">

        <a
            href="{{ $rutaInicio }}"
            class="portal-brand"
        >

            <div class="portal-brand-icon">
                <span>ED</span>
            </div>

            <div class="portal-brand-text">

                <span class="portal-brand-name">
                    EDMA
                </span>

                <small>
                    Sistema Académico
                </small>

            </div>

        </a>

    </div>


    <nav class="portal-navigation">

        {{-- ========================================================
            ADMINISTRADOR
        ======================================================== --}}

        @if ($esAdministrador)

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Principal
                </span>

                <a
                    href="{{
                        route(
                            'portal.admin.inicio'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.admin.inicio'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-house-door"></i>

                    <span>
                        Inicio
                    </span>
                </a>

            </div>


            {{-- Gestión de personas --}}

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Gestión de personas
                </span>


                <a
                    href="{{
                        route(
                            'portal.personas.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.personas.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-person-vcard"></i>

                    <span>
                        Personas
                    </span>
                </a>


                <a
                    href="{{
                        route(
                            'portal.estudiantes.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.estudiantes.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-mortarboard"></i>

                    <span>
                        Estudiantes
                    </span>
                </a>


                <a
                    href="{{
                        route(
                            'portal.empleados.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.empleados.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-briefcase"></i>

                    <span>
                        Empleados
                    </span>
                </a>


                <a
                    href="{{
                        route(
                            'portal.docentes.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.docentes.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-easel"></i>

                    <span>
                        Docentes
                    </span>
                </a>

            </div>


            {{-- Gestión académica --}}

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Gestión académica
                </span>


                <a
                    href="{{
                        route(
                            'portal.programas.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.programas.*'
                            )
                            ||
                            request()->routeIs(
                                'portal.niveles.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-journal-bookmark"></i>

                    <span>
                        Programas y niveles
                    </span>
                </a>


                <a
                    href="{{
                        route(
                            'portal.grupos.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.grupos.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-people"></i>

                    <span>
                        Grupos
                    </span>
                </a>


                <a
                    href="{{
                        route(
                            'portal.horarios.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.horarios.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-clock"></i>

                    <span>
                        Horarios
                    </span>
                </a>


                @if (
                    Route::has(
                        'portal.periodos.index'
                    )
                )

                    <a
                        href="{{
                            route(
                                'portal.periodos.index'
                            )
                        }}"
                        class="
                            portal-menu-link
                            {{
                                request()->routeIs(
                                    'portal.periodos.*'
                                )
                                    ? 'active'
                                    : ''
                            }}
                        "
                    >
                        <i class="bi bi-calendar-range"></i>

                        <span>
                            Períodos académicos
                        </span>
                    </a>

                @endif

            </div>


            {{-- Administración --}}

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Administración
                </span>


                <a
                    href="{{
                        route(
                            'portal.solicitudes-inscripcion.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.solicitudes-inscripcion.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-file-earmark-check"></i>

                    <span>
                        Solicitudes
                    </span>
                </a>


                <a
                    href="{{
                        route(
                            'portal.admin.pagos.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.admin.pagos.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-wallet2"></i>

                    <span>
                        Pagos
                    </span>
                </a>

            </div>


            {{-- Sistema --}}

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Sistema
                </span>


                <a
                    href="{{
                        route(
                            'portal.usuarios.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.usuarios.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-person-lock"></i>

                    <span>
                        Usuarios
                    </span>
                </a>


                @if (
                    Route::has(
                        'portal.configuracion.index'
                    )
                )

                    <a
                        href="{{
                            route(
                                'portal.configuracion.index'
                            )
                        }}"
                        class="
                            portal-menu-link
                            {{
                                request()->routeIs(
                                    'portal.configuracion.*'
                                )
                                    ? 'active'
                                    : ''
                            }}
                        "
                    >
                        <i class="bi bi-gear"></i>

                        <span>
                            Configuración
                        </span>
                    </a>

                @endif

            </div>

        @endif


        {{-- ========================================================
            ESTUDIANTE
        ======================================================== --}}

        @if ($esEstudiante)

            {{-- Principal --}}

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Principal
                </span>

                <a
                    href="{{
                        route(
                            'portal.estudiante.inicio'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.estudiante.inicio'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-house-door"></i>

                    <span>
                        Inicio
                    </span>
                </a>

            </div>


            {{-- Académico --}}

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Académico
                </span>


                <a
                    href="{{
                        route(
                            'portal.mi-matricula.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.mi-matricula.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-journal-check"></i>

                    <span>
                        Mi matrícula
                    </span>
                </a>


                <a
                    href="{{
                        route(
                            'portal.comprobante-matricula.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.comprobante-matricula.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-file-earmark-check"></i>

                    <span>
                        Comprobante de matrícula
                    </span>
                </a>


                @if (
                    Route::has(
                        'portal.historial-academico.index'
                    )
                )

                    <a
                        href="{{
                            route(
                                'portal.historial-academico.index'
                            )
                        }}"
                        class="
                            portal-menu-link
                            {{
                                request()->routeIs(
                                    'portal.historial-academico.*'
                                )
                                    ? 'active'
                                    : ''
                            }}
                        "
                    >
                        <i class="bi bi-journal-text"></i>

                        <span>
                            Historial académico
                        </span>
                    </a>

                @else

                    <div class="portal-menu-link">

                        <i class="bi bi-journal-text"></i>

                        <span>
                            Historial académico
                        </span>

                    </div>

                @endif

            </div>


            {{-- Finanzas --}}

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Finanzas
                </span>


                <a
                    href="{{
                        route(
                            'portal.pagos.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.pagos.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-receipt"></i>

                    <span>
                        Pagos
                    </span>
                </a>


                <a
                    href="{{
                        route(
                            'portal.estado-cuenta.index'
                        )
                    }}"
                    class="
                        portal-menu-link
                        {{
                            request()->routeIs(
                                'portal.estado-cuenta.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >
                    <i class="bi bi-wallet2"></i>

                    <span>
                        Estado de cuenta
                    </span>
                </a>

            </div>


            {{-- Cuenta --}}

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Cuenta
                </span>


                @if (
                    Route::has(
                        'portal.mi-perfil.index'
                    )
                )

                    <a
                        href="{{
                            route(
                                'portal.mi-perfil.index'
                            )
                        }}"
                        class="
                            portal-menu-link
                            {{
                                request()->routeIs(
                                    'portal.mi-perfil.*'
                                )
                                    ? 'active'
                                    : ''
                            }}
                        "
                    >
                        <i class="bi bi-person-circle"></i>

                        <span>
                            Mi perfil
                        </span>
                    </a>

                @else

                    <div class="portal-menu-link">

                        <i class="bi bi-person-circle"></i>

                        <span>
                            Mi perfil
                        </span>

                    </div>

                @endif


                <a
                    href="{{
                        route(
                            'password.change.edit'
                        )
                    }}"
                    class="portal-menu-link"
                >
                    <i class="bi bi-shield-lock"></i>

                    <span>
                        Cambiar contraseña
                    </span>
                </a>

            </div>

        @endif


        {{-- ========================================================
            DOCENTE
        ======================================================== --}}

        @if ($esDocente)

            <div class="portal-menu-section">

                <span class="portal-menu-label">
                    Principal
                </span>


                @if (
                    Route::has(
                        'portal.docente.inicio'
                    )
                )

                    <a
                        href="{{
                            route(
                                'portal.docente.inicio'
                            )
                        }}"
                        class="portal-menu-link"
                    >
                        <i class="bi bi-house-door"></i>

                        <span>
                            Inicio
                        </span>
                    </a>

                @else

                    <div class="portal-menu-link">

                        <i class="bi bi-house-door"></i>

                        <span>
                            Inicio
                        </span>

                    </div>

                @endif

            </div>

        @endif

    </nav>


    {{-- ============================================================
        ESTADO DEL SISTEMA
    ============================================================ --}}

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