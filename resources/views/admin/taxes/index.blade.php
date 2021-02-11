@extends('layouts.admin')

@section('title', 'Impuestos')

@section('content')
<taxe-index :taxes="{{ $taxes }}"></taxe-index>
@endsection