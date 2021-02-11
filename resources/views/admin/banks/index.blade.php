@extends('layouts.admin')
@section('title','Cuentas bancarias')
@section('content')
<bank-index url="{{ url('/admin/banks') }}" :accounts="{{ $accounts }}" :banks="{{ $banks }}"></bank-index>
@endsection