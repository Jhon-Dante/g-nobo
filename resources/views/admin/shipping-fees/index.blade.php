@extends('layouts.admin')

@section('title', 'Tasa de Envio')

@section('content')
    <shipping-fees-index 
        :shipping-fees="{{ $shippingFees }}" 
        :minimun-purchase="{{ $minimunPurchase }}"
    >
    </shipping-fees-index>
@endsection