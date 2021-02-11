@extends('layouts.admin')
@section('title','Blogs')
@section('content')
    <blog-index url="{{ url('/admin/blogs') }}" :posts="{{ $posts }}"></blog-index>
@endsection