<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\MedicalRecord;
use App\Models\Pet;

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

        $upcomingEvents = \App\Models\Event::withCount([
            'enrollments as going_count' => function ($query) {
                $query->where('status', 'GOING');
            },
            'enrollments as interested_count' => function ($query) {
                $query->where('status', 'INTERESTED');
            }
        ])
        ->where('event_date', '>=', now()->startOfDay())
        ->orderBy('event_date', 'asc')
        ->get();

        if (auth()->check()) {
            $userId = auth()->id();
            $upcomingEvents->each(function ($event) use ($userId) {
                $enrollment = $event->enrollments()->where('user_id', $userId)->first();
                $event->user_status = $enrollment ? $enrollment->status : null;
            });
        }

        return view('home', compact('stats', 'featuredPets', 'upcomingEvents'));
    }
}
