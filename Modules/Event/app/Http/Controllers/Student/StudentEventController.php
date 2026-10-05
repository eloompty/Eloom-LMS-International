<?php

namespace Modules\Event\Http\Controllers\Student;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Event\Entities\LmsEvent;
use Modules\Event\Entities\EventRegistration;

class StudentEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    public function index()
    {
        $student = auth('student')->user();

        $myRegistrationEventIds = EventRegistration::where('registrant_type', 'student')
            ->where('registrant_id', $student->id)
            ->pluck('event_id');

        $events = LmsEvent::where('status', 1)
            ->with('sessions')
            ->withCount('confirmedRegistrations')
            ->orderByDesc('id')
            ->get()
            ->map(function ($event) use ($myRegistrationEventIds, $student) {
                $reg = EventRegistration::where('event_id', $event->id)
                    ->where('registrant_type', 'student')
                    ->where('registrant_id', $student->id)
                    ->first();
                $event->my_registration = $reg;
                return $event;
            });

        return view('event::student.index', compact('events'));
    }

    public function register($eventId)
    {
        $student = auth('student')->user();
        $event   = LmsEvent::where('status', 1)->findOrFail($eventId);

        // Check deadline
        if ($event->registration_deadline && now()->gt($event->registration_deadline)) {
            return redirect()->back()->with('failure', 'Registration deadline has passed');
        }

        // Already registered?
        $existing = EventRegistration::where('event_id', $eventId)
            ->where('registrant_type', 'student')
            ->where('registrant_id', $student->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('info', 'You are already registered for this event');
        }

        // Determine status: registered or waitlisted
        $status = 'registered';
        if ($event->isFull()) {
            if (!$event->waiting_list_enabled) {
                return redirect()->back()->with('failure', 'This event is full');
            }
            $status = 'waitlisted';
        }

        EventRegistration::create([
            'event_id'       => $eventId,
            'registrant_type'=> 'student',
            'registrant_id'  => $student->id,
            'status'         => $status,
            'registered_at'  => now(),
        ]);

        $message = $status === 'waitlisted'
            ? 'Added to waiting list — you will be notified if a spot opens'
            : 'Registered successfully!';

        return redirect()->back()->with('success', $message);
    }

    public function cancel($eventId)
    {
        $student = auth('student')->user();

        EventRegistration::where('event_id', $eventId)
            ->where('registrant_type', 'student')
            ->where('registrant_id', $student->id)
            ->whereIn('status', ['registered', 'waitlisted'])
            ->delete();

        return redirect()->back()->with('success', 'Registration cancelled');
    }
}
