@extends('layouts.master')

@section('nav')
    <nav class="navbar navbar-expand-lg navbar-light bg-light nav-pad">
        <a class="navbar-brand" href="{{ url('/') }}"><h4>Laragram</h4></a>
        <div class="ms-auto">
             <a href="{{ url('login') }}">Login</a>
        </div>
    </nav>
@endsection

@section('content')
    <login-code-check></login-code-check>
@endsection
