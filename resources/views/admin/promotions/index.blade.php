@extends('layouts.admin')

@section('title', 'Promociones')

@section('content')
    <promotions-index :promotions="{{ $promotions }}" ></promotions-index>
@endsection