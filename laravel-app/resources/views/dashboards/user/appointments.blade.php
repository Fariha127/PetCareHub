@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.user.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Vet Appointments</h1>
            <p class="text-secondary mb-0">Schedule and manage medical consultations for your pets.</p>
        </div>

        <div class="content-card p-4">
            <h2 class="h5 mb-3 text-success">Book Vet Appointment</h2>
            <form action="{{ route('veterinary.appointments.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Select Pet</label>
                    <select name="pet_id" class="form-select" required>
                        <option value="">Choose pet...</option>
                        @foreach($pets ?? [] as $pet)
                            <option value="{{ $pet->pet_id }}">{{ $pet->pet_name }} ({{ $pet->species }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Select Veterinarian</label>
                    <select name="vet_id" class="form-select" required>
                        <option value="">Choose vet...</option>
                        @foreach($vets ?? [] as $vet)
                            <option value="{{ $vet->user_id }}">{{ $vet->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Date</label>
                    <input type="date" name="appointment_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Reason for Visit</label>
                    <input name="reason" class="form-control" placeholder="e.g. Vaccination check" required>
                </div>
                <div class="col-12 d-grid d-md-block">
                    <button class="btn btn-success">Schedule Appointment</button>
                </div>
            </form>
        </div>

        <div class="content-card p-4 mt-4">
            <h2 class="h5 mb-3 text-success">My Vet Appointments</h2>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Veterinarian</th>
                            <th>Appointment Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($appointments ?? [] as $appointment)
                        <tr>
                            <td><strong>{{ $appointment->pet->pet_name ?? 'N/A' }}</strong></td>
                            <td>{{ $appointment->vet->full_name ?? 'N/A' }}</td>
                            <td>{{ optional($appointment->appointment_date)->format('Y-m-d') }}</td>
                            <td>{{ $appointment->reason }}</td>
                            <td>
                                @if($appointment->status === 'COMPLETED')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">COMPLETED</span>
                                @elseif($appointment->status === 'CANCELLED')
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">CANCELLED</span>
                                @else
                                    <span class="badge bg-warning text-white px-3 py-2 rounded-pill">SCHEDULED</span>
                                @endif
                            </td>
                            <td><span class="text-secondary small">{{ $appointment->notes ?: 'None' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-secondary text-center py-4">No appointments booked yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
