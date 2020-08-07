@extends('layouts.master')

@section('nav')
    <nav class="navbar-expand-lg navbar-light bg-light pr-5 pl-5 pt-2 pb-2">
        <a class="navbar-brand" href="{{ url('/') }}"><strong>Laragram</strong></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="float-right pt-2">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="links">
                    <a href="{{ url('login') }}">Login</a>
                </div>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <login></login>
@endsection
