<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Trainer\Entities\TrainerIntake;

class TrainerSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the submissions.
     * @return Renderable
     */
    public function index()
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $submissions = AssignmentSubmission::join('assignments', 'assignments.id', '=', 'assignment_submissions.assignment_id')
            ->where('assignments.trainer_id', $trainer_id)
            ->select('assignment_submissions.*')
            ->with(['assignment', 'assignmentGrade', 'assignmentSubmissionGrade'])
            ->orderBy('assignment_submissions.id', 'desc')
            ->get();
        foreach ($submissions as $key => $value) {
            $trainerIntakeQuery = TrainerIntake::where('trainer_id', $trainer_id)->whereIn('status', [1, 3]);

            if ($value->assignment && $value->assignment->intake_unit_id) {
                $trainerIntake = $trainerIntakeQuery->where('intake_unit_id', $value->assignment->intake_unit_id)->first();
                $submissions[$key]['submission_edit_route'] = 'trainer.submission.edit';
                $submissions[$key]['submission_pdf_route'] = 'trainer.submission.pdf';
                $submissions[$key]['submission_question_route'] = 'trainer.submission.question.index';
                $submissions[$key]['submission_mcq_route'] = 'trainer.submission.mcq.index';
            } elseif ($value->assignment && $value->assignment->intake_subject_id) {
                $trainerIntake = $trainerIntakeQuery->where('intake_subject_id', $value->assignment->intake_subject_id)->first();
                $submissions[$key]['submission_edit_route'] = 'trainer.subject.submission.edit';
                $submissions[$key]['submission_pdf_route'] = 'trainer.subject.submission.pdf';
                $submissions[$key]['submission_question_route'] = 'trainer.subject.submission.question.index';
                $submissions[$key]['submission_mcq_route'] = 'trainer.subject.submission.mcq.index';
            } else {
                $trainerIntake = null;
                $submissions[$key]['submission_edit_route'] = null;
                $submissions[$key]['submission_pdf_route'] = null;
                $submissions[$key]['submission_question_route'] = null;
                $submissions[$key]['submission_mcq_route'] = null;
            }

            $submissions[$key]['trainer_intake_id'] = $trainerIntake ? $trainerIntake->id : null;
            $submissions[$key]['trainer_course_id'] = $trainerIntake ? $trainerIntake->intake_course_id : null;
        }
        activityLog('Trainer', 'Opened submissions menu from web');
        return view('trainer::trainer.submission.index', compact('submissions'))->with('no', 1);
    }
}
