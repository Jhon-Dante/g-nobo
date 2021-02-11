<ul class="leftside-navigation">

    {{-- <li class="navigation">Menú</li> --}}

    <li>
        <a href="{{ url('/admin/exchange_rate') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Tasa de Cambio">
            <img src="{{ asset('img/icons/tasa-cambio.png') }}" alt="" class="img-responsive">Tasa de Cambio
        </a>
    </li>

    <li>
        <a href="{{ url('/admin/shipping-fees') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Tasa de Envio/Monto Mínimo">
            <img src="{{ asset('img/icons/tasa-envio.png') }}" alt="" class="img-responsive">Tasa de Envio/Monto Mínimo
        </a>
    </li>

    <li>
        <a href="{{ url('/admin/offers') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Ofertas">
            <img src="{{ asset('img/icons/oferta.png') }}" alt="" class="img-responsive">Ofertas
        </a>
    </li>

    <li>
        <a href="{{ url('/admin/discounts') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Ofertas">
            <img src="{{ asset('img/icons/descuentos.png') }}" alt="" class="img-responsive">Descuentos
        </a>
    </li>

    <li>
        <a href="{{ url('/admin/promotions') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Ofertas">
            <img src="{{ asset('img/icons/promotions.png') }}" alt="" class="img-responsive">Promociones
        </a>
    </li>

    <li>
        <a href="{{ url('/admin/taxes') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Ofertas">
            <img src="{{ asset('img/icons/ico-tasa-de-cambio.png') }}" alt="" class="img-responsive">Impuestos
        </a>
    </li>


    <li>
        <a href="{{ url('/admin/products') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Productos">
            <img src="{{ asset('img/icons/ico-productos.png') }}" alt="" class="img-responsive">Productos
        </a> 
    </li>

    <li>
        <a href="{{ url('/admin/replenishment') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Productos">
            <img src="{{ asset('img/icons/ico-productos.png') }}" alt="" class="img-responsive">Reposicion de Inventario
        </a> 
    </li>
{{-- 
    <li>
        <a href="{{ url('/admin/reports') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Reportes">
            <img src="{{ asset('img/icons/ico-productos.png') }}" alt="" class="img-responsive">Reportes
        </a> 
    </li> --}}

    <li>
        <a href="javascript:void(0)" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Reportes">
            <img src="{{ asset('img/icons/reportes.png') }}" alt="" class="img-responsive">
            <img src="{{ asset('img/icons/paso-3.png') }}" alt="" class="img-responsive pull-right ico-flecha  right-icon">       
            Reportes
        </a>
        <ul class="collapsible-body">
            <li><a href="{{ url('/admin/reports/purchases') }}">Ventas</a></li>
            <li><a href="{{ url('/admin/reports/orders') }}">Pedidos</a></li>
            <li><a href="{{ url('/admin/reports/products') }}">Productos mas vendidos</a></li>
        </ul>
    </li>


    {{-- <li>
        <a href="{{ url('/admin/sizes') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Tallas">
            <img src="{{ asset('img/icons/ico-tallas.png') }}" alt="" class="img-responsive">Tallas
        </a>
    </li> --}}

    <li>
        <a href="{{ url('/admin/categories') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Categorías">
            <img src="{{ asset('img/icons/ico-categorias.png') }}" alt="" class="img-responsive">Categorías
        </a>
    </li>

    <li>
        <a href="{{ url('/admin/purchases') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Pedidos">
            <img src="{{ asset('img/icons/ico-pedidos.png') }}" alt="" class="img-responsive">Pedidos
        </a> 
    </li>

    <li>
        <a href="{{ url('/admin/clients') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Clientes">
            <img src="{{ asset('img/icons/ico-clients.png') }}" alt="" class="img-responsive">Clientes
        </a> 
    </li>

    <li>
        <a href="{{ url('/admin/banks') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Cuentas Bancarias">
            <img src="{{ asset('img/icons/cuentas-bancarias.png') }}" alt="" class="img-responsive">Cuentas bancarias
        </a>
    </li> 

    <li>
        <a href="{{ url('/admin/estados') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Cuentas Bancarias">
            <img src="{{ asset('img/icons/estados-municipios.png') }}" alt="" class="img-responsive">Estados y municipios
        </a>
    </li> 

    <li>
        <a href="{{ url('/admin/banners') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Banners">
            <img src="{{ asset('img/icons/ico-banner.png') }}" alt="" class="img-responsive">Banners
        </a> 
    </li>

    {{-- <li>
        <a href="{{ url('/admin/collections') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Colecciones">
            <img src="{{ asset('img/icons/ico-colecciones.png') }}" alt="" class="img-responsive">Colecciones
        </a>
    </li> --}}

    {{-- <li>
        <a href="{{ url('/admin/wholesalers') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Colecciones Jurídicas">
            <img src="{{ asset('img/icons/ico-juridicas.png') }}" alt="" class="img-responsive">Colecciones Jurídicas
        </a>
    </li> --}}
    {{--  <li>
        <a href="{{ url('/admin/designs') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Diseños">
            <img src="{{ asset('img/icons/ico-inicio.png') }}" alt="" class="img-responsive">Diseños
        </a> 
    </li>  --}}
    
    <li>
        <a href="{{ url('/admin/us') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Nosotros">
            <img src="{{ asset('img/icons/ico-nosotros.png') }}" alt="" class="img-responsive">Nosotros
        </a> 
    </li>
    {{-- <li>
        <a href="{{ url('/admin/allies') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Aliados">
            <img src="{{ asset('img/icons/ico-aliados.png') }}" alt="" class="img-responsive">Aliados
        </a> 
    </li> --}}
    
    
    {{-- <li>
        <a href="{{ url('/admin/blogs') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Blogs">
            <img src="{{ asset('img/icons/ico-blogs.png') }}" alt="" class="img-responsive">Blogs
        </a> 
    </li> --}}
    
   
    <li>
        <a href="{{ url('/admin/social') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Social">
            <img src="{{ asset('img/icons/ico-contact.png') }}" alt="" class="img-responsive">Contacto
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/terms') }}" class="collapsible-label1 tooltipped" data-position="right" data-delay="50" data-tooltip="Términos y condiciones">
            <img src="{{ asset('img/icons/ico-condiciones.png') }}" alt="" class="img-responsive">Términos y condiciones
        </a>
    </li>
</ul>