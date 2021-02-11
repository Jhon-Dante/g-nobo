@extends('layouts.admin')

@section('title', 'Promociones')

@section('content')
    <promotions-edit :promotion="{{ $promotion }}" :categories="{{ $categories }}" :product-amounts="{{ $productAmounts }}"></promotions-edit>
@endsection