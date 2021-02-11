@extends('layouts.admin')

@section('title', 'Crear Promocion')

@section('content')
    <promotions-create :categories="{{ $categories }}"></promotions-create>
@endsection