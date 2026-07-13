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

        $validated['created_by'] = auth()->id();

        Event::create($validated);

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
            EventEnrollment::updateOrCreate(
                ['event_id' => $event->event_id, 'user_id' => $userId],
                ['status' => $status]
            );
            return redirect()->back()->with('success', 'Your response has been saved.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for Oracle ORA-20003 past event trigger exception
            if (str_contains($e->getMessage(), '20003')) {
                return redirect()->back()->with('error', 'Cannot enroll in a past event.');
            }
            throw $e;
        }
    }
}
