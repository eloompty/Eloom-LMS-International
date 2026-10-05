<?php

namespace Modules\Announcement\Http\Controllers\Student;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Entities\AnnouncementAcknowledgement;
use Modules\Student\Entities\StudentIntakeCourse;

class StudentAnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    public function index()
    {
        $student = auth('student')->user();

        // Get the student's active intake IDs and course IDs for targeted filtering
        $intakeIds = StudentIntakeCourse::where('student_id', $student->id)
            ->with('intakeCourse')
            ->get()
            ->pluck('intakeCourse.intake_id')
            ->filter()
            ->unique()
            ->values();

        $announcements = Announcement::visible()
            ->where(function ($q) use ($intakeIds) {
                $q->where('audience_type', 'all')
                  ->orWhere('audience_type', 'student')
                  ->orWhere(fn ($q2) => $q2->where('audience_type', 'intake')->whereIn('audience_id', $intakeIds));
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('id')
            ->get();

        $acknowledgedIds = AnnouncementAcknowledgement::where('reader_type', 'student')
            ->where('reader_id', $student->id)
            ->pluck('announcement_id')
            ->toArray();

        return view('announcement::student.index', compact('announcements', 'acknowledgedIds'));
    }

    public function acknowledge(Request $request, $id)
    {
        $student = auth('student')->user();

        AnnouncementAcknowledgement::firstOrCreate([
            'announcement_id' => $id,
            'reader_type'     => 'student',
            'reader_id'       => $student->id,
        ], [
            'acknowledged_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Acknowledged');
    }
}
