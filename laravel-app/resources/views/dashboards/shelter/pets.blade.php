@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('dashboards.shelter.sidebar')
    </div>
    <div class="col-lg-9">
        <div class="dashboard-panel p-4 mb-4">
            <h1 class="h3 fw-bold mb-1">Pets Summaries</h1>
            <p class="text-secondary mb-0">List and manage rescue animals registered in the shelter.</p>
        </div>

        <div class="content-card p-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Species & Breed</th>
                            <th>Age & Gender</th>
                            <th>Status</th>
                            <th>Vaccination</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pets ?? [] as $pet)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $pet->image_path ?: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=80&q=80' }}" 
                                         class="rounded-circle border border-2 border-success shadow-sm" 
                                         style="width: 48px; height: 48px; object-fit: cover;" 
                                         alt="{{ $pet->pet_name }}">
                                    <strong>{{ $pet->pet_name }}</strong>
                                </div>
                            </td>
                            <td>{{ $pet->species }} | {{ $pet->breed ?: 'N/A' }}</td>
                            <td>{{ $pet->age }} yrs | {{ $pet->gender }}</td>
                            <td>
                                @if($pet->adoption_status === 'ADOPTED')
                                    <span class="badge bg-success px-2 py-1 rounded">ADOPTED</span>
                                @elseif($pet->adoption_status === 'PENDING')
                                    <span class="badge bg-warning text-white px-2 py-1 rounded">PENDING</span>
                                @else
                                    <span class="badge bg-info text-white px-2 py-1 rounded">AVAILABLE</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-secondary small">{{ str_replace('_', ' ', $pet->vaccination_status) }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('pets.show', $pet) }}" class="btn btn-outline-success btn-sm px-3">View</a>
                                    <form action="{{ route('pets.destroy', $pet) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this pet?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm px-3">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-secondary text-center py-4">No pets found in the shelter database.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
