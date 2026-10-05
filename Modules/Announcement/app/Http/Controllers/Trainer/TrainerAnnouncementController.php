<?php

namespace Modules\Announcement\Http\Controllers\Trainer;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Entities\AnnouncementAcknowledgement;
use Modules\Intake\Entities\Intake;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;

class TrainerAnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    public function index()
    {
        $announcements = Announcement::visible()
            ->where(fn ($q) => $q->where('audience_type', 'all')->orWhere('audience_type', 'trainer'))
            ->orderByDesc('is_pinned')
            ->orderByDesc('id')
            ->get();

        $trainer = auth('trainer')->user();
        $acknowledgedIds = AnnouncementAcknowledgement::where('reader_type', 'trainer')
            ->where('reader_id', $trainer->id)
            ->pluck('announcement_id')
            ->toArray();

        return view('announcement::trainer.index', compact('announcements', 'acknowledgedIds'));
    }

    // Trainers can post announcements targeting their intake cohorts
    public function create()
    {
        $intakes = Intake::where('status', 1)->orderBy('name')->get();
        return view('announcement::trainer.create', compact('intakes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'body'          => 'required|string',
            'audience_type' => 'required|in:all,intake,student',
            'priority'      => 'required|in:normal,important,urgent',
        ]);

        $trainer = auth('trainer')->user();

        $announcement = Announcement::create([
            'title'              => $request->title,
            'body'               => $request->body,
            'audience_type'      => $request->audience_type,
            'audience_id'        => $request->audience_id,
            'priority'           => $request->priority,
            'is_pinned'          => $request->boolean('is_pinned'),
            'publish_at'         => now(),
            'expires_at'         => $request->expires_at,
            'created_by_user_id' => $trainer->id,
            'created_by_type'    => 'trainer',
            'status'             => 1,
        ]);

        $this->dispatchNotifications($announcement, $trainer->id);

        return redirect()->route('trainer.announcement.index')->with('success', 'Announcement posted');
    }

    public function acknowledge($id)
    {
        $trainer = auth('trainer')->user();

        AnnouncementAcknowledgement::firstOrCreate([
            'announcement_id' => $id,
            'reader_type'     => 'trainer',
            'reader_id'       => $trainer->id,
        ], [
            'acknowledged_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Acknowledged');
    }

    private function dispatchNotifications(Announcement $announcement, int $senderId): void
    {
        $studentIds = collect();

        switch ($announcement->audience_type) {
            case 'all':
                $studentIds = Student::where('status', 1)->where('is_enrolled', 1)->pluck('id');
                break;

            case 'intake':
                $studentIds = StudentIntakeCourse::whereHas(
                    'intakeCourse', fn ($q) => $q->where('intake_id', $announcement->audience_id)
                )->where('is_enrolled', 1)->where('status', 1)->pluck('student_id')->unique();
                break;

            case 'student':
                $studentIds = collect([$announcement->audience_id]);
                break;
        }

        $payload = [
            'sender_type' => 'Trainer',
            'sender_id'   => $senderId,
            'title'       => $announcement->title,
            'body'        => strip_tags($announcement->body),
            'type'        => 'Announcement',
            'link'        => (string) $announcement->id,
            'status'      => 1,
        ];

        foreach ($studentIds as $studentId) {
            Notification::create(array_merge($payload, ['user_type' => 'Student', 'user_id' => $studentId]));
        }
    }
}
