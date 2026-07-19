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
            'pet_id' => ['required', 'string', 'exists:pets,pet_id'],
            'vet_id' => ['required', 'string', Rule::exists('users', 'user_id')->where('role', 'VETERINARIAN')],
            'appointment_date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        \Illuminate\Support\Facades\DB::statement(
            "BEGIN sp_schedule_appointment(:pet_id, :vet_id, :requested_by, :appointment_date, :reason); END;",
            [
                'pet_id' => $data['pet_id'],
                'vet_id' => $data['vet_id'],
                'requested_by' => auth()->id(),
                'appointment_date' => $data['appointment_date'],
                'reason' => $data['reason'],
            ]
        );

        return back()->with('success', 'Appointment booked.');
    }

    public function storeRecord(Request $request)
    {
        $data = $request->validate([
            'pet_id' => ['required', 'string', 'exists:pets,pet_id'],
            'vet_id' => ['required', 'string', Rule::exists('users', 'user_id')->where('role', 'VETERINARIAN')],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['required', 'string', 'max:255'],
            'vaccination_date' => ['nullable', 'date'],
            'next_vaccine_date' => ['nullable', 'date'],
            'prescription' => ['nullable', 'string', 'max:255'],
        ]);

        \Illuminate\Support\Facades\DB::statement(
            "BEGIN sp_add_medical_record(:pet_id, :vet_id, :diagnosis, :treatment, :vaccination_date, :next_vaccine_date, :prescription); END;",
            [
                'pet_id' => $data['pet_id'],
                'vet_id' => $data['vet_id'],
                'diagnosis' => $data['diagnosis'],
                'treatment' => $data['treatment'],
                'vaccination_date' => $data['vaccination_date'] ?? null,
                'next_vaccine_date' => $data['next_vaccine_date'] ?? null,
                'prescription' => $data['prescription'] ?? null,
            ]
        );

        return back()->with('success', 'Medical record saved.');
    }
}
