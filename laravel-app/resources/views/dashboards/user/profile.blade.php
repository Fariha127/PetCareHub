@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.user.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">My Profile</h1>
            <p class="text-secondary mb-0">Manage your personal information and contact details.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="content-card p-4 text-center">
                    <div class="mb-3">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ auth()->user()->profile_photo }}" class="rounded-circle border border-3 border-success shadow-sm" style="width: 140px; height: 140px; object-fit: cover;" alt="Profile Picture">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border border-3 border-success mx-auto shadow-sm" style="width: 140px; height: 140px; font-size: 3rem; color: var(--accent);">
                                {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h3 class="h5 fw-bold mb-1">{{ auth()->user()->full_name }}</h3>
                    <span class="badge badge-soft px-3 py-2 rounded-pill">{{ auth()->user()->role }}</span>
                </div>
            </div>

            <div class="col-md-8">
                <div class="content-card p-4 h-100 d-flex flex-column">
                    <h2 class="h5 mb-3 text-success">Personal Information</h2>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Full Name</span>
                            <strong class="fs-5">{{ auth()->user()->full_name }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Email Address</span>
                            <strong class="fs-5">{{ auth()->user()->email }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Phone Number</span>
                            <strong class="fs-5">{{ auth()->user()->phone ?: 'Not provided' }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary small d-block">Home Address</span>
                            <strong class="fs-5">{{ auth()->user()->address ?: 'Not provided' }}</strong>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            Edit Profile Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 0.8rem;">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-success" id="editProfileModalLabel">Edit Profile Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Full Name</label>
                        <input name="full_name" class="form-control" value="{{ auth()->user()->full_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Phone Number</label>
                        <input name="phone" class="form-control" value="{{ auth()->user()->phone }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Address</label>
                        <input name="address" class="form-control" value="{{ auth()->user()->address }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Profile Photo (File Upload)</label>
                        <input type="file" name="profile_photo_file" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Or Photo URL</label>
                        <input type="url" name="profile_photo_url" class="form-control" placeholder="https://..." value="{{ filter_var(auth()->user()->profile_photo, FILTER_VALIDATE_URL) ? auth()->user()->profile_photo : '' }}">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success btn-sm px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
