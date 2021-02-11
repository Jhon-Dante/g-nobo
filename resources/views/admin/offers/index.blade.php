@extends('layouts.admin')

@section('title', 'Ofertas')

@section('content')
<offer-index :offers="{{ $offers }}" :categories="{{ $categories }}" :products="{{ $products }}"></offer-index>
@endsection