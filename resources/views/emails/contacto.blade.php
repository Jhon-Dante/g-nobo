<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>@lang('Page.Contacto.Correo.Title')</title>
	<link rel="StyleSheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700" />
	@include('emails.style')
</head>
<body>
	<div class="container">
		@include('emails.partials.header')
		<div class="text-center">
			<h4 class="title">@lang('Page.Contacto.Correo.Title')</h4>
		</div>		
		<p><strong>@lang('Page.Contacto.Correo.Nombre'):</strong> {{ $nombre }}</p>
		<p><strong>@lang('Page.Contacto.Correo.Email'):</strong> {{ $email }}</p>
		<p><strong>@lang('Page.Contacto.Correo.Pais'):</strong> {{ $pais }}</p>
		<p><strong>@lang('Page.Contacto.Correo.Mensaje'):</strong> {!! nl2br($mensaje) !!}</p>
	</div>
</body>
</html>