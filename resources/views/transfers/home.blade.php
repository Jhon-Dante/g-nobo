@extends('layouts.master')

@section('title')
	@lang('Page.Transferencia.Title')
@stop

@section('content')
	<div class="contenido" id="transfers">
		<h2 class="title title-line">@lang('Page.Transferencia.Title')</h2>
		<div class="row">
			<div class="offset-md-3 col-md-6">
				{{ Form::open(['v-on:submit.prevent' => 'submit()']) }}
					<div class="form-group">
						{{ Form::label('number',Lang::get('Page.Transferencia.Number')) }}
						{{ Form::text('number','',['class' => 'form-control','v-model' => 'form.number']) }}
					</div>
					<div class="form-group">
						{{ Form::label('from',Lang::get('Page.Transferencia.From')) }}
						{{ Form::select('from',$bancos,null,['class' => 'form-control','v-model' => 'form.from']) }}
					</div>
					<div class="form-group">
						{{ Form::label('to',Lang::get('Page.Transferencia.To')) }}
						{{ Form::select('to',$cuentas,null,['class' => 'form-control','v-model' => 'form.to']) }}
					</div>
					<div class="form-group">
						{{ Form::label('fecha',Lang::get('Page.Transferencia.Fecha')) }}
						{{ Form::text('fecha','',['class' => 'form-control datepicker','v-model' => 'form.fecha','id' => 'datepicker']) }}
					</div>
					<div class="form-group">
						{{ Form::label('total',Lang::get('Page.Transferencia.Total')) }}
						<input type="text" name="total" :value="form.total | VES" class="form-control" disabled />
					</div>
					<div class="text-center">
						<button class="btn btn-default" type="submit">
							@lang('Page.Transferencia.Enviar')
						</button>
					</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
@stop

@section('styles')
	{{ HTML::Style('bower_components/jquery-ui/jquery-ui.min.css') }}
@stop

@section('scripts')
	{{ HTML::Script('bower_components/jquery-ui/jquery-ui.min.js') }}
	{{ HTML::Script('js/datepicker.js') }}
	<script type="text/javascript">
		@if (\App::getLocale() == 'es')
			$(function() {
				$.datepicker.setDefaults($.datepicker.regional['es']);
			});
		@endif

		var vue = new Vue({
			el: '#transfers',
			data: {
				form: {
					from: '',
					to: '',
					fecha: '',
					number: '',
					total: '{{ $total }}'
				}
			},
			methods: {
				submit() {
					setLoader();
					vue.form.fecha = $('#datepicker').val();
					axios.post('{{ URL('transferencia') }}',vue.form)
						.then(function(res) {
							if (res.data.result) {
								swal('','{{ Lang::get('Page.Transferencia.Success') }}','success');
								setTimeout(function() {
									window.location = res.data.url;
								},1500);
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
				}
			}
		});
	</script>
@stop