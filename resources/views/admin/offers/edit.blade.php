@extends('layouts.admin')

@section('title', 'Editar Oferta')

@section('content')
<offer-edit :offer="{{ $offer }}" :categories="{{ $categories }}" :products="{{ $products }}"></offer-edit>
@endsection