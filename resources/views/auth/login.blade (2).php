@extends('layouts.master')

@section('title')
	@lang('Page.Login.Title')
@stop

@section('content')
	<div class="contenido" id="login">
		<div class="row">
			<div class="col-md-6 text-center right-line-container">
				<h2 class="title">@lang('Page.Login.Title')</h2>
				<p>@lang('Page.Login.SubTitle')</p>
				<div class="text-center">
					<a href="{{ URL('register') }}">
						<button class="btn btn-default" type="submit">
							@lang('Page.Login.Registrate')
						</button>
					</a>
				</div>
				<a href="{{ URL('condiciones') }}" class="item-right">
					{{ HTML::Image('img/icons/right.png') }}
					@lang('Page.Login.Condiciones')
				</a>
				<div class="right-line"></div>
			</div>
			<div class="col-md-6 text-center">
				{{ Form::open(['v-on:submit.prevent' => 'submit()']) }}
					<div class="form-group">
						{{ Form::label('email',Lang::get('Page.Login.Email')) }}
						{{ Form::text('email','',['class' => 'form-control','v-model' => 'form.email']) }}
					</div>
					<div class="form-group">
						{{ Form::label('password',Lang::get('Page.Login.Password')) }}
						{{ Form::password('password',['class' => 'form-control','v-model' => 'form.password']) }}
					</div>
					<div class="text-center">
						<button class="btn btn-default" type="submit">
							@lang('Page.Login.Entrar')
						</button>
					</div>
					<a href="{{ URL('recuperar') }}">
						@lang('Page.Login.Recuperar')
					</a>
				{{ Form::close() }}
			</div>
		</div>
		<div class="right">
			<a href="{{ URL('register') }}">
				{{ HTML::Image('img/icons/right.png') }}
			</a>
		</div>
	</div>
@stop

@section('scripts')
	<script type="text/javascript">
		var vue = new Vue({
			el: '#login',
			data: {
				form: {
					email: '',
					password: ''
				}
			},
			methods: {
				submit() {
					setLoader();
					axios.post("{{ URL('login') }}",vue.form)
						.then(function(res) {
							if (res.data.result) {
								window.location = res.data.url;
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