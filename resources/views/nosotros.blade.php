@extends('layouts.master')

@section('title')
@lang('Page.Nosotros.Title')
@stop

@section('content')
<div class="contenido" id="nosotros">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<h2>
				<a href="{{ URL('/') }}" class="float-left">
					<!-- <img src="{{ asset('img/icons/arrow-izquierda.svg') }}" /> -->
				</a>
				<!-- @lang('Page.Nosotros.Title') -->
			</h2>
			<!-- <p>{!! \App::getLocale() == 'es' ? nl2br($nosotros->texto) : nl2br($nosotros->english) !!}</p> -->
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<div class="row img">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
					<!-- <img class="logo-nosotros" src="{{ asset('img/logo-black.png') }}" />
					{{ HTML::Image('img/nosotros.png') }} -->
				</div>
			</div>
			<div class="row margintop">
				<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<!-- <h3>@lang('Page.Nosotros.Mision')</h3> -->
					<!-- <p>{!! \App::getLocale() == 'es' ? nl2br($nosotros->mision) : nl2br($nosotros->mision_english) !!}</p> -->
				</div>
				<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<!-- <h3>@lang('Page.Nosotros.Vision')</h3> -->
					<!-- <p>{!! \App::getLocale() == 'es' ? nl2br($nosotros->vision) : nl2br($nosotros->vision_english) !!}</p> -->
				</div>
			</div>
		</div>
	</div>
</div>
@stop