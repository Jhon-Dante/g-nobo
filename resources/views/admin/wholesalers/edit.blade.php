@extends('layouts.admin')
@section('title','Editar colleccione mayoristas')
@section('content')
    <wholesaler-edit :wholesaler="{{ $wholesaler }}"></wholesaler-edit>
@endsection