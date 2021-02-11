@extends('layouts.admin')
@section('title', 'Terminos y condiciones')
@section('content')
    <term-index url="{{ url('admin/terms') }}" :terms="{{ $terms }}"  :conditions="{{ $conditions }}"></term-index>  
@endsection