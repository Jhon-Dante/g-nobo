<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    {{-- viewport meta tag  --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Favicon icon --}}
    {{-- <link href="../asset/images/favicon.png" type="image/png" rel="icon"> --}}

    {{-- Browser navbar color for mobile --}}
    {{-- Chrome, Firefox OS and Opera  --}}
    <meta name="theme-color" content="#5165d6">
    {{-- Windows Phone  --}}
    <meta name="msapplication-navbutton-color" content="#5165d6">
    {{-- iOS Safari --}}
    <meta name="apple-mobile-web-app-status-bar-style" content="#5165d6">
    {{-- CSRF token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Page title --}}
    <title>@yield('title') | Administrador</title>
    {{-- <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?1.0') }}" /> --}}
    {{-- ================================================ 
    CSS
    ================================================ --}}
    {{-- Your custom  stylesheet --}}
    <link href="{{ asset('css/style.css') }}" type="text/css" rel="stylesheet" />
    {{-- materialize stylesheet --}}
    <link href="{{ asset('vendors/materialize/materialize.min.css') }}" type="text/css" rel="stylesheet" />
    {{-- google material design icons --}}
    <link href="{{ asset('vendors/material-icons/material-icons.css') }}" type="text/css" rel="stylesheet" />
    {{-- prism syntax highlighter --}}
    <link href="{{ asset('vendors/prism/prism.css') }}" type="text/css" rel="stylesheet" />
    {{-- sweet alert --}}
    <link href="{{ asset('vendors/sweetalert/sweetalert.css') }}" type="text/css" rel="stylesheet" />
    {{-- materialcss app stylesheet --}}
    <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet" />
    {{-- materialcss theme stylesheet --}}
    <link href="{{ asset('css/theme-1.css') }}" type="text/css" rel="stylesheet" />

</head>
    <body>
        {{-- Page wrapper --}}
        <div class="page-wrapper">
            {{-- //////////////////////////////////////////////////////////////////////////// --}}
            {{--Control page--}}
            <div id="control-body" class="fixed-sidebar medium-sidebar fixed-nav">
                
                {{-- //////////////////////////////////////////////////////////////////////////// --}}
                {{--prepage loader--}}
                @include('partials.loader')
                {{-- End prepage loader--}}
                {{-- //////////////////////////////////////////////////////////////////////////// --}}


                {{-- //////////////////////////////////////////////////////////////////////////// --}}
                {{--Header--}}
                @include('partials.header')
                {{--End Header--}}
                {{-- //////////////////////////////////////////////////////////////////////////// --}}


                {{-- //////////////////////////////////////////////////////////////////////////// --}}
                {{--Left sidebar--}}

                <aside id="menu">
                    {{-- overlay --}}
                    <div class="left-sidebar-overlay"></div>
                    <div class="left-sidebar">
                        <div id="logo">
                            <img class="logo-image" src="{{ asset('img/logo.png') }}" alt="">
                            {{--  <h1 class="impegno">Wará</h1>  --}}
                        </div>
                        {{--end brand --}}
                        {{--Left sidebar body--}}
                        <div class="left-sidebar-body">
                            {{--sidebar menu--}}
                            @include("partials.menu")
                            {{--End sidebar menu--}}
                        </div>

                        {{--End Left sidebar body--}}

                        {{--Left sidebar footer--}}
                        <div class="sidebar-footer">
                            <a href="javascript:void(0)" id="collapsed-left-sidebar" class="toggle-icon-left-sidebar"><i class="right material-icons">keyboard_arrow_left</i></a>
                        </div>
                        {{--End Left sidebar footer--}}
                    </div>
                </aside>
                {{--End left sidebar--}}
                {{-- //////////////////////////////////////////////////////////////////////////// --}}

                
                {{-- //////////////////////////////////////////////////////////////////////////// --}}
                {{--Page Body--}}
                <main class="page-body" id="app">
                    {{--start section--}}
                        @yield('content')
                    {{--end section--}}
                </main>
                {{--End page body--}}
                {{-- //////////////////////////////////////////////////////////////////////////// --}}
                {{-- @include('partials.footer') --}}
                {{-- //////////////////////////////////////////////////////////////////////////// --}}
                {{--Footer--}}
                
                {{--End footer--}}
                {{-- //////////////////////////////////////////////////////////////////////////// --}}
            </div>
            {{--End page control--}}
            {{-- //////////////////////////////////////////////////////////////////////////// --}}

        </div>
        {{--End page wrapper--}}

        {{-- Scripts --}}

        {{-- Jquery --}}
        {{-- Your custom script --}}
        <script src="{{ asset('js/script.js') }}" type="text/javascript"></script>
        {{--  <script src="{{ asset('vendors/jquery/jquery.min.js') }}" type="text/javascript"></script>  --}}
        {{-- Materialize js --}}
        <script src="{{ asset('vendors/materialize/materialize.min.js') }}" type="text/javascript"></script>
        {{-- prism syntax highlighter --}}
        <script src="{{ asset('vendors/prism/prism.js') }}" type="text/javascript"></script>
        {{-- sweet alert --}}
        <script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
        {{-- materialcss theme script --}}
        <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).on('click', ['.month-next','month-prev'], function () {
                console.clear();
            }); 
        </script>
    </body>
</html>