<div class="mg-x" id="sidebar">
    <div class="filtro" v-on:click="close()"></div>
    <div class="filtro-container">
        <!--<div class="filtro-say">
            <span>
                @auth
                    Hola {{ auth()->user()->name }}!
                @else
                    Bienvenido
                @endauth
            </span>
        </div> -->
         <!--   @auth
                <a href="{{ url('favoritos') }}" class="filtro-favorito-ref">
            @else
                <a class="filtro-favorito-ref"
                href="#" data-target="#loginModal" data-toggle="modal" v-on:click="close()">
            @endauth
                <div class="filtro-favorito">
                    <img src="{{ asset('img/icons/favorito.svg') }}" alt="favorito">
                    <span>Favoritos</span>
                </div>
            </a>
            <ul class="filtro-list">
                <li v-on:click="showPro()" class="filtro-favorito">
                    <img src="{{ asset('img/pro.svg') }}" />
                    <label class="form-check-label">
                        Productos PROkkk
                    </label>
                </li>
            </ul> -->

        <a  
            @auth
                href="{{ url('perfil') }}" 
            @else 
                @click.prevent="entrar"
            @endauth
            class="filtro-favorito-ref"
        >
            <div class="filtro-login">
                <img src="{{ asset('img/icons/login.svg') }}" alt="login">
                <span class="font-bold"> &nbsp;
                    @auth
                        <span>{{ auth()->user()->name }}</span>
                    @else
                        <span>Entrar</span>
                    @endauth
                </span>
            </div>
        </a>
        <div class="filtro-categorias">
            Categorias
        </div>
        <ul class="filtro-list">
            <li v-for="category in categoriesFilters" v-on:click="selectCategorie(category.id)" class="item-desplegable filtro-list-item">
                <div class="filtro-list-category">
                    @if (\App::getLocale() == 'es') 
                        @{{ category.name }} 
                    @else 
                        @{{ category.name_english }} 
                    @endif
                    <img src="{{ asset('img/icons/flecha-derecha-yellow.svg') }}" alt="flecha">
                </div>
                <ul v-if="category_selected == category.id" class="filtro-list-two">
                    <li v-for="subcategory in category.subcategories" class="filtro-list-two-item">
                        <label class="form-check-label" @click="filter(category.id, subcategory.id)">
                            @if (\App::getLocale() == 'es') 
                                @{{ subcategory.name }} 
                            @else 
                                @{{ subcategory.name_english }} 
                            @endif
                        </label>
                    </li>
                </ul>
            </li>
            {{-- @foreach($filtros as $filtro)
                <li v-on:click="selectCategorie({{ $filtro->id }})" class="item-desplegable filtro-list-item">
                    <div class="filtro-list-category">
                        @if (\App::getLocale() == 'es') {{ $filtro->name }} @else @{{ $filtro->name_english }} @endif
                        <img src="{{ asset('img/icons/flecha-derecha-yellow.svg') }}" alt="flecha">
                    </div>
                    <ul v-if="category_selected == {{ $filtro->id }}" class="filtro-list-two">
                        @foreach($filtro->subcategories as $subfiltro)
                            <li class="filtro-list-two-item">
                                <label class="form-check-label" @click="filter({{ $filtro->id }}, {{ $subfiltro->id }})">
                                    @if (\App::getLocale() == 'es') 
                                        {{ $subfiltro->name }} 
                                    @else 
                                        {{ $subfiltro->name_english }} 
                                    @endif
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach --}}
        </ul>
      <!--  <div class="filtro-categorias">
            Información
        </div>
        <ul class="filtro-list">
            <li class="item-desplegable filtro-list-item">
                <div class="filtro-list-category">
                    <a href="{{ URL('nosotros') }}" style="color: #000"> 
                        <label class="form-check-label">Nosotros</label>
                    </a>
                </div>
            </li>
            <li class="item-desplegable filtro-list-item">
                <div class="filtro-list-category">
                    <a href="{{ URL('terminos') }}" style="color: #000"> 
                        <label class="form-check-label">Términos y condiciones</label>
                    </a>
                </div>
            </li>
            <li class="item-desplegable filtro-list-item">
                <div class="filtro-list-category">
                    <a href="{{ URL('contacto') }}" style="color: #000"> 
                        <label class="form-check-label">Contacto</label>
                    </a>
                </div>
            </li>
        </ul>
        <a href="{{ URL('logout') }}" class="filtro-logout">@lang('Page.Perfil.Logout')</a> -->
    </div>
</div>