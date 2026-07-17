@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.shelter.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Manage Events</h1>
            <p class="text-secondary mb-0">Create events and campaigns and track participant enrollment.</p>
        </div>

        <div class="content-card p-4 mb-4">
            <h2 class="h5 mb-3 text-success">Create New Event</h2>
            <form action="{{ route('events.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-bold">Event Title</label>
                    <input name="title" class="form-control" required placeholder="e.g. Street Dog Feeding Campaign">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Event Date</label>
                    <input type="date" name="event_date" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold">Location</label>
                    <input name="location" class="form-control" required placeholder="e.g. Sector 4 Park">
                </div>
                <div class="col-12">
                    <label class="form-label text-secondary small fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Describe the purpose, schedule, and volunteer details..."></textarea>
                </div>
                <div class="col-12 d-grid d-md-block">
                    <button class="btn btn-success px-4">Create Event</button>
                </div>
            </form>
        </div>

        <div class="content-card p-4">
            <h2 class="h5 mb-3 text-success">Current Events</h2>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Going</th>
                            <th>Interested</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($events ?? [] as $evt)
                        <tr>
                            <td>
                                <strong>{{ $evt->title }}</strong>
                            </td>
                            <td>{{ optional($evt->event_date)->format('Y-m-d') }}</td>
                            <td>{{ $evt->location }}</td>
                            <td>
                                <span class="badge bg-success px-2 py-1 rounded">{{ $evt->going_count }} Going</span>
                            </td>
                            <td>
                                <span class="badge bg-warning text-white px-2 py-1 rounded">{{ $evt->interested_count }} Interested</span>
                            </td>
                            <td>
                                <a href="{{ route('events.show', $evt) }}" class="btn btn-outline-success btn-sm">View Attendees</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-secondary text-center py-4">No events created yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
