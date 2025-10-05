@extends('layouts.master')

@section('nav')
    <nav class="navbar navbar-expand-lg navbar-light bg-light nav-pad">
        <a class="navbar-brand" href="{{ url('dashboard') }}"><h4>Laragram</h4></a>
        <div class="ms-auto d-flex align-items-baseline">
            <p class="me-3">Hello, {{ $current_user_first_name }}</p>
            <a href="{{ url('logout') }}">Logout</a>
        </div>
    </nav>
@endsection

@section('content')
    <dashboard :messages="{{ $messages }}" :chats="{{ $chats }}" :users="{{ $users }}" :current_user_id="{{ $current_user_id }}"></dashboard>
@endsection
