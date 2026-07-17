@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.vet.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Manage Appointments</h1>
            <p class="text-secondary mb-0">Track upcoming consultation schedules and manage previous history logs.</p>
        </div>

        <div class="content-card p-4 mb-4">
            <h2 class="h5 mb-3 text-success">Upcoming Appointments</h2>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Owner / Requester</th>
                            <th>Appointment Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($upcoming ?? [] as $app)
                        <tr>
                            <td><strong>{{ $app->pet->pet_name ?? 'N/A' }}</strong></td>
                            <td>{{ $app->requester->full_name ?? 'N/A' }}</td>
                            <td>{{ optional($app->appointment_date)->format('Y-m-d') }}</td>
                            <td>{{ $app->reason }}</td>
                            <td><span class="badge bg-warning text-white">{{ $app->status }}</span></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success btn-sm px-2" data-bs-toggle="modal" data-bs-target="#completeModal-{{ $app->appointment_id }}">
                                        Complete
                                    </button>
                                    <form action="{{ route('dashboard.vet.appointments.status', $app) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this appointment?');">
                                        @csrf
                                        <input type="hidden" name="status" value="CANCELLED">
                                        <button class="btn btn-outline-danger btn-sm px-2">Cancel</button>
                                    </form>
                                </div>

                                <!-- Complete Status Modal -->
                                <div class="modal fade" id="completeModal-{{ $app->appointment_id }}" tabindex="-1" aria-labelledby="completeLabel-{{ $app->appointment_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 0.8rem;">
                                            <div class="modal-header border-bottom-0">
                                                <h5 class="modal-title fw-bold text-success" id="completeLabel-{{ $app->appointment_id }}">Complete Appointment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('dashboard.vet.appointments.status', $app) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="COMPLETED">
                                                <div class="modal-body py-2">
                                                    <p class="text-secondary small">Add diagnosis, prescription, or clinical notes for this visit.</p>
                                                    <div class="mb-3">
                                                        <label class="form-label text-secondary small fw-bold">Consultation Notes</label>
                                                        <textarea name="notes" class="form-control" rows="3" required placeholder="e.g. Pet is healthy. Prescribed multi-vitamins."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    <button class="btn btn-success btn-sm px-3">Submit & Complete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-secondary text-center py-4">No upcoming appointments scheduled.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-card p-4">
            <h2 class="h5 mb-3 text-success">Previous Appointments</h2>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Owner / Requester</th>
                            <th>Appointment Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($previous ?? [] as $app)
                        <tr>
                            <td><strong>{{ $app->pet->pet_name ?? 'N/A' }}</strong></td>
                            <td>{{ $app->requester->full_name ?? 'N/A' }}</td>
                            <td>{{ optional($app->appointment_date)->format('Y-m-d') }}</td>
                            <td>{{ $app->reason }}</td>
                            <td>
                                @if($app->status === 'COMPLETED')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">COMPLETED</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">{{ $app->status }}</span>
                                @endif
                            </td>
                            <td><span class="text-secondary small">{{ $app->notes ?: 'None' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-secondary text-center py-4">No history of previous appointments.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
