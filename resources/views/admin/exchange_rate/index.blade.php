@extends('layouts.admin')

@section('title', 'Tasa de Cambio')

@section('content')
    <change-index :changes="{{ $changes }}"></change-index>
@endsection