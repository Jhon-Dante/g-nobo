<nav class="page-header z-depth-3">
    <div class="nav-wrapper">
        {{-- <a id="logo-container" href="#" class="brand-logo hide-on-med-and-down">{{ config('app.name') }}</a> --}}
        <ul class="left">
            <li class="hide-on-large-only"><a href="javascript:void(0)" id="toggle-left-sidebar" class="hrz-menu text-black"><i class="material-icons color--blue">menu</i></a></li>
        </ul>

        <ul class="right">
            {{--user account--}}
            <li id="user-account-box">
                <a href="javascript:void(0)" class="hrz-menu dropdown-button dropdown-belowOrigin dropdown-widthChange" data-activates='user-account-dropdown'>
                    {{-- <i class="material-icons">account_circle</i> --}}
                    <img src="{{ asset('img/icons/ico-user-i.png') }}" alt="" class="img-responsive ico-user">       
                </a>
            </li>
        </ul>

        {{--user account dropdown--}}
        <ul id='user-account-dropdown' class='dropdown-content'>
            <li>
                <a href="{{ url('admin/profile') }}">
                    <i class="material-icons">perm_identity</i>
                    Mi Perfil
                </a>
            </li>
            <li class="divider"></li>
            <li><a href="{{ url('logout') }}"><i class="material-icons">power_settings_new</i>Salir</a></li>
        </ul>
        {{--end user account dropdown--}}
    </div>
</nav>