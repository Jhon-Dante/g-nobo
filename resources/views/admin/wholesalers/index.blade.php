@extends('layouts.admin')
@section('title','Colecciones juríducas')
@section('content')
<wholesaler-index :wholesaler="{{ $wholesalers }}"></wholesaler-index>
@endsection