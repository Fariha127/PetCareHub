@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @if(auth()->user()->role === 'SHELTER_STAFF' || auth()->user()->role === 'ADMIN')
            @include('dashboards.shelter.sidebar')
        @elseif(auth()->user()->role === 'VETERINARIAN')
            @include('dashboards.vet.sidebar')
        @else
            @include('dashboards.user.sidebar')
        @endif
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Change Password</h1>
            <p class="text-secondary mb-0">Update your account password to keep your account secure.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="content-card p-4">
            <form action="{{ route('dashboard.password.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label text-secondary small fw-bold">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-secondary small fw-bold">New Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label text-secondary small fw-bold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>
                <button class="btn btn-success mt-2">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
