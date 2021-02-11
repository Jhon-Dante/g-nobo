@extends('layouts.admin')

@section('title', 'Descuentos')

@section('content')
<discount-index :discounts="{{ $discounts }}" :categories="{{ $categories }}" :products="{{ $products }}"></offer-discount>
@endsection