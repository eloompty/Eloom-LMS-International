<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Gradebook\Entities\GradeAppeal;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Entities\AnnouncementAcknowledgement;
use Modules\Course\Entities\WorkPlacement;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerIntake;
use Modules\Trainer\Entities\TrainerProfessionalDevelopment;
use Modules\Trainer\Entities\TrainerQualification;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /* Trainer Dashboard */
    public function dashboard()
    {
        activityLog('Trainer', 'Opened Trainer Dashboard from web');
        $id = Auth::guard('trainer')->user()->id;
        $trainerIntakeCourseIds = TrainerIntake::where('trainer_id', $id)
            ->whereIn('status', [1, 3])
            ->pluck('intake_course_id')
            ->filter()
            ->unique();

        $courses = TrainerIntake::where('trainer_id', $id)->whereIn('status', [1, 3])->distinct()->get(['intake_course_id']);
        $total_courses = $courses->count();
        $total_units = TrainerIntake::where('trainer_id', $id)
            ->whereIn('status', [1, 3])
            ->whereNotNull('intake_unit_id')
            ->distinct()
            ->count('intake_unit_id');
        $students = [];
        foreach ($courses as $key => $value) {
            $students[] = StudentIntakeCourse::where('intake_course_id', $value->intake_course_id)->whereIn('status', [1, 3])->count();
        }
        $total_students = array_sum($students);
        $total_assignments = Assignment::where('trainer_id', $id)->where('status', 1)->count();
        $submissions = AssignmentSubmission::join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.id')
        ->select('assignment_submissions.*')->where('assignments.trainer_id', $id)
        ->with(['assignment', 'assignmentGrade'])
        ->orderBy('assignment_submissions.id', 'desc')->paginate(5);
        foreach ($submissions as $key => $value) {
            $trainerIntakeQuery = TrainerIntake::where('trainer_id', $id)->whereIn('status', [1, 3]);

            if ($value->assignment && $value->assignment->intake_unit_id) {
                $trainerIntake = $trainerIntakeQuery->where('intake_unit_id', $value->assignment->intake_unit_id)->first();
                $submissions[$key]['submission_edit_route'] = 'trainer.submission.edit';
                $submissions[$key]['submission_pdf_route'] = 'trainer.submission.pdf';
                $submissions[$key]['submission_question_route'] = 'trainer.submission.question.index';
            } elseif ($value->assignment && $value->assignment->intake_subject_id) {
                $trainerIntake = $trainerIntakeQuery->where('intake_subject_id', $value->assignment->intake_subject_id)->first();
                $submissions[$key]['submission_edit_route'] = 'trainer.subject.submission.edit';
                $submissions[$key]['submission_pdf_route'] = 'trainer.subject.submission.pdf';
                $submissions[$key]['submission_question_route'] = 'trainer.subject.submission.question.index';
            } else {
                $trainerIntake = null;
                $submissions[$key]['submission_edit_route'] = null;
                $submissions[$key]['submission_pdf_route'] = null;
                $submissions[$key]['submission_question_route'] = null;
            }

            $submissions[$key]['trainer_intake_id'] = $trainerIntake ? $trainerIntake->id : null;
        }

        $pendingAppealsCount = GradeAppeal::where('status', 'pending')
            ->whereHas('student.intake', function ($q) use ($trainerIntakeCourseIds) {
                $q->whereIn('intake_course_id', $trainerIntakeCourseIds);
            })
            ->count();

        $trainerAnnouncements = Announcement::visible()
            ->where(function ($q) {
                $q->where('audience_type', 'all')->orWhere('audience_type', 'trainer');
            })
            ->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'important' THEN 2 ELSE 3 END")
            ->limit(3)
            ->get();

        $trainer = auth('trainer')->user();
        $trainerAcknowledgedIds = AnnouncementAcknowledgement::where('reader_type', 'trainer')
            ->where('reader_id', $trainer->id)
            ->pluck('announcement_id')
            ->toArray();

        return view('trainer::trainer.dashboard.dashboard', compact('total_courses', 'total_units', 'total_students', 'total_assignments', 'submissions', 'pendingAppealsCount', 'trainerAnnouncements', 'trainerAcknowledgedIds'));
    }

    /* Trainer Profile */
    public function profile()
    {
        activityLog('Trainer', 'Opened Trainer Profile from web');
        $id = Auth::guard('trainer')->user()->id;
        $qualifications = TrainerQualification::where('trainer_id', $id)->get();
        $professions = TrainerProfessionalDevelopment::where('trainer_id', $id)->get();
        $works = WorkPlacement::where('type', 'Trainer')->where('type_id', $id)->get();
        return view('trainer::trainer.profile', compact('qualifications', 'professions', 'works'));
    }

    /* Change Password */
    public function changePassword()
    {
        activityLog('Trainer', 'Opened Change Password from web');
        return view('trainer::trainer.password.change');
    }

    /* Update Password */
    public function fillPassword(Request $request)
    {
        $trainer = Auth::guard('trainer')->user();
        // Check current password matches from database
        $current = Hash::check($request->current_password, $trainer->password);
        if ($current == true) {
            if ($request->new_password == $request->confirm_password) {
                // Check new password matches from database
                $new = Hash::check($request->new_password, $trainer->password);
                if ($new == true) {
                    activityLog('Trainer', 'Update Password Failed');
                    return redirect()->back()->with('failure', 'New password cannot be as current password');
                } else {
                    $change = Trainer::find($trainer->id);
                    $change->password = Hash::make($request->new_password);
                    $change->save();
                    activityLog('Trainer', 'Updated Password from Web');
                    return redirect()->back()->with('success', 'Password has been updated');
                }
            } else {
                return redirect()->back()->with('failure', 'New Password and Current Password must be same');
            }
        } else {
            activityLog('Student', 'Update Password Failed');
            return redirect()->back()->with('failure', 'Wrong Password');
        }
    }
}
