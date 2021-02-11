@extends('layouts.admin')

@section('title', 'Aliados')

@section('content')
    <allies-index :allies="{{ $allies }}"></allies-index>
@endsection