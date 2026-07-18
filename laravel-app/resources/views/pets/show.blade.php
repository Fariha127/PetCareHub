@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="content-card p-3">
            <img src="{{ $pet->photo_url }}" class="img-fluid rounded-4 pet-detail-cover @if($pet->pet_name === 'Bella') pet-cover--face-top @endif" alt="{{ $pet->pet_name }}">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="content-card p-4 h-100">
            <span class="badge badge-soft mb-3">Pet Details</span>
            <h1 class="h2 fw-bold">{{ $pet->pet_name }}</h1>
            <p class="text-secondary mb-4">{{ $pet->species }} | {{ $pet->breed }}</p>

            <div class="row g-3">
                <div class="col-sm-6"><strong>Age:</strong> {{ $pet->age }}</div>
                <div class="col-sm-6"><strong>Gender:</strong> {{ $pet->gender }}</div>
                <div class="col-sm-6"><strong>Vaccination:</strong> {{ $pet->vaccination_status }}</div>
                <div class="col-sm-6"><strong>Status:</strong> {{ $pet->adoption_status }}</div>
                <div class="col-12"><strong>Health Condition:</strong> {{ $pet->health_condition ?: 'No record provided' }}</div>
                <div class="col-sm-6"><strong>Food Preference:</strong> {{ $pet->food_preference ?: 'None' }}</div>
                <div class="col-sm-6"><strong>Distinct Habit:</strong> {{ $pet->distinct_habit ?: 'None' }}</div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2 flex-wrap">
                @if(auth()->check() && auth()->user()->role === 'USER')
                    <form action="{{ route('adoptions.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pet_id" value="{{ $pet->pet_id }}">
                        <button class="btn btn-success" @disabled($pet->adoption_status !== 'AVAILABLE')>Request Adoption</button>
                    </form>
                @endif
                <a href="{{ route('pets.index') }}" class="btn btn-outline-secondary">Back to listing</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    @php
        $showAdoptions = auth()->check() && in_array(auth()->user()->role, ['SHELTER_STAFF', 'ADMIN']);
        $medicalColClass = $showAdoptions ? 'col-lg-6' : 'col-12';
    @endphp

    <div class="{{ $medicalColClass }}">
        <div class="content-card p-4">
            <h2 class="h5">Medical History</h2>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Diagnosis</th><th>Vaccination</th><th>Next Vaccine</th></tr></thead>
                    <tbody>
                    @forelse($pet->medicalRecords as $record)
                        <tr>
                            <td>{{ $record->diagnosis }}</td>
                            <td>{{ optional($record->vaccination_date)->format('Y-m-d') }}</td>
                            <td>{{ optional($record->next_vaccine_date)->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-secondary">No medical history recorded.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($showAdoptions)
        <div class="col-lg-6">
            <div class="content-card p-4">
                <h2 class="h5">Adoption Requests</h2>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>User</th><th>Date</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($pet->adoptionRequests as $request)
                            <tr>
                                <td>{{ $request->user->full_name ?? 'N/A' }}</td>
                                <td>{{ optional($request->request_date)->format('Y-m-d') }}</td>
                                <td>{{ $request->status }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-secondary">No adoption requests yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
