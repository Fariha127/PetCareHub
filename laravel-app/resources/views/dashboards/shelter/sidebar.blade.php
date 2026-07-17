<div class="sidebar p-4 h-100 shadow-sm">
    <h2 class="h5 mb-4">Shelter Dashboard</h2>
    <div class="d-grid gap-1">
        <a href="{{ route('dashboard.shelter') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.shelter') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Overview & Stats
        </a>
        <a href="{{ route('dashboard.shelter.add_pet') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.shelter.add_pet') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Add New Pet
        </a>
        <a href="{{ route('dashboard.shelter.requests') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.shelter.requests') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Adoption Requests
        </a>
        <a href="{{ route('dashboard.shelter.events') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.shelter.events') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Manage Events
        </a>
        <a href="{{ route('dashboard.shelter.pets') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.shelter.pets') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Pets Summaries
        </a>
        <hr class="border-light opacity-25 my-3">
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100 text-start">Logout</button>
        </form>
    </div>
</div>
