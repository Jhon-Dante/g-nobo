@extends('layouts.master')

@section('title')
	@lang('Page.Home.Inicio')
@stop

@section('content')
	<div class="contenido contenido-no-padding" id="home" @if (isset($slider[0])) style="background-image: url({{ URL('img/slider-fijo.jpg') }})" @endif>
		<div id="carousel" class="carousel slide carousel-fade" data-ride="carousel">

		  <div class="carousel-inner">
			@foreach($slider as $key => $item)
			    <div class="carousel-item @if ($key == 0) active @endif" style="background-image: url({{ URL('img/slider/'.$item->foto) }})">
			      {{-- <img class="d-block w-100" src="{{ URL('img/slider/'.$item->foto) }}" /> --}}
			    </div>
			 @endforeach
		  </div>

		  <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
		    {{ HTML::Image('img/icons/left.png') }}
		  </a>
		  <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
		    {{ HTML::Image('img/icons/right.png') }}
		  </a>		  
		</div>
	</div>
@stop