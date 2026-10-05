<?php

namespace Modules\Announcement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Announcement\Entities\Announcement;
use Modules\Course\Entities\Course;
use Modules\Intake\Entities\Intake;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\Trainer;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        $announcements = Announcement::where('status', 1)->orderByDesc('id')->paginate(20);
        return view('announcement::admin.index', compact('announcements'));
    }

    public function create()
    {
        $intakes = Intake::where('status', 1)->orderBy('name')->get();
        $courses = Course::where('status', 1)->orderBy('course_name')->get();
        return view('announcement::admin.create', compact('intakes', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'audience_type'=> 'required|in:all,intake,course,role,student,trainer',
            'priority'     => 'required|in:normal,important,urgent',
            'publish_at'   => 'nullable|date',
            'expires_at'   => 'nullable|date|after:publish_at',
        ]);

        $announcement = Announcement::create([
            'title'                   => $request->title,
            'body'                    => $request->body,
            'audience_type'           => $request->audience_type,
            'audience_id'             => $request->audience_id,
            'priority'                => $request->priority,
            'require_acknowledgement' => $request->boolean('require_acknowledgement'),
            'is_pinned'               => $request->boolean('is_pinned'),
            'publish_at'              => $request->publish_at,
            'expires_at'              => $request->expires_at,
            'created_by_user_id'      => auth('user')->id(),
            'created_by_type'         => 'user',
            'status'                  => 1,
        ]);

        // Dispatch in-app notifications immediately if publishing now
        if (!$announcement->publish_at || $announcement->publish_at->lte(now())) {
            $this->dispatchNotifications($announcement, auth('user')->id());
        }

        activityLog('Admin', "Announcement created: {$request->title}");
        return redirect()->route('admin.announcement.index')->with('success', 'Announcement published');
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $intakes      = Intake::where('status', 1)->orderBy('name')->get();
        $courses      = Course::where('status', 1)->orderBy('course_name')->get();
        return view('announcement::admin.edit', compact('announcement', 'intakes', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'audience_type'=> 'required|in:all,intake,course,role,student,trainer',
            'priority'     => 'required|in:normal,important,urgent',
        ]);

        $announcement = Announcement::findOrFail($id);
        $announcement->update([
            'title'                   => $request->title,
            'body'                    => $request->body,
            'audience_type'           => $request->audience_type,
            'audience_id'             => $request->audience_id,
            'priority'                => $request->priority,
            'require_acknowledgement' => $request->boolean('require_acknowledgement'),
            'is_pinned'               => $request->boolean('is_pinned'),
            'publish_at'              => $request->publish_at,
            'expires_at'              => $request->expires_at,
        ]);

        activityLog('Admin', "Announcement_id: {$id} updated");
        return redirect()->route('admin.announcement.index')->with('success', 'Announcement updated');
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->update(['status' => 2]);
        activityLog('Admin', "Announcement_id: {$id} deleted");
        return redirect()->back()->with('success', 'Announcement deleted');
    }

    private function dispatchNotifications(Announcement $announcement, int $senderId): void
    {
        $studentIds = collect();
        $trainerIds = collect();

        switch ($announcement->audience_type) {
            case 'all':
                $studentIds = Student::where('status', 1)->where('is_enrolled', 1)->pluck('id');
                $trainerIds = Trainer::where('status', 1)->pluck('id');
                break;

            case 'intake':
                $studentIds = StudentIntakeCourse::whereHas(
                    'intakeCourse', fn ($q) => $q->where('intake_id', $announcement->audience_id)
                )->where('is_enrolled', 1)->where('status', 1)->pluck('student_id')->unique();
                break;

            case 'course':
                $studentIds = StudentIntakeCourse::whereHas(
                    'intakeCourse', fn ($q) => $q->where('course_id', $announcement->audience_id)
                )->where('is_enrolled', 1)->where('status', 1)->pluck('student_id')->unique();
                break;

            case 'student':
                $studentIds = collect([$announcement->audience_id]);
                break;

            case 'trainer':
                $trainerIds = collect([$announcement->audience_id]);
                break;

            case 'role':
                // audience_id not used for role; target both portals
                $studentIds = Student::where('status', 1)->where('is_enrolled', 1)->pluck('id');
                $trainerIds = Trainer::where('status', 1)->pluck('id');
                break;
        }

        $payload = [
            'sender_type' => 'Admin',
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

        foreach ($trainerIds as $trainerId) {
            Notification::create(array_merge($payload, ['user_type' => 'Trainer', 'user_id' => $trainerId]));
        }
    }
}
