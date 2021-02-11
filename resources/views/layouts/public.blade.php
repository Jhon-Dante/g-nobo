<!DOCTYPE html>
<html lang="es">
{{-- Head --}}
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    {{-- viewport meta tag --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Favicon icon --}}
    <link href="../asset/images/favicon.png" type="image/png" rel="icon">

    {{-- Browser navbar color for mobile --}}
    {{-- Chrome, Firefox OS and Opera --}}
    <meta name="theme-color" content="#5165d6">
    {{-- Windows Phone --}}
    <meta name="msapplication-navbutton-color" content="#5165d6">
    {{-- iOS Safari --}}
    <meta name="apple-mobile-web-app-status-bar-style" content="#5165d6">
    {{-- CSRF token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Page title --}}
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?1.0') }}" />
    {{-- ================================================ 
    CSS
    ================================================ --}}
    <link href="{{ asset('css/style.css') }}" type="text/css" rel="stylesheet" />
    {{-- materialize stylesheet --}}
    <link href="{{ asset('vendors/materialize/materialize.min.css') }}" type="text/css" rel="stylesheet" />
    {{-- google material design icons --}}
    <link href="{{ asset('vendors/material-icons/material-icons.css') }}" type="text/css" rel="stylesheet" />
    {{-- prism syntax highlighter --}}
    <link href="{{ asset('vendors/prism/prism.css') }}" type="text/css" rel="stylesheet" />
    {{-- materialcss app stylesheet --}}
    <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet" />
    {{-- materialcss theme stylesheet --}}
    <link href="{{ asset('css/theme-1.css') }}" type="text/css" rel="stylesheet" />
    {{-- Your custom  stylesheet --}}
    


</head>
{{-- End head --}}
{{-- ////////////////////////////////////////////////////////////////////////////  --}}

{{-- ////////////////////////////////////////////////////////////////////////////  --}}
{{-- body --}}

<body>
    {{--  ////////////////////////////////////////////////////////////////////////////  --}}
    {{-- Page wrapper --}}
    <div class="page-wrapper">
        {{-- //////////////////////////////////////////////////////////////////////////// --}}
        {{-- Control page --}}
        <div id="control-body">
            {{-- ////////////////////////////////////////////////////////////////////////////  --}}
            {{-- prepage loader --}}
            @include('partials.loader')
            {{-- End prepage loader --}}
            {{-- //////////////////////////////////////////////////////////////////////////// --}}

            {{-- //////////////////////////////////////////////////////////////////////////// --}}
            {{-- form Page Body --}}
            <main class="bg-impegno">
                {{-- start section --}}
                <section class="section" id="app">
                    @yield("content")
                </section>
                {{-- end section --}}

            </main>
            {{-- End form page body --}}
            {{-- //////////////////////////////////////////////////////////////////////////// --}}

        </div>
        {{-- End page control --}}
        {{-- //////////////////////////////////////////////////////////////////////////// --}}

    </div>
    {{-- End page wrapper --}}
    {{-- //////////////////////////////////////////////////////////////////////////// --}}

    {{-- //////////////////////////////////////////////////////////////////////////// --}}
    {{-- Scripts --}}

    {{-- Jquery --}}
    <script src="{{ asset('vendors/jquery/jquery.min.js') }}" type="text/javascript"></script>
    {{-- Materialize js --}}
    <script src="{{ asset('vendors/materialize/materialize.min.js') }}" type="text/javascript"></script>
    {{-- prism syntax highlighter --}}
    <script src="{{ asset('vendors/prism/prism.js') }}" type="text/javascript"></script>
    {{-- materialcss theme script --}}
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    {{-- Your custom script --}}
    <script src="{{ asset('js/script.js') }}" type="text/javascript"></script>

    {{-- End scripts --}}
    {{-- //////////////////////////////////////////////////////////////////////////// --}}
</body>
{{-- End body --}}
{{-- //////////////////////////////////////////////////////////////////////////// --}}

</html>
{{-- End HTML --}}
{{-- ////////////////////////////////////////////////////////////////////////////  --}}