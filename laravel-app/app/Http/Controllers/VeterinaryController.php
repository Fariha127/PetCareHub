<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\User;
use App\Models\VeterinaryAppointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VeterinaryController extends Controller
{
    public function appointments()
    {
        return view('dashboards.user', [
            'appointments' => VeterinaryAppointment::with(['pet', 'vet'])
                ->latest()
                ->paginate(10),
            'availablePets' => Pet::where('adoption_status', 'AVAILABLE')->count(),
            'pets' => Pet::where('adoption_status', '!=', 'ADOPTED')->orderBy('pet_name')->get(),
            'vets' => User::where('role', 'VETERINARIAN')->orderBy('full_name')->get(),
        ]);
    }

    public function storeAppointment(Request $request)
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('success', 'Please log in before booking an appointment.');
        }

        $data = $request->validate([
            'pet_id' => ['required', 'integer', 'exists:pets,pet_id'],
            'vet_id' => ['required', 'integer', Rule::exists('users', 'user_id')->where('role', 'VETERINARIAN')],
            'appointment_date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        VeterinaryAppointment::create([
            'pet_id' => $data['pet_id'],
            'vet_id' => $data['vet_id'],
            'requested_by' => auth()->id(),
            'appointment_date' => $data['appointment_date'],
            'reason' => $data['reason'],
            'status' => 'SCHEDULED',
        ]);

        return back()->with('success', 'Appointment booked.');
    }

    public function storeRecord(Request $request)
    {
        MedicalRecord::create($request->validate([
            'pet_id' => ['required', 'integer', 'exists:pets,pet_id'],
            'vet_id' => ['required', 'integer', Rule::exists('users', 'user_id')->where('role', 'VETERINARIAN')],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['required', 'string', 'max:255'],
            'vaccination_date' => ['nullable', 'date'],
            'next_vaccine_date' => ['nullable', 'date'],
            'prescription' => ['nullable', 'string', 'max:255'],
        ]));

        return back()->with('success', 'Medical record saved.');
    }
}
