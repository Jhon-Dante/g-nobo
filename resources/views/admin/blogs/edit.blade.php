@extends('layouts.admin')
@section('title', "Editar Blogs")
@section('content')
<blog-edit :set-form="{{$posts}}" :set-images="{{$images}}"></blog-edit>
@endsection