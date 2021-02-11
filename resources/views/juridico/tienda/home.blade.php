@extends('layouts.master')

@section('title')
	@lang('Page.Tienda.Title')
@stop

@section('content')
	<div class="contenido" id="tienda" v-cloak>
		<div class="row row-title">
			<div class="col-md-6">
				<h3>
					<a href="#" v-on:click.prevent="filtro()">
						{{ HTML::Image('img/icons/filtro.png') }}
					</a>
					@lang('Page.Tienda.Catalogo')
				</h3>
			</div>
		</div>

		<p v-for="i in filtros" class="etiqueta" v-if="catalogo_selected == i.id"><i class="fa fa-close" v-on:click="catalogo = 0; load()"></i>
			@if (\App::getLocale() == 'es') @{{ i.name }} @else @{{ i.name_english }} @endif
		</p>

		<div class="row row-productos">
			<div class="col-lg-3 col-md-4 col-6" v-for="item in productos">
				<div class="container-item">
					<a :href="'{{ URL('tienda/ver') }}' + '/' + item.id">
						<img :src="'{{ URL('img/products') }}' + '/' + item.images[0].file" />
					</a>
					<div class="container-text">
						<a :href="'{{ URL('tienda/ver') }}' + '/' + item.id">
							<h4>@if (\App::getLocale() == 'es') @{{ item.name }} @else @{{ item.name_english }} @endif</h4>
						</a>
						<p v-if="currency == 1">@{{ getPrice(item.price,item.coin) | VES }}</p>
						<p v-if="currency == 2">@{{ getPrice(item.price,item.coin) | USD }}</p>
					</div>
					<button class="btn btn-default" v-on:click="ver(item)">
						{{ HTML::Image('img/icons/cart_gold.png') }}
					</button>
				</div>
			</div>
		</div>

		<div class="text-center" v-if="paginator.last_page > 1">
			<ul class="pagination justify-content-center">
	            <li class="page-item disabled" v-if="paginator.current_page == 1">
	                <span class="page-link">
	                    <i class="fa fa-angle-left"></i>
	                </span>
	            </li>
	            <li class="page-item" v-else>
	                <a class="page-link" href="#" rel="prev" v-on:click.prevent="load(paginator.current_page - 1)">
	                    <i class="fa fa-angle-left"></i>
	                </a>
	            </li>
		        <li class="page-item page-of disabled">
		            <span class="page-link">
		                @{{ paginator.current_page }} {{ Lang::get('Page.De') }} @{{ paginator.last_page }}
		            </span>
		        </li>
	            <li class="page-item" v-if="paginator.last_page > paginator.current_page">
	                <a class="page-link" href="#" rel="next" v-on:click.prevent="load(paginator.current_page + 1)">
	                    <i class="fa fa-angle-right"></i>
	                </a>
	            </li>
	            <li class="page-item disabled" v-else>
	                <span class="page-link">
	                    <i class="fa fa-angle-right"></i>
	                </span>
	            </li>
		    </ul>
		</div>

		<div class="modal fade" id="modal-producto">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-body">
		        <div class="row" v-if="producto != null">
		        	<div class="col-md-4 image-modal" style="min-height: 500px;" :style="{ backgroundImage: 'url({{ URL('img/products') }}' + '/' + producto.images[producto.file_selected].file + ')' }">
		        		<button class="close" v-on:click="closeModal()">
		        			<i class="fa fa-close"></i>
		        		</button>
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
								<p class="almayor" style="margin-top: 20px; margin-bottom: -10px;">@lang('Page.Tienda.CantidadesDocena')</p>
								<button class="btn btn-default btn-add" v-on:click="add()">
									{{ HTML::Image('img/icons/cart_black.png') }}
								</button>
		        			</div>
		        			<div class="container-description">
		        				<h4>@if (\App::getLocale() == 'es') @{{ producto.name }} @else @{{ producto.name_english }} @endif</h4>
	        					<p class="precio" v-if="currency == 1">@{{ getPrice(producto.price,producto.coin) | VES }}</p>
	        					<p class="precio" v-if="currency == 2">@{{ getPrice(producto.price,producto.coin) | USD }}</p>
	        					{{-- <p class="almayor">@lang('Page.Tienda.CantidadesDocena')</p> --}}
		        				<h5>@lang('Page.Tienda.Descripcion')</h5>
		        				<p class="descripcion ellipsis">
		        					@if (\App::getLocale() == 'es') @{{ producto.description }} @else @{{ producto.description_english }} @endif
		        				</p>
		        				<a :href="'{{ URL('tienda/ver') }}' + '/' + producto.id">@lang('Page.Tienda.VerMas')</a>
		        			</div>
		        		</div>
		        	</div>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
	
		<div class="filtro" v-on:click="close()"></div>
		<div class="filtro-container">
			<h2>
				@lang('Page.Tienda.Filtros')
				<div class="close" v-on:click="close()">
					{{ HTML::Image('img/icons/cancelar.png') }}
				</div>
			</h2>
			<h3>
				{{ HTML::Image('img/icons/filtro.png') }}
				@lang('Page.Tienda.Catalogo')
			</h3>
			<ul>
				<li>
					<ul>
						<li v-on:click="catalogo = 0">
							<div class="form-check">
							  <label class="form-check-label">
							    <input type="radio" class="form-check-input" v-model="catalogo" value="0" name="detal"><span>@lang('Page.Tienda.Todos')</span>
							  </label>
							</div>
						</li>
						<li v-for="item in filtros" v-on:click="catalogo = item.id" class="item-desplegable">
							<div class="form-check">
							  <label class="form-check-label">
							    <input type="radio" class="form-check-input" v-model="catalogo" :value="item.id" name="detal"><span>
							    	@if (\App::getLocale() == 'es') @{{ item.name }} @else @{{ item.name_english }} @endif
							    </span>
							  </label>
							</div>
						</li>
					</ul>
				</li>
			</ul>
			<div class="text-center">
				<button class="btn btn-default" v-on:click="page = 1; close(); load()">
					@lang('Page.Tienda.Filtrar')
				</button>
			</div>
		</div>
	</div>
@stop

@section('scripts')
	<script type="text/javascript">

		var vue;

		new Vue({
			el: '#tienda',
			data: {
				currency: getCurrency('{{ $_ip }}'),
				productos: [],
				producto: null,
				carrito: [],
				paginator: {},
				exchange: '{{ $_change }}',
				catalogo: 0,
				catalogo_selected: 0,
				categorias: [],
				categorias_selected: [],
				filtros: [],
				page: 1,
				img_preload: [],
				base_preload: '{{ URL('img/products') }}' + '/',
				form: {
					cantidad: 1
				}
			},
			created() {
				vue = this;
				vue.load();
			},
			methods: {
				closeModal() {
					$('.modal').modal('hide');
				},
				filtro() {
					$('.filtro').fadeIn();
					$('.filtro-container').animate({
						left: '0px'
					},250);
				},
				close() {
					$('.filtro').fadeOut();
					$('.filtro-container').animate({
						left: '-500px'
					},250);
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
				load(page = null) {
					if (page != null)
						vue.page = page;
					setLoader();
					axios.post('{{ URL('juridico/tienda/ajax') }}?page=' + vue.page,{
						catalogo: vue.catalogo == 0 ? null : vue.catalogo
					})
						.then(function(res) {
							if (res.data.result) {
								res.data.productos.data.forEach(function(item) {
									item.file_selected = 0;
									item.images.forEach(function(i) {
										vue.img_preload.push(vue.base_preload + i.file);
									});	
								});
								vue.filtros = res.data.filtros;
								vue.paginator = res.data.productos;
								vue.productos = res.data.productos.data;
								vue.carrito = res.data.carrito;
								vue.categorias = res.data.categorias;
								vue_header.cart = vue.carrito.length;
								vue.catalogo_selected = vue.catalogo;
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
				ver(item) {
					vue.producto = item;
					vue.form = {
						cantidad: 1
					}
					vue.updateCarrito();
					$('#modal-producto').modal();
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
								vue.load();
								swal('','{{ Lang::get('Page.Tienda.Agregado') }}','success');
								$('#modal-producto').modal('hide');
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
				inCarrito(id) {
					var carrito = vue.carrito.map(function(item) { return item.id });
					return carrito.indexOf(id) != -1;
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