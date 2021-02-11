<header id="vue_header">   
	<nav class="navbar navbar-expand-lg fixed-top">
		<div class="navbar-left">
			<!-- <a 	
				{{-- @if(Request::is('/') || Request::is('search')) --}}
					v-on:click.prevent="filtro()" 
					href="#"
				{{-- @else  --}}
					{{-- href="{{ url('/?view_categories=1') }}"  --}}
				{{-- @endif --}}
				class="navbar-left-filter">
				<img src="{{ asset('img/icons/menu.svg') }}" alt="" width="30">
				<span>
					Categorias
				</span>
			</a> -->
	
			<a class="navbar-brand" href="{{ URL('/') }}">
				<img src="{{ asset('img/logo.png') }}" class="logo">
			</a>
		</div>
		
		<div class="container-form-header form-no-responsive">
			<form action="{{ route('search') }}" class="navbar-center" autocomplete="off">
				<div class="navbar-form" :class="{ 'hidden-sm': !isVisible }">
					<span v-on:click.prevent="responsiveHeader()" class="navbar-form-quit-search hidden-lg" :class="{ 'hidden-sm': !isVisible }">x</span>
					<input v-model="query" v-on:keyup="autocomplete" autocomplete="off" class="form-control navbar-form-input" name="query" type="text" placeholder="Buscar productos" aria-label="Search" />
					<button class="navbar-form-button">
						<img src="{{ asset('img/icons/search.svg') }}" alt="Search" width="18" height="18">
					</button>
				</div>
				<button class="navbar-form-button-sm hidden-lg" :class="{ 'hidden-sm': isVisible }" v-on:click.prevent="responsiveHeader()">
					<img src="{{ asset('img/icons/search.svg') }}" alt="Search" width="18" height="18">
				</button>
			</form>
			<ul v-if="(suggestions.length > 0 || categories.length > 0) || loadQuery || (queryLoaded && query.length > 0)" v-cloak>
				<li class="close-item">
					<a href="#" v-on:click.prevent="quitSugestions"><i class="fa fa-close"></i></a>
				</li>
				<li class="loading-item" v-if="loadQuery">
					<i class="fa fa-spinner fa-spin fa-fw"></i>
				</li>
				<li class="no-items" v-if="!loadQuery && (suggestions.length == 0 && categories.length == 0 && subcategories.length == 0) && query.length > 0">
					No se encontraron resultados
				</li>
				<li v-for="item in subcategories">
					<a v-on:click="query = item.name;" :href="'{{ url('/') }}?category_id='+ item.category_id +'&subcategory_id='+item.id">
						@{{ item.name }} - @{{ item.categories.name }}
					</a>
				</li>
				<li v-for="item in categories">
					<a v-on:click="query = item.name;" :href="'{{ url('/') }}?category_id='+ item.id +'&subcategory_id='">
						@{{ item.name }}
					</a>
				</li>
				<li v-for="item in suggestions">
					<a v-on:click="query = item.name;" :href="'{{ route('search') }}?query=' + item.name">
						@{{ item.name }} - @{{ item.categories.name }}
					</a>
				</li>
			</ul>
		</div>

		<div class="navbar-right">

			<div class="dropdown">
				<a  
				@auth
				href="{{ url('perfil') }}" 
				@else 
				href="#!"
				data-toggle="modal" data-target="#loginModal"
				@endauth
				class=""
				>
				<img src="{{ asset('img/icons/login.svg') }}" alt="login" class="mr-1">
				<span class="font-bold">@auth {{ auth()->user()->name }} @else Entrar @endauth </span>
			</a>

			@if (Auth::check())
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<a  
				@auth
				href="{{ url('perfil') }}" 
				@else 
				href="#!"
				data-toggle="modal" data-target="#loginModal"
				@endauth
				class="navbar-right-concealable dropdown-item"
				>
				<span>@auth Perfil @else Entrar @endauth </span>

			</a>
			<a class="dropdown-item" href="{{ URL('logout') }}">@lang('Page.Perfil.Logout')</a>
		</div>
		@endif
	</div>

			<div class="dropdown show" :class="{ 'hidden-responsive': isVisible }">
				<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img class="img-monedas mr-1" src="{{ URL('img/icons/moneda.svg') }}" />
					<span class="font-bold text-white text-responsive" v-cloak>
						Monedas
					</span>
				</a>
				<div id="dropdown-currencies-header" class="dropdown-menu text-center" aria-labelledby="dropdownMenuLink">
					<a v-if="currency == 2" href="{{ URL('change-currency/1') }}" >
						{{-- <span class="font-bold" v-cloak>VES</span> --}}
						<img src="{{ asset('img/icons/ve.png') }}" alt="VE" width="25">
					</a>
					<a v-if="currency == 1" href="{{ URL('change-currency/2') }}">
						{{-- <span class="font-bold" v-cloak>USD</span> --}}
					<img src="{{ asset('img/icons/eu.png') }}" alt="EU" width="25">
					</a>
				</div>
			</div>

		
			{{--<div class="navbar-right-social">
				<a target="_blank" href="{{ $_sociales->facebook }}">
					<img src="{{ asset('img/icons/facebook.svg') }}" alt="facebook">
				</a>
				<a target="_blank" href="{{ $_sociales->instagram }}">
					<img src="{{ asset('img/icons/instagram.svg') }}" alt="instagram">
				</a>
			</div>--}}

			<a 	@auth
					href="{{ url('favoritos') }}" 
				@else 
					href="#!"
					data-toggle="modal" data-target="#loginModal"
				@endauth
				 href="{{ url('favoritos') }}" class="navbar-right-ocult">
				<img src="{{ asset('img/icons/favorito.svg') }}" alt="favorito">
				<span class="font-bold"> &nbsp;Favoritos</span>
			</a> 
			<div class="container-form-header form-responsive">
				<form action="{{ route('search') }}" class="navbar-center" autocomplete="off">
					<div class="navbar-form">
						<input v-model="query" v-on:keyup="autocomplete" autocomplete="off" class="form-control navbar-form-input" name="query" type="text" placeholder="Buscar" aria-label="Search">
						<button class="navbar-form-button">
							<img src="{{ asset('img/icons/search.svg') }}" alt="Search" width="18" height="18">
						</button>
					</div>
				</form>
				<ul v-if="(suggestions.length > 0 || categories.length > 0) || loadQuery || (queryLoaded && query.length > 0)" v-cloak>
					<li class="close-item">
						<a href="#" v-on:click.prevent="quitSugestions"><i class="fa fa-close"></i></a>
					</li>
					<li class="loading-item" v-if="loadQuery">
						<i class="fa fa-spinner fa-spin fa-fw"></i>
					</li>
					<li class="no-items" v-if="!loadQuery && (suggestions.length == 0 && categories.length == 0 && subcategories.length == 0) && query.length > 0">
						No se encontraron resultados
					</li>
					<li v-for="item in subcategories">
						<a v-on:click="query = item.name;" :href="'{{ url('/') }}?category_id='+ item.category_id +'&subcategory_id='+item.id">
							@{{ item.name }} - @{{ item.categories.name }}
						</a>
					</li>
					<li v-for="item in categories">
						<a v-on:click="query = item.name;" :href="'{{ url('/') }}?category_id='+ item.id +'&subcategory_id='">
							@{{ item.name }}
						</a>
					</li>
					<li v-for="item in suggestions">
						<a v-on:click="query = item.name;" :href="'{{ route('search') }}?query=' + item.name">
							@{{ item.name }} - @{{ item.categories.name }}
						</a>
					</li>
				</ul>
			</div>
					@if(!Request::is('verificacion'))
				<a
					:class="{ 'hidden-responsive': isVisible }"
					class="cart-header" href="#!" @click="toggleCart()">
					<div style="position: relative; padding-right: 8px;">
						<div class="cart-icon"></div>
						<img data-toggle="modal" data-target="#myModal2" src="{{ asset('img/icons/carrito.svg') }}" alt="favorito">
						<span class="navbar-right-badge" v-cloak>@{{ cart }}</span>
					</div>
					<span class="font-bold navbar-right-subtotal cart-subtotal" v-cloak
						v-if="currency == '1'"> &nbsp;@{{ subtotal | VES }}</span>
					<span class="font-bold navbar-right-subtotal cart-subtotal" v-cloak
						v-if="currency == '2'"> &nbsp;@{{ subtotal | USD }}</span>
				</a>
			@endif
		</div>
	</nav>
		<section class="money-bar shadow-sm">
			<div class="container">
				<div class="row">
					
			
					<div class=" offset-md-3 col-md-6">
						
		
					</div>
				</div>
			</div>
		</section>

</header>