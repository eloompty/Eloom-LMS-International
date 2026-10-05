<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentIntakeUnit;

class IntakeUnitAssignmentQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Show the form for creating a new assignment question.
     * @return Renderable
     */
    public function create($id)
    {
        $assignment = Assignment::findorfail($id);
        $check = checkCourseDeliverySite($assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'add') == true && $assignment && $check == true) {
            activityLog('Admin', $assignment->name . ' assignment question create page opened');
            return view('intake::course.unit.assignment.question.create', compact('assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created assignment question in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $user_id = Auth::guard('user')->user()->id;
        $questions = $request->question;
        $assignment = Assignment::where('id', $id)->first();
        $count = AssignmentQuestion::where('assignment_id', $id)->count();
        foreach ($questions as $key => $value) {
            if ($value != NULL) {
                AssignmentQuestion::create([
                    'assignment_id' => $assignment->id,
                    'question' => $value,
                ]);
            }
        }
        if ($count == 0) {
            $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $assignment->intake_unit_id)->where('status', 1)->get();
            foreach ($studentIntakeUnits as $key => $value) {
                $students = StudentDevice::where('student_id', $value->studentIntakeCourse->student_id)->where('status', 1)->get();
                $title = 'New Assignment';
                $body = 'New Assignment of ' . $value->intakeUnit->unit->name;
                if ($students->count() > 0) {
                    foreach ($students as $key => $student) {
                        $fcmData = [
                            'registration_ids' => [$student->device_token],
                            "notification" => [
                                "title" => $title,
                                "body" => $body,
                                "click_action" => route('student.assignment.index', $assignment->intake_unit_id),
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
                    'link' => $assignment->intake_unit_id,
                ]);
            }
        }
        activityLog('Admin', $assignment->name . ' assignment created');
        $url = $request->url;
        if (strpos($url, 'intake')) {
            return redirect()->route('admin.intake.unit.assignment.question.show',  $assignment->id)->with('success', 'Unit assignment created successfully');
        } else {
            return redirect()->route('admin.assignment.question',  $assignment->id)->with('success', 'Assignment question added successfully');
        }
    }

    /**
     * Show the specified assignment question.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $assignment = Assignment::findorfail($id);
        $check = checkCourseDeliverySite($assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'add') == true && $assignment && $check == true) {
            $questions = AssignmentQuestion::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', $assignment->name . ' assignment question detail page opened');
            return view('intake::course.unit.assignment.question.show', compact('assignment', 'questions'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified assignment question in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $assignmentQuestion = AssignmentQuestion::where('id', $id)->first();
        $assignmentQuestion->update($data);
        activityLog('Admin', '"' . $assignmentQuestion->assignment->name . '"' . ' assignment with question of ' . '"' . $assignmentQuestion->question . '"' . ' updated');
        return redirect()->back()->with('success', 'Question updated successfully');
    }
}
