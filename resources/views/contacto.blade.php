@extends('layouts.master')

@section('title')
	@lang('Page.Contacto.Title')
@stop

@section('content')
	<div class="contenido" id="contacto">
		<div class="row">
			<div class="col-md-4 text-center right-line-container">
				<a href="{{ url('/') }}" class="float-left">
					<img src="{{ asset('img/icons/arrow-izquierda.svg') }}" alt="">
				</a>
				<h2 class="mt-5">@lang('Page.Contacto.Contactanos')</h2>
				<p class="d-flex align-items-center">
					<img src="{{ asset('img/icons/Telefono.svg') }}" alt="" width="30" class="mr-4">
					<span class="text-uppercase font-bold dato">@lang('Page.Contacto.Telefono'): {{ $_sociales->phone }}</span>
				</p>
				<p class="d-flex align-items-center">
					<img src="{{ asset('img/icons/Correo.svg') }}" alt="" width="30" class="mr-4">
					<span class="text-uppercase font-bold dato"> @lang('Page.Contacto.Email'): {{ $_sociales->email }}</span> 
				</p>
				<p class="d-flex align-items-center">
					<img src="{{ asset('img/icons/Ubicacion.svg') }}" alt="" width="30" class="mr-4">
					<span class="text-uppercase font-bold dato">@lang('Page.Contacto.Ubicacion'): {!! nl2br($_sociales->address) !!}</span> 
				</p>
				<div class="right-line"></div>
			</div>
			<div class="col-md-8 text-center mt-5">
				{{ Form::open(['v-on:submit.prevent' => 'submit()']) }}
					<div class="row">
						<div class="col-md-6">
							<div class="d-flex flex-column justify-content-between h-100">
								<div class="form-group shadow-sm">
									{{-- {{ Form::label('nombre',Lang::get('Page.Contacto.Nombre')) }} --}}
									{{ Form::text('nombre','',['class' => 'form-control','v-model' => 'form.nombre', 'placeholder' => 'Nombre']) }}
								</div>
								<div class="form-group shadow-sm">
									{{-- {{ Form::label('email',Lang::get('Page.Contacto.Email')) }} --}}
									{{ Form::text('email','',['class' => 'form-control','v-model' => 'form.email', 'placeholder' => 'Correo electrónico']) }}
								</div>
								<div class="form-group shadow-sm">
									{{-- {{ Form::label('pais',Lang::get('Page.Contacto.Pais')) }} --}}
									{{ Form::select('pais',$paises,null,['class' => 'form-control','v-model' => 'form.pais', 'placeholder' => 'Pais']) }}
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group shadow-sm">
								{{-- {{ Form::label('mensaje',Lang::get('Page.Contacto.Mensaje')) }} --}}
								{{ Form::textarea('mensaje','',['class' => 'form-control','v-model' => 'form.mensaje', 'rows' => 7, 'placeholder' => 'Cuentanos como podemos ayudarte']) }}
							</div>
						</div>
					</div>
					<div class="text-center mt-3">
						<button class="btn btn-primary btn-viveres" type="submit">
							@lang('Page.Contacto.Enviar')
						</button>
					</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
@stop

@section('scripts')
	<script type="text/javascript">
		var vue = new Vue({
			el: '#contacto',
			data: {
				form: {
					nombre: '',
					email: '',
					mensaje: '',
					pais: ''
				}
			},
			methods: {
				submit() {
					setLoader();
					axios.post("{{ URL('contacto') }}",vue.form)
						.then(function(res) {
							if (res.data.result) {
								swal('','{{ Lang::get('Page.Contacto.Success') }}','success');
								vue.form = {};
							}
							else {
								swal('',res.data.error,'warning');
							}
						})
						.catch(function(err) {
							swal('',"{{ Lang::get('Page.Error') }}",'warning');
						})
						.then(function() {
							quitLoader();
						});
				}
			}
		});
	</script>
@stop