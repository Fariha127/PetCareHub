@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.user.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">My Adoption Requests</h1>
            <p class="text-secondary mb-0">Track the status of your pet adoption applications.</p>
        </div>

        <div class="content-card p-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Species & Breed</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Reviewed By</th>
                            <th>Decision Date</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($myRequests ?? [] as $request)
                        <tr>
                            <td>
                                <strong class="text-success">{{ $request->pet->pet_name ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $request->pet->species ?? 'N/A' }} | {{ $request->pet->breed ?? 'N/A' }}</td>
                            <td>{{ optional($request->request_date)->format('Y-m-d') }}</td>
                            <td>
                                @if($request->status === 'APPROVED')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">APPROVED</span>
                                @elseif($request->status === 'REJECTED')
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">REJECTED</span>
                                @else
                                    <span class="badge bg-warning text-white px-3 py-2 rounded-pill">PENDING</span>
                                @endif
                            </td>
                            <td>{{ $request->reviewer->full_name ?? 'Pending Review' }}</td>
                            <td>{{ $request->decision_date ? optional($request->decision_date)->format('Y-m-d') : 'N/A' }}</td>
                            <td><span class="text-secondary small">{{ $request->remarks ?: 'No remarks' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-secondary text-center py-4">No adoption requests submitted yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
