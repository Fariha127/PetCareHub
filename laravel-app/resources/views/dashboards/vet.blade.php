@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        <div class="sidebar p-4 h-100">
            <h2 class="h5 mb-4">Veterinarian Dashboard</h2>
            <div class="d-grid gap-2">
                <a href="{{ route('dashboard.vet') }}">Appointments</a>
                <a href="{{ route('pets.index') }}">Pet Listing</a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-link text-white p-0 text-start">Logout</button></form>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold">Veterinary Care</h1>
            <div class="row g-3 mt-2">
                <div class="col-md-4"><div class="p-3 bg-light rounded-3"><div class="text-secondary">Appointments</div><div class="fs-2 fw-bold text-success">{{ count($appointments ?? []) }}</div></div></div>
                <div class="col-md-4"><div class="p-3 bg-light rounded-3"><div class="text-secondary">Medical Records</div><div class="fs-2 fw-bold text-success">{{ count($records ?? []) }}</div></div></div>
                <div class="col-md-4"><div class="p-3 bg-light rounded-3"><div class="text-secondary">Upcoming Vaccines</div><div class="fs-2 fw-bold text-success">{{ $vaccinationsDue ?? 0 }}</div></div></div>
            </div>
        </div>

        <div class="content-card p-4 mb-4">
            <h2 class="h5 mb-3">Add Medical Record</h2>
            <form action="{{ route('veterinary.records.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <select name="pet_id" class="form-select" required>
                        <option value="">Select pet</option>
                        @foreach($pets ?? [] as $pet)
                            <option value="{{ $pet->pet_id }}">{{ $pet->pet_name }} - {{ $pet->species }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="vet_id" class="form-select" required>
                        <option value="">Select vet</option>
                        @foreach($vets ?? [] as $vet)
                            <option value="{{ $vet->user_id }}">{{ $vet->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"><input name="diagnosis" class="form-control" placeholder="Diagnosis" required></div>
                <div class="col-md-6"><input name="treatment" class="form-control" placeholder="Treatment" required></div>
                <div class="col-md-6"><input name="prescription" class="form-control" placeholder="Prescription"></div>
                <div class="col-md-4"><input type="date" name="vaccination_date" class="form-control"></div>
                <div class="col-md-4"><input type="date" name="next_vaccine_date" class="form-control"></div>
                <div class="col-md-4 d-grid"><button class="btn btn-success">Save Record</button></div>
            </form>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="content-card p-4">
                    <h2 class="h5 mb-3">Appointments</h2>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Pet</th><th>Date</th><th>Status</th></tr></thead>
                            <tbody>
                            @forelse($appointments ?? [] as $appointment)
                                <tr><td>{{ $appointment->pet->pet_name ?? 'N/A' }}</td><td>{{ optional($appointment->appointment_date)->format('Y-m-d') }}</td><td>{{ $appointment->status }}</td></tr>
                            @empty
                                <tr><td colspan="3" class="text-secondary">No appointments scheduled.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="content-card p-4">
                    <h2 class="h5 mb-3">Recent Medical Records</h2>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Pet</th><th>Diagnosis</th><th>Next Vaccine</th></tr></thead>
                            <tbody>
                            @forelse($records ?? [] as $record)
                                <tr><td>{{ $record->pet->pet_name ?? 'N/A' }}</td><td>{{ $record->diagnosis }}</td><td>{{ optional($record->next_vaccine_date)->format('Y-m-d') }}</td></tr>
                            @empty
                                <tr><td colspan="3" class="text-secondary">No records saved yet.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
