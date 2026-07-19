<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventEnrollment;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:1000',
            'event_date' => 'required|date|after_or_equal:today',
            'location' => 'required|string|max:255',
        ]);

        \Illuminate\Support\Facades\DB::statement(
            "BEGIN sp_create_event(:title, :description, :event_date, :location, :created_by); END;",
            [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'event_date' => $validated['event_date'],
                'location' => $validated['location'],
                'created_by' => auth()->id(),
            ]
        );

        return redirect()->back()->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        $event->load(['enrollments.user']);

        $goingEnrollments = $event->enrollments->where('status', 'GOING');
        $interestedEnrollments = $event->enrollments->where('status', 'INTERESTED');

        return view('events.show', compact('event', 'goingEnrollments', 'interestedEnrollments'));
    }

    public function enroll(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|string|in:INTERESTED,GOING,CANCEL',
        ]);

        $userId = auth()->id();
        $status = $request->status;

        if ($status === 'CANCEL') {
            EventEnrollment::where('event_id', $event->event_id)
                ->where('user_id', $userId)
                ->delete();
            return redirect()->back()->with('success', 'Your event enrollment has been cancelled.');
        }

        try {
            \Illuminate\Support\Facades\DB::statement(
                "BEGIN sp_enroll_event(:event_id, :user_id, :status); END;",
                [
                    'event_id' => $event->event_id,
                    'user_id' => $userId,
                    'status' => $status,
                ]
            );
            return redirect()->back()->with('success', 'Your response has been saved.');
        } catch (\Illuminate\Database\QueryException $e) {
            
            if (str_contains($e->getMessage(), '20003')) {
                return redirect()->back()->with('error', 'Cannot enroll in a past event.');
            }
            throw $e;
        }
    }
}
