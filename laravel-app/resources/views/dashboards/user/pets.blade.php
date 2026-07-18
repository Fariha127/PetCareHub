@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.user.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">My Adopted Pets</h1>
            <p class="text-secondary mb-0">Here are the loving pets you have adopted into your family.</p>
        </div>

        <div class="row g-4">
            @forelse($myPets ?? [] as $pet)
                <div class="col-md-6 col-lg-4">
                    <div class="card content-card h-100 border-0 overflow-hidden shadow-sm">
                        <img src="{{ $pet->image_path ?: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=900&q=80' }}" 
                             class="card-img-top pet-cover" 
                             alt="{{ $pet->pet_name }}">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h5 mb-2 text-success">{{ $pet->pet_name }}</h3>
                            <p class="text-secondary mb-1 small">
                                <strong>Species:</strong> {{ $pet->species }}
                            </p>
                            <p class="text-secondary mb-1 small">
                                <strong>Breed:</strong> {{ $pet->breed ?: 'N/A' }}
                            </p>
                            <p class="text-secondary mb-1 small">
                                <strong>Age:</strong> {{ $pet->age }} years | <strong>Gender:</strong> {{ $pet->gender }}
                            </p>
                            <p class="text-secondary mb-3 small">
                                <strong>Vaccination:</strong> {{ $pet->vaccination_status }}
                            </p>
                            
                            <div class="mt-auto">
                                <a href="{{ route('pets.show', $pet) }}" class="btn btn-outline-success btn-sm w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card content-card border-0 text-center p-5 shadow-sm">
                        <h3 class="h5 fw-bold text-secondary mb-2">No Adopted Pets Yet</h3>
                        <p class="text-secondary mb-4">You haven't adopted any pets yet. Browse our available pets and submit an adoption request today!</p>
                        <div>
                            <a href="{{ route('pets.index') }}" class="btn btn-success">Browse Available Pets</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
