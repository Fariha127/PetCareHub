@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.vet.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Veterinary Care Overview</h1>
            <p class="text-secondary mb-0">Monitor your appointments, medical records, and upcoming vaccinations.</p>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="content-card p-3 text-center h-100">
                    <span class="text-secondary small d-block mb-1">My Appointments</span>
                    <strong class="fs-2 text-success">{{ $appointmentsCount }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-card p-3 text-center h-100">
                    <span class="text-secondary small d-block mb-1">Medical Records Written</span>
                    <strong class="fs-2 text-success">{{ $recordsCount }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-card p-3 text-center h-100">
                    <span class="text-secondary small d-block mb-1">Upcoming Vaccines Due</span>
                    <strong class="fs-2 text-success">{{ $vaccinationsDueCount }}</strong>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="content-card p-4 h-100">
                    <h2 class="h5 mb-3 text-success">Recent Medical Records</h2>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Pet</th>
                                    <th>Diagnosis</th>
                                    <th>Next Vaccine</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($recentRecords ?? [] as $record)
                                <tr>
                                    <td><strong>{{ $record->pet->pet_name ?? 'N/A' }}</strong></td>
                                    <td>{{ $record->diagnosis }}</td>
                                    <td>{{ $record->next_vaccine_date ? optional($record->next_vaccine_date)->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-secondary text-center py-3">No records saved yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="content-card p-4 h-100">
                    <h2 class="h5 mb-3 text-success">Upcoming Vaccinations</h2>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Pet</th>
                                    <th>Next Vaccine Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($vaccinations ?? [] as $vac)
                                <tr>
                                    <td><strong>{{ $vac->pet->pet_name ?? 'N/A' }}</strong></td>
                                    <td><span class="badge bg-warning text-white">{{ optional($vac->next_vaccine_date)->format('Y-m-d') }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-secondary text-center py-3">No vaccinations due soon.</td>
                                </tr>
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
