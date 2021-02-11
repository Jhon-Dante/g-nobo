@extends('layouts.master')

@section('title')
	@lang('Page.Home.Inicio')
@stop

@section('content')
	<div class="contenido" id="intro">
		<div class="text-center">{{ HTML::Image('img/logo-intro.png') }}</div>
		<h2>{{ \App::getLocale() == 'es' ? $_sociales->slogan : $_sociales->english_slogan }}</h2>
		<div class="text-center">
			<a href="{{ URL('home') }}">
				<button class="btn btn-default">
					@lang('Page.Home.Entrar')
				</button>
			</a>
		</div>
	</div>
@stop