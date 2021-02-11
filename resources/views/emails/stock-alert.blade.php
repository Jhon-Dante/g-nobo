<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Alerta por Umbral de Existencia | {{ env('APP_NAME') }}</title>
	<link rel="StyleSheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700" />
	@include('emails.new-style')
</head>
<body>
	<div class="container">
		@include('emails.partials.header')
		<div class="text-center">
			<h2 class="title">Alerta de Umbral de Existencia</h2>
		</div>
		<div class="row text-center">
			<div class="col-12">
				<p class="intro-text">Uno o mas productos han llegando a su Umbral de Existencia o se encuentran agotados</p>
			</div>
		</div>
		<table cellspacing="0" cellpadding="0">
			<thead align="left">
				<tr>
					<th>Nombre</th>
					<th class="text-center">Unidades disponibles</th>
				</tr>
			</thead>
			<tbody>
				@foreach($products as $product)
					<tr class="borde-blanco">
						<td>
							{{ $product['name'] }}
						</td>
						<td class="text-center">
							@if($product['amount'] == 0) 
								Agotado
							@else
								{{ $product['amount'] }}
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
    </div>
</body>