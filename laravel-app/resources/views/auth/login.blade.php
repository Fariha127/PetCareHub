@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="hero-panel p-4 p-lg-5 h-100">
                    <div class="section-title mb-2">Account access</div>
                    <h1 class="display-6 fw-bold mb-3">Welcome back to PetCareHub</h1>
                    <p class="text-secondary mb-4">Sign in to review adoptions, update pet records, and track shelter or veterinary activity.</p>
                    <ul class="list-unstyled mb-0 text-secondary small">
                        <li class="mb-2">Secure role-based access</li>
                        <li class="mb-2">Unified dashboard for pets and care</li>
                        <li>Fast reporting for course demos</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
        <div class="content-card p-4 p-lg-5">
            <div class="section-title mb-2">Welcome back</div>
            <h1 class="h3 fw-bold mb-4">Login to PetCareHub</h1>
            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button class="btn btn-success w-100">Login</button>
            </form>
            <p class="text-secondary mt-3 mb-0">Need an account? <a href="{{ route('register') }}" class="link-success">Register</a></p>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
