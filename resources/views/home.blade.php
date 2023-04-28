@extends('layouts.app')
 
@section('title', 'home')
 
@section('content')
    <enrollments-vue data="{{$records}}"/>
@endsection