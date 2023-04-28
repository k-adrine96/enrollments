@extends('layouts.app')
 
@section('title', 'edit')
 
@section('content')
    <edit-vue
        enrollment="{{ $enrollment }}"
        courses="{{$courses}}"
        users="{{$users}}"
        status="{{$status}}"
    />
@endsection