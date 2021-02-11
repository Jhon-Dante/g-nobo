@extends('layouts.master')

@section('title')
	@lang('Page.Blog.Ver')
@stop

@section('content')
	<div class="contenido contenido-no-padding" id="ver-blog">
		<div id="carousel" class="carousel slide carousel-fade" data-ride="carousel">

		  <div class="carousel-inner">
			@foreach($blog->images as $key => $item)
			    <div class="carousel-item @if ($key == 0) active @endif">
			      <img class="d-block w-100" src="{{ URL('img/blogs/'.$item->file) }}" />
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

		<div class="container-text">
			<h2>{{ \App::getLocale() == 'es' ? $blog->title : $blog->title_english }}</h2>
			<p class="fech">@lang('Page.Blog.Fecha'): {{ \Carbon\Carbon::parse($blog->created_at)->format('d/m/Y') }}</p>
			<p>{!! \App::getLocale() == 'es' ? nl2br($blog->description) : nl2br($blog->description_english) !!}</p>
		</div>
	</div>
@stop