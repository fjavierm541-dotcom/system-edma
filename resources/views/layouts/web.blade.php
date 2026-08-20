<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    {{-- =====================================================
         SEO principal
    ====================================================== --}}

    <title>
        @yield(
            'title',
            'Edumerican Academy Honduras | Formación en inglés'
        )
    </title>

    <meta
        name="description"
        content="@yield(
            'description',
            'Edumerican Academy Honduras ofrece formación virtual en inglés para niños, jóvenes y adultos mediante programas organizados en niveles progresivos.'
        )"
    >

    <meta
        name="robots"
        content="index, follow"
    >

    <meta
        name="author"
        content="Edumerican Academy Honduras"
    >

    <link
        rel="canonical"
        href="{{ url()->current() }}"
    >


    {{-- =====================================================
         Favicon
    ====================================================== --}}

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/brand/favicon-edma.png') }}"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('images/brand/favicon-edma.png') }}"
    >


    {{-- =====================================================
         Open Graph
    ====================================================== --}}

    <meta
        property="og:type"
        content="website"
    >

    <meta
        property="og:site_name"
        content="Edumerican Academy Honduras"
    >

    <meta
        property="og:title"
        content="@yield(
            'og_title',
            trim($__env->yieldContent(
                'title',
                'Edumerican Academy Honduras'
            ))
        )"
    >

    <meta
        property="og:description"
        content="@yield(
            'og_description',
            trim($__env->yieldContent(
                'description',
                'Formación virtual en inglés para niños, jóvenes y adultos.'
            ))
        )"
    >

    <meta
        property="og:url"
        content="{{ url()->current() }}"
    >

    <meta
        property="og:image"
        content="@yield(
            'og_image',
            asset('images/brand/edma-social-share.jpg')
        )"
    >


    {{-- =====================================================
         Twitter / X
    ====================================================== --}}

    <meta
        name="twitter:card"
        content="summary_large_image"
    >

    <meta
        name="twitter:title"
        content="@yield(
            'twitter_title',
            trim($__env->yieldContent(
                'title',
                'Edumerican Academy Honduras'
            ))
        )"
    >

    <meta
        name="twitter:description"
        content="@yield(
            'twitter_description',
            trim($__env->yieldContent(
                'description',
                'Formación virtual en inglés para niños, jóvenes y adultos.'
            ))
        )"
    >

    <meta
        name="twitter:image"
        content="@yield(
            'twitter_image',
            asset('images/brand/logo-edma.png')
        )"
    >


    {{-- =====================================================
         Navegadores móviles
    ====================================================== --}}

    <meta
        name="theme-color"
        content="#001749"
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