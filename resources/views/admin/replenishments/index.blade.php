@extends('layouts.admin')

@section('title', 'Reposicion de Inventario')

@section('content')
    <replenishments-index :products="{{$products}}"></replenishments-index>
@endsection