<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentIntakeSubject;

class IntakeSubjectAssignmentFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Show the form for creating a new assignment file.
     * @return Renderable
     */
    public function create($id)
    {
        $assignment = Assignment::findorfail($id);
        $check = checkCourseDeliverySite($assignment->intakeSubject->intakeCourse->course_id);
        if (checkRole('intake_course', 'add') == true && $assignment && $check == true) {
            activityLog('Admin', $assignment->name . ' assignment file create page opened');
            return view('intake::course.subject.assignment.file.create', compact('id', 'assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created assignment file in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $user_id = Auth::guard('user')->user()->id;
        $assignment = Assignment::where('id', $id)->first();
        $imageName = time() . '.' . request()->path->getClientOriginalExtension();
        request()->path->move(public_path('/images/assignments'), $imageName);
        $data['path'] = 'images/assignments/' . $imageName;
        $assignment->update($data);
        $studentIntakeSubjects = StudentIntakeSubject::where('intake_subject_id', $assignment->intake_subject_id)->where('status', 1)->get();
        foreach ($studentIntakeSubjects as $key => $value) {
            $students = StudentDevice::where('student_id', $value->studentIntakeCourse->student_id)->where('status', 1)->get();
            $title = 'New Assignment';
            $body = 'New Assignment of ' . $value->intakeSubject->subject->name;
            if ($students->count() > 0) {
                foreach ($students as $key => $student) {
                    $fcmData = [
                        'registration_ids' => [$student->device_token],
                        "notification" => [
                            "title" => $title,
                            "body" => $body,
                            "click_action" => route('student.assignment.index',  $assignment->intake_subject_id),
                        ],
                        "data" => [
                            "is_admin" => false,
                        ],
                    ];
                }
                fcm($fcmData);
            }
            Notification::create([
                'user_type' => 'Student',
                'user_id' => $value->studentIntakeCourse->student_id,
                'sender_type' => 'Admin',
                'sender_id' => $user_id,
                'title' => $title,
                'body' => $body,
                'type' => 'Assignment',
                'link' => $assignment->intake_subject_id,
            ]);
        }
        activityLog('Admin', $assignment->name . ' assignment file created');
        return redirect()->route('admin.intake.subject.assignment.index',  $assignment->intake_subject_id)->with('success', 'Subject assignment created successfully');
    }
}
