@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        <div class="sidebar p-4 h-100">
            <h2 class="h5 mb-4">Shelter Dashboard</h2>
            <div class="d-grid gap-2">
                <a href="{{ route('dashboard.shelter') }}">Overview</a>
                <a href="{{ route('pets.index') }}">Pet Records</a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-link text-white p-0 text-start">Logout</button></form>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold">Shelter Operations</h1>
            <div class="row g-3 mt-2">
                <div class="col-md-3"><div class="p-3 bg-light rounded-3"><div class="text-secondary">Total Pets</div><div class="fs-2 fw-bold text-success">{{ $totalPets ?? 0 }}</div></div></div>
                <div class="col-md-3"><div class="p-3 bg-light rounded-3"><div class="text-secondary">Adopted Pets</div><div class="fs-2 fw-bold text-success">{{ $totalAdoptedPets ?? 0 }}</div></div></div>
                <div class="col-md-3"><div class="p-3 bg-light rounded-3"><div class="text-secondary">Pending Requests</div><div class="fs-2 fw-bold text-success">{{ $pendingRequests ?? 0 }}</div></div></div>
                <div class="col-md-3"><div class="p-3 bg-light rounded-3"><div class="text-secondary">Capacity Left</div><div class="fs-2 fw-bold text-success">{{ $availableSlots ?? 0 }}</div></div></div>
            </div>
        </div>

        <div class="content-card p-4 mb-4">
            <h2 class="h5 mb-3">Add Pet Information</h2>
            <form action="{{ route('pets.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3"><input name="pet_name" class="form-control" placeholder="Pet name" required></div>
                <div class="col-md-2"><input name="species" class="form-control" placeholder="Species" required></div>
                <div class="col-md-2"><input name="breed" class="form-control" placeholder="Breed"></div>
                <div class="col-md-1"><input type="number" name="age" class="form-control" placeholder="Age" min="0" required></div>
                <div class="col-md-2">
                    <select name="gender" class="form-select" required>
                        <option value="MALE">Male</option>
                        <option value="FEMALE">Female</option>
                        <option value="UNKNOWN">Unknown</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="vaccination_status" class="form-select" required>
                        <option value="VACCINATED">Vaccinated</option>
                        <option value="PARTIAL">Partial</option>
                        <option value="NOT_VACCINATED">Not Vaccinated</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="adoption_status" class="form-select" required>
                        <option value="AVAILABLE">Available</option>
                        <option value="PENDING">Pending</option>
                        <option value="ADOPTED">Adopted</option>
                    </select>
                </div>
                <div class="col-md-3"><input name="image_path" class="form-control" placeholder="Image URL"></div>
                <div class="col-md-3"><input name="health_condition" class="form-control" placeholder="Health condition"></div>
                <div class="col-12 d-grid d-md-block"><button class="btn btn-success">Save Pet</button></div>
            </form>
        </div>

        <div class="content-card p-4 mb-4">
            <h2 class="h5 mb-3">Adoption Requests</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Pet</th><th>Adopter</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    @forelse($requests ?? [] as $request)
                        <tr>
                            <td>{{ $request->pet->pet_name ?? 'N/A' }}</td>
                            <td>{{ $request->user->full_name ?? 'N/A' }}</td>
                            <td>{{ optional($request->request_date)->format('Y-m-d') }}</td>
                            <td><span class="badge badge-soft">{{ $request->status }}</span></td>
                            <td>
                                @if($request->status === 'PENDING')
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('adoptions.approve', $request) }}" method="POST">@csrf<button class="btn btn-success btn-sm">Approve</button></form>
                                        <form action="{{ route('adoptions.reject', $request) }}" method="POST">@csrf<button class="btn btn-outline-danger btn-sm">Reject</button></form>
                                    </div>
                                @else
                                    <span class="text-secondary small">Reviewed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-secondary">No adoption requests yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="content-card p-4">
                    <h2 class="h5 mb-3">Recent Pet Records</h2>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Name</th><th>Species</th><th>Vaccination</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                            @forelse($pets ?? [] as $pet)
                                <tr>
                                    <td>{{ $pet->pet_name }}</td>
                                    <td>{{ $pet->species }}</td>
                                    <td>{{ $pet->vaccination_status }}</td>
                                    <td>{{ $pet->adoption_status }}</td>
                                    <td>
                                        <form action="{{ route('pets.destroy', $pet) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-secondary">No pet records yet.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="content-card p-4">
                    <h2 class="h5 mb-3">Current Pet Count</h2>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
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
    </div>
</div>
@endsection
