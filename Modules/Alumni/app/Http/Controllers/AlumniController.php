<?php

namespace Modules\Alumni\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Alumni\Entities\AlumniProfile;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\CRM\Entities\Lead;

class AlumniController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        $alumni = AlumniProfile::with('student', 'intakeCourse.intakeCourse.course')
            ->where('status', 1)
            ->orderByDesc('graduation_date')
            ->paginate(30);
        return view('alumni::admin.index', compact('alumni'));
    }

    // Manually create an alumni profile for a student who completed a course
    public function create()
    {
        $students = Student::where('status', 1)->orderBy('first_name')->get();
        $studentIds = $students->pluck('id')->toArray();
        $studentIntakeCourses = StudentIntakeCourse::with('intakeCourse.course', 'intakeCourse.intake')
            ->whereIn('student_id', $studentIds)
            ->where('is_enrolled', 1)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        $studentCourses = [];
        foreach ($studentIntakeCourses as $studentIntakeCourse) {
            $intakeCourse = $studentIntakeCourse->intakeCourse;
            $courseName = $intakeCourse && $intakeCourse->course ? $intakeCourse->course->course_name : 'Course';
            $intakeName = $intakeCourse && $intakeCourse->intake ? $intakeCourse->intake->name : null;
            $referenceName = $intakeCourse ? $intakeCourse->reference_name : null;
            $label = $courseName;
            if ($referenceName) {
                $label .= ' - ' . $referenceName;
            }
            if ($intakeName) {
                $label .= ' (' . $intakeName . ')';
            }
            $label .= ' [ID: ' . $studentIntakeCourse->id . ']';

            $studentCourses[$studentIntakeCourse->student_id][] = [
                'id' => $studentIntakeCourse->id,
                'text' => $label,
            ];
        }

        return view('alumni::admin.create', compact('students', 'studentCourses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'               => 'required|exists:students,id',
            'student_intake_course_id' => 'required|exists:student_intake_courses,id',
        ]);

        $studentIntakeCourse = StudentIntakeCourse::where('id', $request->student_intake_course_id)
            ->where('student_id', $request->student_id)
            ->where('is_enrolled', 1)
            ->where('status', 1)
            ->first();

        if (!$studentIntakeCourse) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['student_intake_course_id' => 'Please select an enrolled intake course for the selected student.']);
        }

        AlumniProfile::firstOrCreate(
            ['student_id' => $request->student_id],
            [
                'student_intake_course_id' => $request->student_intake_course_id,
                'graduation_date'          => $request->graduation_date ?? now()->toDateString(),
                'status'                   => 1,
            ]
        );

        activityLog('Admin', "Alumni profile created for student_id: {$request->student_id}");
        return redirect()->route('admin.alumni.index')->with('success', 'Alumni profile created');
    }

    public function show($id)
    {
        $alumni = AlumniProfile::with('student', 'intakeCourse.intakeCourse.course')->findOrFail($id);
        return view('alumni::admin.show', compact('alumni'));
    }

    // Convert alumni re-enrollment interest into a CRM lead
    public function convertToLead($id)
    {
        $alumni   = AlumniProfile::with('student')->findOrFail($id);
        $student  = $alumni->student;

        Lead::firstOrCreate(
            ['email' => $student->email],
            [
                'first_name'   => $student->first_name,
                'family_name'  => $student->last_name,
                'email'        => $student->email,
                'phone'        => $student->phone ?? '',
                'lead_status'  => 'Alumni Re-Enrollment',
                'approval_state' => 'approved',
                'status'       => 1,
            ]
        );

        activityLog('Admin', "Alumni alumni_id: {$id} converted to CRM lead");
        return redirect()->back()->with('success', 'Alumni added to CRM as re-enrollment lead');
    }

    // Alumni directory (opt-in members only)
    public function directory()
    {
        $alumni = AlumniProfile::with('student')
            ->where('directory_visible', true)
            ->where('status', 1)
            ->orderBy('id')
            ->get();
        return view('alumni::admin.directory', compact('alumni'));
    }
}
