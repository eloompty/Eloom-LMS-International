<?php

namespace Modules\Event\Http\Controllers\Trainer;

use Illuminate\Routing\Controller;
use Modules\Event\Entities\EventRegistration;
use Modules\Event\Entities\LmsEvent;

class TrainerEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    public function index()
    {
        $trainer = auth('trainer')->user();

        $myRegistrationEventIds = EventRegistration::where('registrant_type', 'trainer')
            ->where('registrant_id', $trainer->id)
            ->pluck('event_id');

        $events = LmsEvent::where('status', 1)
            ->with('sessions')
            ->withCount('confirmedRegistrations')
            ->orderByDesc('id')
            ->get()
            ->map(function ($event) use ($myRegistrationEventIds) {
                $event->trainer_registered = $myRegistrationEventIds->contains($event->id);
                return $event;
            });

        return view('event::trainer.index', compact('events'));
    }
}
