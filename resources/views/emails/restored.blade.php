<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Cambio de clave</title>
	<link rel="StyleSheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700" />
	@include('emails.new-style')
</head>
<body>
	<div class="container">
        @include('emails.partials.header')
        <h1 class="title text-center">RECUPERACIÓN DE CLAVE</h1>
        <div class="text-justify">
            <p>Hola, {{ $user->name }}</p>
            <p class="mt-2">Esta es una Notificación de <b>RECUPERACIÓN O CAMBIO DE CLAVE</b></p>
            <p>Tu usuario es: <code>{{ $user->email }}</code></p>
            <p class="mt-1">Tu nueva clave es: <code>{{ $password }}</code></p>
            <p class="mt-2">Por favor guarda este registro en un lugar seguro, y recuerda que tu clave es confidencial no debes entregársela a terceras personas.</p>
        </div>
        @include('emails.partials.footer')
	</div>
</body>
</html>