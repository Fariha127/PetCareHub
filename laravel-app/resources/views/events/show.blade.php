@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 fw-bold text-success mb-0">{{ $event->title }}</h1>
            <a href="{{ route('dashboard.shelter') }}" class="btn btn-outline-success">Back to Dashboard</a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-card p-4">
            <h2 class="h5 mb-3">Event Details</h2>
            <p class="text-secondary">{{ $event->description }}</p>
            <hr class="my-3">
            <div class="mb-2">
                <strong>Date:</strong> <span class="text-secondary">{{ $event->event_date->format('F d, Y') }}</span>
            </div>
            <div class="mb-2">
                <strong>Location:</strong> <span class="text-secondary">{{ $event->location }}</span>
            </div>
            <div>
                <strong>Created By:</strong> <span class="text-secondary">{{ $event->creator->full_name ?? 'Shelter Staff' }}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="content-card p-4 mb-4">
            <h2 class="h5 mb-3">Attendees Marking "Going" ({{ $goingEnrollments->count() }})</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Signed Up</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($goingEnrollments as $enrollment)
                        <tr>
                            <td><strong>{{ $enrollment->user->full_name }}</strong></td>
                            <td>{{ $enrollment->user->email }}</td>
                            <td>{{ $enrollment->user->phone ?? 'N/A' }}</td>
                            <td>{{ $enrollment->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-secondary text-center py-3">No users have marked "Going" yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-card p-4">
            <h2 class="h5 mb-3">Attendees Marking "Interested" ({{ $interestedEnrollments->count() }})</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Signed Up</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($interestedEnrollments as $enrollment)
                        <tr>
                            <td><strong>{{ $enrollment->user->full_name }}</strong></td>
                            <td>{{ $enrollment->user->email }}</td>
                            <td>{{ $enrollment->user->phone ?? 'N/A' }}</td>
                            <td>{{ $enrollment->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-secondary text-center py-3">No users have marked "Interested" yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
