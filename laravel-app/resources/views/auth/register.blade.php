@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <div class="hero-panel p-4 p-lg-5 h-100">
                    <div class="section-title mb-2">New account</div>
                    <h1 class="display-6 fw-bold mb-3">Join the PetCareHub workspace</h1>
                    <p class="text-secondary mb-4">Create an account as an adopter, shelter staff member, vet, or admin to start using role-based workflows.</p>
                    <p class="small text-secondary mb-0">Already registered? <a href="{{ route('login') }}" class="link-success">Login here</a>.</p>
                </div>
            </div>
            <div class="col-lg-7">
        <div class="content-card p-4 p-lg-5">
            <div class="section-title mb-2">Create account</div>
            <h1 class="h3 fw-bold mb-4">Register for PetCareHub</h1>
            <form>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" placeholder="Your name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select class="form-select">
                            <option>User/Adopter</option>
                            <option>Shelter Staff</option>
                            <option>Veterinarian</option>
                            <option>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="name@example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" placeholder="Phone number">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" placeholder="Street, city">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm password">
                    </div>
                </div>
                <button class="btn btn-success w-100 mt-4">Register</button>
            </form>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
