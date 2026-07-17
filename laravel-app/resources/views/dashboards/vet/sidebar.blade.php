<div class="sidebar p-4 h-100 shadow-sm">
    <h2 class="h5 mb-4">Vet Dashboard</h2>
    <div class="d-grid gap-1">
        <a href="{{ route('dashboard.vet') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.vet') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Overview & Stats
        </a>
        <a href="{{ route('dashboard.vet.add_record') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.vet.add_record') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Add Medical Record
        </a>
        <a href="{{ route('dashboard.vet.appointments') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 {{ request()->routeIs('dashboard.vet.appointments') ? 'bg-success fw-bold shadow-sm' : 'opacity-75' }}">
           Appointments
        </a>
        <hr class="border-light opacity-25 my-3">
        <a href="{{ route('pets.index') }}" 
           class="btn btn-link text-decoration-none text-white text-start p-2 rounded-2 opacity-75">
           Pet Listing
        </a>
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100 text-start">Logout</button>
        </form>
    </div>
</div>
