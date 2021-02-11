@extends('layouts.admin')

@section('title', 'Nosotros')

@section('content')
    <us-index :info="{{ $us != null ? $us : '{}' }}"></us-index>
@endsection