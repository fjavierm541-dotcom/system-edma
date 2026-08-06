<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Portal EDMA')
    </title>

    @vite(['resources/js/app.js'])

    @stack('styles')
</head>

<body class="portal-body">

    <div class="portal-wrapper" id="portalWrapper">

        {{-- Menú lateral --}}
        <x-portal.sidebar />

        {{-- Fondo móvil para cerrar el sidebar --}}
        <div
            class="portal-sidebar-overlay"
            id="portalSidebarOverlay"
            aria-hidden="true"
        ></div>

        <div class="portal-main">

            {{-- Barra superior --}}
            <x-portal.navbar />

            <main class="portal-content">

                <div class="portal-content-container">

                    {{-- Encabezado de la página --}}
                    @hasSection('page-header')
                        <div class="portal-page-header">
                            @yield('page-header')
                        </div>
                    @endif

                    {{-- Alertas globales --}}
                    <x-portal.alerts />

                    {{-- Contenido principal --}}
                    @yield('content')

                </div>

            </main>

            <x-portal.footer />

        </div>

    </div>

    @stack('scripts')

</body>
</html>
