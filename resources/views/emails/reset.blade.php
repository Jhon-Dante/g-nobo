<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>@lang('Page.ResetEmail.Title')</title>
	<link rel="StyleSheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700" />
	@include('emails.style')
</head>
<body>
	<div class="container" style="background: white;">
		<div class="text-center">
			@include('emails.partials.header')
			<h4 class="title">@lang('Page.ResetEmail.Title')</h4>
			<p class="text-justify">@lang('Page.ResetEmail.Continuar')</p>
			<div class="codigo">{{ $codigo }}</div>
			@include('emails.partials.footer')
		</div>			
	</div>
</body>
</html>