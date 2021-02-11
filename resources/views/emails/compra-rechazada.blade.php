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
		<h1 class="title text-center">Orden #{{ $compra->id }}</h1>
		<div class="text-justify">
            <p>Hola, <b>{{ $user->name }}</b></p>
			<p class="mt-2">Tu orden Orden Nro. {{ $compra->payment_type == '4' ? $compra->transaction_code : $compra->transferNumber  }} 
				fue <b>RECHAZADA</b> por: {{ $compra->rejectReason }}, lamentamos el incoveniente y te invitamos a seguir comprando nuestros productos.
			</p>
			<p class="mt-2">Puedes monitorear el estatus de tu orden desde la opcion historial de pedido que se encuentra
				en tu perfil de usuario de la tienda.
			</p>
			<hr class="mt-2">
		</div>
		<div class="row mt-2">
			<div class="col-12">
				<p class="contact-title">
					DATOS DEL CLIENTE
				</p>
			</div>
			<div class="col-12">
			<p class="contact-text"><b>Nombre y Apellido:</b> {{ $user->name }} - 
				<b>{{ $user->persona == 1 ? 'Cédula de Identidad:' : 'Rif:' }}</b> 
				{{ $user->identificacion }}
			</p>
			</div>
			<div class="col-12">
				<p class="contact-text"><b>Teléfono:</b> {{ $user->telefono }} - <b>Correo electrónico:</b> {{ $user->email }}</p>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-12">
				<p class="contact-title">
					DATOS DE LA PERSONA QUE RECIBE
				</p>
			</div>
			<div class="col-12">
				<p class="contact-text"><b>Recibe:</b> {{ $user->name }} - 
					<b>{{ $user->persona == 1 ? 'Cédula de Identidad:' : 'Rif:' }}</b> {{ $user->identificacion }} </p>
			</div>
			<div class="col-12">
				<p class="contact-text"><b>Municipio:</b> {{ $user->municipality->name }} <b>Sector:</b> {{ $user->parish->name }}</p>
			</div>
			<div class="col-12">
				<p class="contact-text"><b>Dirección de entrega:</b> {{ $compra->delivery->address }}</p>
			</div>
			<div class="col-12">
				<p class="contact-text">
					<b>Fecha:</b> {{ $compra->delivery->date_formated }}  - 
					<b>Turno:</b> {{ $compra->delivery->turn_formated }}
				</p>
			</div>
			@if($compra->delivery->note)
				<div class="col-12">
					<p class="contact-text"><b>Nota:</b> {{ $compra->delivery->note }}</p>
				</div>
			@endif
		</div>
		<hr>
		<div class="row">
			<div class="col-12">
				<p class="contact-title">
					DATOS DE PAGO
				</p>
			</div>
			<div class="col-12">
				<p class="contact-text">
					<b>Método:</b> {{ $compra->text_payment_type }}
				</p>
			</div>
			@if($compra->payment_type == 1 || $compra->payment_type == 2){{-- Transferencia o Pago Movil  --}}
				<div class="col-12">
					<p class="contact-text">
						<b>Banco:</b> {{ $compra->transfer->bankAccount->bank->name }} - 
						<b>{{ $compra->payment_type == 1 ? 'Nro. Cuenta:' : 'Tel:' }} </b>
						{{ $compra->payment_type == 1 ? $compra->transfer->bankAccount->number :  $compra->transfer->bankAccount->phone }}
					</p>
				</div>
				<div class="col-12">
					<p class="contact-text">
						<b>Nro. Referencia:</b> {{ $compra->transfer->number }}
					</p>
				</div>
			@endif
			@if($compra->payment_type == 3) {{-- Zelle --}}
				<div class="col-12">
					<p class="contact-text">
						<b>Nombre:</b> {{ $compra->transfer->name }}
					</p>
				</div>
				<div class="col-12">
					<p class="contact-text">
						<b>Nro. Referencia:</b> {{ $compra->transfer->number }}
					</p>
				</div>
			@endif
			@if($compra->payment_type == 4) {{-- PayPal --}}
				<div class="col-12">
					<p class="contact-text">
						<b>Código de transacción:</b> {{ $compra->transaction_code }}
					</p>
				</div>
			@endif
			@if($compra->payment_type == 5) {{-- Efectivo --}}
				<div class="col-12">
					<p class="contact-text">
						<b>El cliente paga con:</b> {{ Money::getByCurrency(
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
						<b>Código de transacción:</b> {{ $compra->transfer->number }}
					</p>
				</div>
			@endif
		</div>
		<hr>
		<div class="row text-justify">
			<div class="col-12">
				<p class="contact-title">
					INFORMACION DE TU PEDIDO
				</p>
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
						<td class="text-center"><b>{{ $item->quantity }}</b></td>
						<td class="text-center"><b>{{ Money::getByCurrency(CalcPrice::getByCurrency($item->price,$item->coin,$compra->exchange->change, $compra->currency), $compra->currency) }}</b></td>
						<td class="text-right"><b>{{ Money::getByCurrency(CalcPrice::getByCurrency($item->price,$item->coin,$compra->exchange->change, $compra->currency) * $item->quantity, $compra->currency) }}</td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr class="text-justify">
					<td colspan="3">
						<p class="font-bold">Subtotal</p>
					</td>
					<td class="money" colspan="">
						<p><b>{{ Money::getByCurrency(Total::getByCurrency($compra), $compra->currency) }}</b></p>
					</td>
				</tr>
				<tr class="text-justify">
					<td colspan="3">
						<p class="font-bold">Costo de Envio</p>
					</td>
					<td class="money" colspan="">
						<p><b>{{ Money::getByCurrency(CalcPrice::getByCurrency($compra->shipping_fee, $compra->details[0]->coin, $compra->exchange->change, $compra->currency), $compra->currency) }}</b></p>
					</td>
				</tr>
				<tr class="text-justify">
					<td colspan="3">
						<p class="font-bold">Método de pago:</p>
					</td>
					<td class="money" colspan="">
						<p><b>{{ $compra->text_payment_type }}</b></p>
					</td>
				</tr>
				<tr class="text-justify">
					<td colspan="3">
						<p class="font-bold">Total</p>
					</td>
					<td class="money" colspan="">
						<p>
							<b>{{ 
								Money::getByCurrency(
									Total::getByCurrency($compra) + CalcPrice::getByCurrency($compra->shipping_fee, $compra->details[0]->coin, $compra->exchange->change, $compra->currency),
									$compra->currency
								) 
							}}</b>
						</p>
					</td>
				</tr>
			</tfoot>
		</table>	
		@include('emails.partials.footer')
	</div>
</body>
</html>