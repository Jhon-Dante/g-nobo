@extends('layouts.admin')
@section('title', 'Contacto')
@section('content')
    <social-index url="{{ url('admin/social') }}" :posts="{{ $social }}"></social-index>  
@endsection