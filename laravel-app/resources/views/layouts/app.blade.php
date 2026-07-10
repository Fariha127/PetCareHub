<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PetCareHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ink: #16202a;
            --accent: #2f6f68;
            --accent-2: #d9efe9;
            --surface: #f7f7f2;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: linear-gradient(180deg, #f4f7f2 0%, #ffffff 42%, #eef5f1 100%);
            color: var(--ink);
        }

        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.82) !important;
        }

        .hero-panel,
        .content-card,
        .dashboard-panel {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(47, 111, 104, 0.12);
            border-radius: 0.5rem;
            box-shadow: 0 18px 40px rgba(18, 39, 34, 0.08);
        }

        .badge-soft {
            background: var(--accent-2);
            color: var(--accent);
        }

        .sidebar {
            min-height: 100%;
            background: linear-gradient(180deg, #16332f 0%, #214c45 100%);
            color: #fff;
            border-radius: 0.5rem;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
        }

        .sidebar a:hover {
            color: #fff;
        }

        .section-title {
            letter-spacing: 0.03em;
            text-transform: uppercase;
            font-size: 0.78rem;
            color: var(--accent);
        }

        .pet-cover {
            height: 180px;
            width: 100%;
            object-fit: cover;
        }

        .pet-detail-cover {
            aspect-ratio: 16 / 10;
            min-height: 360px;
            max-height: 420px;
            width: 100%;
            object-fit: cover;
        }

        .pet-cover--face-top {
            object-position: center 38%;
        }

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top border-bottom">
    <div class="container py-2">
        <a class="navbar-brand fw-bold text-success" href="{{ route('home') }}">PetCareHub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navLinks">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navLinks">
            <ul class="navbar-nav ms-auto gap-lg-2 align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('pets.index') }}">Pets</a></li>
                @auth
                    @if(auth()->user()->role === 'USER')
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.user') }}">User Dashboard</a></li>
                    @endif
                    @if(in_array(auth()->user()->role, ['SHELTER_STAFF', 'ADMIN'], true))
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.shelter') }}">Shelter Dashboard</a></li>
                    @endif
                    @if(in_array(auth()->user()->role, ['VETERINARIAN', 'ADMIN'], true))
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.vet') }}">Vet Dashboard</a></li>
                    @endif
                    @if(auth()->user()->role === 'ADMIN')
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.admin') }}">Admin Dashboard</a></li>
                    @endif
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-success btn-sm">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="btn btn-outline-success btn-sm" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-success btn-sm" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
