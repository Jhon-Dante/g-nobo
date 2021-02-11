@extends('layouts.master')

@section('title')
	@lang('Page.Aliados.Title')
@stop

@section('content')
	<div class="contenido" id="aliados" v-cloak>
		<h2 class="title">@lang('Page.Aliados.Title')</h2>
		<div class="masonry">
			@foreach($aliados as $aliado)
				<div class="item item-column">
					<div class="container-item">
						<a href="#" v-on:click.prevent="ver({{ $aliado }})">
							<div id="carousel{{ $aliado->id }}" class="carousel slide carousel-fade" data-ride="carousel" data-interval="false">
							
							  <div class="carousel-inner">
									@foreach($aliado->fotos as $key => $item)
											<div class="carousel-item carousel-item--overflow @if ($key == 0) active @endif">
												<img class="carousel-item__image d-block w-100" src="{{ URL('img/aliados/'.$item->file_miniature) }}" />
											</div>
									@endforeach
							  </div>
							
							  <a class="carousel-control-prev" href="#carousel{{ $aliado->id }}" role="button" data-slide="prev">
							    {{ HTML::Image('img/icons/left.png') }}
							  </a>
							  <a class="carousel-control-next" href="#carousel{{ $aliado->id }}" role="button" data-slide="next">
							    {{ HTML::Image('img/icons/right.png') }}
							  </a>		  
							</div>
						</a>
						
						<div class="container-text">
							<a href="#" v-on:click.prevent="ver({{ $aliado }})"><h2>{{ $aliado->name }}</h2></a>
							<div class="text-center">
								<table>
									<tr>
										<td>
											<a target="_blank" href="{{ $aliado->instagram }}" class="instagram_white">
												{{ HTML::Image('img/icons/instagram.png') }}
											</a>
										</td>
										<td>
											<a target="_blank" href="{{ $aliado->facebook }}" class="facebook_white">
												{{ HTML::Image('img/icons/facebook.png') }}
											</a>
										</td>
										<td>
											<a target="_blank" href="{{ $aliado->twitter }}" class="youtube">
												{{ HTML::Image('img/icons/twitter.png') }}
											</a>
										</td>
									</tr>
								</table>
							</div>
							<p class="bold">@lang('Page.Aliados.Ubicacion'):</p>
							<p>{!! nl2br($aliado->address) !!}</p>
						</div>
					</div>
				</div>
			@endforeach
		</div>
		<div class="text-center">
			{{ $aliados->links('vendor.pagination.bootstrap-4') }}
		</div>

		<div class="modal fade" id="modal-aliado" v-hide="aliado != null">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-body">
		        <div class="item" v-if="aliado != null">
					<div class="container-item">
						<div id="carousel-modal" class="carousel slide carousel-fade" data-ride="carousel">
						
						  <div class="carousel-inner">
						    <div v-for="(item,key) in aliado.fotos" class="carousel-item carousel-item--full-size" :class="key == 0 ? 'active' : ''">
						      <img class="carousel-item__image--full-size d-block w-100" :src="'{{ URL('img/aliados') }}' + '/' + item.file" />
						    </div>
						  </div>

						  <div class="controls-inside">
					      	  <a class="carousel-control-prev" href="#carousel-modal" role="button" data-slide="prev">
							    {{ HTML::Image('img/icons/left.png') }}
							  </a>
							  <a class="carousel-control-next" href="#carousel-modal" role="button" data-slide="next">
							    {{ HTML::Image('img/icons/right.png') }}
							  </a>
					      </div>

						</div>
						
						<div class="container-text">
							<h2>@{{ aliado.name }}</h2>
							<div class="text-center">
								<table>
									<tr>
										<td>
											<a target="_blank" :href="aliado.instagram" class="instagram_white">
												{{ HTML::Image('img/icons/instagram.png') }}
											</a>
										</td>
										<td>
											<a target="_blank" :href="aliado.facebook" class="facebook_white">
												{{ HTML::Image('img/icons/facebook.png') }}
											</a>
										</td>
										<td>
											<a target="_blank" :href="aliado.twitter" class="youtube">
												{{ HTML::Image('img/icons/twitter.png') }}
											</a>
										</td>
									</tr>
								</table>
							</div>
							<p class="bold">@lang('Page.Aliados.Ubicacion'):</p>
							<p class="nl2br">@{{ aliado.address }}</p>
						</div>
					</div>
				</div>
		      </div>
		    </div>
		  </div>
		</div>	
	</div>
@stop

@section('scripts')
	<script type="text/javascript">

		$(document).ready(function() {

			$('#modal-aliado').on('hide.bs.modal', function(e) {
				vue.aliado = null;
			});

			if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
				$('.item-column').css('display','inline-block');
			}

			$('.item-column').animate({
				opacity: '1'
			},0);
		});

		var vue = new Vue({
			el: '#aliados',
			data: {
				aliado: null
			},
			methods: {
				ver(item) {
					vue.aliado = item;
					$('#modal-aliado').modal();
				},
				close() {
					vue.aliado = null;
					$('#modal-aliado').modal('hide');
				}
			}
		});
	</script>
@stop