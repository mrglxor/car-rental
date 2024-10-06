@extends('layout.app')
@section('sidebar')
    @include('layout.sidebar.admin')
@endsection
@section('content')
    <h1>Dashboard Admin</h1>
    <p>Wellcome {{ Auth::user()->name }} - {{ Auth::user()->role }}</p>
@endsection