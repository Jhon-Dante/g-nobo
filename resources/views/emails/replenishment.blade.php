<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Reposicion de Inventario | {{ env('APP_NAME') }}</title>
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
		<h2 class="title">REPOSICIÓN DE INVENTARIO</h2>
		<p>Usuario autor de la operación: {{$rep->user->name}}</p>
		<br>
		<p>
			Se registro una {{$rep->type == 0 ? 'Entrada' : 'Salida'}} del producto {{$rep->presentation->product->es_name}}.
			Presentacion: {{ $presentationFormatted }}
		</p>
		<br>
		<p>
			Resultado del movimiento:
			<br>
			Cantidad en Existencia: {{$rep->existing}}
			<br>
			Cantidad de Reposición: {{$rep->modified}}
			<br>
			Cantidad Total: {{$rep->final}}
		</p>
        <p>
			Razón: {{$rep->reason}}
		</p>
	</div>
</body>
</html>