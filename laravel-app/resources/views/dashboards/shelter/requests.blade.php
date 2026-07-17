@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.shelter.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Adoption Requests</h1>
            <p class="text-secondary mb-0">Approve or reject incoming adoption applications from users.</p>
        </div>

        <div class="content-card p-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Adopter</th>
                            <th>Pet Name</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($requests ?? [] as $req)
                        <tr>
                            <td>
                                <strong>{{ $req->user->full_name ?? 'N/A' }}</strong><br>
                                <span class="text-secondary small">{{ $req->user->email ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <strong>{{ $req->pet->pet_name ?? 'N/A' }}</strong><br>
                                <span class="text-secondary small">{{ $req->pet->species ?? 'N/A' }}</span>
                            </td>
                            <td>{{ optional($req->request_date)->format('Y-m-d') }}</td>
                            <td>
                                @if($req->status === 'APPROVED')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">APPROVED</span>
                                @elseif($req->status === 'REJECTED')
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">REJECTED</span>
                                @else
                                    <span class="badge bg-warning text-white px-3 py-2 rounded-pill">PENDING</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $req->remarks ?: 'None' }}</span>
                            </td>
                            <td>
                                @if($req->status === 'PENDING')
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('adoptions.approve', $req) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="remarks" value="Approved by staff">
                                            <button class="btn btn-success btn-sm px-3">Approve</button>
                                        </form>
                                        <form action="{{ route('adoptions.reject', $req) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="remarks" value="Rejected by staff">
                                            <button class="btn btn-danger btn-sm px-3">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-secondary small">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-secondary text-center py-4">No adoption requests found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
