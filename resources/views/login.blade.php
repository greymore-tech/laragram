@extends('layouts.master')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-icon">
                <i class="fa-solid fa-mobile-screen-button"></i>
            </div>
            <h2>Sign In to Telegram</h2>

            <form method="POST" action="{{ route('auth.login.check') }}" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="phone" class="form-label mb-1">Phone Number</label>
                    <input id="phone" type="text" class="form-control" name="phone" required autofocus placeholder="+1234567890">
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        Send Code
                    </button>
                </div>
            </form>

            <p class="auth-note">
                Please enter your number in the international format.<br>
                (e.g., `+91` for India, `+1` for USA)
            </p>
        </div>
    </div>
@endsection
