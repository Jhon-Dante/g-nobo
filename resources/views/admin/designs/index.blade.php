@extends('layouts.admin')

@section('title', 'Diseños')

@section('content')
    <design-index :designs="{{ $designs }}"></design-index>
@endsection