@extends('layouts.admin')

@section('title', 'Editar Impuesto')

@section('content')
<taxe-edit :taxe="{{ $taxe }}"></taxe-edit>
@endsection