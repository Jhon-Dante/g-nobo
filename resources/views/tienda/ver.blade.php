@extends('layouts.master')

@section('title')
	@lang('Page.Tienda.VerProducto')
@stop

@section('content')
	<div class="contenido" id="ver-producto" v-cloak>
		<div class="container">
		<div class="row row-title">
			<div class="col-md-6">
				<a href="javascript:history.back()">
					<img src="{{ asset('img/icons/arrow-izquierda.svg') }}" /> @lang('Page.Tienda.Regresar')
				</a>
			</div>
		</div>
			<div class="row producto-container" v-if="producto != null">
				<div class="col-md-2">
					<div class="well text-center clearfix d-flex justify-content-center">
						<div id="myCarousel" class="carousel vertical slide pull-left" >
							<div class="custom-carousel-inner carousel-inner">
								<div class="item active">
                  					<div class="row-fluid">
										<table>
											<tr v-for="image in producto.images">
                        						<td>
													<div class="span3">
														<a href="#" style ="width: 50px; " class="small-thumbnail" v-on:click="setImagePreview(image.image_url)">
															<img class="img-thumbnail" :src="image.image_url"/>
														</a>
													</div>	
												</td>
											</tr>
										</table>
                  					</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 image-modal">
					<div class="text-center">
						<img class="img-principal" :src="imagePreview" />
					</div>
				</div>
				<div class="col-md-6">
					<div class="container-texto">
						<div class="container-title">
							<h3>@if (\App::getLocale() == 'es') @{{ producto.name }} @else @{{ producto.name_english }} @endif</h3>
						</div>
						<div class="container-category">
							<span class="BogleWeb-Black">Categoria:</span> @{{ producto.categories.name }}
						</div>
						<div class="container-category">
							<span class="BogleWeb-Black">Subcategoria:</span> @{{ producto.subcategories.name }}
						</div>
						<div class="container-price-description">
							{{-- <div class="container-price">
								<template v-if="form.cantidad < 12">
									<span class="precio" v-if="currency == 1">@{{ getPrice(producto.price,producto.coin) | VES }}</span>
									<span class="precio" v-if="currency == 2">@{{ getPrice(producto.price,producto.coin) | USD }}</span>
								</template>
								<template v-if="form.cantidad >= 12">
									<span class="precio" v-if="currency == 1">@{{ getPrice(producto.price,producto.coin) | VES }}</span>
									<span class="precio" v-if="currency == 2">@{{ getPrice(producto.price,producto.coin) | USD }}</span>
								</template>
							</div> --}}
							<div class="shop-product-presentations" v-if="producto.variable">
								<select name="amount_id"  v-model="producto.amount_id" id="" class="form-control form-control-sm text-center">
									<option :value="amount.id" v-for="amount in producto.amounts" v-cloak>
										 @{{ amount.presentation }} @{{ getUnit(amount.unit) }}
									</option>
								</select>
							</div>
							<div class="container-price" :class="{'container-price--old': producto.offer}">
								<span v-if="currency == 1" v-cloak class="precio">@{{ getPriceByCurrency(getPrice(producto), producto.coin) | VES }}</span>
								<span v-if="currency == 2" v-cloak class="precio">@{{ getPriceByCurrency(getPrice(producto), producto.coin) | USD }}</span>
							</div>
							<div class="container-price" v-if="producto.offer">
								<span v-if="currency == 1" v-cloak class="precio">@{{ getPriceByCurrency($getPrice(producto, true), producto.coin) | VES }}</span>
								<span v-if="currency == 2" v-cloak class="precio">@{{ getPriceByCurrency($getPrice(producto, true), producto.coin) | USD }}</span>
							</div>
							<div class="font-medium" style="font-size: 0.80rem; margin-top: 10px;">
								@{{ producto.taxe ? producto.taxe.name : 'Exento de IVA' }} 
							</div>
							<div class="container-description">
								<p class="nl2br">
									<h4>
										@if (\App::getLocale() == 'es')
											Descripcion
										@else
											Description
										@endif
									</h4>
									@if (\App::getLocale() == 'es') @{{ producto.description }} @else @{{ producto.description_english }} @endif
								</p>
							</div>
							<div class="container-shop">
								<button class="btn btn-primary" v-on:click="addToCar(producto)">
									<img src="{{ asset('img/icons/carrito-black.svg') }}" alt="carrito" width="20">&nbsp;
									<span>
										Agregar<span class="text-responsive"> al carrito</span>
									</span>
								</button>
							</div>
							
						</div>
					</div>
				</div>

				<template v-if="producto.offer">
					<span class="shop-product-offer-dias" >Por @{{ $diffDaysOffer(producto.offer.end) }} día@{{ $diffDaysOffer(producto.offer.end) > 1 ? 's' :  '' }}</span>
					<div class="shop-product-offer">
						<img src="{{ asset('img/descuento.svg') }}" alt="Descuento">
						<span class="shop-product-offer-percent">-@{{ producto.offer.percentage }}%</span>
					</div>
				</template>
			</div>
			
			
		</div>

		<div class="container-fliud rela-x">
			
			<h2 v-if="related_products.length > 0" class="BogleWeb-Regular py-4" v-cloak>Productos Relacionados</h2>

			<div class="shop-category-container shop-category-container-view" v-if="related_products.length > 0">
				<div class="shop-category-products">
					<div class="owl-carousel owl-theme owl-related owl-relatex">
						<div class="item" v-for="item in related_products" v-cloak>
							<div class="shop-product-container" 
							    :class="{
							    	'shadow shop-product-container-blue': item.hover,
							    	'': !item.hover,
							    	'in-cart': carrito && carrito.map(function(i){ return i.id }).indexOf(item.id) != -1
							    }"
								v-on:mouseleave="item.hover = false"  
								v-on:mouseover="item.hover = true">
								<div class="shop-product-favorite" v-on:click="toggleFavorite(item)">
									<img src="{{ asset('img/icons/favorito.svg') }}" alt="favorito" v-if="item.favorite" width="20">
									<img src="{{ asset('img/icons/unfavorite.svg') }}" alt="favorito" v-else width="20">
								</div>
								<template v-if="item.offer">
									<span class="shop-product-offer-dias" >Por @{{ $diffDaysOffer(item.offer.end) }} día@{{ $diffDaysOffer(item.offer.end) > 1 ? 's' :  '' }}</span>
									<div class="shop-product-offer">
										<img src="{{ asset('img/descuento.svg') }}" alt="Descuento">
										<span class="shop-product-offer-percent">@{{ item.offer.percentage }}%</span>
									</div>
								</template>
								<div class="shop-product-image p-3" :style="{ backgroundImage: 'url(' + item.image_url + ')' }" v-on:click="gotToProduct(item.id)">
								</div>
								<div class="shop-product-name" v-on:click="gotToProduct(item.id)">
									<span v-cloak>
										@if (\App::getLocale() == 'es') @{{ item.es_name }} @else @{{ item.en_name }} @endif
									</span>
								</div>
								<div class="shop-product-taxe font-medium">
									@{{ item.taxe ? item.taxe.name : 'Exento de IVA' }} 
								</div>
								{{-- <div class="shop-product-price">
									<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency(getPrice(item), item.coin) | VES }}</span>
									<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency(getPrice(item), item.coin) | USD }}</span>
								</div> --}}
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
										<img src="{{ asset('img/icons/carrito-black.svg') }}" alt="carrito" width="18" style="width: 18px !important;">&nbsp;
										<span>
											Agregar<span class="text-responsive"> al carrito</span>
										</span>
									</button>
								</div>
							</div>
						</div>
					</div>

					<div class="shop-category-products-arrow shop-category-products-arrow--right" v-on:click="prev()">
						<img src="{{ asset('img/icons/flecha-derecha.svg') }}" alt="">
					</div>
					<div class="shop-category-products-arrow shop-category-products-arrow--left" v-on:click="next()">
						<img src="{{ asset('img/icons/flecha-izquierda.svg') }}" alt="">
					</div>
				</div>
			</div>

		</div>
	</div>

	@include('layouts.footer')
@stop

@section('scripts')
	<script type="text/javascript" src="{{asset('bower_components/owl-carousel/dist/owl.carousel.min.js') }}"></script>
	<script type="text/javascript">

	$(document).ready(function () {
		$('#myCarousel').carousel({
			interval: true
			//interval: 2000
		});
	});

		var vue;
		const unities = [
			{name: 'Gr', id: 1},
			{name: 'Kg', id: 2},
			{name: 'Ml', id: 3},
			{name: 'L', id: 4},
			{name: 'Cm', id: 5}
		]

		new Vue({
			el: '#ver-producto',
			data: {
				currency: currentCurrency,
				producto: null,
				cart: [],
				exchange: '@{{ $_change }}',
				img_preload: [],
				imagePreview: '',
				base_preload: '{{ URL('img/products') }}' + '/',
				form: {
					talla: '',
					color: '',
					cantidad: 1
				},
				inCart: null,
				unities: unities,
				related_products: [],
			},
			created: function() {
				vue = this;
				let inCart = localStorage.getItem('cart')
				if(inCart){
					inCart = JSON.parse(localStorage.getItem('cart'))
					this.inCart = inCart
					vue.carrito = inCart
				}else{
					vue.carrito = []
				}
				vue.load();
				// vue.loadCart()
			},
			mounted: function(){
				vue_header.cart = vue.carrito.length
			},
			methods: {
				load: function() {
					setLoader();
					axios.post('{{ URL('tienda/get') }}',{id: '{{ $id }}'})
						.then(function(res) {
							if (res.data.result) {
								res.data.producto.images.forEach(function(i) {
									vue.img_preload.push(vue.base_preload + i.file);
								});
								res.data.producto.price = res.data.producto.colors[0].amounts[0].price
								res.data.producto.file_selected = 0;
								res.data.producto.amounts = res.data.producto.colors[0].amounts
								res.data.producto.amount_id = res.data.producto.amounts[0].id
								vue.producto = res.data.producto;
								vue.imagePreview = res.data.producto.image_url;

								vue.related_products = res.data.related.map(function(item) {
									item.favorite = item.favorites.length > 0
									item.price = item.colors[0].amounts[0].price
									item.file_selected = 0;
									item.amounts = item.colors[0].amounts
									item.amount_id = item.amounts[0].id
									return item;
								});

								vue.updateCarrito();
								vue.initCarousels();
							}
						})
						.catch(function(err) {
							swal('','{{ Lang::get('Page.Error') }}','warning');
						})
						.then(function() {
							quitLoader();
						});
				},
				getUnit: function(unit) {
					return this.unities.find(function(u) { return u.id == unit }).name
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
						price = (price / this.exchange).toFixed(2);
					}
					else if (coin == '2' && this.currency == '1') {
						price = (price * this.exchange).toFixed(2);
					}
					else if (coin == '3' && this.currency == '2') {
						price = (price / this.exchange).toFixed(2);
					}
					return parseFloat(price);
				},
				addToCar: function(producto) { // Este metodo agrega directamente al carrito
					let amount;
					if(producto.variable) {
						amount = producto.amounts.find(function(amount) {
							return amount.id == producto.amount_id
						})
					} else {
						amount = producto.amounts[0]
					}

					let inCartStorage = localStorage.getItem('cart')
					if(inCartStorage) {
						this.cart = JSON.parse(localStorage.getItem('cart'))
					} else {
						this.cart = []
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
						this.updateHeader()
					}

					// setLoader();
					localStorage.setItem('cart', JSON.stringify(this.cart))
					vue_header.cart = this.cart.length
					vue_cart_modal.newProductAddToCart = !vue_cart_modal.newProductAddToCart
					swal('', 'Producto Agregado exitosamente', 'success')

					// axios.post('{{ URL('tienda/add') }}',item)
					// 	.then(function(res) {
					// 		if (!res.data.result) {
					// 			swal('',res.data.error,'warning');
					// 		}
					// 	})
					// 	.catch(function(err) {
					// 		swal('','{{ Lang::get('Page.Error') }}','warning');
					// 	}).finally(quitLoader)
				},
				// loadCart() {
					// axios.post('{{ URL('carrito/ajax') }}')
					// 	.then(function(res) {
					// 		if (res.data.result) {
					// 			// vue.carrito = res.data.carrito;
					// 			vue_header.cart = this.inCart.length;
					// 			vue.updateHeader()
					// 			if ('{{ Session('success') }}' != '') {
					// 				swal('','Su pedido se ha recibido exitosamente','success');
					// 			}
					// 			else if ('{{ Session('errors') }}' != '') {
					// 				swal('', 'No se pudo procesar su pedido', 'warning');
					// 			}
					// 		}
					// 	})
					// 	.catch(function(err) {
					// 		swal('','{{ Lang::get('Page.Error') }}','warning');
					// 	})
				// },
			getCarrito: function() {
					var carrito = this.cart.map(function(item) { return item.id });
					return this.cart[carrito.indexOf(vue.producto.id)];
				},
				updateCarrito: function() {
					if (vue.getCarrito() != null) {
						vue.form.talla = vue.getCarrito().talla;
						vue.form.color = vue.getCarrito().color;
						vue.form.cantidad = vue.getCarrito().cantidad;
					}
				},
				updateHeader: function() {
					let total = 0;
					vue.carrito.forEach(function(item) {
						total += item.cantidad * vue.getPriceByCurrency(item.amount.price,item.producto.coin).toFixed(2);
					});
					vue_header.subtotal = total;
				},
				inCarrito: function(amountId) {
					var cart = vue.carrito.map(function(item) { return item.amount_id });
					return cart.indexOf(amountId) != -1;
				},
				setImagePreview: function(imageUrl) {
					vue.imagePreview = imageUrl;
				},
				preload: function() {
					var images = new Array();
					for (i = 0; i < vue.img_preload.length; i++) {
						images[i] = new Image()
						images[i].src = vue.img_preload[i]
					}
				},
				toggleFavorite: function(item) {
					const isNoteSession = '{{ Auth::guest() }}'
					if(isNoteSession) {
						$('#loginModal').modal('show')
						return
					}
					const store = '{{ URL('favoritos/store') }}'
					const destroy = '{{ URL('favoritos/destroy') }}'
					const url = item.favorite ? destroy : store
					item.favorite = !item.favorite
					if(isNoteSession) {
						return
					}
					axios.post(url, { product_id: item.id }).catch(function(err) {
						swal('','{{ Lang::get('Page.Error') }}','warning');
					})
				},
				gotToProduct: function(id) {
					window.location = id
				},
				initCarousels: function() {
					//$('.owl-carousel').owlCarousel('destroy')
					setTimeout(function() {
						$('.owl-relatex').owlCarousel({
							loop:true,
							margin:5,
							nav:false,
							dots: false,
							responsive:{
								200: {
									items: 1
								},
								300: {
									items: 1
								},
								400: {
									items: 1
								},
								500:{
									items: 1
								},
								600: {
									items: 2
								},
								768:{
									items: 3
								},
								1024:{
									items: 5
								},
								1400:{
									items: 8
								}
							},
							
						})
					}, 300)
				},
				prev: function() {
					$('.owl-related')
						.trigger('next.owl.carousel');
				},
				next: function() {
					$('.owl-related')
						.trigger('prev.owl.carousel', [300]);
				},
			}
		})
	</script>
@stop
@section('styles')
	<script type="text/javascript" src="{{ asset('bower_components/owl-carousel/dist/assets/owl.carousel.min.css') }}"></script>
	<style>
		*{
			font-family: 'BogleWeb', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
		}
	</style>
@stop