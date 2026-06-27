@extends('layouts.app')

@section('content')
<div class="hero-panel p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-7">
            <div class="section-title mb-2">Pet Adoption & Veterinary Care Management</div>
            <h1 class="display-5 fw-bold">Manage pet adoption, shelter operations, and veterinary records for one large shelter.</h1>
            <p class="lead mt-3 text-secondary">PetCareHub is designed for Oracle SQL, relational modeling, and course-level reporting with a clean Bootstrap interface.</p>
            <div class="d-flex gap-2 flex-wrap mt-4">
                <a href="{{ route('pets.index') }}" class="btn btn-success btn-lg">Browse Pets</a>
                <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg">Create Account</a>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="content-card p-4">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light">
                            <div class="fs-2 fw-bold text-success">{{ $stats['totalPets'] ?? 0 }}</div>
                            <div class="text-secondary">Total Pets</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light">
                            <div class="fs-2 fw-bold text-success">{{ $stats['adoptedPets'] ?? 0 }}</div>
                            <div class="text-secondary">Adopted</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light">
                            <div class="fs-2 fw-bold text-success">{{ $stats['pendingRequests'] ?? 0 }}</div>
                            <div class="text-secondary">Pending Requests</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light">
                            <div class="fs-2 fw-bold text-success">{{ $stats['vaccinatedPets'] ?? 0 }}</div>
                            <div class="text-secondary">Vaccination Records</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Featured Pets</h2>
            <a href="{{ route('pets.index') }}" class="link-success text-decoration-none">View all</a>
        </div>
    </div>

    @forelse($featuredPets ?? [] as $pet)
        <div class="col-md-4">
            <div class="card content-card h-100 border-0 overflow-hidden">
                <img src="{{ $pet->photo_url }}" class="card-img-top pet-cover @if($pet->pet_name === 'Bella') pet-cover--face-top @endif" alt="{{ $pet->pet_name }}">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="h5 mb-0">{{ $pet->pet_name }}</h3>
                        <span class="badge badge-soft">{{ $pet->adoption_status }}</span>
                    </div>
                    <p class="text-secondary mb-1">{{ $pet->species }} | {{ $pet->breed }}</p>
                    <p class="text-secondary mb-3">Age: {{ $pet->age }} | Vaccination: {{ $pet->vaccination_status }}</p>
                    <div class="mt-auto d-flex gap-2">
                        <a href="{{ route('pets.show', $pet) }}" class="btn btn-outline-success btn-sm">Details</a>
                        @if(auth()->check() && auth()->user()->role === 'USER' && $pet->adoption_status === 'AVAILABLE')
                            <form action="{{ route('adoptions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="pet_id" value="{{ $pet->pet_id }}">
                                <button class="btn btn-success btn-sm">Request Adoption</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        @php
            $samplePets = [
                [
                    'pet_name' => 'Luna',
                    'species' => 'Dog',
                    'breed' => 'Labrador Mix',
                    'adoption_status' => 'AVAILABLE',
                    'image' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=900&q=80',
                ],
                [
                    'pet_name' => 'Milo',
                    'species' => 'Cat',
                    'breed' => 'Tabby',
                    'adoption_status' => 'AVAILABLE',
                    'image' => 'https://images.unsplash.com/photo-1519052537078-e6302a4968d4?auto=format&fit=crop&w=900&q=80',
                ],
                [
                    'pet_name' => 'Coco',
                    'species' => 'Rabbit',
                    'breed' => 'Mini Lop',
                    'adoption_status' => 'AVAILABLE',
                    'image' => 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?auto=format&fit=crop&w=900&q=80',
                ],
            ];
        @endphp

        @foreach($samplePets as $pet)
            <div class="col-md-4">
                <div class="card content-card h-100 border-0 overflow-hidden">
                    <img src="{{ $pet['image'] }}" class="card-img-top pet-cover" alt="{{ $pet['pet_name'] }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="h5 mb-0">{{ $pet['pet_name'] }}</h3>
                            <span class="badge badge-soft">{{ $pet['adoption_status'] }}</span>
                        </div>
                        <p class="text-secondary mb-1">{{ $pet['species'] }} | {{ $pet['breed'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    @endforelse
</div>
@endsection
