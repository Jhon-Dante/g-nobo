@extends('layouts.admin')

@section('title', 'Clientes')

@section('content')
    <client-index :clients="{{ $clients }}" :states="{{ $states }}"></client-index>
@endsection