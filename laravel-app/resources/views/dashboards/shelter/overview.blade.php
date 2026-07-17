@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.shelter.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Overview & Statistics</h1>
            <p class="text-secondary mb-0">High-level status of the shelter capacity and operations.</p>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="content-card p-3 text-center h-100">
                    <span class="text-secondary small d-block mb-1">Total Pets Registered</span>
                    <strong class="fs-2 text-success">{{ $totalPets }}</strong>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="content-card p-3 text-center h-100">
                    <span class="text-secondary small d-block mb-1">Adopted Pets</span>
                    <strong class="fs-2 text-success">{{ $totalAdoptedPets }}</strong>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="content-card p-3 text-center h-100">
                    <span class="text-secondary small d-block mb-1">Pending Adoptions</span>
                    <strong class="fs-2 text-success">{{ $pendingRequests }}</strong>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="content-card p-3 text-center h-100">
                    <span class="text-secondary small d-block mb-1">Vaccinations Recorded</span>
                    <strong class="fs-2 text-success">{{ $vaccinationStats }}</strong>
                </div>
            </div>
        </div>

        <div class="content-card p-4">
            <h2 class="h5 mb-3 text-success">Shelter Capacity Status</h2>
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="mb-2">
                        <strong>Current Occupancy:</strong> {{ $currentPetCount }} / {{ $shelterCapacity }} Pets
                    </div>
                    <div class="progress" style="height: 1.5rem; border-radius: 0.3rem; overflow: hidden;">
                        @php
                            $percentage = ($currentPetCount / $shelterCapacity) * 100;
                            $progressClass = 'bg-success';
                            if ($percentage > 85) $progressClass = 'bg-danger';
                            elseif ($percentage > 60) $progressClass = 'bg-warning';
                        @endphp
                        <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $currentPetCount }}" aria-valuemin="0" aria-valuemax="{{ $shelterCapacity }}">
                            {{ round($percentage) }}% Used
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-start ps-4">
                        <span class="text-secondary small d-block">Available Slots</span>
                        <strong class="fs-3 text-success">{{ $availableSlots }} Slots Left</strong>
                        <p class="text-secondary small mb-0 mt-1">Shelter is at {{ round($percentage) }}% capacity. Registration of new pets is permitted as long as slots remain.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
