@php
    $usuarioActual = auth()->user();

    $personaActual = $usuarioActual?->persona;

    $nombreUsuario =
        $personaActual?->nombre_completo
        ?? $usuarioActual?->username
        ?? 'Usuario';

    $inicialesUsuario =
        $personaActual?->iniciales
        ?? 'ED';

    $rolUsuario =
        $usuarioActual?->roles
            ?->pluck('nombre')
            ->filter()
            ->implode(', ');

    $rolUsuario = $rolUsuario ?: 'Sin rol';


    /*
    |--------------------------------------------------------------------------
    | Resolver ruta de perfil
    |--------------------------------------------------------------------------
    */

    $rutaPerfil = null;

    if ($personaActual?->estudiante) {

        $rutaPerfil = route(
            'portal.estudiantes.show',
            $personaActual->estudiante
        );

    } elseif (
        $personaActual?->empleado?->docente
    ) {

        $rutaPerfil = route(
            'portal.docentes.show',
            $personaActual->empleado->docente
        );

    } elseif ($personaActual?->empleado) {

        $rutaPerfil = route(
            'portal.empleados.show',
            $personaActual->empleado
        );

    } elseif ($personaActual) {

        $rutaPerfil = route(
            'portal.personas.show',
            $personaActual
        );
    }
@endphp


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

        {{-- =====================================================
             Notificaciones
             ===================================================== --}}

        <button
            type="button"
            class="portal-icon-button"
            aria-label="Notificaciones"
        >
            <i class="bi bi-bell"></i>

            <span class="portal-notification-dot"></span>
        </button>


        {{-- =====================================================
             Usuario autenticado
             ===================================================== --}}

        <div class="dropdown">

            <button
                type="button"
                class="portal-user-button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >

                {{-- Avatar --}}
                <span class="portal-user-avatar">
                    {{ $inicialesUsuario }}
                </span>


                {{-- Nombre y rol --}}
                <span class="portal-user-information">

                    <strong>
                        {{ $nombreUsuario }}
                    </strong>

                    <small>
                        {{ $rolUsuario }}
                    </small>

                </span>


                <i class="bi bi-chevron-down"></i>

            </button>


            {{-- =================================================
                 Menú desplegable
                 ================================================= --}}

            <ul class="dropdown-menu dropdown-menu-end portal-user-menu">

                {{-- Mi perfil --}}
                <li>

                    @if ($rutaPerfil)

                        <a
                            class="dropdown-item"
                            href="{{ $rutaPerfil }}"
                        >
                            <i class="bi bi-person"></i>

                            Mi perfil
                        </a>

                    @else

                        <span
                            class="dropdown-item disabled"
                        >
                            <i class="bi bi-person"></i>

                            Mi perfil
                        </span>

                    @endif

                </li>


                {{-- Cambiar contraseña --}}
                <li>

                    <a
                        class="dropdown-item"
                        href="{{ route('password.change.edit') }}"
                    >
                        <i class="bi bi-key"></i>

                        Cambiar contraseña
                    </a>

                </li>


                <li>
                    <hr class="dropdown-divider">
                </li>


                {{-- Cerrar sesión --}}
                <li>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="m-0"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="dropdown-item text-danger"
                        >
                            <i class="bi bi-box-arrow-right"></i>

                            Cerrar sesión
                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</header>