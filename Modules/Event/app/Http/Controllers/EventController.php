<?php

namespace Modules\Event\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Event\Entities\LmsEvent;
use Modules\Event\Entities\EventSession;
use Modules\Event\Entities\EventRegistration;
use Modules\Certificate\Services\CertificateService;
use Modules\Certificate\Entities\CertificateTemplate;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        $events = LmsEvent::where('status', 1)->withCount('confirmedRegistrations')->orderByDesc('id')->paginate(20);
        return view('event::admin.index', compact('events'));
    }

    public function create()
    {
        $certTemplates = CertificateTemplate::orderBy('name')->get();
        return view('event::admin.create', compact('certTemplates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:online,physical,hybrid',
        ]);

        $event = LmsEvent::create([
            'title'                  => $request->title,
            'description'            => $request->description,
            'type'                   => $request->type,
            'location_id'            => $request->location_id,
            'online_link'            => $request->online_link,
            'capacity'               => $request->capacity,
            'registration_deadline'  => $request->registration_deadline,
            'waiting_list_enabled'   => $request->boolean('waiting_list_enabled'),
            'issue_certificate'      => $request->boolean('issue_certificate'),
            'certificate_template_id'=> $request->certificate_template_id,
            'created_by_user_id'     => auth('user')->id(),
            'status'                 => 1,
        ]);

        // Store sessions
        if ($request->has('sessions')) {
            foreach ($request->sessions as $session) {
                if (!empty($session['title']) && !empty($session['starts_at'])) {
                    EventSession::create([
                        'event_id'  => $event->id,
                        'title'     => $session['title'],
                        'starts_at' => $session['starts_at'],
                        'ends_at'   => $session['ends_at'] ?? $session['starts_at'],
                    ]);
                }
            }
        }

        activityLog('Admin', "Event created: {$event->title}");
        return redirect()->route('admin.event.index')->with('success', 'Event created');
    }

    public function show($id)
    {
        $event = LmsEvent::with('sessions', 'registrations')->findOrFail($id);
        return view('event::admin.show', compact('event'));
    }

    public function edit($id)
    {
        $event = LmsEvent::with('sessions')->findOrFail($id);
        return view('event::admin.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $event = LmsEvent::findOrFail($id);
        $event->update($request->only([
            'title','description','type','location_id','online_link',
            'capacity','registration_deadline','waiting_list_enabled',
            'issue_certificate','certificate_template_id',
        ]));
        activityLog('Admin', "Event_id: {$id} updated");
        return redirect()->route('admin.event.index')->with('success', 'Event updated');
    }

    public function destroy($id)
    {
        LmsEvent::findOrFail($id)->update(['status' => 2]);
        activityLog('Admin', "Event_id: {$id} deleted");
        return redirect()->back()->with('success', 'Event deleted');
    }

    // Mark attendees post-event and optionally issue certificates
    public function markAttendance(Request $request, $id)
    {
        $event       = LmsEvent::findOrFail($id);
        $attendeeIds = $request->input('attendee_ids', []);

        EventRegistration::where('event_id', $id)
            ->whereIn('id', $attendeeIds)
            ->update(['status' => 'attended', 'attended_at' => now()]);

        // Issue participation certificates if configured
        if ($event->issue_certificate && $event->certificate_template_id) {
            $service = new CertificateService();
            foreach ($attendeeIds as $regId) {
                $reg = EventRegistration::find($regId);
                if ($reg && $reg->registrant_type === 'student') {
                    $service->issue(
                        $reg->registrant_id,
                        $event->certificate_template_id,
                        'event',
                        null,
                        null,
                        auth('user')->id()
                    );
                }
            }
        }

        activityLog('Admin', "Attendance marked for event_id: {$id}");
        return redirect()->back()->with('success', 'Attendance recorded');
    }

    // Promote from waiting list when a spot opens
    public function promoteWaitlist($eventId)
    {
        $next = EventRegistration::where('event_id', $eventId)
            ->where('status', 'waitlisted')
            ->orderBy('registered_at')
            ->first();

        if ($next) {
            $next->update(['status' => 'registered']);
            activityLog('Admin', "Waitlisted registration_id: {$next->id} promoted to registered for event_id: {$eventId}");
            return redirect()->back()->with('success', 'Participant promoted from waiting list');
        }

        return redirect()->back()->with('info', 'No one on the waiting list');
    }
}
