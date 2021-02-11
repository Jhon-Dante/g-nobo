@extends('layouts.master')

@section('title')
@lang('Page.Tienda.Title')
@stop

@section('styles')
<style>
	div, p, a, h1, h2, h3, h4, h5, h6, span, button{
		font-family: 'BogleWeb', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
	}
	#tienda #promotionModal.modal .modal-dialog {
		width: 96% !important;
	}
	.modal-content-body{
		overflow-x: auto;
		max-height: 70vh;
	}
	.combo-item{
		background-size: 100% 100%;
		height: 18rem;
		margin: 0;
		border-radius: 10px;
		cursor: pointer;
	}
	@media(min-width: 1380px){
		.combo-item{
			height: 23rem;
		}
	}
	@media (min-width: 768px){
		.modal-content-body{
			overflow-x: auto;
			max-height: 60vh;
		}
	}
	@media(max-width: 500px){
		.combo-item{
			height: 25rem;
		}
	}
	.owl-item:has(> div.promotionItem){
		width: 33% !important;
	}
</style>
@endsection

@section('content')

<div id="tienda" v-cloak class="shop">
	<div class="modal fade login-modal" id="promotionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding: 0;">
		<div class="modal-dialog" style="min-width: 40% !important; border-radius: 10px !important; background-color: transparent !important;" role="document">
			<div class="modal-content" style="border: 3px solid #1353dc !important; padding: 2rem 2rem 1rem 2rem !important;border-radius: 7px !important;">
				<div class="container">
					<div class="w-100">
						<div class="text-right">
							<a href="#!" class="modal-close waves-effect waves-green btn-flat text-dark" v-on:click="() => $('#promotionModal').modal('hide') ">
								<i class="fa fa-close"></i>
							</a>
						</div>
					</div>
					<div class="text-center">
						<h4 style="color: #ef4f25;">Detalles del Combo</h4>
					</div>
					<div class="text-left modal-content-body" v-if="itemModal">
						<p v-for="item in itemModal.products"> @{{item.product_amount.product.name+' '+item.product_amount.presentation+ (item.product_amount && item.product_amount.unit && getUnit(item.product_amount.unit) || '') +' - Cant: '+item.amount}} </p>
					</div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>

	{{-- PRODUCTOS CONTAINER --}}
	<div class="shop-products-container"  id="products-container">


		{{-- SLIDER --}}
		@if (!isset ($category_id))
		<section class="banner-menu">
			<div class="container mg-top">
				<div class="row h-50">
					<div class="col-md-3">
						<div class="menu-lx">
							<div class="filtro-say">
						<!--	<span>
									@auth
									Hola {{ auth()->user()->name }}!
									@else
									Bienvenido
									@endauth
								</span> -->
								<div class="title-cx">
									<img src="{{ asset('img/icons/menu.svg') }}" alt="" width="25"> Categorias
								</div>
							</div>



		<div class="desk-m  category-menu">
			<div id="divMenu">    
				<ul >    
					<li  v-for="category in categoriesFilters" v-on:click="selectCategorie(category.id)"  class="item-desplegable filtro-list-item">
						<div class="filtro-list-category">
							  <div  @click="filter(category.id, '')">
                            <div class="filtro-list-category">
                                @if (\App::getLocale() == 'es') @{{ category.name }} @else @{{ category.name_english }} @endif
                            </div>
                        </div>
						</div>

						<ul  class="filtro-list-two">
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

				</ul>    
			</div>
		</div>


<div class="mov-m">
	<template v-if="!category_selected">
            <ul class="filtro-list">
                <li v-for="category in categoriesFilters" v-on:click="selectCategorie(category.id)" class="item-desplegable filtro-list-item">
                    <div class="filtro-list-category">
                        @if (\App::getLocale() == 'es') @{{ category.name }} @else @{{ category.name_english }} @endif
                        <img src="{{ asset('img/icons/flecha-derecha-yellow.svg') }}" alt="flecha">
                    </div>
                </li>
            </ul>
        </template>
        <template v-if="category_selected">
            <div class="filtro-categorias filtro-back" v-on:click="category_selected = null">
                <img src="{{ asset('img/icons/flecha-izquierda-gray.svg') }}" alt="flecha">
                Menu Principal
            </div>
            <ul class="filtro-list">
                <template v-for="category in categoriesFilters">
                    <template v-if="category_selected == category.id">
                        <li class="item-desplegable filtro-list-item item-principal" @click="filter(category.id, '')">
                            <div class="filtro-list-category">
                                @if (\App::getLocale() == 'es') @{{ category.name }} @else @{{ category.name_english }} @endif
                            </div>
                        </li>
                        <li v-for="subcategory in category.subcategories" @click="filter(category.id, subcategory.id)" class="item-desplegable filtro-list-item">
                            <div class="filtro-list-category" @click="filter(category.id, subcategory.id)">
                                @if (\App::getLocale() == 'es') @{{ subcategory.name }} @else @{{ subcategory.name_english }} @endif
                            </div>
                        </li>
                    </template>
                </template>
            </ul>
        </template>
</div>


</div>
</div>
<div class="col-md-9 align-self-center">
	<div class="contenido contenido-no-padding" id="home" @if (isset($slider[0])) style="background-image: url({{ URL('img/slider-fijo.jpg') }})" @endif>
		<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				@foreach($slider as $key => $item)
				<li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}" class="@if ($key == 0) active @endif"></li>
				@endforeach
			</ol>
			<div class="carousel-inner">
				@foreach($slider as $key => $item)
				<div class="carousel-item @if ($key == 0) active @endif" style="background-image: url({{ URL('img/slider/'.$item->foto) }})">
				</div>
				@endforeach
			</div>
<!--<a class="carousel-control-prev" data-target="#carouselExampleIndicators" role="button" data-slide="prev">
<span class="carousel-control-prev-icon" aria-hidden="true"></span>
</a>
<a class="carousel-control-next" data-target="#carouselExampleIndicators" role="button" data-slide="next">
<span class="carousel-control-next-icon" aria-hidden="true"></span>
</a>-->
</div>
</div>
</div>

</div>
</div>
</section>

@endif


{{-- Promotions --}}
<div class="shop-category-container" v-show="promotions && promotions.length > 0 && !showProOnly && !showOfferOnly && !subcategory_id && !category_id && !query">
	<h2 class="BogleWeb-Regular text-center text-uppercase py-4 title-pro">
		<a href="#"  v-on:click.prevent="showPromotion()">Todo a un Click </a>
	</h2>
	<div class="shop-category-products">
		<div :class="showPromotionOnly == 1 ? 'row' : 'owl-carousel owl-carousel-promotions owl-theme owl-carousel-promotion'" style="padding: 0 10%;width: 100% !important">
			<div :class="showPromotionOnly == 1 ? 'col-lg-6 col-md-4 col-sm-12' : 'promotionItem'" v-for="item in promotions"
			v-cloak
			>
			<div>
				<div v-if="showPromotionOnly == 1">
					<div style="padding: 0 15%">
						<div
						class="shop-product-container combo-item" 
						:class="{
						' shop-product-container-blue': item.hover,
						'': !item.hover,
						'in-cart': cart && cart.map(function(i){ return i.id }).indexOf(item.id) != -1
					}"
					:style="{
					backgroundImage: 'url(' + item.image + ')',
				}"
				v-on:mouseleave="item.hover = false"  
				v-on:mouseover="item.hover = true"
				v-on:click="presentationModal(item)">
			</div>
		</div>
	</div>
	<div v-else
	class="shop-product-container combo-item" 
	:class="{
	' shop-product-container-blue': item.hover,
	'': !item.hover,
	'in-cart': cart && cart.map(function(i){ return i.id }).indexOf(item.id) != -1
}"
:style="{
backgroundImage: 'url(' + item.image + ')',}"
v-on:mouseleave="item.hover = false"  
v-on:mouseover="item.hover = true"
v-on:click="presentationModal(item)">
</div>
<div class="shop-product-button" style="position: relative;margin-top: 2rem;">
	<button class="btn btn-primary d-flex align-items-center justify-content-center" v-on:click="addToCarPromotion(item)">
		<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | VES }}</span>
					<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | USD }}</span>
	</button>
</div>
</div>
</div>
</div>
<div v-if="showPromotionOnly != 1">
	<div class="shop-category-products-arrow shop-category-products-arrow--right" v-on:click="prev('promotion')">
		<img src="{{ asset('img/icons/flecha-derecha.svg') }}" alt="anterior">
	</div>
	<div class="shop-category-products-arrow shop-category-products-arrow--left" v-on:click="next('promotion')">
		<img src="{{ asset('img/icons/flecha-izquierda.svg') }}" alt="siguiente">
	</div>
</div>
</div>
</div>

{{-- PRODUCTS PRO --}}
<div class="shop-category-container mt-5 mb-5 more-sell-wall" v-if="showPromotionOnly != 1 && !showOfferOnly && !subcategory_id && !category_id && !query && products_pro.length > 0 && discount_id == 0 && !showOfferOnly" :style="{ backgroundColor : 'navy'}">
	<h2 class="BogleWeb-Regular text-center text-uppercase py-4 title-pro" v-cloak>
		<a href="#" v-on:click.prevent="showPro()" class="LOMasVendido" :style="{ color : 'white !important'}">LO MÁS VENDIDO</a>
		<img src="{{ URL('img/chip.png') }}" style="width: auto; padding: 7px; display:none;" />
	</h2>
	<div class="shop-category-products">
		<div :class="showProOnly == 1 ? 'row' : 'owl-carousel owl-theme owl-carousel-pro'">
			<div class="item" v-for="item in products_pro" v-cloak :class="showProOnly == 1 ? 'col-lg-3 col-md-4 col-sm-6 col-12' : ''">
				<div class="shop-product-container border border-primary" 
				:class="{
				' shop-product-container-blue': item.hover,
				'': !item.hover,
				'in-cart': cart && cart.map(function(i){ return i.id }).indexOf(item.id) != -1
			}"
			v-on:mouseleave="item.hover = false"  
			v-on:mouseover="item.hover = true">
			<template v-if="item.offer">
				<span class="shop-product-offer-dias"><span class="shop-product-offer-by">Por</span> @{{ $diffDaysOffer(item.offer.end) }} día@{{ $diffDaysOffer(item.offer.end) > 1 ? 's' :  '' }}</span>
				<div class="shop-product-offer">
					<img src="{{ asset('img/descuento.svg') }}" alt="Descuento">
					<span class="shop-product-offer-percent">-@{{ item.offer.percentage }}%</span>
				</div>
			</template>
			<div class="shop-product-image p-3" :style="{ backgroundImage: 'url(' + item.image_url + ')' }" v-on:click="gotToProduct(item.id)">
			</div>
			<div class="shop-product-name" v-on:click="gotToProduct(item.id)">
				<span v-cloak>
					@if (\App::getLocale() == 'es') @{{ item.es_name }} @else @{{ item.en_name }} @endif
					<br>
				</span>
			</div>
			
			<div class="shop-product-presentations" v-if="item.variable">
				<select name="amount_id"  v-model="item.amount_id" id="" class="form-control form-control-sm text-center">
					<option :value="amount.id" v-for="amount in item.amounts" v-cloak>
						@{{ amount.presentation }} @{{ amount && amount.unit && getUnit(amount.unit) || '' }}
					</option>
				</select>
			</div>

			<div class="shop-product-button">
				<button class="btn btn-primary d-flex align-items-center justify-content-center" v-on:click="addToCar(item)">
					<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | VES }}</span>
					<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | USD }}</span>
				</button>
			</div>
		</div>
	</div>
</div>

<template v-if="showProOnly == 0">
	<div class="shop-category-products-arrow shop-category-products-arrow--right" v-on:click="prev('pro')">
		<img src="{{ asset('img/icons/flecha-derecha.svg') }}" alt="">
	</div>
	<div class="shop-category-products-arrow shop-category-products-arrow--left" v-on:click="next('pro')">
		<img src="{{ asset('img/icons/flecha-izquierda.svg') }}" alt="">
	</div>
</template>
</div>
</div>

{{-- PRODUCTS OFFER --}}
<div class="shop-category-container" v-if="showPromotionOnly != 1 && !showProOnly && !subcategory_id && !category_id && !query && products_offer.length > 0 && discount_id == 0">
	<h2 class="BogleWeb-Regular text-center text-uppercase py-4 title-pro"><a href="#" v-on:click="showOffer()">Productos en oferta</a></h2>
	<div class="shop-category-products">
		<div :class="showOfferOnly == 1 ? 'row' : 'owl-carousel owl-theme owl-carousel-offer'">
			<div class="item" v-for="item in products_offer" v-cloak :class="showOfferOnly == 1 ? 'col-lg-3 col-md-4 col-sm-6 col-12' : ''">
				<div class="shop-product-container border border-primary" 
				:class="{
				' shop-product-container-blue': item.hover,
				'': !item.hover,
				'in-cart': cart && cart.map(function(i){ return i.id }).indexOf(item.id) != -1
			}"
			v-on:mouseleave="item.hover = false"  
			v-on:mouseover="item.hover = true">
			<span class="shop-product-offer-dias"><span class="shop-product-offer-by">Por</span> @{{ $diffDaysOffer(item.offer.end) }} día@{{ $diffDaysOffer(item.offer.end) > 1 ? 's' :  '' }}</span>
			<div class="shop-product-offer">
				<img src="{{ asset('img/descuento.svg') }}" alt="Descuento">
				<span class="shop-product-offer-percent">-@{{ item.offer.percentage }}%</span>
			</div>
			<div class="shop-product-image p-3" :style="{ backgroundImage: 'url(' + item.image_url + ')' }" v-on:click="gotToProduct(item.id)">
			</div>
			<div class="shop-product-name" v-on:click="gotToProduct(item.id)">
				<span v-cloak>
					@if (\App::getLocale() == 'es') @{{ item.es_name }} @else @{{ item.en_name }} @endif
				</span>
			</div>
			
			<div class="shop-product-presentations" v-if="item.variable">
				<select name="amount_id"  v-model="item.amount_id" id="" class="form-control form-control-sm text-center">
					<option :value="amount.id" v-for="amount in item.amounts" v-cloak>
						@{{ amount.presentation }} @{{ getUnit(amount.unit) }}
					</option>
				</select>
			</div>

			<div class="shop-product-button">
				<button class="btn btn-primary d-flex align-items-center justify-content-center" v-on:click="addToCar(item)">
					<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | VES }}</span>
					<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | USD }}</span>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="shop-category-products-arrow shop-category-products-arrow--right" v-on:click="prev('offer')">
	<img src="{{ asset('img/icons/flecha-derecha.svg') }}" alt="anterior">
</div>
<div class="shop-category-products-arrow shop-category-products-arrow--left" v-on:click="next('offer')">
	<img src="{{ asset('img/icons/flecha-izquierda.svg') }}" alt="siguiente">
</div>
</div>
</div>

{{-- PRORUDCTS DISCOUNT --}}
<div class="shop-category-container" v-for="discount in discounts" :key="discount.id" v-if="showPromotionOnly != 1 && !subcategory_id && !category_id && showProOnly == 0 && !showOfferOnly && !query">
	<h2 class="BogleWeb-Regular text-center text-uppercase pt-4 pb-2 title-pro" v-cloak>
		<a href="#" v-on:click.prevent="showDiscount(discount.id)" v-if="discount.type == 'quantity_product'">@{{ discount.name }}</a>
		<span v-else>@{{ discount.name }}</span>
	</h2>
	<p class="text-center mb-1" v-cloak v-if="discount.type == 'quantity_product'"><b>
		Si compras @{{ discount[discount.type] }} productos tendrás un @{{ discount.percentage }}% de descuento en la compra total del producto.
	</b></p>
	<p class="text-center mb-1" v-cloak v-if="discount.type == 'minimum_purchase'"><b>
		Obtén @{{ discount.percentage }}% de descuento en tus compras a partir de 
		<span v-if="currency == 1">@{{ getPriceByCurrency(discount[discount.type], '2') | VES }}</span>
		<span v-if="currency == 2">@{{ discount[discount.type] | USD }}</span>
	</b></p>
	<p class="text-center mb-1" v-cloak v-if="discount.type == 'quantity_purchase'"><b>Obtén @{{ discount.percentage }}% de descuento en tu compra #@{{ discount[discount.type] + 1 }}</b></p>
	<p class="text-center mb-3" v-cloak><small>Válido hasta: @{{ discount.end | date }}</small></p>
	<div class="shop-category-products">
		<div
		:class="discount_id != 0 ? 'row' : 'owl-carousel owl-theme owl-carousel-discount-' + discount.id"
		>
		<div class="item" v-for="item in discount.products" v-cloak :class="discount_id != 0 ? 'col-lg-3 col-md-4 col-sm-6 col-12' : ''">
			<div class="shop-product-container border border-primary" 
			:class="{
			' shop-product-container-blue': item.hover,
			'': !item.hover,
			'in-cart': cart && cart.map(function(i){ return i.id }).indexOf(item.id) != -1
		}"
		v-on:mouseleave="item.hover = false"  
		v-on:mouseover="item.hover = true">
		<template v-if="item.offer">
			<span class="shop-product-offer-dias"><span class="shop-product-offer-by">Por</span> @{{ $diffDaysOffer(item.offer.end) }} día@{{ $diffDaysOffer(item.offer.end) > 1 ? 's' :  '' }}</span>
			<div class="shop-product-offer">
				<img src="{{ asset('img/descuento.svg') }}" alt="Descuento">
				<span class="shop-product-offer-percent">-@{{ item.offer.percentage }}%</span>
			</div>
		</template>
		<div class="shop-product-image p-3" :style="{ backgroundImage: 'url(' + item.image_url + ')' }" v-on:click="gotToProduct(item.id)">
		</div>
		<div class="shop-product-name" v-on:click="gotToProduct(item.id)">
			<span v-cloak>
				@if (\App::getLocale() == 'es') @{{ item.es_name }} @else @{{ item.en_name }} @endif
			</span>
		</div>
		
		<div class="shop-product-presentations" v-if="item.variable">
			<select name="amount_id"  v-model="item.amount_id" id="" class="form-control form-control-sm text-center">
				<option :value="amount.id" v-for="amount in item.amounts" v-cloak>
					@{{ amount.presentation }} @{{ getUnit(amount.unit) }}
				</option>
			</select>
		</div>

		<div class="shop-product-button">
			<button class="btn btn-primary d-flex align-items-center justify-content-center" v-on:click="addToCar(item)">
				<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | VES }}</span>
					<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | USD }}</span>
			</button>
		</div>
	</div>
</div>
</div>

<div class="shop-category-products-arrow shop-category-products-arrow--right" v-on:click="prev('discount-' + discount.id)" v-if="discount.type == 'quantity_product'">
	<img src="{{ asset('img/icons/flecha-derecha.svg') }}" alt="">
</div>
<div class="shop-category-products-arrow shop-category-products-arrow--left" v-on:click="next('discount-' + discount.id)" v-if="discount.type == 'quantity_product'">
	<img src="{{ asset('img/icons/flecha-izquierda.svg') }}" alt="">
</div>
</div>
</div>

{{-- PRODUCTS --}}
<div class="shop-category-container" v-for="category in categories" :key="category.id" v-if="showPromotionOnly != 1 && !subcategory_id && !category_id && showProOnly == 0 && discount_id == 0 && !showOfferOnly && !isLoadingCategories">
	<h2 class="BogleWeb-Regular text-center text-uppercase py-4 title-pro" v-cloak><a href="#" v-on:click.prevent="filter(category.id,'')">@{{ category.name }}</a></h2>
	<div class="shop-category-products" v-if="!query">
		<div class="owl-carousel owl-theme"
		:class="'owl-carousel-' + category.id"
		>
		<div class="item" v-for="item in category.products" v-cloak>
			<div class="shop-product-container border border-primary" 
			:class="{
			' shop-product-container-blue': item.hover,
			'': !item.hover,
			'in-cart': cart && cart.map(function(i){ return i.id }).indexOf(item.id) != -1
		}"
		v-on:mouseleave="item.hover = false"  
		v-on:mouseover="item.hover = true">
		<template v-if="item.offer">
			<span class="shop-product-offer-dias"><span class="shop-product-offer-by">Por</span> @{{ $diffDaysOffer(item.offer.end) }} día@{{ $diffDaysOffer(item.offer.end) > 1 ? 's' :  '' }}</span>
			<div class="shop-product-offer">
				<img src="{{ asset('img/descuento.svg') }}" alt="Descuento">
				<span class="shop-product-offer-percent">-@{{ item.offer.percentage }}%</span>
			</div>
		</template>
		<div class="shop-product-image p-3" :style="{ backgroundImage: 'url(' + item.image_url + ')' }" v-on:click="gotToProduct(item.id)">
		</div>
		<div class="shop-product-name" v-on:click="gotToProduct(item.id)">
			<span v-cloak>
				@if (\App::getLocale() == 'es') @{{ item.es_name }} @else @{{ item.en_name }} @endif
			</span>
		</div>
		
		<div class="shop-product-presentations" v-if="item.variable">
			<select name="amount_id"  v-model="item.amount_id" id="" class="form-control form-control-sm text-center">
				<option :value="amount.id" v-for="amount in item.amounts" v-cloak>
					@{{ amount.presentation }} @{{ getUnit(amount.unit) }}
				</option>
			</select>
		</div>

		<div class="shop-product-button">
			<button class="btn btn-primary d-flex align-items-center justify-content-center" v-on:click="addToCar(item)">
				<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | VES }}</span>
					<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | USD }}</span>
			</button>
		</div>
	</div>
</div>
</div>

<div class="shop-category-products-arrow shop-category-products-arrow--right" v-on:click="prev(category.id)">
	<img src="{{ asset('img/icons/flecha-derecha.svg') }}" alt="">
</div>
<div class="shop-category-products-arrow shop-category-products-arrow--left" v-on:click="next(category.id)">
	<img src="{{ asset('img/icons/flecha-izquierda.svg') }}" alt="">
</div>
</div>
<div v-else>
	<div class="row row-categories-filter" style="margin: 0px">
		<div class="col-lg-3 col-md-4 col-sm-6 col-12" v-for="item in category.products" v-cloak>
			<div class="shop-product-container border border-primary" 
			:class="{
			' shop-product-container-blue': item.hover,
			'': !item.hover,
			'in-cart': cart && cart.map(function(i){ return i.id }).indexOf(item.id) != -1
		}"
		v-on:mouseleave="item.hover = false"  
		v-on:mouseover="item.hover = true">
		<template v-if="item.offer">
			<span class="shop-product-offer-dias"><span class="shop-product-offer-by">Por</span> @{{ $diffDaysOffer(item.offer.end) }} día@{{ $diffDaysOffer(item.offer.end) > 1 ? 's' :  '' }}</span>
			<div class="shop-product-offer">
				<img src="{{ asset('img/descuento.svg') }}" alt="Descuento">
				<span class="shop-product-offer-percent">-@{{ item.offer.percentage }}%</span>
			</div>
		</template>
		<div class="shop-product-image p-3" :style="{ backgroundImage: 'url(' + item.image_url + ')' }" v-on:click="gotToProduct(item.id)">
		</div>
		<div class="shop-product-name" v-on:click="gotToProduct(item.id)">
			<span v-cloak>
				@if (\App::getLocale() == 'es') @{{ item.es_name }} @else @{{ item.en_name }} @endif
			</span>
		</div>
		
		<div class="shop-product-presentations" v-if="item.variable">
			<select name="amount_id"  v-model="item.amount_id" id="" class="form-control form-control-sm text-center">
				<option :value="amount.id" v-for="amount in item.amounts" v-cloak>
					@{{ amount.presentation }} @{{ getUnit(amount.unit) }}
				</option>
			</select>
		</div>

		<div class="shop-product-button">
			<button class="btn btn-primary d-flex align-items-center justify-content-center" v-on:click="addToCar(item)">
				<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | VES }}</span>
					<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | USD }}</span>
			</button>
		</div>
	</div>
</div>
</div>
</div>
</div>

{{-- PRODUCTOS FILTRADOS POR CATEGORIA --}}
<div class="shop-category-container"  v-for="category in categories.filter(function(i) { return i.id == category_id })" :key="category.id" v-if="!isLoadingCategories && (subcategory_id || category_id)">
<section class="ban-cat">
<img class="img-fluid" alt="Responsive image" src="{{ asset('img/banner-categorias.jpg') }}">
</section>
	<h2 class="BogleWeb-Regular text-center text-uppercase py-5 title-pro" v-cloak><a href="#" v-on:click.prevent="filter(category.id,'')">@{{ category.name }}</a></h2>
	<div v-for="category in categories">
		<div class="row row-categories-filter" style="margin: 0px">
			<div class="col-lg-3 col-md-4 col-sm-6 col-12" v-for="item in category.products" v-cloak>
				<div class="shop-product-container border border-primary" 
				:class="{
				' shop-product-container-blue': item.hover,
				'': !item.hover,
				'in-cart': cart && cart.map(function(i){ return i.id }).indexOf(item.id) != -1
			}"
			v-on:mouseleave="item.hover = false"  
			v-on:mouseover="item.hover = true">
			<template v-if="item.offer">
				<span class="shop-product-offer-dias"><span class="shop-product-offer-by">Por</span> @{{ $diffDaysOffer(item.offer.end) }} día@{{ $diffDaysOffer(item.offer.end) > 1 ? 's' :  '' }}</span>
				<div class="shop-product-offer">
					<img src="{{ asset('img/descuento.svg') }}" alt="Descuento">
					<span class="shop-product-offer-percent">-@{{ item.offer.percentage }}%</span>
				</div>
			</template>
			<div class="shop-product-image p-3" :style="{ backgroundImage: 'url(' + item.image_url + ')' }" v-on:click="gotToProduct(item.id)">
			</div>
			<div class="shop-product-name" v-on:click="gotToProduct(item.id)">
				<span v-cloak>
					@if (\App::getLocale() == 'es') @{{ item.es_name }} @else @{{ item.en_name }} @endif
				</span>
			</div>
			
			<div class="shop-product-presentations" v-if="item.variable">
				<select name="amount_id"  v-model="item.amount_id" id="" class="form-control form-control-sm text-center">
					<option :value="amount.id" v-for="amount in item.amounts" v-cloak>
						@{{ amount.presentation }} @{{ getUnit(amount.unit) }}
					</option>
				</select>
			</div>

			<div class="shop-product-button">
				<button class="btn btn-primary d-flex align-items-center justify-content-center" v-on:click="addToCar(item)">
					<span v-if="currency == 1" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | VES }}</span>
					<span v-if="currency == 2" v-cloak>@{{ getPriceByCurrency($getPrice(item, true), item.coin) | USD }}</span>
				</button>
			</div>
		</div>
	</div>
</div>
</div>
</div>

{{-- <div class="shop-category-container" v-if="isLoadingCategories" style="padding-top: 50px;">
<div class="loader">Loading...</div>
</div> --}}

<div class="shop-category-container" style="padding-top: 10vh; padding-bottom: 10vh;" v-if="categories.length === 0 && !isLoadingCategories" v-cloak>
	<h2 class="BogleWeb-Regular text-center text-uppercase py-4">📦 No se encontraron productos</h2>
</div>

<infinite-loading v-on:infinite="infiniteHandler">
	<span slot="no-more"></span>
	<span slot="no-results"></span>
</infinite-loading>

@include('layouts.footer')

</div>



@include('layouts.sidebar')

</div>

@stop

@section('scripts')
<script src="{{ asset('js/vue-infinite-loading.js') }}"></script>
<script type="text/javascript">
	var vue;
	var unities = [
	{name: 'Gr', id: 1},
	{name: 'Kg', id: 2},
	{name: 'Ml', id: 3},
	{name: 'L', id: 4},
	{name: 'Cm', id: 5}
	]

	const vue_carrito = new Vue({
		el: '#tienda',
		data: {
			unities: [],
			promotions: '{!! json_encode($promotions) !!}',
			minimumDiscount: '{!! json_encode($minimumDiscount) !!}',
			quantityDiscount: '{!! json_encode($quantityDiscount) !!}',
			setCart: "{{ isset($setCart) ? 'true' : 'null' }}",
			category_selected: null,
			currency: currentCurrency,
			categoriesFilters: [],
			discounts: [],
			producto: null,
cart: [], // Este corresponde la los items de carrito en esta pagina
// cart: '@php echo (addslashes(json_encode($carrito))) @endphp',
			paginator: {},
			exchange: '@{{ $_change }}',
			query: '',
			categories: [],
			subcategories: [],
			products_pro: [],
			products_offer: [],
			filtros: [],
			page: 0,
			base_preload: '{{ URL('img/products') }}' + '/',
			form: {
				talla: '',
				color: '',
				cantidad: 1
			},
			isLoading: true,
			isLoadingCategories: true,
			viewCategories: '{{ isset($view_categories) ? true : false }}',
			category_id: '{{ isset($category_id) ? $category_id : '' }}',
			subcategory_id: '{{ isset($subcategory_id) ? $subcategory_id : '' }}',
			minimunPurchase: '{{ $minimunPurchase ? $minimunPurchase : 0 }}',
			showProOnly: {{ isset($showPro) ? $showPro : 0 }},
			showPromotionOnly: {{ isset($showPromotion) ? $showPromotion : 0 }},
			discount_id: {{ isset($discount_id) ? $discount_id : 0 }},
			showOfferOnly: {{ isset($showOffer) ? $showOffer : 0 }},
			pollingForData: false,
			itemModal: null,
			disabled: true,
		},
		created: function() {
			vue = this;
		},
		watch:{
			cart: function(val, oldVal){
				this.updateStorage()
			},
			showPromotionOnly(newVal, oldVal){
				if(this.showPromotionOnly == 1){
					$('.owl-carousel-promotions').owlCarousel('destroy');
				}
			}
		},
		computed: {
			minimun: function() {
				return parseFloat(this.getPriceByCurrency(this.minimunPurchase, 2))
			},
			quantityPurchaseDiscount: function() {
				let total = vue.getSubtotalUsd()
				let percentage = vue.quantityDiscount.percentage
				return total * (percentage / 100)
			},	
		},
			mounted: function() {
				let inCart = localStorage.getItem('cart')
				if(inCart){
					inCart = JSON.parse(localStorage.getItem('cart'))
					vue_header.cart = inCart.length
					this.cart = inCart
				}else{
					this.cart = []
				}

				//Esta condicion solo aplica cuando es redireccion posterior al pago de paypal
				if(this.setCart  && this.setCart == 'true' || '{{ Session('success') }}' != '') {
					this.cart = []
					localStorage.setItem('cart', JSON.stringify(this.cart))
					vue_header.cart = this.cart.length
					vue_cart_modal.newProductAddToCart = !vue_cart_modal.newProductAddToCart
				}
		this.getPromotions()
				this.disabled = false
				this.promotions = JSON.parse(this.promotions)

				this.minimumDiscount = JSON.parse(this.minimumDiscount)
				this.quantityDiscount = JSON.parse(this.quantityDiscount)
				// this.cart = JSON.parse(this.cart)

				if ('{{ Session('success') }}' != '') {
					swal('','Su pedido se ha recibido exitosamente','success');
				} else if ('{{ Session('errors') }}' != '') {
					swal('', 'No se pudo procesar su pedido', 'warning');
				}

				vue.loadFilters()
				vue.loadPro()
				vue.loadDiscounts()
				// vue.load(null, !this.viewCategories, null);

		if(this.viewCategories) {
			vue_header.filtro()
		}
		this.unities = unities
	},
	methods: {
		getPromotions(){
			const url = '{{route('getPromotions')}}'
			axios.get(url).then(res => {
				if(res && res.data && res.data.length > 0){
					this.promotions = res.data
					$('.owl-carousel-promotions').owlCarousel('destroy')
					this.initCarousels()
					this.$forceUpdate()
				}
			})
			.catch(function(err) {
			})
		},
		
		_getPromotionPrice(item){
			let total = 0
			if(item.products && item.products.length > 0){
				item.products.forEach(element => {
					let subtotal = 0
					subtotal = element.amount * (element.product_amount.price - (element.product_amount.price * item.discount_percentage) / 100)
					total += subtotal
				})
			}
			total = this._formatMiles(total)
			return total
		},
		_formatMiles(n) {
			let c, d, t, s, i, j;
			c = isNaN(c = Math.abs(c)) ? 2 : c,
			d = d == undefined ? "." : d,
			t = t == undefined ? "," : t,
			s = n < 0 ? "-" : "",
			i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
			j = (j = i.length) > 3 ? j % 3 : 0;

			return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
		},
		updateStorage(){
			localStorage.setItem('cart', JSON.stringify(this.cart))
		},
		infiniteHandler: function($state) {
			vue.page++
			vue.load(vue.page, !this.viewCategories, $state);
		},
		addQuantity: function(item) {
			if(item.amount.amount == item.cantidad || item.cantidad == item.amount.max) {
				return
			}

			item.cantidad++;
			vue.update(item)
			this.updateStorage()
		},
		verify: function(url) {
			if(vue.getTotal() < vue.minimun) {
				vue.messageMinimun()
				return
			}

			const errorMinItem = this.cart.find(function(item) {
				return item.cantidad < item.amount.min;
			})

			if(errorMinItem) {
				let productName = errorMinItem.producto.name 
				let min = errorMinItem.amount.min
				if(errorMinItem.producto.variable) {
					productName = productName + ' ' + errorMinItem.amount.presentation + vue.getUnit(errorMinItem.amount.unit);
				}

				swal('','No se puede procesar la compra porque el mínimo de compra de ' + productName + ' es de: ' + min + ' unidades','warning');
				return 
			}

			window.location = url
		},
		hasMinimumDiscount: function() {
			return vue.minimumDiscount && vue.getSubtotalUsd() >= vue.minimumDiscount.minimum_purchase
		},
		getTotal: function() {
			var subtotal = vue.getSubtotalUsd()

			if(vue.hasMinimumDiscount()) {
				subtotal = subtotal - vue.getMinimumDiscount()
			}

			if(vue.quantityDiscount) {
				subtotal = subtotal - vue.quantityPurchaseDiscount
			}

			return vue.getPriceByCurrency(subtotal, '2')
		},
		getSubtotalUsd: function() {
			var total = 0;
			this.cart.forEach(function(item, index) {
				let priceCart = vue.$getPriceCart(item)
				total += priceCart;
			});
// vue_header.subtotal = total;
			return total;
		},
		getMinimumDiscount: function() {
			let total = vue.getSubtotalUsd()
			let percentage = vue.minimumDiscount.percentage
			return total * (percentage / 100)
		},
		presentationModal(item){
			this.itemModal = item;
			$("#promotionModal").modal('show');
		},
		toggleFavorite: function(item) {
			const isNoteSession = '{{ Auth::guest() }}'
			if(isNoteSession) {
				$('#loginModal').modal('show')
				return
			}
			const store = '{{ URL('favoritos/store') }}'
			const destroy = '{{ URL('favoritos/destroy') }}'
			const url = item.favorite ? destroy : store
			item.favorite = !item.favorite
			if(isNoteSession) {
				return
			}
			axios.post(url, { product_id: item.id }).catch(function(err) {
				swal('','{{ Lang::get('Page.Error') }}','warning');
			})
		},
		prev: function(categoryId) {
			$('.owl-carousel-' + categoryId)
			.trigger('next.owl.carousel');
		},
		next: function(categoryId) {
			$('.owl-carousel-' + categoryId)
			.trigger('prev.owl.carousel', [300]);
		},
		getPrice: function(producto) {
			if(producto.variable) {
				return producto.amounts.find(function(amount) { return amount.id == producto.amount_id }).price
			} else {
				return producto.price_1
			}
		},
		filtro: function() {
			$('.filtro').fadeIn();
			$('.filtro-container').animate({
				left: '0px'
			},250);
		},
		close: function() {
			vue_header.close()
		},
		getPriceByCurrency: function(precio,coin) {
			var price = precio;
			if (coin == '1' && vue.currency == '2') {
				price = price / vue.exchange;
			}
			else if (coin == '2' && vue.currency == '1') {
				price = price * vue.exchange;
			}
			return price;
		},
		getSubCategory: function(id){
			axios.post(`admin/subcategory/${id}`)
			.then(res => {
				let name=""
				if(res.data.result){
					name= res.data.result
				}else{
					name="nada"
				}
				alert(name)
				return name;
			})
			.catch(function(err) {
			})
		},
		entrar: function() {
			vue_header.filtro()
			$('#loginModal').modal('show')
		},
		selectCategorie: function(categoryId) {
			this.category_selected = categoryId == this.category_selected ? null : categoryId
		},
		showDiscount: function(discountId) {
			window.location = homeUrl + '?discount_id=' + discountId
		},
		showPro: function() {
			window.location = homeUrl + '?showPro=1'
		},
		showPromotion: function() {
			window.location = homeUrl + '?showPromotion=1'
		},
		showOffer: function() {
			window.location = homeUrl + '?showOffer=1'
		},
		getValue: function(productId) {
			return this.cart.find(function(item) { return item.id == productId }).cantidad
		},
		loadCart: function() {
			axios.post('{{ URL('carrito/ajax') }}')
			.then(function(res) {
				if (res.data.result) {
					this.cart = res.data.carrito;
					vue_header.cart = this.cart.length;

				}
			})
			.catch(function(err) {
				swal('','{{ Lang::get('Page.Error') }}','warning');
			})
		},
		_setLookMore(item, value){
			item.showMore = value
			this.$forceUpdate()
		},
		loadFilters() {
			axios.post('{{ URL('tienda/filters') }}').then(res => {
				vue.categoriesFilters = res.data.filters.map(function(category) {
					category.open = true
					return category
				})
			}).catch(function(error) {
			}).then(function() {

			});
		},
		loadPro() {
// setLoader()
			axios.post('{{ URL('tienda/get-pro') }}',{
				showPro: this.showProOnly
			}).then(function(res) {
				vue.products_pro = res.data.products_pro.map(function(product) {
					product.favorite = product.favorites.length > 0
					product.hover = false
					product.quantity = 0 
					product.file_selected = 0;
					product.amounts = product.colors[0].amounts
					const amount = product.amounts[0]
					product.amount_id = amount.id
					return product
				});

				vue.initCarousels()

			}).catch(function(error) {
			}).then(function() {
				this.isLoading = false
// quitLoader();
			});
		},
		loadDiscounts() {
			axios.post('{{ URL('tienda/discounts') }}').then(res => {
				vue.products_offer = res.data.products_offer.map(function(product) {
					product.favorite = product.favorites.length > 0
					product.hover = false
					product.quantity = 0 
					product.file_selected = 0;
					product.amounts = product.colors[0].amounts
					const amount = product.amounts[0]
					product.amount_id = amount.id
					return product
				});

				let discounts = res.data.discounts
				if(vue.discount_id) {
					discounts = discounts.filter(function(discount) {
						return discount.id == vue.discount_id
					})
				}

				vue.discounts = discounts.map(function(discount) {
					discount.products = discount.products.map(function(product) {
						product.favorite = product.favorites.length > 0
						product.hover = false
						product.quantity = 0 
						product.file_selected = 0;
						product.amounts = product.colors[0].amounts
						const amount = product.amounts[0]
						product.amount_id = amount.id
						return product								
					});

					return discount
				})
			}).catch(function(error) {
			}).then(function() {

			});
		},
		load: function(page, loading, $state) {
			vue.page++
			if(page == undefined) {
				page = null
			}

			if(loading == undefined) {
				loading = true
			}

			if (page != null) {
				vue.page = page;
			}

			axios.post('{{ URL('tienda/ajax') }}?page=' + vue.page,{
				subcategory_id: this.subcategory_id,
				category_id: this.category_id,
				query: this.query,
				showPro: this.showProOnly
			})
			.then(function(res) {
				if (res.data.result) {

					const categoryWithProduct = res.data.categories.data.filter(function(category) {
						return category.products.length > 0
					});

					const rawCategoryList = categoryWithProduct.map(function(category) {
						category.products = category.products.map(function(product) {
							product.favorite = product.favorites.length > 0
							product.hover = false
							product.quantity = 0 
							product.file_selected = 0;
							product.amounts = product.colors[0].amounts
							const amount = product.amounts[0]
							product.amount_id = amount.id
							return product								
						});

						return category
					});

					// const catego = rawCategoryList.length > 0 
					// 		? rawCategoryList 
					// 		: vue.categories 

					if(rawCategoryList.length) {
						vue.categories = vue.categories.concat(rawCategoryList)
						$state.loaded()
					} else {
						$state.complete()
					}

					if (vue.categories.length === 0 && vue.query) {
						swal('','{{ Lang::get('Page.Resultados.Busqueda') }}','warning');
					}

					vue.initCarousels()
				}
			})
			.catch(function(err) {
				swal('','{{ Lang::get('Page.Error') }}','warning');
			})
			.then(function() {
				vue.isLoadingCategories = false
			});
		},
		initCarousels: function() {
// $('.owl-carousel').owlCarousel('destroy')
			setTimeout(function() {
				$('.owl-carousel-promotions').owlCarousel({
					loop:false,
					margin:5,
					nav:false,
					dots: false,
					responsive:{
						200: {
							items: 1
						},
						300: {
							items: 1
						},
						400: {
							items: 1
						},
						500:{
							items:2
						},
						600: {
							items: 2
						},
						768:{
							items:3
						},
						1024:{
							items: 3
						},
						1400:{
							items: 3
						}
					},

				})
			}, 0)
			setTimeout(function() {
				$('.owl-carousel').owlCarousel({
					loop:false,
					margin:5,
					nav:false,
					dots: false,
					responsive:{
						200: {
							items: 1
						},
						300: {
							items: 1
						},
						400: {
							items: 1
						},
						500:{
							items:2
						},
						600: {
							items: 2
						},
						768:{
							items:3
						},
						1024:{
							items: 5
						},
						1400:{
							items: 5
						}
					},

				})
			}, 0)

		},
		inCarritoById: function(productId) {
			return this.cart.map(function(item) { return item.id }).indexOf(productId) != -1;
		},
		inCarrito: function(amountId) {
			var cart = this.cart.map(function(item) { return item.amount_id });
			return cart.indexOf(amountId) != -1;
		},
		getCarrito: function() {
			var carrito = vue.carrito.map(function(item) { return item.id });
			return vue.carrito[carrito.indexOf(vue.producto.id)];
		},
		addToCarPromotion(item){
			item.products.forEach((element, i) => {
				let data = {
					... element.product_amount.product,
					promotion_id: item.id,
					isPromotion: true,
					amount_id: element.product_amount.id,
					discount_percentage: item.discount_percentage,
					amounts: [{
						... element.product_amount,
						isPromotion: true,
					}],
					promotion:{
						id: item.id,
						amount: element.amount,
						discount_percentage: item.discount_percentage
					},
					discount:{
						quantity_product: element.amount,
						percentage: item.discount_percentage
					},
					cantidad: element.amount,
					colors: [{
						... element.product_amount.product_color,
						amounts: [{
							... element.product_amount,
							isPromotion: true
						}]
					}]
				}
				this.addToCar(data)
			});
		},
	addToCar: function(producto) { // Este metodo agrega directamente al carrito
						let amount;
					if(producto.variable) {
						amount = producto.amounts.find(function(amount) {
							return amount.id == producto.amount_id
						})
					} else {
						amount = producto.amounts[0]
					}

					let inCartStorage = localStorage.getItem('cart')
					if(inCartStorage) {
						this.cart = JSON.parse(localStorage.getItem('cart'))
					} else {
						this.cart = []
					}

					if(this.inCarrito(amount.id)) {
						swal('', 'Su producto ya esta en el carrito','warning');
						return
					}
		var item = {
			id: producto.id,
			cantidad: producto.cantidad || 1,
			color: producto.colors[0].id,
// talla: producto.categories.sizes[0].id,
			image: producto.image_url,
			producto: producto,
			amount_id: amount.id,
			amount: amount,
			discountAviable: true
		}

		if(amount.amount > 0) {
			const newCart = this.cart.map(function(cartItem) {
				return cartItem
			})
			newCart.push(item)
			this.cart = newCart
		}

		setLoader();
		localStorage.setItem('cart', JSON.stringify(this.cart))
		vue_header.cart = this.cart.length
		vue_cart_modal.newProductAddToCart = !vue_cart_modal.newProductAddToCart
		swal('', 'Producto Agregado exitosamente', 'success')
// const inCart = JSON.parse(localStorage.getItem('cart'))

// axios.post('{{ URL('tienda/add') }}',item)
// 	.then(function(res) {
// 		this.cart.forEach(function(product) {
// 			if(item.amount_id == product.amount_id) {
// 				product.discountAvialable = res.data.discountAvialable
// 			}
// 		})
// 		if (!res.data.result) {
// 			swal('',res.data.error,'warning');
// 		}
// 	})
// 	.catch(function(err) {
// 		swal('','{{ Lang::get('Page.Error') }}','warning');
// 	}).finally(quitLoader)
		quitLoader()
	},
	getSubtotal: function() {
		var total = 0;
		this.cart.forEach(function(item, index) {
let priceCart = vue.$getPriceCart(item) //<--- cantidad * precio
			let pricePriceByCurrency = vue.getPriceByCurrency(priceCart, item.producto.coin)
			total += parseFloat(pricePriceByCurrency.toFixed(2));
		});
		vue_header.subtotal = total;
		return total;
	},
	eliminar: function(key, item) {
		setLoader();
		let newCart = []
		if(item.producto.isPromotion){
			const promotion = this.promotions.find(element => element.id == item.producto.promotion.id)
			if(promotion){
				const ids = promotion.products.map(element => {
					return element.product_id
				});
				this.cart.map(element => {
					const toDelete = ids.find(id => {
						return id == element.amount_id
					})
					if(!toDelete){
						newCart.push(element)
					}
				});
				this.cart = newCart
			}
		}else{
			var newArray = this.cart.filter(function(item, k) {
				return key != k
			})
			newCart = newArray
			this.cart = newArray
		}
		localStorage.setItem('cart', JSON.stringify(newCart))
					vue_header.cart = this.cart.length
		quitLoader();
	},
	checkquantity: function(event, key) {
		const value = event.target.value
		if(value <= 0) {
			const item = this.cart[key]
			item.cantidad = 1
			this.cart[key] = item
		}
	},
	update: function(producto) {
		if(!producto.cantidad) {
			return
		}
		var item = {
			id: producto.id,
			cantidad: producto.cantidad,
			color: producto.color,
			talla: producto.talla,
			amount_id: producto.amount_id
		}
		axios.post('{{ URL('tienda/add') }}',item)
		.catch(function(err) {
		});
	},
	filter: function(category_id, subcategory_id) {
// this.category_id = category_id
// this.subcategory_id = subcategory_id
// this.page = 1; 
// this.close(); 
// this.load()
		window.location = homeUrl + '?category_id=' + category_id + '&' + 'subcategory_id=' + subcategory_id
	},
	addItem: function(productId) {
		const item = this.cart.find(function(item) { return item.id == productId })
		if(item.cantidad < item.amount.amount) {
			item.cantidad++
		}
	},
	subItem: function(productId) {
		const item = this.cart.find(function(item) { return item.id == productId })
		if(item.cantidad > 1) {
			item.cantidad--
		}
	},
	getUnit: function(unit) {
		return this.unities.find(function(u) { return u.id == unit }).name
	},
	gotToProduct: function(id) {
		window.location = "tienda/ver/" + id
	},
	messageMinimun: function() {
		const restante = (this.minimun - this.getTotal()).toFixed(2)
		let faltanteText = ''

		if(this.currency == 1) {
			faltanteText = this.$options.filters.VES(restante)
		} else {
			faltanteText = this.$options.filters.USD(restante)
		}

		swal('', 'Agregue ' + faltanteText + ' para llegar al mínimo de compra', 'warning')
	}
}
})
</script>
@stop
