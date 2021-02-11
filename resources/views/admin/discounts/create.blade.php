@extends('layouts.admin')

@section('title', 'Crear Descuento')

@section('content')
<discount-create :categories="{{ $categories }}" :products="{{ $products }}"></discount-create>
@endsection