@extends('layouts.master')

@section('title')
	Verificación
@stop

@section('styles')
<!– [if gte IE 7]>
<style>
	@media all and (-ms-high-contrast:none)
     {
	 *::-ms-backdrop, .verification-content { 
			justify-content: space-around !important;
	 	} /* IE11 */
     }
</style>
<![endif]–>
@endsection
@section('content')
<div id="verificacion" class="verification" v-cloak>
    <div class="verification-title">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/icons/arrow-izquierda.svg') }}" alt="verificacion"/>
        </a>
        <h1 class="font-extrabold text-center">Verificación</h1>
    </div>
    <div class="verification-bg"></div>
    <div class="verification-content">
		{{-- CART --}}
        <div class="verification-cart">
			<div class="verificacion-cart-container shadow">
				<header class="verification-cart-header">
					{{ auth()->user()->name }} / {{ auth()->user()->email }}
				</header>
				<section>
					<template v-for="(item,index) in cart">
						<div class="verification-cart-item" >
							<div class="verification-cart-item-photo" :class="{ 'shadow-sm' : index % 2 == 0 }" :style="{ backgroundImage: 'url(' + item.image + ')' }"></div>
							<div class="verification-cart-item-data">
								<div class="verification-cart-item-data-name" v-cloak>
									@if (\App::getLocale() == 'es') 
										@{{ item.producto.name }} 
									@else 
										@{{ item.producto.name_english }} 
									@endif
								</div>
								<div class="verification-cart-item-data-unitary" v-cloak>
									<span v-if="currency == 1">@{{ getPriceByCurrency($getPriceByAmount(item.producto, item.amount), item.producto.coin) | VES }}</span>
									<span v-if="currency == 2">@{{ getPriceByCurrency($getPriceByAmount(item.producto, item.amount), item.producto.coin) | USD }}</span>
									<span v-if="item.producto.variable" v-cloak> -
										@{{ item.amount.presentation }} @{{ getUnit(item.amount.unit) }}
									</span>
									<div class="shop-cart-item-data-taxe">
										@{{ item.producto.taxe ? item.producto.taxe.name : 'Exento de IVA' }} 
									</div>
								</div>
								<div class="verification-cart-item-data-buttons">
									<div class="verification-cart-item-data-controls" :class="{ 'verification-cart-item-data-controls--error': item.amount.amount < item.cantidad }">
										<div class="verification-cart-item-data-unid px-3 font-semibold" v-cloak>
											@{{ item.cantidad }} unid.
										</div>
										{{-- <button class="verification-cart-item-data-controls--add" v-on:click="item.cantidad > 1 ? item.cantidad-- : null; update(item)">-</button>
										<input type="number" v-model="item.cantidad" max="99" width="20" min="1" @blur="update(item)" @keyup="checkquantity($event, index)">
										<button class="verification-cart-item-data-controls--sub" v-on:click="item.amount.amount > item.cantidad ? item.cantidad++ : null; item.amount.amount >= item.cantidad ? update(item) : null">+</button> --}}
									</div>
									<span v-if="currency == 1" class="verification-cart-item-data-price" v-cloak>
										@{{ getPriceByCurrency($getPriceCart(item), item.producto.coin) | VES }}
									</span>
									<span v-if="currency == 2" class="verification-cart-item-data-price" v-cloak>
										@{{ getPriceByCurrency($getPriceCart(item), item.producto.coin) | USD }}
									</span>
								</div>
								<span class="shop-cart-item-data-discount float-right pr-5 pt-2" 
									v-if="item.producto.discount && item.cantidad >= item.producto.discount.quantity_product && item.discountAvialable"
								>
									@{{ item.producto.discount.quantity_product }} x @{{ item.producto.discount.percentage }}% dcto.
								</span>
								<span v-show="item.amount.amount < item.cantidad" class="verification-cart-item-data-error">Cantidad no disponible</span>
							</div>
						</div>
					</template>
				</section>
			</div>
			<footer class="text-center mt-4">
				<div class="d-flex flex-row-reverse verification-cart-subtotal">
					<span>
						<span class="font-bold verifications-cart-subtotal-text">Subtotal:</span>
						<span v-if="currency == 1" class="font-extrabold verification-cart-subtotal-price" v-cloak>@{{ subtotal | VES }}</span>
						<span v-if="currency == 2" class="font-extrabold verification-cart-subtotal-price" v-cloak>@{{ subtotal | USD }}</span>
					</span>
				</div>
				<div class="d-flex flex-row-reverse shop-cart-discount pr-0" v-if="hasMinimumPurchaseDiscount()">
					<span>
						<span class="font-bold shop-cart-discount-text">
							-@{{ minimumDiscount.percentage }}% @{{ minimumDiscount.name }}:
							<span v-if="currency == 1" class="font-extrabold shop-cart-subtotal-price" v-cloak>-@{{ minimumPurchaseDiscount | VES }}</span>
							<span v-if="currency == 2" class="font-extrabold shop-cart-subtotal-price" v-cloak>-@{{ minimumPurchaseDiscount | USD }}</span>
						</span>
					</span>
				</div>
				<div class="d-flex flex-row-reverse shop-cart-discount pr-0" v-if="quantityDiscount && getSubtotalUsd() > 0" v-cloak>
					<span>
						<span class="font-bold shop-cart-discount-text" v-cloak>
							-@{{ quantityDiscount.percentage }}% @{{ quantityDiscount.name }}:
							<span v-if="currency == 1" class="font-extrabold shop-cart-subtotal-price" v-cloak>-@{{ quantityPurchaseDiscount | VES }}</span>
							<span v-if="currency == 2" class="font-extrabold shop-cart-subtotal-price" v-cloak>-@{{ quantityPurchaseDiscount | USD }}</span>
						</span>
					</span>
				</div>
				<div class="d-flex flex-row-reverse verification-cart-subtotal" v-if="shipping_selected != 2">
					<span>
						<span class="font-bold verifications-cart-subtotal-text">Costo de envío:</span>
						<span v-if="currency == 1" class="font-extrabold verification-cart-subtotal-price" v-cloak>@{{ shipping | VES }}</span>
						<span v-if="currency == 2" class="font-extrabold verification-cart-subtotal-price" v-cloak>@{{ shipping | USD }}</span>
					</span>
				</div>
				<div class="d-flex flex-row-reverse verification-cart-subtotal"  v-if="shipping_selected != 2 && free">
					<span class="verification-cart-subtotal-free">(Municipio con envío gratuito)</span>
				</div>
				<div class="d-flex flex-row-reverse verification-cart-subtotal" v-if="shipping_selected != 2 && this.form.payment_method == this.paypal_method">
					<span>
						<span class="font-bold verifications-cart-subtotal-text">Comisión PayPal:</span>
						<span v-if="currency == 1" class="font-extrabold verification-cart-subtotal-price" v-cloak>@{{ comission | VES }}</span>
						<span v-if="currency == 2" class="font-extrabold verification-cart-subtotal-price" v-cloak>@{{ comission | USD }}</span>
					</span>
				</div>
				<div class="d-flex flex-row-reverse">
					<span class="verification-cart-total">
						<span class="font-bold verifications-cart-total-text">TOTAL:</span>
						<span v-if="currency == 1" class="font-extrabold verification-cart-total-price">@{{ total | VES }}</span>
						<span v-if="currency == 2" class="font-extrabold verification-cart-total-price">@{{ total | USD }}</span>
					</span>
				</div>
			</footer>
		</div>
		
		{{-- ENTREGA --}}
        <div class="verification-send">
			<h2 class="text-center">Información de Entrega</h2>


			<div class="verification-send-data shadow" v-show="shipping_selected != shipping_pickup">
				<div class="form-check mt-1 mb-2">
					<input class="form-check-input" type="checkbox" v-model="my_address" id="adressR" value="true">
					<label class="form-check-label" for="adressR">Usar la dirección de mi registro</label>
				</div>
				<div class="form-row mb-2 pb-1">
					<div class="col" v-show="shipping_selected == shipping_nacional">
						{{ Form::select('estado',$estados, null,[
							'class' => 'form-control yellow-select', 
							'v-model' => 'form.estado', 
							'v-on:change' => "changeEstado", 
							'placeholder' => 'Estado',
							':disabled' => "my_address"
						]) }}
					</div>
					<div class="col">
						<select placeholder="Municipio" 
							name="municipio" 
							id="municipio" 
							class="form-control yellow-select" 
							:disabled="form.estado == '' || my_address"
							@change="changeMunicipio"
							v-model="form.municipio">
							<option value="" disabled selected>Municipio</option>
							<option :value="m.id" v-for="m in municipalities">
								@{{ m.name }}
							</option>
						</select>
					</div>
					<div class="col">
						<select 
							placeholder="Sector" 
							name="parroquia" 
							id="parroquia" 
							class="form-control yellow-select"
							:disabled="form.municipio == '' || my_address"
							v-model="form.parroquia">
							<option value="">Sector</option>
							@foreach ($parroquias as $parroquia)
								<option 
									v-show="form.municipio == {{ $parroquia->municipality_id }}" 
									value="{{ $parroquia->id }}">
									{{ $parroquia->name }}
								</option>
							@endforeach
						</select>
					</div>
				</div>

				<textarea name="" id="" v-model="form.address" rows="2" class="form-control input-gray" placeholder="Dirección de entrega:" :disabled="my_address"></textarea>
                                <div class="form-row mt-2 pt-1">
					<div class="col form-group" @click="openCalendar('datepicker')" >
						<input type="text" id="datepicker" readonly class="form-control input-gray" placeholder="Fecha de entrega">
					</div>
					<div class="col form-group">
						<select v-model="form.turn" type="text" class="form-control yellow-select" placeholder="Turno">
							<option value="">Turno de entrega</option>
							<option value="1">Mañana</option>
							<option value="2">Tarde</option>
							<option value="3">Noche</option>
						</select>
					</div>
				</di>
				<textarea name="" id="" v-model="form.note" rows="2" class="form-control input-gray" placeholder="Nota:"></textarea>
				<div class="verification-send-data-checks" v-if="shipping_selected == shipping_nacional">
					<div class="form-check">
						<input class="form-check-input" type="radio" v-model="form.type" id="exampleRadios1" value="1" checked>
						<label class="form-check-label" for="exampleCheck1">Cobro al destino</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" v-model="form.type" id="exampleRadios1" value="2" checked>
						<label class="form-check-label" for="exampleCheck2">Envío a tienda</label>
					</div>
				</div>
			</div>

			<div class="verification-send-data shadow" v-show="shipping_selected == shipping_pickup">
				<textarea name="" id="" rows="2" class="form-control input-gray" placeholder="Dirección de viveres" disabled></textarea>
				<p class="font-bold mt-4">Números de contacto: 0423232323 / 403434343434</p>
			</div>

			<h2 class="text-center mt-4" v-show="shipping_selected != shipping_pickup" :class="">Pagar con</h2>
			{{-- BS --}}
			<template v-if="currency == 1 && shipping_selected != shipping_pickup">
				<div class="d-flex justify-content-center">
					<div class="verification-send-card shadow-sm" v-for="(method, i) in bs_methods" @click="form.payment_method = method.value">
						<div class="verification-send-card-dot" v-if="method.value == form.payment_method"></div>
						@{{ method.label }}
					</div>
				</div>

				<div class="verification-send-info shadow-sm" v-if="form.payment_method != cash_method">
					<div class="form-group">
						<select name="bank_id" id="" class="form-control yellow-select" v-model="form.bank_id">
							<option value="" disabled selected>Seleccione el banco</option>
							@foreach($banks as $account)
								<option value="{{ $account->id }}">
									{{ $account->bank->name }} - {{ $account->type == 1 ? 'Corriente' : 'Ahorro' }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<input type="text" v-model="form.number" class="form-control input-gray" maxlength="25" placeholder="Nro. de Referencia">
					</div>
					<div class="verification-send-paymentinfo text-center" v-if="form.bank_id != ''">
						@foreach($banks as $account)
							<span v-if="form.payment_method == 1 && form.bank_id == {{ $account->id }}">
								{{ $account->name }} -
								{{ $account->bank->name }} -
								{{ $account->type == 1 ? 'Corriente' : 'Ahorro' }} - 
								{{ $account->number }} -
								{{ $account->identification }}
							</span>
							<span v-if="form.payment_method == 2 && form.bank_id == {{ $account->id }}">
								{{ $account->name }} -
								{{ $account->bank->name }} -
								{{ $account->identification }} -
								{{ $account->phone }} 
							</span>
						@endforeach
					</div>
				</div>

				<div class="verification-send-info shadow-sm" v-if="form.payment_method == cash_method">
					<input type="number" v-model="form.pay_with" maxlength="25" class="form-control input-gray" 
					placeholder="Ingresa en monto con el que pagaras">
					<div for="" class="text-center mt-2">(tendremos preparado tu cambio)</div>
				</div>
			</template>

			{{-- USD --}}
			<template v-if="currency == 2 && shipping_selected != shipping_pickup">
				<div class="d-flex justify-content-center align-items-center">
					<div class="verification-send-card-zelle shadow-sm" @click="form.payment_method = zelle_method">
						<div class="verification-send-card-dot" v-if="form.payment_method == zelle_method"></div>
						<img src="{{ asset('img/icons/zellelogo.svg') }}" alt="zelle">
					</div>
					{{--<div class="verification-send-card-paypal shadow-sm" @click="form.payment_method = paypal_method">
						<div class="verification-send-card-dot" v-if="form.payment_method == paypal_method"></div>
						<img src="{{ asset('img/icons/paypallogo.svg') }}" alt="paypal">
					</div>--}}
					<div class="verification-send-card shadow-sm"  @click="form.payment_method = cash_method">
						<div class="verification-send-card-dot" v-if="form.payment_method == cash_method"></div>
						Efectivo
					</div>
				</div>
				<div class="verification-send-paymentinfo text-center" v-if="form.payment_method == zelle_method">
					@foreach($zelles as $zelle)
					<span>
						{{ $zelle->name }} <br />
						{{ $zelle->email }}
					</span>
					@endforeach
				</div>

				<div class="verification-send-info shadow-sm d-flex" v-if="form.payment_method == zelle_method">
					<input type="text" v-model="form.name" maxlength="20" class="form-control input-gray" placeholder="Nombre">
					<input type="text" v-model="form.number" maxlength="25" class="form-control input-gray" placeholder="Nro. de Referencia">
				</div>

				<div class="verification-send-info shadow-sm" v-if="form.payment_method == cash_method">
					<input type="number" v-model="form.pay_with" maxlength="25" class="form-control input-gray" 
					placeholder="Ingresa en monto con el que pagaras">
					<div for="" class="text-center mt-2">(tendremos preparado tu cambio)</div>
				</div>
				
				{{-- <div class="verification-send-data shadow stripe-form" v-show="form.payment_method == stripe_method">
					<p class="text-center">Datos de tarjeta</p>
<!-- 					<div class="form-row" v-if="cardImage">
						<div class="col-sm-4 form-group col-md-offse-2">
							<img :src="getCardImage()" alt="" class="responsive-img">
						</div>
					</div> -->
					<div class="form-row">
						<div v-if="hasStripeSaved" class="col-md-12" style="display: flex;">
							<div class="form-group" style="width: 100%; margin-right: 4px;">
								<input type="text" disabled="" v-model="cardSaved" maxlength="16" autocomplete="off" class="form-control" placeholder="Número de Tarjeta">
							</div>
							
							<img :src="getCardImage()" alt="" class="responsive-img" style="width: 60px; height: 30px; margin-top: 5px;" v-if="cardImage">
						</div>

						<div class="col-md-12" v-if="!hasStripeSaved" style="display: flex;">
							<div class="form-group" style="width: 100%; margin-right: 4px;">
								<input type="text" id="stripe-number" name="number" @keyup="onlyNumbers('number')" v-model="stripe.number" maxlength="16" autocomplete="off" class="form-control" placeholder="Número de Tarjeta">
							</div>

							<img :src="getCardImage()" alt="" class="responsive-img" style="width: 60px; height: 30px; margin-top: 5px;" v-if="cardImage">
						</div>

						<div class="col-sm-12 form-group" @click="openCalendar('date')">
							<input type="text" id="date" disabled="" name="date" autocomplete="off" v-model="stripe.date" class="form-control" placeholder="Caducidad (MM/AA)">
						</div>

						<div class="col-sm-12 form-group">
							<input type="password" name="code" @keyup="onlyNumbers('code')" maxlength="4" max="999" autocomplete="off" v-model="stripe.code" class="form-control" placeholder="Código de Tarjeta">
						</div>
						
						<div class="col-sm-12 text-center form-group"  v-if="hasStripeSaved">
							<button class="btn btn-small btn-primary" @click="clearStripe()">Pagar con otros datos</button>
						</div>
						
						<div class="col-sm-12 form-check">
						    <input type="checkbox" v-model="remember" class="form-check-input" id="remember-stripe">
						    <label class="form-check-label" for="remember-stripe">Recordar mis datos para una próxima compra</label>
						</div>
					</div>
				</div> --}}
			</template>


			<div class="text-center mt-4">
				<button class="btn btn-viveres btn-primary" @click="submit">Continuar</button>
			</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/gijgo.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="{{ URL('js/crypt.js') }}"></script>

<script>
	const STRIPE_PUBLIC_KEY = '{{ env('STRIPE_PUBLIC_KEY') }}'
	const errorMessagesStripe = {
		incorrect_number: "El número de tarjeta es incorrecto.",
		invalid_number: "El número de tarjeta no es un número de tarjeta valido.",
		invalid_expiry_month: "El mes de expiración de la tarjeta es invalido.",
		invalid_expiry_year: "El año de expiración de la tarjeta es invalido.",
		invalid_cvc: "El código de seguiridad de la tarjeta es invalido.",
		expired_card: "La tarjeta ha expirado.",
		incorrect_cvc: "El código de seguridad es incorrecto.",
		incorrect_zip: "El código postal de la tarjeta falló en la validación.",
		card_declined: "La tarjeta fue rechazada.",
		missing: "No ha cargado un tarjeta de credito.",
		processing_error: "Se produjo un error al procesar la tarjeta.",
		rate_limit:  "Se produjo un error debido a que las solicitudes llegaron a la API demasiado rápido. Háganos saber si se encuentra constantemente con este error."
	};

	const unities = [
		{name: 'Gr', id: 1},
		{name: 'Kg', id: 2},
		{name: 'Ml', id: 3},
		{name: 'L', id: 4},
		{name: 'Cm', id: 5}
	]
	const ZULIA_ID = 1864;
	const CARABOBO_ID = 1849;
	const PAYPAL = 4;
	const ZELLE = 3
	const CASH = 5
	const STRIPE = 6

	const NATIONAL = 0
	const REGIONAL = 1
	const PICKUP = 2 // No se usa por ahora
	const SHIPPING_TYPES = [
		'Nacional',
		'Regional',
		'Pick Up'
	]
	const SHIPPING_TYPES_LIST = {
		NATIONAL: 1,
		REGIONAL: 2
	}

	const BS_METHODS = [
		{label: 'Transferencia', value: '1'}, 
		{label: 'Pago Movil', value: '2'}
		// {label: 'Efectivo', value: '5'}
	]

    const verificacion = new Vue({
        el: '#verificacion',
        data: {
			paypalURL: '{{ URL('payment') }}',
			cart: '@php echo (addslashes(json_encode($carrito))) @endphp',
			municipios: '{!! json_encode($municipios) !!}',
			minimumDiscount: '{!! json_encode($minimumDiscount) !!}',
			quantityDiscount: '{!! json_encode($quantityDiscount) !!}',
			currency: currentCurrency,
			exchange: '{{ $_change }}',
			shipping_nacional: NATIONAL,
			shipping_regional: REGIONAL,
			shipping_pickup: PICKUP,
			shipping_selected: REGIONAL,
			shipping_types: SHIPPING_TYPES,
			bs_methods: BS_METHODS,
			my_address: false,
			remember: false,
			form: {
				estado: CARABOBO_ID,
				municipio: '',
				parroquia: '',
				payment_method: 3,
				type: null,
				address: '',
				name: '',
				number: '',
				date: '',
				turn: '',
				bank_id: '',
				note: '',
				pay_with: '',
				token: ''
			},
			stripe: {
				number: '',
				code: '',
				date: ''
			},
			hasStripeSaved: false,
			cardSaved: '',
			unities: unities,
			costs: {
				regional: 0,
				nacional: 0
			},
			paypal_method: PAYPAL,
			cash_method: CASH,
			zelle_method: ZELLE,
			zulia_id: ZULIA_ID,
			carabobo_id: CARABOBO_ID,
			stripe_method: STRIPE,
			free: false,
			user: '{!! json_encode(Auth::user()) !!}',
			cardImage: ''
		},
		watch: {
			stripe: {
				deep: true,
				handler(newValue) {
					const type = Stripe.card.cardType(newValue.number)

					if(type !== 'Unknown') {
						this.cardImage = type
					} else {
						this.cardImage = ''
					}
				}
			},
			shipping_selected: function(newValue) {
				this.form.estado = ''
				this.form.municipio = ''
				this.form.parroquia = ''
				this.free = false

				if(newValue == 1) {
					this.form.estado = CARABOBO_ID
				}
			},
			my_address: function(newValue) {
				if(newValue) {
					this.form.estado = this.user.estado_id
					this.form.municipio =  this.user.municipality_id
					this.form.parroquia = this.user.parish_id	
					this.form.address = this.user.direccion
					this.updateStatusFree()
				} else {
					this.form.estado = CARABOBO_ID
					this.form.municipio = ''
					this.form.parroquia = ''
					this.form.address = ''
					this.free = false
				}
			},
		},
		computed: {
			municipalities: function() {
				return verificacion.municipios.filter(function(m) {
					return m.estado_id == verificacion.form.estado
				})
			},
			isStripeValid: function() {
				return this.stripe.code && this.stripe.number && this.stripe.date
			},
			subtotal: function() {
				return this.getPriceByCurrency(this.getSubtotalUsd(), '2')
			},
			shipping: function() {
				if(this.free) {
					return 0
				}
				
				if(this.shipping_selected == 0) {
					return this.getPriceByCurrency(this.costs.nacional, 2)
				} 
				
				if(this.shipping_selected == 1) {
					return this.getPriceByCurrency(this.costs.regional, 2)
				}

				return 0
			},
			comission: function() {
				let subtotal = this.getSubtotalUsd()
				if(this.hasMinimumPurchaseDiscount()) {
					subtotal = subtotal - this.minimumPurchaseDiscount
				}

				if(this.quantityDiscount) {
					subtotal = subtotal - this.quantityPurchaseDiscount
				}	

				let comission = (subtotal + this.shipping) * 0.055;
				return verificacion.getPriceByCurrency(comission, '2')
			},
			minimumPurchaseDiscount: function() {
				let total = verificacion.getSubtotalUsd()
				let percentage = verificacion.minimumDiscount.percentage
				return verificacion.getPriceByCurrency(total * (percentage / 100), '2')
			},
			quantityPurchaseDiscount: function() {
				let total = verificacion.getSubtotalUsd()
				let percentage = verificacion.quantityDiscount.percentage
				return verificacion.getPriceByCurrency(total * (percentage / 100), '2')
			},
			total: function() {
				var total = this.getSubtotal() + this.shipping

				if(this.form.payment_method == this.paypal_method) {
					total = total + this.comission
				}

				if(this.hasMinimumPurchaseDiscount()) {
					total = total - this.minimumPurchaseDiscount
				}

				if(this.quantityDiscount) {
					total = total - this.quantityPurchaseDiscount
				}

				return total
			},
		},
		created: function() {
			this.getShippingFees();
			const decrypt = StripeDecrypt();
			if (decrypt) {
				this.stripe = JSON.parse(decrypt);
				this.remember = true;
				this.hasStripeSaved = true
				this.cardSaved = this.stripe.number.split('').map(function(number, index) {
					return index < 12 ? '*' : number
				}).join('')
			}
		},
        mounted: function() {
			Stripe.setPublishableKey(STRIPE_PUBLIC_KEY);
			this.minimumDiscount = JSON.parse(this.minimumDiscount)
			this.quantityDiscount = JSON.parse(this.quantityDiscount)
			this.user = JSON.parse(this.user)
			// this.cart = JSON.parse(this.cart)
			const inCart = JSON.parse(localStorage.getItem('cart'))
			if(!inCart || !Array.isArray(inCart) || inCart.length < 1){
				window.history.back()
			}else{
				if(inCart){
					this.cart = inCart
					vue_header.cart = inCart.length
				}
				else
					this.cart = []
			}
			this.municipios = JSON.parse(this.municipios)
		
			this.form.payment_method = this.currency == 2 // Si la moneda es USD
				? STRIPE
				: 1 // Transferencia

			setTimeout(function() {
				today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
				this.datepicker = $('#datepicker').datepicker({
					format: 'dd-mm-yyyy',
					uiLibrary: 'bootstrap4',
					minDate: today,
					maxDate: function() {
						var date = new Date();
						date.setDate(date.getDate()+3);
						return new Date(date.getFullYear(), date.getMonth(), date.getDate());
					},
					change: function (e) {
						verificacion.form.date = e.target.value
					}
				});

				this.date = $('#date').datepicker({
					format: 'mm/yyyy',
					uiLibrary: 'bootstrap4',
					change: function (e) {
						verificacion.stripe.date = e.target.value
					},
					minDate: today,
				});
			},100)
		},
		methods: {
			getCardImage: function() {
				const baseUrl = "{{ asset('/img') }}";

				return baseUrl + '/' + this.cardImage + '.png'
			},
			cardFuntion: function(e) {
				const value = e.target.value 
				console.log('stripe.number', this.stripe.number)
				this.stripe.number += value.split('').filter(i => i != '*').join('')

				const result = value.split('').map(function(number, index) {
					return index < 12 ? '*' : number
				}).join('')

				e.target.value = result
			},
			clearStripe: function() {
				this.hasStripeSaved = false
				this.cardImage = ''
				setTimeout(() => {
					this.stripe = {
						number:'',
						code: '',
						date: ''
					}
				})
			},	
			hasMinimumPurchaseDiscount:function() {
				return verificacion.minimumDiscount && 
					verificacion.getSubtotalUsd() >= verificacion.minimumDiscount.minimum_purchase
			},
			getSubtotalUsd:function() {
				var total = 0;
				verificacion.cart.forEach(function(item, index) {
					let priceCart = verificacion.$getPriceCart(item) //<--- cantidad * precio
					total += priceCart;
				});
				return total;
			},
			onlyNumbers: function(prop) {
				const value = this.stripe[prop]
				const regex = /^([0-9])*$/
				if (!regex.test(value)) {
					this.stripe[prop] = value.slice(0, -1)
					this.onlyNumbers(prop)
				}	
			},
			changeMont: function(event) {
				this.stripe.date = event.target.value
			},
			openCalendar: function(datepicker) {
				this[datepicker].open()
			},
			changeShipping: function(shipping) {
				if(shipping == this.shipping_nacional) {
					return
				}
				this.shipping_selected = shipping
			},
			changeEstado: function() {
				this.free = false
				this.municipio = { id: null }
			},
			changeMunicipio: function() {
				this.form.parroquia = ''
				if(!this.form.municipio) {
					return
				}

				this.updateStatusFree()
			},
			updateStatusFree: function() {
				const self = this;
				const municipio = this.municipios.find(function(m)  { return m.id == self.form.municipio })
				this.free = municipio.free
			},
			getPrice: function(producto) {
				if(producto.variable) {
					return producto.amounts.find(function(amount) { return amount.id == producto.amount_id }).price
				} else {
					return producto.price_1
				}
			},
			getPriceByCurrency: function(precio,coin) {
				var price = precio;
				if (coin == '1' && this.currency == '2') {
					price = price / this.exchange;
				}
				else if (coin == '2' && this.currency == '1') {
					price = price * this.exchange;
				}
				return price;
			},
			getSubtotal: function() {
				var total = 0;
				verificacion.cart.forEach(function(item, index) {
					let priceCart = verificacion.$getPriceCart(item) //<--- cantidad * precio
					let pricePriceByCurrency = verificacion.getPriceByCurrency(priceCart, item.producto.coin)
					total += parseFloat(pricePriceByCurrency.toFixed(2));
				});
				vue_header.subtotal = total;
				return total;
			},
			getUnit: function(unit) {
				if(!unit) {
						return
				}
				return this.unities.find(function(u) { return u.id == unit }).name
			},
			getMethod: function(data) {
				let params = {...data, items: this.cart}
				if(this.form.payment_method == this.paypal_method) {
					return axios.post('{{ URL('paypal/purchase-data') }}', params)
				}

				if(this.form.payment_method == this.stripe_method) {
					return axios.post('{{ URL('stripe/payment') }}', params)
				} 

				return axios.post('transferencia', params)
			},
			submit: function() {
				const errorMinItem = verificacion.cart.find(function(item) {
					return item.cantidad < item.amount.min;
				})

				if(errorMinItem) {
					let productName = errorMinItem.producto.name 
					let min = errorMinItem.amount.min
					if(errorMinItem.producto.variable) {
						productName = productName + ' ' + errorMinItem.amount.presentation  + verificacion.getUnit(errorMinItem.amount.unit);
					}

					swal('','No se puede procesar la compra porque el mínimo de compra de ' + productName + ' es de: ' + min + ' unidades','warning');
					return 
				}

				if(this.form.payment_method == this.stripe_method) {
					this.sendStripe()
					return
				}

				this.send()
			},
			sendStripe: function() {
				if(!this.isStripeValid) {
					swal('', 'Debe llenar todos los datos de su tarjeta de credito','warning');
					return
				}
				
				setLoader();
				this.getStripeToken()
					.then(function(res) {
						quitLoader()
						console.log('success stripe', res)
						verificacion.form.token = res.data.id // Token de stripe
						verificacion.send()
					}).catch(function(err) {
						quitLoader()
						if(err.type == 'card_error') {
							swal('',errorMessagesStripe[err.code],'error');
							return
						}
						swal('','Se ha producido un error con stripe','error');
						console.log('error stripe', err)
					})
			},
			send: function() {
				if(verificacion.form.payment_method == verificacion.stripe_method && !verificacion.isStripeValid) {
					swal('', 'Debe llenar todos los datos de su tarjeta de credito','warning');
					return
				}
				if(this.cart.length == 0) {
					swal('', 'Debe poseer productos en el carrito para continuar','warning');
					return;
				}
				setLoader();
				const shippingUSD = verificacion.currency == '1' ? (verificacion.shipping / verificacion.exchange) : verificacion.shipping;
				const data = {
					estado: verificacion.form.estado,
					municipio: verificacion.form.municipio,
					parroquia: verificacion.form.parroquia,
					shipping_selected: verificacion.shipping_selected,
					free: verificacion.free,
					address: verificacion.form.address,
					shipping_fee: shippingUSD,
					date: verificacion.form.date,
					turn: verificacion.form.turn,
					type: verificacion.form.type,
					currency: verificacion.currency,
					payment_method: verificacion.form.payment_method,
					name: verificacion.form.name,
					number: verificacion.form.number,
					bank_id: verificacion.form.bank_id,
					note: verificacion.form.note,
					pay_with: verificacion.form.pay_with,
					token: verificacion.form.token
				}
				const localCart = this;
				axios.post('{{ URL('carrito/check') }}', localCart.cart)
					.then(function(res) {
						if (res.data.result) {
							return verificacion.getMethod(data) // Me devuelve el metodo al que va a pagar
						}
						
						swal('', res.data.error,'warning');
						if(res.data.deleted) {
							if(res.data.item) {
								const findProductIndex = localCart.cart.findIndex(cartProduct => cartProduct.id == res.data.item);
								const foundProduct = localCart.cart[findProductIndex];
								if(foundProduct.producto.isPromotion) {
									localCart.cart = localCart.cart.filter((cartProduct) => cartProduct.producto.promotion_id != foundProduct.producto.promotion_id)
									localStorage.setItem('cart', JSON.stringify(localCart.cart))
									vue_header.cart = localCart.cart.length
								} else {
									localCart.cart.splice(findProductIndex, 1);
									localStorage.setItem('cart', JSON.stringify(localCart.cart))
									vue_header.cart = localCart.cart.length
								}
							}
							if(res.data.promotion) {
								localCart.cart = localCart.cart.filter((cartProduct) => cartProduct.producto.promotion_id != res.data.promotion)
								localStorage.setItem('cart', JSON.stringify(localCart.cart))
								vue_header.cart = localCart.cart.length
							}
							if(res.data.offer) {
								localCart.cart = localCart.cart.filter((cartProduct) => (!cartProduct.producto.offer) 
								|| (cartProduct.producto.offer && cartProduct.producto.offer.id != res.data.offer))
								localStorage.setItem('cart', JSON.stringify(localCart.cart))
								vue_header.cart = localCart.cart.length
							}
							if(res.data.discount) {
								localCart.cart = localCart.cart.filter((cartProduct) => (!cartProduct.producto.discount) 
								|| (cartProduct.producto.discount && cartProduct.producto.discount.id != res.data.discount))
								localStorage.setItem('cart', JSON.stringify(localCart.cart))
								vue_header.cart = localCart.cart.length
							}
							// setTimeout(function() {
							// 	location.reload()
							// }, 2000)
						}
					})
					.then(function(res) { // Si es paypal te devuelve la URL de PAYPAL
						if (verificacion.form.payment_method == verificacion.stripe_method) {
							if (verificacion.remember) {
								StripeCrypt(JSON.stringify(verificacion.stripe));
							}					
						}
						if(!res.data.error){
							if(verificacion.form.payment_method != verificacion.paypal_method){
								localStorage.removeItem('cart')
							}
							if(res.data.url){
								window.location = res.data.url
							}else{
								window.history.back()
							}
						}
					})
					.catch(function(err) {
						console.log(err);
						if(err.response.status === 422) {
							swal('',err.response.data.error,'warning');
							return false;
						}
						swal('','Se ha producido un error','error');
					})
					.finally(function() {
						quitLoader();
					});
			},
			getShippingFees: function() {
				setLoader();
				axios.get('{{ URL('shipping-fees/get') }}')
					.then(function(response) {
						response.data.forEach(function(item) {
							if(item.type === SHIPPING_TYPES_LIST.NATIONAL) {
								verificacion.costs.nacional = item.amount
							}
							if(item.type === SHIPPING_TYPES_LIST.REGIONAL) {
								verificacion.costs.regional = item.amount
							}
						})
					})
					.catch(function(err) {
						swal('','Se ha producido un error','error');
					})
					.finally(function() {
						quitLoader();
					});
			},
			getStripeToken: function() {
				return new Promise(function(resolve, reject) {
					const exp_month = verificacion.stripe.date.split('/')[0]
					const exp_year = verificacion.stripe.date.split('/')[1]
					Stripe.card.createToken({
						number: verificacion.stripe.number,
						cvc: verificacion.stripe.code,
						exp_month: exp_month,
						exp_year: exp_year
					}, function(status, response) {
						if(response.error) {
							reject(response.error)
							return
						}

						resolve({status: status, data: response})
					})
				})
			}
		}
    })
</script>
@endsection
