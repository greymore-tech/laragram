@extends('layouts.master')

@section('nav')
    <nav class="navbar-expand-lg navbar-light bg-light nav-pad">
        <a class="navbar-brand" href="{{ url('dashboard') }}"><h4>Laragram</h4></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="float-right pt-2">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="links">
                    <a href="{{ url('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <dashboard :contacts="{{ $contacts }}"></dashboard>
@endsection
