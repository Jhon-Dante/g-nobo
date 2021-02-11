<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Pdf Pedidos</title>
	<link rel="stylesheet" href="{{ asset('css/orders.css') }}" />
</head>
<body>
	<div class="header">
		<div class="img">
			<img class="logo" src="{{ asset('img/logo-black.png') }}">
		</div>
	</div>
	<div class="invoice-box">
		<table cellpadding="0" cellspacing="0">
			<tr class="top">
				<td class="title">
					<h4 class="text-center text-uppercase">ORDEN: #{{ $compra->id }}</h4>
				</td>
			</tr>
			<tr class="information">
				<td>
					<table>
						<tr>
							<td class="border-top">
								<h5 class="text-left">DATOS DEL CLIENTE</h5>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Nombre y Apellido: </strong>{{ $user->name }} -
								<strong>{{ $user->persona == 1 ? 'Cédula de Identidad' : 'Rif' }}:</strong>
								{{ $user->identificacion }}
								<br>
								<strong>Teléfono: </strong> {{ $user->telefono }} - <strong>Correo electrónico: </strong> {{ $user->email }}<br>
								<strong>Estatus: {{ $compra->status_text }}</strong>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="information">
				<td>
					<table>
						<tr>
							<td class="border-top">
								<h5 class="text-left">DATOS DE LA PERSONA QUE RECIBE </h5>
							</td>
						</tr>
						<tr>
							<td>
								<strong>Recibe:</strong> {{ $user->name }} - <strong>{{ $user->persona == 1 ? 'Cédula de Identidad:' : 'Rif:' }}</strong> {{ $user->identificacion }} <br />
								<strong>Municipio:</strong> {{ $user->municipality->name }} <strong>Sector:</strong> {{ $user->parish->name }} <br />
								@if (isset($compra->delivery->state))
									<strong>Estado: </strong> {{$compra->delivery->state->nombre}} <br>
								@endif
								@if (isset($compra->delivery->note))
									<strong>Nota: </strong> {{$compra->delivery->note }} <br>
									@endif
								<strong>Dirección de entrega:</strong> {{ $compra->delivery->address }} <br />
								<strong>Fecha de Pedido:</strong> {{ \Carbon\Carbon::parse($compra->created_at)->format('d-m-Y H:i A') }} <br>
								<strong>Fecha de Entrega:</strong> {{ \Carbon\Carbon::parse($compra->delivery->date_formated)->format('d-m-Y H:i A') }} - <strong>Turno:</strong> {{ $compra->delivery->turn_formated }}
								@if (isset($compra->delivery->pay_with))
									<strong>Monto a pagar por el cliente:</strong>&nbsp; {{$compra->delivery->pay_with}}
								@endif
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0">
			<tr class="information">
				<td class="border-top">
					<h4 class="text-left text-uppercase">INFORMACION DE TU PEDIDO</h4>
				</td>
			</tr>
		</table>
		<table class="bordered">
			<tr>
				<th>Descripción</th>
				<th>Impuesto</th>
				<th>Cantidad</th>
				<th>Costo</th>
				<th>Total</th>
			</tr>
			@foreach($compra->details as $item)
			<tr>
				<td>
					@if($item->product == null)
					{{ $item->discount_description }}
					@else
					{{ \App::getLocale() == 'es' ? $item->product->name : $item->product->name_english }}
					{{ $item->presentation }}
					{{ $item->unit }}
					{{ $item->discounts_text }}
					@endif
				</td>
				<td class="text-center">
					@if($item->product != null) 
						{{ $item->product->taxe ? $item->product->taxe->name : 'Exento' }}
					@endif
				</td>
				<td class="text-center">{{ $item->quantity }}</td>
				<td class="text-center">{{ Money::getByCurrency(CalcPrice::getByCurrency($item->price,$item->coin,$compra->exchange->change, $compra->currency), $compra->currency) }}</td>
				<td class="text-right">{{ Money::getByCurrency(CalcPrice::getByCurrency($item->price,$item->coin,$compra->exchange->change, $compra->currency) * $item->quantity, $compra->currency) }}</td>
			</tr>
			@endforeach
			<tr>
				<th colspan="4">Subtotal</th>
				<td>{{ Money::getByCurrency(Total::getByCurrency($compra), $compra->currency) }}</td>
			</tr>
			<tr>
				<th colspan="4">Costo de Envio</th>
				<td>{{ Money::getByCurrency(CalcPrice::getByCurrency($compra->shipping_fee, $compra->details[0]->coin, $compra->exchange->change, $compra->currency), $compra->currency) }}</td>
			</tr>
			<tr>
				<th colspan="4">Método de pago</th>
				<td>{{ $compra->text_payment_type }}</td>
			</tr>
			<tr>
				<th colspan="4">Total</th>
				<td>{{
				Money::getByCurrency(
					Total::getByCurrency($compra) + CalcPrice::getByCurrency($compra->shipping_fee, $compra->details[0]->coin, $compra->exchange->change, $compra->currency),
					$compra->currency
				) 
			}}</td>
			</tr>
		</table>
		<p class="text-center">
			Atentamente, <br />
			Tu Equipo ProMArKet Latino
		</p>
	</div>
</body>
</html>