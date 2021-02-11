<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>
		Orden #{{ $compra->id }}
	</title>
	<link rel="StyleSheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700" />
	@include('emails.new-style')
</head>
<body>
	<div class="container">
		@include('emails.partials.header')
		<div class="text-center">
			<h4 class="title">
				Orden #{{ $compra->id }}
			</h4>
		</div>
		<div class="row text-center">
			<div class="col-12">
				<p class="intro-text">Hola {{ $user->name }}, tu orden o pedido en {{ config('app.name') }} se ha {{ $statusName }}.</p>
			</div>
			<div class="col-12">
				<p class="intro-text">Los detalles se muestran a continuación para tu referencia.</p>
			</div>
			<div class="col-12">
				<p class="intro-text">Puedes chequear tu orden o pedido a cualquier hora ingresando a tu área de cliente</p>
			</div>
		</div>
		<div class="row text-justify">
			<div class="col-12">
				<h4 class="items-title">
					Tu pedido
				</h4>
			</div>
		</div>
		<table cellspacing="0" cellpadding="0">
			<thead align="left">
				<tr>
					<th>Descripción</th>
					<th class="text-center">Impuesto</th>
					<th class="text-center">@lang('Page.EmailCompra.Cantidad')</th>
					<th class="text-center">@lang('Page.EmailCompra.Costo')</th>
					<th class="text-right">@lang('Page.EmailCompra.Total')</th>
				</tr>
			</thead>
			<tbody>
				@foreach($compra->details as $item)
					<tr class="borde-blanco">
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
			</tbody>
			<tfoot>
				<tr class="text-justify">
					<td colspan="3">
						<p class="font-bold">Subtotal</p>
					</td>
					<td class="money" colspan="">
						<p>{{ Money::getByCurrency(Total::getByCurrency($compra), $compra->currency) }}</p>
					</td>
				</tr>
				<tr class="text-justify">
					<td colspan="3">
						<p class="font-bold">Costo de Envio</p>
					</td>
					<td class="money" colspan="">
						<p>{{ Money::getByCurrency(CalcPrice::getByCurrency($compra->shipping_fee, $compra->details[0]->coin, $compra->exchange->change, $compra->currency), $compra->currency) }}</p>
					</td>
				</tr>
				<tr class="text-justify">
					<td colspan="3">
						<p class="font-bold">Método de pago:</p>
					</td>
					<td class="money" colspan="">
						<p>{{ $compra->text_payment_type }}</p>
					</td>
				</tr>
				<tr class="text-justify">
					<td colspan="3">
						<p class="font-bold">Total</p>
					</td>
					<td class="money" colspan="">
						<p>
							{{ 
								Money::getByCurrency(
									Total::getByCurrency($compra) + CalcPrice::getByCurrency($compra->shipping_fee, $compra->details[0]->coin, $compra->exchange->change, $compra->currency),
									$compra->currency
								) 
							}}
						</p>
					</td>
				</tr>
			</tfoot>
		</table>
		<div class="row col-12 text-justify mt-2">
			<div class="col-12">
				<p class="delivery-title">
					Datos de contacto del Cliente
				</p>
			</div>
			<div class="col-12">
				<p class="contact-text">Nombre y Apellido: {{ $user->name }} - 
					{{ $user->persona == 1 ? 'Cédula de Identidad:' : 'Rif:' }} 
					{{ $user->identificacion }}
				</p>
			</div>
			<div class="col-12">
				<p class="contact-text">Teléfono: {{ $user->telefono }} - Correo electrónico: {{ $user->email }}</p>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<p class="delivery-title">
					Datos de contacto de la Persona que recibe
				</p>
			</div>
			<div class="col-12">
				<p class="contact-text">Recibe: {{ $user->name }} - 
					{{ $user->persona == 1 ? 'Cédula de Identidad:' : 'Rif:' }} {{ $user->identificacion }} </p>
			</div>
			<div class="col-12">
				<p class="contact-text">Municipio: {{ $user->municipality->name }} Sector: {{ $user->parish->name }}</p>
			</div>
			<div class="col-12">
				<p class="contact-text">Dirección de entrega: {{ $compra->delivery->address }}</p>
			</div>
			<div class="col-12">
				<p class="contact-text">
					Fecha: {{ $compra->delivery->date_formated }}  - 
					Turno: {{ $compra->delivery->turn_formated }}
				</p>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<p class="delivery-title">
					Datos de pago
				</p>
			</div>
			<div class="col-12">
				<p class="contact-text">
					Método: {{ $compra->text_payment_type }}
				</p>
			</div>
			@if($compra->payment_type == 1 || $compra->payment_type == 2){{-- Transferencia o Pago Movil  --}}
				<div class="col-12">
					<p class="contact-text">
						Banco: {{ $compra->transfer->bankAccount->bank->name }} - 
						{{ $compra->payment_type == 1 ? 'Nro. Cuenta:' : 'Tel:' }} 
						{{ $compra->payment_type == 1 ? $compra->transfer->bankAccount->number :  $compra->transfer->bankAccount->phone }}
					</p>
				</div>
				<div class="col-12">
					<p class="contact-text">
						Nro. Referencia: {{ $compra->transfer->number }}
					</p>
				</div>
			@endif
			@if($compra->payment_type == 3) {{-- Zelle --}}
				<div class="col-12">
					<p class="contact-text">
						Nombre: {{ $compra->transfer->name }}
					</p>
				</div>
				<div class="col-12">
					<p class="contact-text">
						Nro. Referencia: {{ $compra->transfer->number }}
					</p>
				</div>
			@endif
			@if($compra->payment_type == 4) {{-- PayPal --}}
				<div class="col-12">
					<p class="contact-text">
						Código de transacción: {{ $compra->transaction_code }}
					</p>
				</div>
			@endif
			@if($compra->payment_type == 5) {{-- Efectivo --}}
				<div class="col-12">
					<p class="contact-text">
						El cliente paga con: {{ Money::getByCurrency(
							CalcPrice::getByCurrency(
								$compra->delivery->pay_with, 
								$compra->currency, 
								$compra->exchange->change, 
								$compra->currency), $compra->currency
						) }} 
					</p>
				</div>
			@endif
			@if($compra->payment_type == 6) {{-- Stripe --}}
				<div class="col-12">
					<p class="contact-text">
						Código de transacción: {{ $compra->transfer->number }}
					</p>
				</div>
			@endif
		</div>
		@include('emails.partials.footer')
	</div>
</body>
</html>