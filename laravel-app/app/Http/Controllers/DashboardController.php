<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\User;
use App\Models\VeterinaryAppointment;

class DashboardController extends Controller
{
    private const SHELTER_CAPACITY = 500;

    public function userDashboard()
    {
        return view('dashboards.user', [
            'availablePets' => Pet::where('adoption_status', 'AVAILABLE')->count(),
            'myRequests' => AdoptionRequest::with('pet')
                ->where('user_id', auth()->id())
                ->latest('request_date')
                ->take(10)
                ->get(),
            'appointments' => VeterinaryAppointment::with(['pet', 'vet'])
                ->where('requested_by', auth()->id())
                ->latest('appointment_date')
                ->take(5)
                ->get(),
            'pets' => Pet::where('adoption_status', '!=', 'ADOPTED')->orderBy('pet_name')->get(),
            'vets' => User::where('role', 'VETERINARIAN')->orderBy('full_name')->get(),
        ]);
    }

    public function shelterDashboard()
    {
        $currentPetCount = Pet::where('adoption_status', '!=', 'ADOPTED')->count();

        return view('dashboards.shelter', [
            'totalPets' => Pet::count(),
            'totalAdoptedPets' => Pet::where('adoption_status', 'ADOPTED')->count(),
            'pendingRequests' => AdoptionRequest::where('status', 'PENDING')->count(),
            'vaccinationStats' => MedicalRecord::whereNotNull('vaccination_date')->count(),
            'shelterCapacity' => self::SHELTER_CAPACITY,
            'currentPetCount' => $currentPetCount,
            'availableSlots' => max(self::SHELTER_CAPACITY - $currentPetCount, 0),
            'requests' => AdoptionRequest::with(['user', 'pet'])
                ->latest('request_date')
                ->take(10)
                ->get(),
            'pets' => Pet::latest()->take(8)->get(),
            'events' => \App\Models\Event::withCount([
                'enrollments as going_count' => function ($query) {
                    $query->where('status', 'GOING');
                },
                'enrollments as interested_count' => function ($query) {
                    $query->where('status', 'INTERESTED');
                }
            ])->latest()->get(),
        ]);
    }

    public function vetDashboard()
    {
        return view('dashboards.vet', [
            'appointments' => VeterinaryAppointment::with(['pet', 'requester'])
                ->latest('appointment_date')
                ->take(10)
                ->get(),
            'records' => MedicalRecord::with(['pet', 'vet'])
                ->latest()
                ->take(10)
                ->get(),
            'pets' => Pet::orderBy('pet_name')->get(),
            'vets' => User::where('role', 'VETERINARIAN')->orderBy('full_name')->get(),
            'vaccinationsDue' => MedicalRecord::whereNotNull('next_vaccine_date')
                ->whereDate('next_vaccine_date', '>=', now())
                ->count(),
        ]);
    }
}
