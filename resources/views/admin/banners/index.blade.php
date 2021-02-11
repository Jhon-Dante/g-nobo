@extends('layouts.admin')
@section('title','Banners')
@section('content')
    <banners-index url="{{ url('/admin/banners') }}" :banners="{{ $banners }}"></banners-index>
@endsection