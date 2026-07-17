@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.shelter.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Register New Pet</h1>
            <p class="text-secondary mb-0">Record a newly admitted animal in the database catalog.</p>
        </div>

        <div class="content-card p-4">
            <form action="{{ route('pets.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Pet Name</label>
                    <input name="pet_name" class="form-control" required placeholder="e.g. Bruno">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Species</label>
                    <select name="species" class="form-select" required>
                        <option value="">Select...</option>
                        <option value="Dog">Dog</option>
                        <option value="Cat">Cat</option>
                        <option value="Bird">Bird</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Breed</label>
                    <input name="breed" class="form-control" placeholder="e.g. Golden Retriever">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary small fw-bold">Age (Years)</label>
                    <input type="number" name="age" step="0.1" class="form-control" required placeholder="e.g. 2">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary small fw-bold">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select...</option>
                        <option value="MALE">Male</option>
                        <option value="FEMALE">Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary small fw-bold">Vaccination Status</label>
                    <select name="vaccination_status" class="form-select" required>
                        <option value="NOT_VACCINATED">Not Vaccinated</option>
                        <option value="PARTIALLY_VACCINATED">Partially Vaccinated</option>
                        <option value="FULLY_VACCINATED">Fully Vaccinated</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label text-secondary small fw-bold">Photo URL</label>
                    <input type="url" name="image_path" class="form-control" placeholder="https://images.unsplash.com/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Food Preference</label>
                    <input name="food_preference" class="form-control" placeholder="e.g. Dry kibble, chicken breast">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Distinct Habit</label>
                    <input name="distinct_habit" class="form-control" placeholder="e.g. Loves running, fetches tennis balls">
                </div>
                <div class="col-12">
                    <label class="form-label text-secondary small fw-bold">Description & Biography</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Tell us more about the pet's temperament, health status, and adoption needs..."></textarea>
                </div>
                <div class="col-12 d-grid d-md-block">
                    <button class="btn btn-success px-4">Register Pet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
