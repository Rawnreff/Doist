@extends('layouts.app')

@section('content')
<div class="auth-container">
    <!-- Left Side - Brand Section -->
    <div class="auth-left">
        <div class="brand-content">
            <h1 class="brand-title">Organize your day</h1>
            <h2 class="brand-subtitle">Stay <span class="highlight">FOCUSED</span>. Stay productive.</h2>
            <p class="brand-description">Stay on track. Every Day</p>
        </div>
        
        <!-- Book Stack Illustration -->
        <div class="book-stack">
            <div class="book book-1"></div>
            <div class="book book-2"></div>
            <div class="book book-3"></div>
            <div class="book book-4"></div>
            <div class="book book-5"></div>
            <div class="book book-6"></div>
            <div class="star star-1">✦</div>
            <div class="star star-2">✦</div>
            <div class="star star-3">✦</div>
        </div>
    </div>

    <!-- Right Side - Form Section -->
    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-header">
                <h3 class="auth-title">Sign Up</h3>
            </div>

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Enter Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Create Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" 
                           name="password_confirmation" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn-auth">
                    Sign Up
                </button>

                <div class="auth-footer">
                    <p>Already have an account? 
                        <a href="{{ route('login') }}" class="auth-link">Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ===== AUTH PAGES STYLING ===== */
.auth-container {
    display: flex;
    min-height: 100vh;
    height: 100vh;
    background: white;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
}

.auth-left {
    flex: 1;
    background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 50%, #c084fc 100%);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    padding: 4rem 3rem;
    position: relative;
    overflow: hidden;
    height: 100vh;
}

.brand-content {
    z-index: 2;
    max-width: 500px;
}

.brand-title {
    font-size: 3rem;
    font-weight: 300;
    color: white;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.brand-subtitle {
    font-size: 3rem;
    font-weight: 300;
    color: white;
    margin-bottom: 2rem;
    line-height: 1.2;
}

.highlight {
    font-weight: 700;
}

.brand-description {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 300;
}

.book-stack {
    position: absolute;
    bottom: 10%;
    right: 10%;
    width: 300px;
    height: 250px;
    z-index: 1;
}

.book {
    position: absolute;
    border-radius: 4px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.book-1 {
    width: 120px;
    height: 20px;
    background: #10b981;
    bottom: 0;
    left: 50px;
    transform: rotate(-2deg);
}

.book-2 {
    width: 110px;
    height: 18px;
    background: #f59e0b;
    bottom: 20px;
    left: 60px;
    transform: rotate(1deg);
}

.book-3 {
    width: 115px;
    height: 22px;
    background: #3b82f6;
    bottom: 40px;
    left: 55px;
    transform: rotate(-1deg);
}

.book-4 {
    width: 125px;
    height: 19px;
    background: #ef4444;
    bottom: 65px;
    left: 45px;
    transform: rotate(2deg);
}

.book-5 {
    width: 105px;
    height: 17px;
    background: #8b5cf6;
    bottom: 85px;
    left: 65px;
    transform: rotate(-1deg);
}

.book-6 {
    width: 130px;
    height: 21px;
    background: #06b6d4;
    bottom: 105px;
    left: 40px;
    transform: rotate(1deg);
}

.star {
    position: absolute;
    color: rgba(255, 255, 255, 0.6);
    font-size: 1.2rem;
    animation: twinkle 2s infinite;
}

.star-1 {
    top: 20%;
    left: 20%;
    animation-delay: 0s;
}

.star-2 {
    top: 60%;
    left: 10%;
    animation-delay: 0.7s;
}

.star-3 {
    top: 30%;
    right: 30%;
    animation-delay: 1.4s;
}

@keyframes twinkle {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}

.auth-right {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background: white;
    height: 100vh;
    overflow-y: auto;
}

.auth-card {
    width: 100%;
    max-width: 400px;
    padding: 2rem;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-title {
    font-size: 2rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.auth-form .form-group {
    margin-bottom: 1.5rem;
}

.auth-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    color: #374151;
    font-weight: 500;
}

.auth-form input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s;
    background: #f9fafb;
}

.auth-form input:focus {
    outline: none;
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    background: white;
}

.btn-auth {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 1rem;
}

.btn-auth:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.auth-footer {
    text-align: center;
    margin-top: 2rem;
    color: #6b7280;
}

.auth-link {
    color: #8b5cf6;
    font-weight: 500;
    text-decoration: none;
}

.auth-link:hover {
    text-decoration: underline;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc2626;
}

/* Responsive Design */
@media (max-width: 768px) {
    .auth-container {
        flex-direction: column;
    }
    
    .auth-left {
        min-height: 40vh;
        height: 40vh;
        padding: 2rem;
    }
    
    .brand-title,
    .brand-subtitle {
        font-size: 2rem;
    }
    
    .book-stack {
        display: none;
    }
    
    .auth-right {
        padding: 1rem;
        height: 60vh;
    }
}
</style>
@endsection