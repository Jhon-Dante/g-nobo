@extends('layouts.master')

@section('title')
	@lang('Page.Tienda.Title')
@stop

@section('content')
	<div id="favoritos" v-cloak class="favoritos">
		<a href="{{ url('/') }}" class="favoritos-back">
			<img src="{{ asset('img/icons/arrow-izquierda.svg') }}" alt="verificacion"/>
		</a>
		<div class="container">
			<div class="row">
				<div class="col-md-6 text-left d-flex">
					<h3 class="font-extrabold favoritos-title">FAVORITOS</h3>
				</div>
				<div class="col-md-6">
					<a class="btn btn-primary float-right" href="{{ url('/') }}">
						<div class="d-flex align-items-center justify-content-center">
							<img src="{{ asset('img/icons/carrito-black.svg') }}" alt="carrito" width="18">&nbsp;
							<span>
								IR AL CARRITO
							</span>
						</div>
					</a>
				</div>
			</div>
			<div class="row mt-4">
				<div class="col-md-3 col-sm-12 mb-3" v-for="item of products">
					<div class="shop-product-container" 
					:class="{
				    	'shadow shop-product-container-blue': item.hover,
				    	'shadow-sm': !item.hover,
				    	'in-cart': cart && cart.map(function(i){ return i.id }).indexOf(item.id) != -1
				    }"
					@mouseleave="item.hover = false"  
					@mouseover="item.hover = true">
					<div class="shop-product-quit" @click="remove(item.id)">
						x
					</div>
					<span class="shop-product-offer-dias" v-if="item.offer"><span class="shop-product-offer-by">Por</span> @{{ $diffDaysOffer(item.offer.end) }} día@{{ $diffDaysOffer(item.offer.end) > 1 ? 's' :  '' }}</span>
					<div class="shop-product-offer" v-if="item.offer">
						<img src="{{ asset('img/descuento.svg') }}" alt="Descuento">
						<span class="shop-product-offer-percent">-@{{ item.offer.percentage }}%</span>
					</div>
					<div class="shop-product-image p-3" :style="{ backgroundImage: 'url(' + item.image_url + ')' }" v-on:click="gotToProduct(item.id)">
					</div>
					<div class="shop-product-name" v-on:click="gotToProduct(item.id)">
						<span v-cloak>
							@if (\App::getLocale() == 'es') @{{ item.es_name }} @else @{{ item.en_name }} @endif
						</span>
					</div>
					<div class="shop-product-price " :class="{ 'shop-product-price--old': item.offer }">
						<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency(getPrice(item), item.coin) | VES }}</span>
						<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency(getPrice(item), item.coin) | USD }}</span>
					</div>
					<div class="shop-product-price shop-product-price--offer" v-if="item.offer">
						<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | VES }}</span>
						<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | USD }}</span>
					</div>
					<div class="shop-product-presentations" v-if="item.variable">
						<select name="amount_id"  v-model="item.amount_id" id="" class="form-control form-control-sm text-center">
							<option :value="amount.id" v-for="amount in item.amounts" v-cloak>
								 @{{ amount.presentation }} @{{ getUnit(amount.unit) }}
							</option>
						</select>
					</div>
					<div class="shop-product-relleno" v-else></div>
					<div class="shop-product-button">
						<button class="btn btn-primary d-flex align-items-center justify-content-center" v-on:click="addToCar(item)">
							<img src="{{ asset('img/icons/carrito-black.svg') }}" alt="carrito" width="18">&nbsp;
							<span>
								Agregar<span class="text-responsive"> al carrito</span>
							</span>
						</button>
					</div>
				</div>
				</div>
			</div>
			<div class="row mt-5" v-if="products.length == 0">
				<div class="col-md-12">
					<h3 class="text-center font-medium">No ha agregado productos a favoritos</h3>
				</div>
			</div>
		</div>
	</div>
@stop

@section('scripts')
	<script type="text/javascript">
		const unities = [
			{name: 'Gr', id: 1},
			{name: 'Kg', id: 2},
			{name: 'Ml', id: 3},
			{name: 'L', id: 4},
			{name: 'Cm', id: 5}
		]

		const favorito = new Vue({
			el: '#favoritos',
			data: {
				products: [],
				exchange: '{{ $_change }}',
				currency: currentCurrency,
				cart: [],
				unities: unities
			},
			mounted: function() {
				this.getFavorites()
				this.loadCart()
			},
			created: function() {
				vue = this;
			},
			methods: {
				getFavorites: function() {
					setLoader();
					axios.get('{{ url('favoritos/ajax') }}')
						.then(function(res) {
							console.log('favoritos', res.data)
							quitLoader()
							favorito.products = res.data.map(function(item) {
								item.hover = false
								item.amounts = item.colors[0].amounts
								const amount = item.amounts[0]
								item.amount_id = amount.id
								return item
							})
						}).catch(function(err) {
							quitLoader()
							swal('','{{ Lang::get('Page.Error') }}','warning');
							console.log(err);
						})
				},
				addToCar: function(producto) { // Este metodo agrega directamente al carrito
					let amount;
					if(producto.variable) {
						amount = producto.amounts.find(function(amount) { return amount.id == producto.amount_id })
					} else {
						amount = producto.amounts[0]
					}

					if(this.inCarrito(amount.id)) {
						swal('', 'Su producto ya esta en el carrito','warning');
						return
					}

					var item = {
						id: producto.id,
						cantidad: 1,
						color: producto.colors[0].id,
						talla: producto.categories.sizes[0].id,
						image: producto.image_url,
						producto: producto,
						amount_id: amount.id,
						amount: amount
					}
					
					if(amount.amount > 0) {
						const newCart = this.cart.map(function(cartItem) {
							return cartItem
						})
						newCart.push(item)
						this.cart = newCart
					}

					setLoader();
					vue_header.cart = this.cart.length
					this.getTotalUsd()

					axios.post('{{ URL('tienda/add') }}',item)
						.then(function(res) {
							if (!res.data.result) {
								swal('',res.data.error,'warning');
								return
							}
							
							swal('', 'Se agrego el producto al carrito','success');
						})
						.catch(function(err) {
							swal('','{{ Lang::get('Page.Error') }}','warning');
							console.log(err);
						}).finally(quitLoader)
				},
				getPrice: function(producto) {
					if(producto.variable) {
						return producto.amounts.find(function(amount) { return amount.id == producto.amount_id }).price
					} else {
						return producto.price_1
					}
				},
				gotToProduct: function(id) {
					window.location = "tienda/ver/" + id
				},
				getPriceByCurrency: function(precio,coin) {
					var price = precio;
					if (coin == '1' && favorito.currency == '2') {
						price = price / favorito.exchange;
					}
					else if (coin == '2' && favorito.currency == '1') {
						price = price * favorito.exchange;
					}
					return price;
				},
				inCarrito: function(amountId) {
					var cart = vue.cart.map(function(item) { return item.amount_id });
					return cart.indexOf(amountId) != -1;
				},
				loadCart: function() {
					axios.post('{{ URL('carrito/ajax') }}')
						.then(function(res) {
							if (res.data.result) {
								favorito.cart = res.data.carrito;
								favorito.getTotalUsd()
							}
						})
						.catch(function(err) {
							swal('','{{ Lang::get('Page.Error') }}','warning');
							console.log(err);
						})
				},
				remove: function(productId) {
					setLoader();
					axios.post('{{ URL('favoritos/destroy') }}', { product_id: productId }).then(function(res) {
						quitLoader()
						const index = favorito.products.findIndex(function(item){
							return item.id == productId
						})
						favorito.products.splice(index, 1)
					}).catch(function(err) {
						quitLoader()
						swal('','{{ Lang::get('Page.Error') }}','warning');
						console.log(err);
					})
				},
				getUnit: function(unit) {
					return this.unities.find(function(u) { return u.id == unit }).name
				},
				getTotalUsd: function() {
					var total = 0;
					favorito.cart.forEach(function(item) {
						total += item.cantidad * favorito.getPriceByCurrency(favorito.$getPriceByAmount(item.producto, item.amount),item.producto.coin).toFixed(2);
					});
					vue_header.subtotal = total;
				},
			}
		})
	</script>
@stop