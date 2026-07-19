<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdoptionController extends Controller
{
    public function store(Request $request)
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('success', 'Please log in before requesting adoption.');
        }

        $data = $request->validate([
            'pet_id' => ['required', 'string', 'exists:pets,pet_id'],
        ]);

        DB::transaction(function () use ($data) {
            AdoptionRequest::create([
                'user_id' => auth()->id(),
                'pet_id' => $data['pet_id'],
                'request_date' => now(),
                'status' => 'PENDING',
            ]);

            DB::table('pets')
                ->where('pet_id', $data['pet_id'])
                ->where('adoption_status', 'AVAILABLE')
                ->update(['adoption_status' => 'PENDING', 'updated_at' => now()]);
        });

        return back()->with('success', 'Adoption request submitted.');
    }

    public function approve(AdoptionRequest $adoptionRequest)
    {
        DB::transaction(function () use ($adoptionRequest) {
            // Call the database stored procedure to approve this request
            DB::statement("BEGIN sp_process_adoption_request(:request_id, :status, :reviewer_id, :remarks); END;", [
                'request_id' => $adoptionRequest->request_id,
                'status' => 'APPROVED',
                'reviewer_id' => auth()->id(),
                'remarks' => $adoptionRequest->remarks ?? '',
            ]);

            // Note: The database trigger 'trg_adoption_status_update' automatically
            // updates the pet's adoption_status to 'ADOPTED' when this request is APPROVED.

            // Fetch other pending requests to reject them
            $pendingRequests = AdoptionRequest::where('pet_id', $adoptionRequest->pet_id)
                ->where('request_id', '!=', $adoptionRequest->request_id)
                ->where('status', 'PENDING')
                ->get();

            foreach ($pendingRequests as $pending) {
                DB::statement("BEGIN sp_process_adoption_request(:request_id, :status, :reviewer_id, :remarks); END;", [
                    'request_id' => $pending->request_id,
                    'status' => 'REJECTED',
                    'reviewer_id' => auth()->id(),
                    'remarks' => 'Another adoption request was approved.',
                ]);
            }
        });

        return back()->with('success', 'Adoption request approved.');
    }

    public function reject(AdoptionRequest $adoptionRequest)
    {
        DB::transaction(function () use ($adoptionRequest) {
            
            DB::statement("BEGIN sp_process_adoption_request(:request_id, :status, :reviewer_id, :remarks); END;", [
                'request_id' => $adoptionRequest->request_id,
                'status' => 'REJECTED',
                'reviewer_id' => auth()->id(),
                'remarks' => 'Rejected by shelter staff.',
            ]);

            $hasPending = AdoptionRequest::where('pet_id', $adoptionRequest->pet_id)
                ->where('status', 'PENDING')
                ->exists();

            if (! $hasPending && $adoptionRequest->pet?->adoption_status === 'PENDING') {
                $adoptionRequest->pet->update(['adoption_status' => 'AVAILABLE']);
            }
        });

        return back()->with('success', 'Adoption request rejected.');
    }

    public function history()
    {
        $requests = AdoptionRequest::with(['pet', 'user'])
            ->latest('request_date')
            ->paginate(10);

        return view('dashboards.user', compact('requests'));
    }
}
