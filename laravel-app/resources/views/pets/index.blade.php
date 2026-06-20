@extends('layouts.app')

@section('content')
<div class="content-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <div class="section-title">Pet Listing</div>
            <h1 class="h3 mb-0">Search, filter, and sort available pets</h1>
        </div>
        
    </div>

    <form method="GET" action="{{ route('pets.index') }}" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Search by name, breed, species">
        </div>
        <div class="col-md-2">
            <input type="text" name="species" value="{{ request('species') }}" class="form-control" placeholder="Species">
        </div>
        <div class="col-md-2">
            <input type="text" name="breed" value="{{ request('breed') }}" class="form-control" placeholder="Breed">
        </div>
        <div class="col-md-1">
            <input type="number" name="age" value="{{ request('age') }}" class="form-control" placeholder="Age">
        </div>
        <div class="col-md-2">
            <select name="vaccination_status" class="form-select">
                <option value="">Vaccination</option>
                <option value="VACCINATED" @selected(request('vaccination_status') === 'VACCINATED')>Vaccinated</option>
                <option value="PARTIAL" @selected(request('vaccination_status') === 'PARTIAL')>Partial</option>
                <option value="NOT_VACCINATED" @selected(request('vaccination_status') === 'NOT_VACCINATED')>Not Vaccinated</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="adoption_status" class="form-select">
                <option value="">Adoption Status</option>
                <option value="AVAILABLE" @selected(request('adoption_status') === 'AVAILABLE')>Available</option>
                <option value="PENDING" @selected(request('adoption_status') === 'PENDING')>Pending</option>
                <option value="ADOPTED" @selected(request('adoption_status') === 'ADOPTED')>Adopted</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                <option value="age" @selected(request('sort') === 'age')>Age</option>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-success">Apply Filters</button>
        </div>
    </form>
</div>

<div class="row g-4">
    @forelse($pets as $pet)
        <div class="col-md-6 col-xl-4">
            <div class="card content-card h-100 border-0 overflow-hidden">
                <img src="{{ $pet->image_path ?: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=900&q=80' }}" class="card-img-top pet-cover" alt="{{ $pet->pet_name }}">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h2 class="h5 mb-0">{{ $pet->pet_name }}</h2>
                        <span class="badge badge-soft">{{ $pet->adoption_status }}</span>
                    </div>
                    <p class="text-secondary mb-1">{{ $pet->species }} | {{ $pet->breed }}</p>
                    <p class="text-secondary mb-3">Age: {{ $pet->age }} | Vaccination: {{ $pet->vaccination_status }}</p>
                    <div class="mt-auto d-flex gap-2">
                        <a href="{{ route('pets.show', $pet) }}" class="btn btn-outline-success btn-sm">Details</a>
                        @if($pet->adoption_status === 'AVAILABLE')
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
        <div class="col-12">
            <div class="alert alert-light border">No pets found for the selected filters.</div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $pets->links() }}
</div>
@endsection
