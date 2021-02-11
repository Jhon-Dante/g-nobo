@extends('layouts.master')

@section('title')
	@lang('Page.Perfil.Title')
@stop

@section('content')
	<div class="contenido contenido-no-padding" id="perfil" v-cloak>
		<div class="modal fade" id="viewHistory" tabindex="-1" role="dialog" aria-labelledby="modalHistory" aria-hidden="true" style="overflow-y: scroll !important;">
			<div class="modal-dialog modal-lg modal-purchase-detail" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Detalle del pedido</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<template v-if="history">
							<div class="col s12 m12" v-if="history.payment_type != '5'"><b>ID transacción:</b> @{{ history.payment_type == 4 ? history.transaction_code : history.transfer.number }}</div>
							<div class="col s12 m6"><b>Fecha:</b> @{{ history.created_at | datetime }}</div>
							<div class="col s12 m12"><b>Medio de pago:</b> @{{ history.payment_type | metodo }}</div>
							<div class="col s12">
								<div class="table-responsive">
									<table class="table table-bordered">
											<thead>
												<th>Descripción</th>
												<th>Impuesto</th>
												<th class="text-center">Precio</th>
												<th class="text-center">Cantidad</th>
												<th>Subtotal</th>
											</thead>
											<tbody v-if="history.details">
												<tr v-for="(d, i) in history.details" :key="i" :class="{ borderGold: i == 0 }">
													<td v-if="d.producto">
														@{{ d.producto.name }} @{{ d.discounts_text }} 
													</td>
													<td v-else>@{{ d.discount_description }}</td>
													<td>
														<span v-if="d.producto">
															@{{ d.producto.taxe ? d.producto.taxe.name : 'Exento' }}
														</span>
													</td>
													<td class="text-center" v-if="history.currency == 1">@{{ getPrice(d.price, d.coin, history.exchange.change, history.currency) | VES }}</td>
													<td class="text-center" v-if="history.currency == 2">@{{ getPrice(d.price, d.coin, history.exchange.change, history.currency) | USD }}</td>
													<td class="text-center">@{{ d.quantity }}</td>
													<td v-if="history.currency == 1">@{{ getPrice(d.price, d.coin, history.exchange.change, history.currency) * d.quantity | VES }}</td>
													<td v-if="history.currency == 2">@{{ getPrice(d.price, d.coin, history.exchange.change, history.currency) * d.quantity | USD }}</td>
												</tr>
												<tr>
													<td colspan="4" class="right-align"><b>Subtotal</b></td>
													<td v-if="history.currency == 1">@{{ getSubtotal(history) | VES }}</td>
													<td v-if="history.currency == 2">@{{ getSubtotal(history) | USD }}</td>
												</tr>
												<tr>
													<td colspan="4" class="right-align"><b>Costo de envío</b></td>
													<td>
														<span v-if="history.currency == 1">@{{ getShippingFee(history) | VES }}</span>
														<span v-if="history.currency == 2">@{{ getShippingFee(history) | USD }}</span>
														<br /> 
														<span style="font-size: .80rem" v-if="history.free_shipping">(envío gratuito)</span>
													</td>
												</tr>
													<tr>
														<td colspan="4" class="right-align"><b>Total</b></td>
														<td v-if="history.currency == 1">@{{ getTotal(history) | VES }}</td>
														<td v-if="history.currency == 2">@{{ getTotal(history) | USD }}</td>
													</tr>
											</tbody>
									</table>
								</div>
							</div>
						</template>
					</div>
				</div>
			</div>	
		</div>
		<div class="row">
			<div class="col-lg-3 separacion">
				
				<h2 class="title-perfil">
					<a href="#" v-on:click.prevent="filtroProfile()">
						{{ HTML::Image('img/icons/menu.svg','',['class' => 'filtro-button test']) }}
					</a>
					@lang('Page.Perfil.Title')
				</h2>
				<ul>
					<li>
						<a href="#" :class="{ bold: seccion == 1 }" v-on:click.prevent="seccion = 1">@lang('Page.Perfil.Editar.Title')</a>
					</li>
					<li>
						<a href="#" :class="{ bold: seccion == 2 }" v-on:click.prevent="seccion = 2">@lang('Page.Perfil.CambiarPassword.Title')</a>
					</li>
					<li>
						<a href="#" :class="{ bold: seccion == 3 }" v-on:click.prevent="seccion = 3">@lang('Page.Perfil.Historial.Title')</a>
					</li>
					<li>
						<a href="{{ URL('logout') }}">@lang('Page.Perfil.Logout')</a>
					</li>
				</ul>
			</div>
			<div class="col-lg-9">
				{{ Form::open(['v-on:submit.prevent' => 'submit()', 'v-if' => 'seccion == 1','class' => 'seccion']) }}
					<h3>@lang('Page.Perfil.Editar.Title')</h3>
					<div class="row">
						<div class="col-sm-12 col-md-6">
						<div class="form-group">
								<label for="identification" v-if="user.persona == 1">{{ Lang::get('Page.Perfil.Editar.Document') }}</label>
								<label for="identification" v-if="user.persona == 2">{{ Lang::get('Page.Perfil.Editar.DocumentRif') }}</label>
								{{ Form::text('identification','',['class' => 'form-control','v-model' => 'user.full_document','disabled' => 'true']) }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{{ Form::label('nombre',Lang::get('Page.Perfil.Editar.Nombre')) }}
								{{ Form::text('nombre','',['class' => 'form-control','v-model' => 'user.name','disabled' => 'true']) }}
							</div>
							<div class="form-group">
								{{ Form::label('pais',Lang::get('Page.Perfil.Editar.Pais')) }}
								{{ Form::select('pais',$paises,null,['class' => 'form-control','v-model' => 'user.pais_id', 'v-on:change' => "user.estado_id = ''"]) }}
							</div>
							<div class="form-group">
								{{ Form::label('municipio_id',Lang::get('Page.Perfil.Editar.Municipio')) }}
								<select name="municipio_id" id="municipio_id" class="form-control" tabindex="8" v-model="user.municipality_id">
									<option v-for="item in municipios" :value="item.id" v-if="user.estado_id == item.estado_id" :selected="user.municipality_id	== item.id">@{{ item.name }}</option>
								</select>
							</div>
							<div class="form-group">
								{{ Form::label('referencia',Lang::get('Page.Perfil.Editar.Ref')) }}
								{{ Form::text('referencia','',['class' => 'form-control','maxlength' => '100', 'v-model' => 'user.referencia']) }}
							</div>
							<div class="form-group">
								{{ Form::label('telefono',Lang::get('Page.Perfil.Editar.Telefono')) }}
								{{ Form::number('telefono','',['class' => 'form-control','v-model' => 'user.telefono']) }}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								{{ Form::label('email',Lang::get('Page.Perfil.Editar.Email')) }}
								{{ Form::text('email','',['class' => 'form-control','v-model' => 'user.email','disabled' => 'true']) }}
							</div>
							<div class="form-group">
								{{ Form::label('estado',Lang::get('Page.Perfil.Editar.Estado')) }}
								<select :disabled="user.pais_id == ''" name="estado" id="estado" class="form-control" tabindex="8" v-model="user.estado_id" @change="user.municipality_id = ''; user.parish_id = ''">
									<option v-for="item in estados" :value="item.id" v-if="user.pais_id == item.pais_id">@{{ item.nombre }}</option>
								</select>
							</div>
							<div class="form-group">
								{{ Form::label('parroquia_id',Lang::get('Page.Perfil.Editar.Parroquia')) }}
								<select name="parroquia_id" id="parroquia_id" class="form-control" tabindex="8" v-model="user.parish_id">
									<option v-for="item in parroquias" :value="item.id" v-if="item.municipality_id == parseInt(user.municipality_id)" :selected="user.parish_id	== item.id">@{{ item.name }}</option>
								</select>
							</div>
							<div class="form-group">
								<label for="direccion" v-if="user.persona == 1">{{ Lang::get('Page.Perfil.Editar.Direccion') }}</label>
								<label for="direccion" v-if="user.persona == 2">{{ Lang::get('Page.Perfil.Editar.DireccionFiscal') }}</label>
								{{ Form::text('direccion','',['class' => 'form-control','maxlength' => '100', 'v-model' => 'user.direccion']) }}
							</div>
							<div class="form-group">
								{{ Form::label('codigo',Lang::get('Page.Perfil.Editar.Codigo')) }}
								{{ Form::number('codigo','',['class' => 'form-control','v-model' => 'user.codigo']) }}
							</div>
						</div>
					</div>
					<div class="text-center">
						<button class="btn btn-primary" type="submit">
							@lang('Page.Perfil.Editar.Guardar')
						</button>
					</div>
				{{ Form::close() }}

				{{ Form::open(['v-on:submit.prevent' => 'submitPassword()', 'v-if' => 'seccion == 2','class' => 'seccion']) }}
					<h3>@lang('Page.Perfil.CambiarPassword.Title')</h3>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{{ Form::label('old_password',Lang::get('Page.Perfil.CambiarPassword.Actual')) }}
								{{ Form::password('old_password',['class' => 'form-control', 'v-model' => 'password.old_password']) }}
							</div>
							<div class="form-group">
								{{ Form::label('password',Lang::get('Page.Perfil.CambiarPassword.Nueva')) }}
								{{ Form::password('password',['class' => 'form-control', 'v-model' => 'password.password']) }}
							</div>
							<div class="form-group">
								{{ Form::label('password_confirmation',Lang::get('Page.Perfil.CambiarPassword.Confirmar')) }}
								{{ Form::password('password_confirmation',['class' => 'form-control', 'v-model' => 'password.password_confirmation']) }}
							</div>
						</div>
					</div>
					<div class="text-center">
						<button class="btn btn-primary" type="submit">
							@lang('Page.Perfil.CambiarPassword.Cambiar')
						</button>
					</div>
				{{ Form::close() }}

				<div class="seccion" v-if="seccion == 3">
					<h3>@lang('Page.Perfil.Historial.Title')</h3>
					<div class="responsive-table" v-if="pedidos.length > 0">
						<table class="table table-bordered text-center">
							<thead>
								<tr>
									<th>@lang('Page.Perfil.Historial.ID')</th>
									<th>@lang('Page.Perfil.Historial.Fecha')</th>
									<th>@lang('Page.Perfil.Historial.Monto')</th>
									<th>@lang('Page.Perfil.Historial.Metodo')</th>
									<th>@lang('Page.Perfil.Historial.Entrega')</th>
									<th>@lang('Page.Perfil.Historial.Status')</th>
									<th>@lang('Page.Perfil.Historial.Acciones')</th>
								</tr>
							</thead>
							<tbody>
								<tr :class="{ borderGold: index == 0 }" v-for="(item, index) in pedidos">
									<td>@{{ padZeros(item.id) }}</td>
									<td>@{{ item.created_at | datetime }}</td>
									<td>
										<span class="bold-gold" v-if="item.currency == 1">@{{ getTotal(item) | VES }}</span>
										<span class="bold-gold" v-if="item.currency == 2">@{{ getTotal(item) | USD }}</span>
									</td>
									<td>@{{ item.payment_type | metodo }}</td>
									<td>
										<span v-if="item.delivery.type == null">@lang('Page.Perfil.Historial.EnvioRegional')</span>
										<span v-if="item.delivery.type == 1">@lang('Page.Perfil.Historial.EnvioDestino')</span>
										<span v-if="item.delivery.type == 2">@lang('Page.Perfil.Historial.EnvioTienda')</span>
									</td>
									<td >
										<p v-if="item.status == '0'" class="bold bold-status bold-gold">@lang('Page.Perfil.Historial.Pendiente')</p>
										<p v-if="item.status == '1'" class="bold bold-status bold-gold">Procesando</p>
										<p v-if="item.status == '3'" class="bold bold-status bold-gold">Completado</p>
										<p v-if="item.status == '2'" class="bold bold-status bold-red">Rechazado</p>
									</td>
									<td>
										<a href="#!" class="btn btn-primary btn-action" data-toggle="modal" data-target="#viewHistory" @click="_view(item)" style="margin-bottom: 3px;">
											@lang('Page.Perfil.Historial.Ver')
										</a>
										<a href="#!" class="btn btn-primary btn-action" data-toggle="modal" data-target="#viewHistory" @click="_view(item)" style="margin-bottom: 2px;">
											{{ HTML::Image('img/icons/print.png')}}
										</a>
									</td>
								</tr>
							</tbody>
						</table>
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

					<div class="text-center">
						<h4 class="no-items" v-if="pedidos.length <= 0">@lang('Page.Perfil.Historial.NoItems')</h4>
					</div>

				</div>
			</div>
		</div>

		<div class="filtro profile" v-on:click="closeProfile()"></div>
		<div class="filtro-container profile">
			<div class="filtro-say">
				<span>
					@lang('Page.Perfil.Title')
				</span>
			</div>
			<ul class="filtro-list">
				<li class="item-desplegable filtro-list-item">
						<div class="filtro-list-category">
							<a href="#" :class="{ bold: seccion == 1 }" v-on:click.prevent="seccion = 1; closeProfile()">@lang('Page.Perfil.Editar.Title')</a>
						</div> 
				</li>
				<li class="item-desplegable filtro-list-item">
						<div class="filtro-list-category">
							<a href="#" :class="{ bold: seccion == 2 }" v-on:click.prevent="seccion = 2; closeProfile()">@lang('Page.Perfil.CambiarPassword.Title')</a>
						</div> 
				</li>
				<li class="item-desplegable filtro-list-item">
						<div class="filtro-list-category">
							<a href="#" :class="{ bold: seccion == 3 }" v-on:click.prevent="seccion = 3; closeProfile()">@lang('Page.Perfil.Historial.Title')</a>
						</div> 
				</li>
				<li class="item-desplegable filtro-list-item">
						<div class="filtro-list-category">
							<a href="{{ URL('logout') }}">@lang('Page.Perfil.Logout')</a>
						</div> 
				</li>
			</ul>
			<!-- <ul>
				<li>
					<a href="#" :class="{ bold: seccion == 2 }" v-on:click.prevent="seccion = 2; close()">@lang('Page.Perfil.CambiarPassword.Title')</a>
				</li>
				<li>
					<a href="#" :class="{ bold: seccion == 3 }" v-on:click.prevent="seccion = 3; close()">@lang('Page.Perfil.Historial.Title')</a>
				</li>
				<li>
					<a href="{{ URL('logout') }}">@lang('Page.Perfil.Logout')</a>
				</li>
			</ul> -->
		</div>
	</div>
@stop

@section('scripts')
	<script type="text/javascript">
		var vue;

		new Vue({
			el: '#perfil',
			data: {
				seccion: 1,
				user: {!! json_encode($user) !!},
				estados: {!! json_encode($estados) !!},
				municipios: {!! json_encode($municipalities) !!},
				parroquias: {!! json_encode($parishes) !!},
				pedidos: [],
				cart: [], // Este corresponde la los items de carrito en esta pagina
				currency: currentCurrency,
				exchange: '{{ $_change }}',
				history: null,
				password: {
					old_password: '',
					password: '',
					password_confirmation: ''
				},
				paginator: {}
			},
			created: function() {
				vue = this;
				vue.load();
				vue.loadCart();
			},
			methods: {
				filtroProfile: function() {
					$('.filtro.profile').fadeIn();
					$('.filtro-container.profile').animate({
						left: '0px'
					},250);
				},
				closeProfile: function() {
					$('.filtro.profile').fadeOut();
					$('.filtro-container.profile').animate({
						left: '-500px'
					},250);
				},
				getTotal: function(pedido) {
					// console.log({
					// 	fee: this.getShippingFee(pedido),
					// 	sub: this.getSubtotal(pedido)
					// })
					return (parseFloat(this.getShippingFee(pedido)) + parseFloat(this.getSubtotal(pedido))).toFixed(2)
				},	
				getShippingFee: function(pedido) {
					return this.getPrice(pedido.shipping_fee, 2, pedido.exchange.change, pedido.currency)
				},		
				getSubtotal: function(pedido) {
					var total = 0;
					pedido.details.forEach(function(item) {
						total += item.quantity * vue.getPrice(item.price,item.coin,pedido.exchange.change,pedido.currency);
					});
					return total.toFixed(2);
				},
				getPrice: function(precio, coin, exchange, currency) {
					var price = precio;
					if (coin == '1' && currency == 2) {
						price = price / exchange;
					}
					else if (coin == '2' && currency == 1) {
						price = price * exchange;
					}
					return price.toFixed(2);
				},
				load: function(page) {
					if (!page) {
						page = 1;
					}
					setLoader();
					axios.post('{{ URL('perfil/pedidos') }}?page=' + page)
						.then(function(res) {
							if (res.data.result) {
								// console.log(res.data.pedidos.data);
								vue.pedidos = res.data.pedidos.data;
								vue.paginator = res.data.pedidos;
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
				_view: function(item) {
					vue.history = null;
					vue.history = item;
					console.log(item);
				},
				submit: function() {
					setLoader();

					let rawUser = vue.user;					
					rawUser.municipio_id = vue.user.municipality_id ? vue.user.municipality_id : null;
					rawUser.parroquia_id = vue.user.parish_id ? vue.user.parish_id : null;

					axios.post('{{ URL('perfil') }}', rawUser)
						.then(function(res) {
							if (res.data.result) {
								swal('',"{{ Lang::get('Page.Perfil.Success') }}",'success');
							}
							else {
								swal('',res.data.error,'warning');
							}
						})
						.catch(function(err) {
							swal('','{{ Lang::get('Page.Error') }}','warning');
						})
						.then(function() {
							quitLoader();
						});
				},
				submitPassword: function() {
					setLoader();
					axios.post('{{ URL('password') }}',vue.password)
						.then(function(res) {
							if (res.data.result) {
								swal('',"{{ Lang::get('Page.Perfil.SuccessPassword') }}",'success');
								vue.password = {
									old_password: '',
									password: '',
									password_confirmation: ''
								}
							}
							else {
								swal('',res.data.error,'warning');
							}
						})
						.catch(function(err) {
							swal('','{{ Lang::get('Page.Error') }}','warning');
						})
						.then(function() {
							quitLoader();
						});
				},
				getPriceByCurrency: function(precio,coin) {
					var price = precio;
					if (coin == '1' && vue.currency == '2') {
						price = price / vue.exchange;
					}
					else if (coin == '2' && vue.currency == '1') {
						price = price * vue.exchange;
					}
					return price;
				},
				loadCart: function() {
					axios.post('{{ URL('carrito/ajax') }}')
						.then(function(res) {
							if (res.data.result) {
								vue.cart = res.data.carrito;
								vue_header.cart = vue.cart.length;
								var total = 0;
								vue.cart.forEach(function(item) {
									total += item.cantidad * vue.getPriceByCurrency(item.amount.price,item.producto.coin).toFixed(2);
								});
								vue_header.subtotal = total;
							}
						})
						.catch(function(err) {
							swal('','{{ Lang::get('Page.Error') }}','warning');
							console.log(err);
						})
				},
				padZeros: function(num) {
					if (!String.prototype.repeat) {
					  String.prototype.repeat = function(count) {
					    'use strict';
					    if (this == null) {
					      throw new TypeError('can\'t convert ' + this + ' to object');
					    }
					    var str = '' + this;
					    count = +count;
					    if (count != count) {
					      count = 0;
					    }
					    if (count < 0) {
					      throw new RangeError('repeat count must be non-negative');
					    }
					    if (count == Infinity) {
					      throw new RangeError('repeat count must be less than infinity');
					    }
					    count = Math.floor(count);
					    if (str.length == 0 || count == 0) {
					      return '';
					    }
					    if (str.length * count >= 1 << 28) {
					      throw new RangeError('repeat count must not overflow maximum string size');
					    }
					    var maxCount = str.length * count;
					    count = Math.floor(Math.log(count) / Math.log(2));
					    while (count) {
					       str += str;
					       count--;
					    }
					    str += str.substring(0, maxCount - str.length);
					    return str;
					  }
					}
					if (!String.prototype.padStart) {
					    String.prototype.padStart = function padStart(targetLength,padString) {
					        targetLength = targetLength>>0;
					        padString = String((typeof padString !== 'undefined' ? padString : ' '));
					        if (this.length > targetLength) {
					            return String(this);
					        }
					        else {
					            targetLength = targetLength-this.length;
					            if (targetLength > padString.length) {
					                padString += padString.repeat(targetLength/padString.length);
					            }
					            return padString.slice(0,targetLength) + String(this);
					        }
					    };
					}
					return num.toString().padStart(5, "0");
				}
			}
		});
	</script>
@stop