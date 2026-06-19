@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        <div class="sidebar p-4 h-100">
            <h2 class="h5 mb-4">Admin Dashboard</h2>
            <div class="d-grid gap-2">
                <a href="{{ route('dashboard.admin') }}">Overview</a>
                <a href="{{ route('pets.index') }}">Pets</a>
                <a href="{{ route('login') }}">Logout</a>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold">Administration Summary</h1>
            <div class="row g-3 mt-2">
                <div class="col-md-3"><div class="p-3 bg-light rounded-4"><div class="text-secondary">Total Pets</div><div class="fs-2 fw-bold text-success">{{ $totalPets ?? 0 }}</div></div></div>
                <div class="col-md-3"><div class="p-3 bg-light rounded-4"><div class="text-secondary">Adopted Pets</div><div class="fs-2 fw-bold text-success">{{ $totalAdoptedPets ?? 0 }}</div></div></div>
                <div class="col-md-3"><div class="p-3 bg-light rounded-4"><div class="text-secondary">Pending Requests</div><div class="fs-2 fw-bold text-success">{{ $pendingRequests ?? 0 }}</div></div></div>
                <div class="col-md-3"><div class="p-3 bg-light rounded-4"><div class="text-secondary">Vaccinations</div><div class="fs-2 fw-bold text-success">{{ $vaccinationStats ?? 0 }}</div></div></div>
            </div>
        </div>

        <div class="content-card p-4 mb-4">
            <h2 class="h5 mb-3">Shelter Occupancy</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Capacity</th><th>Current Pets</th><th>Available Slots</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>{{ $shelterCapacity ?? 0 }}</td>
                            <td>{{ $currentPetCount ?? 0 }}</td>
                            <td>{{ $availableSlots ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
