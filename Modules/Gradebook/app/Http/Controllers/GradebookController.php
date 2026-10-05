<?php

namespace Modules\Gradebook\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gradebook\Services\GradebookService;
use Modules\Gradebook\Entities\GradeAppeal;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Dompdf\Dompdf;
use Dompdf\Options;

class GradebookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index($studentId, $studentIntakeCourseId)
    {
        $student      = Student::findOrFail($studentId);
        $intakeCourse = StudentIntakeCourse::with('intakeCourse.course', 'intakeCourse.intake')
            ->findOrFail($studentIntakeCourseId);

        $data = (new GradebookService)->build($studentId, $studentIntakeCourseId);

        activityLog('Admin', "Viewed gradebook for student_id: {$studentId}");

        return view('gradebook::admin.index', compact('student', 'intakeCourse', 'data'));
    }

    public function transcript($studentId, $studentIntakeCourseId)
    {
        $student      = Student::findOrFail($studentId);
        $intakeCourse = StudentIntakeCourse::with('intakeCourse.course', 'intakeCourse.intake')
            ->findOrFail($studentIntakeCourseId);

        $data = (new GradebookService)->build($studentId, $studentIntakeCourseId);

        $html = view('gradebook::admin.transcript-pdf', compact('student', 'intakeCourse', 'data'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "transcript_{$student->id}_" . now()->format('Ymd') . ".pdf";
        activityLog('Admin', "Generated transcript for student_id: {$studentId}");

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // Admin landing — list all enrolled students with links to their individual gradebooks
    public function students()
    {
        $intakeCourses = StudentIntakeCourse::with('student', 'intakeCourse.course', 'intakeCourse.intake')
            ->where('is_enrolled', 1)
            ->where('status', 1)
            ->orderBy('student_id')
            ->paginate(40);

        activityLog('Admin', 'Viewed gradebook student list');
        return view('gradebook::admin.students', compact('intakeCourses'));
    }

    // Grade appeal management
    public function appeals()
    {
        $appeals = GradeAppeal::with([
            'student',
            'subjectMark.studentIntakeSubject.intakeSubject.subject',
            'subjectMark.studentIntakeSubject.intakeSubject.intakeCourse.course',
            'subjectMark.studentIntakeSubject.intakeSubject.intakeCourse.intake',
            'unitMark.studentIntakeUnit.intakeUnit.unit',
            'unitMark.studentIntakeUnit.intakeUnit.intakeSubject.subject',
            'unitMark.studentIntakeUnit.intakeUnit.intakeCourse.course',
            'unitMark.studentIntakeUnit.intakeUnit.intakeCourse.intake',
        ])->orderByDesc('created_at')->get();

        return view('gradebook::admin.appeals.index', compact('appeals'));
    }

    public function resolveAppeal(Request $request, $id)
    {
        $appeal = GradeAppeal::findOrFail($id);
        $appeal->update([
            'status'        => $request->status,
            'admin_notes'   => $request->admin_notes,
            'admin_user_id' => auth('user')->id(),
        ]);
        activityLog('Admin', "grade_appeal_id: {$id} resolved with status: {$request->status}");
        return redirect()->back()->with('success', 'Appeal updated successfully');
    }
}
