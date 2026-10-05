<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Country\Entities\Country;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Announcement\Entities\AnnouncementAcknowledgement;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /* Student Dashboard */
    public function dashboard()
    {
        activityLog('Student', 'Opened Student Dashboard from web');
        $id = Auth::guard('student')->user()->id;
        $courses = StudentIntakeCourse::where('student_id', $id)->whereIn('status', [1, 3])->get();
        if (count($courses) > 0) {
            $total_courses = $courses->count();
            $intakeUnits = [];
            $assignments = [];
            $submissions = [];
            $due_submissions = []; 
            foreach ($courses as $key => $value) {
                $units[] =  $value->studentIntakeUnit()->count();
                $intakeUnits = StudentIntakeUnit::where('student_intake_course_id', $value->id)->where('status', 1)->get();
                foreach ($intakeUnits as $key => $intakeUnit) {
                    $assignments = Assignment::where('intake_unit_id', $intakeUnit->intake_unit_id)->where('status', 1)->orderBy('id', 'desc')->get();
                    if ($assignments->count() > 0) {
                        foreach ($assignments as $key => $assignment) {
                            $submissions = $assignment->submission->where('student_id', $id)->count();
                            if ($submissions ==  0) {
                                $assignment['student_intake_id'] = $intakeUnit->id;
                                if ($intakeUnit->due_date != NULL) {
                                    $due_date = $intakeUnit->due_date;
                                } else {
                                    if ($intakeUnit->intakeUnit->due_date != NULL) {
                                        $due_date = $intakeUnit->intakeUnit->due_date;
                                    } else {
                                        $due_date = $assignment->due_date;
                                    }
                                }
                                if ($due_date < date('Y-m-d')) {
                                    $assignment['grade'] = 'Over Due';
                                } else {
                                    $assignment['grade'] = 'Due';
                                }
                                $due_submissions[] = $assignment;
                                $due_submissions = array_slice($due_submissions, 0, 4, true);
                            }
                        }
                    }
                }
            }
            $total_units = array_sum($units);
            $total_assignments = AssignmentSubmission::where('student_id', $id)->where('status', 1)->count();
            $notifications = Notification::where('user_id', $id)->where('user_type', 'Student')->orderBy('id', 'desc')->paginate(3);

            $intakeIds = $courses->pluck('id')->toArray();

            $dashAnnouncements = \Modules\Announcement\Entities\Announcement::visible()
                ->where(function ($q) use ($id, $intakeIds) {
                    $q->where('audience_type', 'all')
                      ->orWhere(function ($q2) use ($intakeIds) {
                          $q2->where('audience_type', 'intake')->whereIn('audience_id', $intakeIds);
                      })
                      ->orWhere(function ($q2) use ($id) {
                          $q2->where('audience_type', 'student')->where('audience_id', $id);
                      });
                })
                ->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'important' THEN 2 ELSE 3 END")
                ->limit(3)
                ->get();

            $dashAcknowledgedIds = AnnouncementAcknowledgement::where('reader_type', 'student')
                ->where('reader_id', $id)
                ->pluck('announcement_id')
                ->toArray();

            $pendingSurveys = \Modules\Survey\Entities\SurveyInstance::where('status', 1)
                ->where(function ($q) { $q->whereNull('dispatch_at')->orWhere('dispatch_at', '<=', now()); })
                ->where(function ($q) { $q->whereNull('closes_at')->orWhere('closes_at', '>=', now()); })
                ->whereDoesntHave('responses', function ($q) use ($id) {
                    $q->where('respondent_type', 'student')->where('respondent_id', $id);
                })
                ->with('template')
                ->limit(3)
                ->get();

            $upcomingEvents = \Modules\Event\Entities\LmsEvent::where('status', 1)
                ->where(function ($q) {
                    $q->whereNull('registration_deadline')->orWhere('registration_deadline', '>=', now());
                })
                ->withCount('confirmedRegistrations')
                ->with(['registrations' => function ($q) use ($id) {
                    $q->where('registrant_type', 'student')->where('registrant_id', $id);
                }])
                ->orderBy('id', 'desc')
                ->limit(3)
                ->get();

        } else {
            $total_courses = 0;
            $total_units = 0;
            $total_assignments = 0;
            $due_submissions = [];
            $notifications = [];
            $dashAnnouncements   = collect();
            $dashAcknowledgedIds = [];
            $pendingSurveys      = collect();
            $upcomingEvents      = collect();
        }
        return view('student::student.dashboard.dashboard', compact('total_courses', 'total_units', 'total_assignments', 'due_submissions', 'notifications', 'dashAnnouncements', 'dashAcknowledgedIds', 'pendingSurveys', 'upcomingEvents'));
    }

    /* Student Profile */
    public function profile()
    {
        activityLog('Student', 'Opened Student Profile from web');
        $countries = Country::where('status', 1)->pluck('name', 'id');
        return view('student::student.profile', compact('countries'));
    }

    /* Change Student Password */
    public function changePassword()
    {
        activityLog('Student', 'Opened Change Password from web');
        return view('student::student.password.change');
    }

    /* Update Student Password */
    public function fillPassword(Request $request)
    {
        $student = Auth::guard('student')->user();
        // Check current password matches from database
        $current = Hash::check($request->current_password, $student->password);
        if ($current == true) {
            if ($request->new_password == $request->confirm_password) {
                // Check new password matches from database
                $new = Hash::check($request->new_password, $student->password);
                if ($new == true) {
                    activityLog('Student', 'Update Password Failed');
                    return redirect()->back()->with('failure', 'New password cannot be as current password');
                } else {
                    $change = Student::find($student->id);
                    $change->password = Hash::make($request->new_password);
                    $change->save();
                    activityLog('Student', 'Updated Password from Web');
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
