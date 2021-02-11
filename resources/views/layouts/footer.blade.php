<footer class="footer">
    <footer class="container">
        <div class="row">
            <div class="col-md-5 bg-left">
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
            <div class="col-md-4 bg-right">
                <h5>Contáctenos</h5>
                <div class="f-info"><a href="https://wa.me/584124189590">
                        <p><img src="{{ asset('img/icons/phone-ico.svg') }}" alt="Phone"> Teléfono 04124189590 </p>
                    </a>
                    <p><img src="{{ asset('img/icons/envelop-ico.svg') }}" alt="Envelop"> <a href="mailto:invtatotogo@gmail.com">Invtatotogo@gmail.com</a> </p>
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
                <div class="f-info"><a href="https://wa.me/584124189590">
                        <p><img src="{{ asset('img/icons/reloj-ico.svg') }}" alt="Horario"> Teléfono 04124189590 </p>
                    </a>
                    <p><img src="{{ asset('img/icons/horario-ico.svg') }}" alt="Horario"><a href="mailto:invtatotogo@gmail.com">Invtatotogo@gmail.com</a> </p>
                </div>
            </div>
        </div>
        </div>
    </footer>