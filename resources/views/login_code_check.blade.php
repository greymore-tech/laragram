@extends('layouts.master')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2>Enter Verification Code</h2>

            <form method="POST" action="{{ route('auth.login.code.check') }}" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="secret_code" class="form-label mb-1">Code</label>
                    <input id="secret_code" type="text" class="form-control" name="secret_code" required autofocus placeholder="Enter the code here">
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>
                </div>
            </form>

            <p class="auth-note">
                We've sent a login code to your Telegram app.
            </p>
        </div>
    </div>
@endsection
