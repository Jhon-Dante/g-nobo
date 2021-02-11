@extends('layouts.admin')

@section('title', 'Colecciones')

@section('content')
    <collection-index :collections="{{ $collections }}"></collection-index>
@endsection