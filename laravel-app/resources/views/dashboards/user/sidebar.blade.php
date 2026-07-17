<div class="sidebar p-4 h-100 shadow-sm">
    <h2 class="h5 mb-4">User Dashboard</h2>
    <div class="d-grid gap-1">
        <a href="{{ route('dashboard.user') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.user') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Profile Overview
        </a>
        <a href="{{ route('dashboard.user.requests') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.user.requests') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Adoption Requests
        </a>
        <a href="{{ route('dashboard.user.appointments') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.user.appointments') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Vet Appointments
        </a>
        <a href="{{ route('dashboard.user.pets') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.user.pets') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           My Adopted Pets
        </a>
        <hr class="border-light opacity-25 my-3">
        <a href="{{ route('pets.index') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 opacity-75">
           Browse Pets
        </a>
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100 text-start">Logout</button>
        </form>
    </div>
</div>
