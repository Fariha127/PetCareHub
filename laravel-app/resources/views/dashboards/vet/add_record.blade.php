@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.vet.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Add Medical Record</h1>
            <p class="text-secondary mb-0">Record clinical details, treatments, and vaccinations for shelter animals.</p>
        </div>

        <div class="content-card p-4">
            <form action="{{ route('veterinary.records.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Select Pet</label>
                    <select name="pet_id" class="form-select" required>
                        <option value="">Select pet...</option>
                        @foreach($pets ?? [] as $pet)
                            <option value="{{ $pet->pet_id }}">{{ $pet->pet_name }} ({{ $pet->species }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Select Veterinarian</label>
                    <select name="vet_id" class="form-select" required>
                        <option value="">Select veterinarian...</option>
                        @foreach($vets ?? [] as $vet)
                            <option value="{{ $vet->user_id }}" {{ $vet->user_id === auth()->id() ? 'selected' : '' }}>{{ $vet->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Diagnosis</label>
                    <input name="diagnosis" class="form-control" placeholder="e.g. Ear Infection" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Treatment</label>
                    <input name="treatment" class="form-control" placeholder="e.g. Administered ear drops" required>
                </div>
                <div class="col-12">
                    <label class="form-label text-secondary small fw-bold">Prescription</label>
                    <input name="prescription" class="form-control" placeholder="e.g. 3 drops daily for 7 days">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Vaccination Date (Optional)</label>
                    <input type="date" name="vaccination_date" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Next Vaccine Date (Optional)</label>
                    <input type="date" name="next_vaccine_date" class="form-control">
                </div>
                <div class="col-12 d-grid d-md-block">
                    <button class="btn btn-success px-4">Save Medical Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
