@extends('layouts.master')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-icon">
                <i class="fa-solid fa-paper-plane"></i>
            </div>
            <h2>Welcome to Laragram</h2>
            <p class="text-secondary">
                A fast and modern web client for Telegram, built with Laravel and Vue.
            </p>
            <div style="margin-top: 30px;">
                <a href="{{ route('auth.login') }}" class="btn btn-primary">
                    Login with Telegram
                </a>
            </div>
        </div>
    </div>
@endsection
