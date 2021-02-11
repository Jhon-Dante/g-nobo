@extends('layouts.admin')
@section('title','Perfil')
@section('content')
    <profile-form url="{{ url('/admin/profile') }}" :user="{{ Auth::user() }}"></profile-form>
@endsection