@extends('layouts.admin')

@section('title','Banners')

@section('content')
    <banners-create url="{{ url('/admin/banners') }}"></banners-create>
@endsection