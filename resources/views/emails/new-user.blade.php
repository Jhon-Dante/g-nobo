<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Nuevo registro de usuario | {{ env('APP_NAME') }}</title>
	<style type="text/css">
		.container {
			text-align: center;
			font-family: Calibri;
			padding: 40px;
		}
	</style>
</head>
<body>
	<div class="container">
		<h2 class="title">Nuevo registro de usuario</h2>
        <p>Un nuevo usuario se ha registrado, su nombre: {{$user->name}} y su email: {{$user->email}} </p>
	</div>
</body>
</html>