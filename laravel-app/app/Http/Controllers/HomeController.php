<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\MedicalRecord;
use App\Models\Pet;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'totalPets' => Pet::count(),
            'adoptedPets' => Pet::where('adoption_status', 'ADOPTED')->count(),
            'pendingRequests' => AdoptionRequest::where('status', 'PENDING')->count(),
            'vaccinatedPets' => MedicalRecord::whereNotNull('vaccination_date')->count(),
        ];

        $featuredPets = Pet::where('adoption_status', 'AVAILABLE')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('stats', 'featuredPets'));
    }
}
