@extends('layouts.master')

@section('title')
	@lang('Page.Condiciones.Title')
@stop

@section('content')
	<div class="contenido" id="condiciones">
		<h3 class="title title-line">
			@lang('Page.Condiciones.Title')
		</h3>
		<p>
			{!! \App::getLocale() == 'es' ? nl2br($condiciones->texto) : nl2br($condiciones->english) !!}
		</p>
{{-- 		<p class="item-right">
			{{ HTML::Image('img/icons/right.png') }}
			@lang('Page.Condiciones.DHL')
		</p>	 --}}		
	</div>
@stop