<footer class="footer">
    <footer class="container">
        <div class="row">
            <div class="col-md-3 bg-left">
                <img src="{{ asset('img/logo.png') }}" class="logo" style="width: 200px;">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="col-md-3 bg-left">
                <h5>Categorías</h5>
                <div class="row category-footer">
                    <div class="col-md-4">
                        <ul class="nav flex-column mt-4">
                        <li class="nav-item p-0" v-for="(category, key) in categories">
                            <a :href="'{{ url('/') }}?category_id=' + category.id">
                                @{{ category.name }}
                            </a>
                        </li>
                        {{--@if(!is_null($categories))
                            @foreach($categories as $key)
                                <li class="nav-item p-0" >
                                    <a :href="{{ url('/') }}?category_id={{$key->id}}">
                                        {{ $key->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif--}}
                    </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3 bg-right">
                <h5>Contáctenos</h5>
                <div class="f-info">
                    <a href="https://wa.me/584124189590">
                        <p><img src="{{ asset('img/icons/phone-ico.svg') }}" alt="Phone">Teléfono 04124189590</p>
                    </a>
                    <a href="mailto:invtatotogo@gmail.com">
                        <p><img src="{{ asset('img/icons/envelop-ico.svg') }}" alt="Envelop">Invtatotogo@gmail.com</p>
                    </a>
                </div>
                <div class="img-rf">
                    <a href="{{ URL('contacto') }}">
                        <img src="{{ asset('img/icons/facebook-ico.svg') }}" alt="Facebook">
                    </a>
                    <a href="{{ URL('contacto') }}">
                        <img src="{{ asset('img/icons/twitter-ico.svg') }}" alt="Twitter">
                    </a>
                    <a href="https://instagram.com/tato2gobodegon?igshid=1fwisjrjabfnf">
                        <img src="{{ asset('img/icons/instagram-ico.svg') }}" alt="Instagram">
                    </a>
                </div>
            </div>
            <div class="col-md-3 bg-right">
                <h5>Horarios</h5>
                <div class="f-info">
                    <a href="https://wa.me/584124189590">
                        <p><img src="{{ asset('img/icons/reloj-ico.svg') }}" alt="Horario">Teléfono 04124189590 </p>
                    </a>
                    <a href="mailto:invtatotogo@gmail.com">
                        <p><img src="{{ asset('img/icons/horario-ico.svg') }}" alt="Horario">Invtatotogo@gmail.com </p>
                    </a>
                </div>
            </div>
        </div>
        </div>
    </footer>