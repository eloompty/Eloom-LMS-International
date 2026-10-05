<?php

namespace Modules\Intake\Http\Controllers;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Attendance\Entities\Attendance;
use Modules\Attendance\Entities\ClassSession;
use Modules\Attendance\Services\AttendanceMarker;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeTime;
use Modules\Student\Entities\StudentIntakeSubject;

class IntakeSubjectAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Timetable-driven sessions list + (optionally) a selected session's marking roster.
     */
    public function index(Request $request, $id, $year, $month)
    {
        $intakeSubject = IntakeSubject::findorfail($id);
        $check = checkCourseDeliverySite($intakeSubject->intakeCourse->course_id);
        if (checkRole('intake_course', 'view') != true || $check != true) {
            return abort(404);
        }

        $intakeCourseStartingDate = $intakeSubject->intakeCourse->starting_date;
        $start    = new DateTime($intakeCourseStartingDate);
        $end      = new DateTime(date('Y-m-d'));
        $interval = DateInterval::createFromDateString('1 year');
        $period   = new DatePeriod($start, $interval, $end);
        $intakeYears = [];
        foreach ($period as $dt) {
            $intakeYears[] = $dt->format("Y");
        }
        if (!in_array(date('Y'), $intakeYears)) {
            $intakeYears[] = date('Y');
        }

        $students = StudentIntakeSubject::where('intake_subject_id', $id)->where('status', 1)
            ->orderBy('id', 'desc')->get()
            ->filter(fn ($ss) => optional($ss->studentIntakeCourse)->student_id)
            ->map(fn ($ss) => (object) [
                'id' => $ss->studentIntakeCourse->student_id,
                'name' => userName('Student', $ss->studentIntakeCourse->student_id),
            ])->values();

        $marker = new AttendanceMarker();
        $slots = $marker->timetableSlots('subject', $id, $year, $month);
        $hasAnyTimetable = IntakeTime::where('intake_subject_id', $id)->where('status', '!=', 2)->exists();

        $selectedSession = null;
        $existingMarks = collect();
        if ($request->filled('time')) {
            $selectedSession = $marker->sessionForSlot('subject', (int) $id, (int) $request->time);
            $existingMarks = Attendance::where('class_session_id', $selectedSession->id)->get()->keyBy('student_id');
        }

        activityLog('Admin', $intakeSubject->subject->name . ' subject attendance opened');

        return view('intake::course.subject.attendance.index', [
            'intakeSubject' => $intakeSubject,
            'title' => $intakeSubject->subject->name,
            'scope' => 'subject',
            'scopeId' => (int) $id,
            'students' => $students,
            'slots' => $slots,
            'selectedSession' => $selectedSession,
            'existingMarks' => $existingMarks,
            'year' => $year,
            'month' => $month,
            'intakeYears' => $intakeYears,
            'hasAnyTimetable' => $hasAnyTimetable,
            'indexRoute' => 'admin.intake.subject.attendance.index',
            'markRoute' => 'admin.intake.subject.attendance.mark',
            'createTimetableUrl' => route('admin.intake.subject.time.index', $id),
            'periodBase' => url('admin/intake/course/semester/subject/attendance'),
            'yearAjaxUrl' => url('admin/intake/course/semester/subject/attendance/year'),
        ]);
    }

    /**
     * Save the roster marks for a materialised session.
     */
    public function mark(Request $request, $sessionId)
    {
        $session = ClassSession::findOrFail($sessionId);
        $intakeSubject = IntakeSubject::findorfail($session->intake_subject_id);
        if (checkRole('intake_course', 'view') != true || checkCourseDeliverySite($intakeSubject->intakeCourse->course_id) != true) {
            return abort(404);
        }

        $marks = $this->collectMarks($request);
        (new AttendanceMarker())->markSession($session, $marks, Auth::guard('user')->user()->id, 'Admin');
        activityLog('Admin', $intakeSubject->subject->name . ' attendance marked for ' . $session->date);

        return redirect()->to(route('admin.intake.subject.attendance.index', [
            $session->intake_subject_id, date('Y', strtotime($session->date)), date('m', strtotime($session->date)),
        ]) . '?time=' . $session->intake_time_id)->with('success', 'Attendance saved');
    }

    private function collectMarks(Request $request): array
    {
        $statuses = $request->input('status', []);
        $remarks = $request->input('remarks', []);
        $feedback = $request->input('feedback', []);
        $visible = $request->input('feedback_visible', []);
        $leftEarly = $request->input('left_early', []);

        $marks = [];
        foreach ($statuses as $studentId => $status) {
            $marks[$studentId] = [
                'attendance_status' => $status,
                'remarks' => $remarks[$studentId] ?? null,
                'feedback' => $feedback[$studentId] ?? null,
                'feedback_visible_to_student' => !empty($visible[$studentId]),
                'left_early_at' => $leftEarly[$studentId] ?? null,
            ];
        }
        return $marks;
    }

    /* Get lists of month by year */
    public function getMonthByYear(Request $request)
    {
        $year = $request->year;
        $intakeId = $request->intakeid;
        $intakeSubject = IntakeSubject::find($intakeId);
        $intakeCourseStartingDate = $intakeSubject->intakeCourse->starting_date;
        $startYear = date("Y", strtotime($intakeCourseStartingDate));
        if ($startYear == $year && date('Y') == $year) {
            $start = new DateTime($intakeCourseStartingDate);
            $end = new DateTime(date('Y-m-d'));
        } elseif ($startYear == $year && date('Y') != $year) {
            $start = new DateTime($intakeCourseStartingDate);
            $enddate = date($year . '-' . '12-31');
            $end = new DateTime($enddate);
        } elseif ($startYear != $year && date('Y') == $year) {
            $startDate  = date($year . '-' . '01-01');
            $start = new DateTime($startDate);
            $end = new DateTime(date('Y-m-d'));
        } elseif ($startYear != $year && date('Y') != $year) {
            $startDate  = date($year . '-' . '01-01');
            $start = new DateTime($startDate);
            $enddate = date($year . '-' . '12-31');
            $end = new DateTime($enddate);
        }
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        $intakeMonths = [];
        foreach ($period as $dt) {
            $intakeMonths[$dt->format("m")] =  $dt->format("F");
        }
        if (empty($intakeMonths) && $year == date('Y')) {
            $intakeMonths[date('m')] = date('F');
        }
        return response()->json($intakeMonths);
    }
}
