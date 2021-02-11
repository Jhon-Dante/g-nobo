<?php
	use App\Libraries\Cart;
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>{{ config('app.name') }} | @yield('title')</title>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<!-- favicon -->
	<link rel="shortcut icon" href="{{ asset('favicon.ico?').config('app.version') }}" type="image/x-icon">
	<link rel="icon" href="{{ asset('favicon.ico?').config('app.version') }}" type="image/x-icon">

	
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css" rel="stylesheet" />
	<link href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}" type="text/css" rel="stylesheet" />
	<link href="{{ asset('bower_components/sweetalert/dist/sweetalert.css') }}" type="text/css" rel="stylesheet" />
	<link href="{{ asset('bower_components/hold-on/src/css/HoldOn.min.css') }}" type="text/css" rel="stylesheet" />
	<link href="{{ asset('bower_components/owl-carousel/dist/assets/owl.carousel.min.css') }}" type="text/css" rel="stylesheet" />
	<link href="{{ asset('bower_components/owl-carousel/dist/assets/owl.theme.default.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/main.css?1.2.1') }}" type="text/css" rel="stylesheet" />
	<link href="{{ asset('css/public.css') }}" type="text/css" rel="stylesheet" />
	<!-- Facebook Pixel Code -->
	<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '1851899381618335');
		fbq('track', 'PageView');
	</script>
	<noscript>
		<img height="1" width="1" style="display:none"
			src="https://www.facebook.com/tr?id=1851899381618335&ev=PageView&noscript=1"
		/>
	</noscript>
	<!-- End Facebook Pixel Code -->
	@yield('styles')
</head>
<body style="overflow-x: hidden !important;">

	@include('layouts.header')

	<main>

		@yield('content')
		
		@include('modals.modal-cart') 

		@include('layouts.modal-login-register')

		@if(!Request::is('/') && !Request::is('search'))
			@include('layouts.sidebar')
		@endif

		@if(!Request::is('/home') && !Request::is('search'))
			<!--@include('layouts.footer') -->
		@endif
	</main>

	<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/array-find-polyfill.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/vue/vue.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/axios/dist/axios.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/hold-on/src/js/HoldOn.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/owl-carousel/dist/owl.carousel.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/loader.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/filtros.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/plugins.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/currency.js?1.0') }}"></script>
	<script type="text/javascript">
		var _transfer = '{{ Lang::get('Page.Transferencia.Type') }}';
		const countCart = '{{ Cart::count() }}'
		const pageError  = '{{ Lang::get('Page.Error') }}'
		const loginUrl = '{{ URL('login') }}'
		const messageTerminos = '{{ Lang::get('Page.Register.Terminos') }}'
		const loginSuccess = '{{ Lang::get('Page.Register.Success') }}'
		const registerUrl = '{{ URL('register') }}'
		const registerError = '{{ Lang::get('Page.Error') }}'
		const currencySession = '{{ Session::get('currentCurrency', "3")}}';
		const currentCurrency = '{{ Session::get('currentCurrency', "2")}}';
		const shopUrl = '{{ URL('tienda/ajax') }}'
		const homeUrl = '{{ URL('/') }}'
	</script>
	<script type="text/javascript" src="{{ asset('js/modal-login-register.js?1.0') }}"></script>
	<script src="{{ asset('js/header.js?v=').config('app.version') }}"></script>
	<script src="{{ asset('js/cartModule.js?v=').config('app.version') }}"></script> 
	<script src="{{ asset('js/cartModal.js?v=').config('app.version') }}"></script>
	<script type="text/javascript"> 
		vue_header.query = '{{ isset($query) ? $query : '' }}';
		var vue_cart_page = null;
	</script>
	@if(!Request::is('/') && !Request::is('search'))
		<script type="text/javascript" src="{{ asset('js/sidebar.js?1.0') }}"></script>
	@endif
	@yield('scripts')
</body>
</html>
