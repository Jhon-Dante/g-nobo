@extends('layouts.master')

@section('title')
	@lang('Page.Tienda.VerProducto')
@stop

@section('content')
	<div class="contenido" id="ver-producto" v-cloak>
		<div class="row row-title">
			<div class="col-md-6">
				<a href="{{ URL('tienda') }}">
					<h3>
						{{ HTML::Image('img/icons/left.png') }}
						@lang('Page.Tienda.Catalogo')
					</h3>
				</a>
			</div>
		</div>

		<div class="row producto-container" v-if="producto != null">
        	<div class="col-md-4 image-modal" style="min-height: 500px" :style="{ backgroundImage: 'url({{ URL('img/products') }}' + '/' + producto.images[producto.file_selected].file + ')' }">
        		<i class="fa fa-chevron-left" v-on:click="producto.file_selected == 0 ? (producto.file_selected = producto.images.length - 1) : producto.file_selected--"></i>
        			<img class="img-principal" :src="'{{ URL('img/products') }}' + '/' + producto.images[0].file" />
        		<i class="fa fa-chevron-right" v-on:click="producto.file_selected == producto.images.length -1 ? producto.file_selected = 0 : producto.file_selected++"></i>
        	</div>
        	<div class="col-md-8">
        		<div class="container-texto">
        			<div class="container-add">				
        				<div class="item-cantidad">
							<button class="btn btn-default remove" v-on:click="form.cantidad > 1 ? form.cantidad-- : null">
								{{ HTML::Image('img/icons/cant.png') }}
							</button>
								<span>@{{ form.cantidad }}</span>
							<button class="btn btn-default add" v-on:click="form.cantidad++">
								{{ HTML::Image('img/icons/add.png') }}
							</button>
						</div>
						<button class="btn btn-default btn-add" v-on:click="add()">
							{{ HTML::Image('img/icons/cart_black.png') }}
						</button>
						<p class="almayor" style="margin-top: 20px; margin-bottom: -10px;">@lang('Page.Tienda.CantidadesDocena')</p>
						<a href="{{ URL('condiciones') }}" target="_blank">
							@lang('Page.Tienda.Condiciones')
						</a>
        			</div>
        			<div class="container-description">
        				<h4>@if (\App::getLocale() == 'es') @{{ producto.name }} @else @{{ producto.name_english }} @endif</h4>
    					<p class="precio" v-if="currency == 1">@{{ getPrice(producto.price,producto.coin) | VES }}</p>
    					<p class="precio" v-if="currency == 2">@{{ getPrice(producto.price,producto.coin) | USD }}</p>
        				<h5>@lang('Page.Tienda.Descripcion')</h5>
        				<p class="nl2br">
        					@if (\App::getLocale() == 'es') @{{ producto.description }} @else @{{ producto.description_english }} @endif
        				</p>
        			</div>
        		</div>
        	</div>
        </div>
	</div>
@stop

@section('scripts')
	{{ HTML::Script('bower_components/owl-carousel/dist/owl.carousel.min.js') }}
	<script type="text/javascript">
		var vue;

		new Vue({
			el: '#ver-producto',
			data: {
				currency: getCurrency('{{ $_ip }}'),
				producto: null,
				carrito: [],
				exchange: '{{ $_change }}',
				img_preload: [],
				base_preload: '{{ URL('img/products') }}' + '/',
				form: {
					talla: '',
					color: '',
					cantidad: 1
				}
			},
			created() {
				vue = this;
				vue.load();
			},
			methods: {
				load() {
					setLoader();
					axios.post('{{ URL('juridico/tienda/get') }}',{id: '{{ $id }}'})
						.then(function(res) {
							if (res.data.result) {
								res.data.producto.images.forEach(function(i) {
									vue.img_preload.push(vue.base_preload + i.file);
								});
								res.data.producto.file_selected = 0;
								vue.producto = res.data.producto;
								vue.carrito = res.data.carrito;
								vue.updateCarrito();
							}
						})
						.catch(function(err) {
							swal('','{{ Lang::get('Page.Error') }}','warning');
							console.log(err)
						})
						.then(function() {
							quitLoader();
						});
				},
				getPrice(precio,coin) {
					var price = precio;
					if (coin == '1' && vue.currency == '2') {
						price = price / vue.exchange;
					}
					else if (coin == '2' && vue.currency == '1') {
						price = price * vue.exchange;
					}
					return price;
				},
				add() {
					setLoader();
					var item = {
						id: vue.producto.id,
						cantidad: vue.form.cantidad
					}
					axios.post('{{ URL('juridico/tienda/add') }}',item)
						.then(function(res) {
							if (res.data.result) {
								swal('','{{ Lang::get('Page.Tienda.Agregado') }}','success');
								vue.carrito = res.data.carrito;
								vue_header.cart = vue.carrito.length;
							}
							else {
								swal('',res.data.error,'warning');
							}
						})
						.catch(function(err) {
							swal('','{{ Lang::get('Page.Error') }}','warning');
							console.log(err);
						})
						.then(function() {
							quitLoader();
						});
				},
				getCarrito() {
					var carrito = vue.carrito.map(function(item) { return item.id });
					return vue.carrito[carrito.indexOf(vue.producto.id)];
				},
				updateCarrito() {
					if (vue.getCarrito() != null) {
						vue.form.cantidad = vue.getCarrito().cantidad;
					}
				},
				inCarrito() {
					var carrito = vue.carrito.map(function(item) { return item.id });
					return carrito.indexOf(vue.producto.id) != -1;
				},
				preload() {
					var images = new Array();
					for (i = 0; i < vue.img_preload.length; i++) {
						images[i] = new Image()
						images[i].src = vue.img_preload[i]
					}
				}
			}
		})
	</script>
@stop