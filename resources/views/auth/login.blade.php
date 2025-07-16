@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Welcome back</h2>
                <p class="text-muted">Stay FOCUSED. Stay productive.</p>
            </div>

            <div class="auth-card p-4 p-md-5">
                <h3 class="text-center mb-4">Login</h3>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                        Login
                    </button>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-primary">Forgot Your Password?</a>
                        </div>
                    @endif

                    <div class="text-center mt-4">
                        <p class="text-muted">Don't have an account? 
                            <a href="{{ route('register') }}" class="text-primary">Sign Up</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection