@extends('layouts.admin')

@section('title', 'Descuentos')

@section('content')
<discount-edit :discount="{{ $discount }}"  :categories="{{ $categories }}" :products="{{ $products }}"></discount-edit>
@endsection