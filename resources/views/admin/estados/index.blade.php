@extends('layouts.admin')

@section('title', 'Estados')

@section('content')
<estado-index :estados="{{ $estados }}" ></estado-index>
@endsection