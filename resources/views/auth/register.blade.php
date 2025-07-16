@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Organize your day</h2>
                <p class="text-muted">Stay FOCUSED. Stay productive.</p>
            </div>

            <div class="auth-card p-4 p-md-5">
                <h3 class="text-center mb-4">Sign Up</h3>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" type="text" class="form-control auth-input @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Enter Email</label>
                        <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Create Password</label>
                        <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control auth-input" 
                               name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                        Sign Up
                    </button>

                    <div class="text-center mt-4">
                        <p class="text-muted">Already have an account? 
                            <a href="{{ route('login') }}" class="text-primary">Login</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .auth-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
    }
    
    .auth-input {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border);
        transition: all 0.2s;
    }
    
    .auth-input:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    
    .btn-primary {
        background-color: var(--primary);
        border: none;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-light);
    }
    
    .divider {
        display: flex;
        align-items: center;
        color: var(--text-light);
        font-size: 0.875rem;
    }
    
    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid var(--border);
        margin: 0 0.5rem;
    }
</style>
@endsection