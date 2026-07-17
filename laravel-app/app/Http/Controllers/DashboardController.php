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
        return view('dashboards.user.profile');
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_photo_url' => 'nullable|url|max:255',
        ]);

        $user->full_name = $validated['full_name'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];

        if ($request->hasFile('profile_photo_file')) {
            $file = $request->file('profile_photo_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $user->profile_photo = '/uploads/profiles/' . $filename;
        } elseif (!empty($validated['profile_photo_url'])) {
            $user->profile_photo = $validated['profile_photo_url'];
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function userRequests()
    {
        $myRequests = AdoptionRequest::with('pet')
            ->where('user_id', auth()->id())
            ->latest('request_date')
            ->get();

        return view('dashboards.user.requests', compact('myRequests'));
    }

    public function userAppointments()
    {
        $userId = auth()->id();
        $appointments = VeterinaryAppointment::with(['pet', 'vet'])
            ->where('requested_by', $userId)
            ->latest('appointment_date')
            ->get();

        $pets = Pet::whereHas('adoptionRequests', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('status', 'APPROVED');
        })->orderBy('pet_name')->get();

        if ($pets->isEmpty()) {
            $pets = Pet::where('adoption_status', '!=', 'ADOPTED')->orderBy('pet_name')->get();
        }

        $vets = User::where('role', 'VETERINARIAN')->orderBy('full_name')->get();

        return view('dashboards.user.appointments', compact('appointments', 'pets', 'vets'));
    }

    public function userPets()
    {
        $myPets = Pet::whereHas('adoptionRequests', function ($query) {
            $query->where('user_id', auth()->id())->where('status', 'APPROVED');
        })->get();

        return view('dashboards.user.pets', compact('myPets'));
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
