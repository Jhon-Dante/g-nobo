@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
    <product-index :taxes="{{ $taxes }}" :categories="{{ $categories }}" :designs="{{ $designs }}" :collections="{{ $collections }}" ></product-index>
@endsection