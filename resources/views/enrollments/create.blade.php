@extends('layouts.app')
 
@section('title', 'create')
 
@section('content')
    <create-vue
        courses="{{$courses}}"
        users="{{$users}}"
        status="{{$status}}"
    />
@endsection