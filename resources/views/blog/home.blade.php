@extends('layouts.master')

@section('title')
	@lang('Page.Blog.Title')
@stop

@section('content')
	<div class="contenido" id="mundo">
		<h2 class="title">@lang('Page.Blog.Title')</h2>
		<p>@lang('Page.Blog.SubTitle')</p>
		<div class="masonry">
			@foreach($blogs as $blog)
				<div class="item-column">
					<a href="{{ URL('mundo/view',$blog->id) }}">
						<div class="item">
							<img src="{{ URL('img/blogs/'.$blog->images[0]->file_miniature) }}" />
							<div class="container-text">
								<h4>{{ \App::getLocale() == 'es' ? $blog->title : $blog->title_english }}</h4>
								<p class="fecha">@lang('Page.Blog.Fecha'): {{ \Carbon\Carbon::parse($blog->created_at)->format('d/m/Y') }}</p>
								<p class="ellipsis">{!! \App::getLocale() == 'es' ? nl2br($blog->description) : nl2br($blog->description_english) !!}</p>
							</div>
						</div>
					</a>
				</div>
			@endforeach
		</div>
		<div class="text-center">
			{{ $blogs->links('vendor.pagination.bootstrap-4') }}
		</div>		
	</div>
@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
				$('.item-column').css('display','inline-block');
			}

			$('.item-column').animate({
				opacity: '1'
			},0);
		});
	</script>
@stop