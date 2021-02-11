@extends('layouts.master')

@section('title')
	@lang('Page.Register.Title')
@stop

@section('content')
	<div class="contenido" id="register">
		<div class="left">
			<a href="{{ URL('login') }}">
				{{ HTML::Image('img/icons/left.png') }}
			</a>
		</div>
		<div class="row">
			<div class="col-md-5 text-center right-line-container">
				<h2 class="title">@lang('Page.Register.Registrate')</h2>
				<p>@lang('Page.Register.SubTitle')</p>
				<div class="text-center">
					<a href="{{ URL('login') }}">
						<button class="btn btn-default" type="submit">
							@lang('Page.Register.Login')
						</button>
					</a>
				</div>
				<a href="{{ URL('condiciones') }}" class="item-right">
					{{ HTML::Image('img/icons/right.png') }}
					@lang('Page.Register.Condiciones')
				</a>
				<div class="right-line"></div>
			</div>
			<div class="col-md-7 text-center">
				{{ Form::open(['v-on:submit.prevent' => 'submit()']) }}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{{ Form::label('nombre',Lang::get('Page.Register.Nombre')) }}
								{{ Form::text('nombre','',['class' => 'form-control','v-model' => 'form.nombre', 'tabindex' => "1", 'maxlength' => '20']) }}
							</div>
							<div class="form-group">
								{{ Form::label('type',Lang::get('Page.Register.Tipo')) }}
								{{ Form::select('type',[
									'1' => Lang::get('Page.Register.Natural'),
									'2' => Lang::get('Page.Register.Juridica')
								],null,['class' => 'form-control','v-model' => 'form.type']) }}
							</div>
							<div class="form-group" :class="{ opcional: form.type != '2' }">
								{{ Form::label('empresa',Lang::get('Page.Register.Empresa')) }}
								<input type="text" v-model="form.empresa" :disabled="form.type != '2'" class="form-control" name="empresa" id="empresa"  />
							</div>
							<div class="form-group">
								{{ Form::label('pais',Lang::get('Page.Register.Pais')) }}
								{{ Form::select('pais',$paises,null,['class' => 'form-control','v-model' => 'form.pais', 'v-on:change' => "form.estado = ''" ]) }}
							</div>
							<div class="form-group">
								{{ Form::label('codigo',Lang::get('Page.Register.Codigo')) }}
								{{ Form::number('codigo','',['class' => 'form-control','v-model' => 'form.codigo']) }}
							</div>
							<div class="form-group">
								{{ Form::label('direccion',Lang::get('Page.Register.Direccion')) }}
								{{ Form::text('direccion','',['class' => 'form-control','v-model' => 'form.direccion']) }}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								{{ Form::label('email',Lang::get('Page.Register.Email')) }}
								{{ Form::text('email','',['class' => 'form-control','v-model' => 'form.email', ]) }}
							</div>
							<div class="form-group">
								{{ Form::label('identificacion',Lang::get('Page.Register.Identificacion')) }}
								{{ Form::number('identificacion','',['class' => 'form-control','v-model' => 'form.identificacion']) }}
							</div>
							<div class="form-group">
								{{ Form::label('telefono',Lang::get('Page.Register.Telefono')) }}
								{{ Form::number('telefono','',['class' => 'form-control','v-model' => 'form.telefono', ]) }}
							</div>
							<div class="form-group">
								{{ Form::label('estado',Lang::get('Page.Register.Estado')) }}
								<select :disabled="form.pais == ''" name="estado" id="estado" class="form-control" v-model="form.estado">
									<option v-for="item in estados" :value="item.id" v-if="form.pais == item.pais_id">@{{ item.nombre }}</option>
								</select>
							</div>							
							<div class="form-group">
								{{ Form::label('password',Lang::get('Page.Register.Password')) }}
								{{ Form::password('password',['class' => 'form-control','v-model' => 'form.password']) }}
							</div>
							<div class="form-group">
								{{ Form::label('password_confirmation',Lang::get('Page.Register.RePassword')) }}
								{{ Form::password('password_confirmation',['class' => 'form-control','v-model' => 'form.password_confirmation']) }}
							</div>
						</div>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="checkbox" id="check-terminos" v-model="terminos">
					  <label class="form-check-label" for="check-terminos">
					    @lang('Page.Register.HeLeido',['url' => \URL('terminos')])
					  </label>
					</div>
					<div class="text-center">
						<button class="btn btn-default" type="submit">
							@lang('Page.Register.Registrar')
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
			el: '#register',
			data: {
				terminos: false,
				form: {
					nombre: '',
					password: '',
					password_confirmation: '',
					telefono: '',
					pais: '',
					email: '',
					direccion: '',
					codigo: '',
					type: '',
					identificacion: '',
					estado: '',
					empresa: ''
				},
				estados: {!! json_encode($estados) !!}
			},
			methods: {
				submit() {
					if (!vue.terminos) {
						swal('','{{ Lang::get('Page.Register.Terminos') }}','warning');
						return false;
					}
					setLoader();
					axios.post("{{ URL('register') }}",vue.form)
						.then(function(res) {
							if (res.data.result) {
								swal('','{{ Lang::get('Page.Register.Success') }}','success');
								setTimeout(function() {
									window.location = res.data.url;
								},1500);
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