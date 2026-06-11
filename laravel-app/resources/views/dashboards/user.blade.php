@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        <div class="sidebar p-4 h-100">
            <h2 class="h5 mb-4">User Dashboard</h2>
            <div class="d-grid gap-2">
                <a href="{{ route('pets.index') }}">Browse Pets</a>
                <a href="{{ route('dashboard.user') }}">My Adoption Requests</a>
                <a href="{{ route('veterinary.appointments') }}">Vet Appointments</a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-link text-white p-0 text-start">Logout</button></form>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold">Dashboard Overview</h1>
            <div class="row g-3 mt-2">
                <div class="col-md-4"><div class="p-3 bg-light rounded-4"><div class="text-secondary">Available Pets</div><div class="fs-2 fw-bold text-success">{{ $availablePets ?? 0 }}</div></div></div>
                <div class="col-md-4"><div class="p-3 bg-light rounded-4"><div class="text-secondary">Pending Requests</div><div class="fs-2 fw-bold text-success">{{ isset($myRequests) ? $myRequests->where('status', 'PENDING')->count() : 0 }}</div></div></div>
                <div class="col-md-4"><div class="p-3 bg-light rounded-4"><div class="text-secondary">Approved Requests</div><div class="fs-2 fw-bold text-success">{{ isset($myRequests) ? $myRequests->where('status', 'APPROVED')->count() : 0 }}</div></div></div>
            </div>
        </div>

        <div class="content-card p-4">
            <h2 class="h5 mb-3">My Adoption Requests</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Pet</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($myRequests ?? [] as $request)
                        <tr>
                            <td>{{ $request->pet->pet_name ?? 'N/A' }}</td>
                            <td>{{ optional($request->request_date)->format('Y-m-d') }}</td>
                            <td>{{ $request->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-secondary">No requests submitted yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-card p-4 mt-4">
            <h2 class="h5 mb-3">Book Vet Appointment</h2>
            <form action="{{ route('veterinary.appointments.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <select name="pet_id" class="form-select" required>
                        <option value="">Pet</option>
                        @foreach($pets ?? [] as $pet)
                            <option value="{{ $pet->pet_id }}">{{ $pet->pet_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="vet_id" class="form-select" required>
                        <option value="">Veterinarian</option>
                        @foreach($vets ?? [] as $vet)
                            <option value="{{ $vet->user_id }}">{{ $vet->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><input type="date" name="appointment_date" class="form-control" required></div>
                <div class="col-md-3"><input name="reason" class="form-control" placeholder="Reason" required></div>
                <div class="col-12 d-grid d-md-block"><button class="btn btn-success">Book Appointment</button></div>
            </form>
        </div>

        <div class="content-card p-4 mt-4">
            <h2 class="h5 mb-3">My Vet Appointments</h2>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Pet</th><th>Vet</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($appointments ?? [] as $appointment)
                        <tr>
                            <td>{{ $appointment->pet->pet_name ?? 'N/A' }}</td>
                            <td>{{ $appointment->vet->full_name ?? 'N/A' }}</td>
                            <td>{{ optional($appointment->appointment_date)->format('Y-m-d') }}</td>
                            <td>{{ $appointment->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-secondary">No appointments booked yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
