@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card border-0 shadow-lg rounded-4 p-5" style="width: 100%; max-width: 450px;">
        <div class="text-center mb-5">
            <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                <i data-lucide="lock" class="size-8 text-primary"></i>
            </div>
            <h2 class="fw-bold text-dark">Admin Access</h2>
            <p class="text-muted small">Please enter your credentials to manage the EstatePro platform.</p>
        </div>
        
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="username" class="form-label small fw-bold text-muted">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i data-lucide="user" class="size-4 text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 py-3 bg-light @error('username') is-invalid @enderror" id="username" name="username" required autofocus value="{{ old('username') }}" placeholder="admin">
                </div>
                @error('username')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-5">
                <label for="password" class="form-label small fw-bold text-muted">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i data-lucide="key" class="size-4 text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 py-3 bg-light @error('password') is-invalid @enderror" id="password" name="password" required placeholder="••••••••">
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                Sign In to Dashboard
            </button>
        </form>
        
        <div class="text-center mt-5">
            <a href="{{ route('home') }}" class="text-muted small text-decoration-none">
                <i data-lucide="arrow-left" class="size-3 me-1"></i> Back to Public Site
            </a>
        </div>
    </div>
</div>
@endsection
