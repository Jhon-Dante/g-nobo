@extends('layouts.admin')

@section('title', 'Crear Oferta')

@section('content')
<offer-create :categories="{{ $categories }}" :products="{{ $products }}"></offer-create>
@endsection