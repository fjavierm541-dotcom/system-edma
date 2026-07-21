<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Edumerican Academy Honduras')
    </title>

    <meta
        name="description"
        content="@yield('description', 'Edumerican Academy Honduras')"
    >

    @vite('resources/js/app.js')

</head>

<body>

    @include('components.website.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.website.footer')

    @include('components.website.scripts')

    @stack('scripts')

</body>

</html>