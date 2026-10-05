<?php

namespace Modules\Gradebook\Http\Controllers\Trainer;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Gradebook\Services\GradebookService;
use Modules\Gradebook\Entities\GradeAppeal;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\TrainerIntake;

class TrainerGradebookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    // Lists all students in intakes assigned to this trainer
    public function index()
    {
        $trainer = auth('trainer')->user();

        $trainerIntakeCourseIds = TrainerIntake::where('trainer_id', $trainer->id)
            ->where('status', 1)
            ->pluck('intake_course_id')
            ->filter()
            ->unique();

        $intakeCourses = StudentIntakeCourse::with([
            'student',
            'intakeCourse.course',
            'intakeCourse.intake',
        ])
        ->whereHas('intakeCourse', fn ($q) => $q->whereIn('id', $trainerIntakeCourseIds))
        ->where('status', 1)
        ->get()
        ->groupBy('student_id');

        return view('gradebook::trainer.index', compact('intakeCourses'));
    }

    public function show($studentId, $studentIntakeCourseId)
    {
        $trainer      = auth('trainer')->user();
        $student      = Student::findOrFail($studentId);
        $intakeCourse = StudentIntakeCourse::with('intakeCourse.course', 'intakeCourse.intake')
            ->findOrFail($studentIntakeCourseId);

        $data = (new GradebookService)->build($studentId, $studentIntakeCourseId);

        return view('gradebook::trainer.show', compact('trainer', 'student', 'intakeCourse', 'data'));
    }

    // Trainer reviews a pending appeal for marks in their units
    public function respondToAppeal(Request $request, $appealId)
    {
        $request->validate(['trainer_response' => 'required|string|max:2000']);

        $trainer = auth('trainer')->user();
        $appeal  = GradeAppeal::where('status', 'pending')->findOrFail($appealId);

        $appeal->update([
            'trainer_response' => $request->trainer_response,
            'trainer_id'       => $trainer->id,
            'status'           => 'trainer_reviewed',
        ]);

        return redirect()->back()->with('success', 'Response submitted to admin for review');
    }

    public function pendingAppeals()
    {
        $appeals = GradeAppeal::with('student')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        return view('gradebook::trainer.appeals', compact('appeals'));
    }
}
