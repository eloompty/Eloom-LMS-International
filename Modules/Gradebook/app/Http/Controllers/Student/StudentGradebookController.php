<?php

namespace Modules\Gradebook\Http\Controllers\Student;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Gradebook\Services\GradebookService;
use Modules\Gradebook\Entities\GradeAppeal;
use Modules\Student\Entities\StudentIntakeCourse;
use Dompdf\Dompdf;
use Dompdf\Options;

class StudentGradebookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    public function courses()
    {
        $student = auth('student')->user();
        $enrolledCourses = StudentIntakeCourse::with('intakeCourse.course', 'intakeCourse.intake')
            ->where('student_id', $student->id)
            ->where('status', 1)
            ->get();
        return view('gradebook::student.courses', compact('enrolledCourses'));
    }

    public function index($studentIntakeCourseId)
    {
        $student      = auth('student')->user();
        $intakeCourse = StudentIntakeCourse::where('student_id', $student->id)
            ->with('intakeCourse.course', 'intakeCourse.intake')
            ->findOrFail($studentIntakeCourseId);

        $data = (new GradebookService)->build($student->id, $studentIntakeCourseId);

        return view('gradebook::student.index', compact('student', 'intakeCourse', 'data'));
    }

    public function transcript($studentIntakeCourseId)
    {
        $student      = auth('student')->user();
        $intakeCourse = StudentIntakeCourse::where('student_id', $student->id)
            ->with('intakeCourse.course', 'intakeCourse.intake')
            ->findOrFail($studentIntakeCourseId);

        $data = (new GradebookService)->build($student->id, $studentIntakeCourseId);

        $html = view('gradebook::student.transcript-pdf', compact('student', 'intakeCourse', 'data'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "transcript_" . now()->format('Ymd') . ".pdf";

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function submitAppeal(Request $request, $markType, $markId)
    {
        $request->validate(['reason' => 'required|string|max:2000']);

        $student = auth('student')->user();

        GradeAppeal::create([
            'student_id' => $student->id,
            'mark_type'  => $markType,
            'mark_id'    => $markId,
            'reason'     => $request->reason,
            'status'     => 'pending',
        ]);

        return redirect()->back()->with('success', 'Grade appeal submitted successfully');
    }

    public function myAppeals()
    {
        $student = auth('student')->user();
        $appeals = GradeAppeal::where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        return view('gradebook::student.appeals', compact('appeals'));
    }
}
