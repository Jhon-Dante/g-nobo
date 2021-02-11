@extends('layouts.admin')

@section('title', 'Municipios')

@section('content')
<municipality-index :estado="{{ $estado }}" :municipalities="{{ $municipalities }}" :estados="{{ $estados }}" :estado-id="{{ $estadoId }}"></municipality-index>
@endsection