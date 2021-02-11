@extends('layouts.admin')

@section('title', 'Tallas')

@section('content')
    <size-index url="{{ url('/admin') }}" :sizes="{{ $sizes }}"></size-index>
@endsection