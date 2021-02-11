@extends('layouts.admin')
@section('title', 'Contacto')
@section('content')
<social-edit :posts="{{ $social }}" url="{{ url('') }}"></social-edit>
@endsection