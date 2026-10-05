<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentChoice;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentIntakeUnit;

class IntakeUnitAssignmentMCQController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Show the form for creating a new assignment mcq.
     * @return Renderable
     */
    public function create($id)
    {
        $assignment = Assignment::findorfail($id);
        $check = checkCourseDeliverySite($assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'add') == true && $assignment && $check == true) {
            activityLog('Admin', $assignment->name . ' assignment mcq create page opened');
            return view('intake::course.unit.assignment.mcq.create', compact('id', 'assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created assignment mcq in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $assignment = Assignment::where('id', $id)->first();
        $question = AssignmentQuestion::create([
            'assignment_id' => $assignment->id,
            'question' => $request->question,
        ]);
        $choices = $request->choice;
        $is_corrects = $request->is_correct;
        foreach ($choices as $key => $value) {
            AssignmentChoice::create([
                'assignment_question_id' => $question->id,
                'choice' => $value,
                'is_correct' => $is_corrects[$key]
            ]);
        }
        return redirect()->back()->with('success', 'Question has been added');
    }

    public function finish(Request $request, $id)
    {
        $user_id = Auth::guard('user')->user()->id;
        $assignment = Assignment::where('id', $id)->first();
        $question = AssignmentQuestion::create([
            'assignment_id' => $assignment->id,
            'question' => $request->question,
        ]);
        $choices = $request->choice;
        $is_corrects = $request->is_correct;
        foreach ($choices as $key => $value) {
            AssignmentChoice::create([
                'assignment_question_id' => $question->id,
                'choice' => $value,
                'is_correct' => $is_corrects[$key]
            ]);
        }
        $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id',  $assignment->intake_unit_id)->where('status', 1)->get();
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
                            "click_action" => route('student.assignment.index',  $assignment->intake_unit_id),
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
        activityLog('Admin', $assignment->name . ' assignment created');
        return redirect()->route('admin.intake.unit.assignment.mcq.show',  $assignment->id)->with('success', 'Unit assignment created successfully');
    }

    /**
     * Show the specified assignment mcq.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $assignment = Assignment::findorfail($id);
        $check = checkCourseDeliverySite($assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'add') == true && $assignment && $check == true) {
            $questions = AssignmentQuestion::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            foreach ($questions as $key => $value) {
                $questions[$key]['choices'] = $value->choice;
            }
            activityLog('Admin', '"' . $assignment->name . '"' . ' detail page opened');
            return view('intake::course.unit.assignment.mcq.show', compact('assignment', 'questions'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified assignment mcq.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $question = AssignmentQuestion::find($id);
        if (checkRole('intake_course', 'add') == true && $question) {
            $choices = $question->choice;
            activityLog('Admin', '"' . $question->assignment->name . '"' . ' assignment with question of ' . '"' . $question->question . '"' . ' edit page opened');
            return view('intake::course.unit.assignment.mcq.edit', compact('question', 'choices'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified assignment mcq in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $question = AssignmentQuestion::where('id', $id)->first();
        $question->update([
            'question' => $data['question'],
            'status' => $data['status']
        ]);

        $assignmentChoices = $question->choice;
        foreach ($assignmentChoices as $key => $value) {
            $assignment_choice[] = $value->id;
        }
        $deleteChoice = array_diff($assignment_choice, $data['choice_id']);
        foreach ($deleteChoice as $key => $value) {
            $choice = AssignmentChoice::where('id', $value)->delete();
        }

        $choiceCount = count($data['choice']);
        $choiceIdCount = count($data['choice_id']);
        if ($choiceCount > $choiceIdCount) {
            $diff = $choiceCount - $choiceIdCount;
            for ($i = 0; $i < $diff; $i++) {
                array_push($data['choice_id'], 0);
            }
        }

        foreach ($data['choice'] as $key => $value) {
            $choice = AssignmentChoice::where('id', $data['choice_id'][$key])->first();
            if ($choice) {
                $choice->update([
                    'choice' => $value,
                    'is_correct' => $data['is_correct'][$key]
                ]);
            } else {
                AssignmentChoice::create([
                    'assignment_question_id' => $question->id,
                    'choice' => $value,
                    'is_correct' => $data['is_correct'][$key]
                ]);
            }
        }
        activityLog('Admin', '"' . $question->assignment->name . '"' . ' assignment with question of ' . '"' . $question->question . '"' . ' updated');
        return redirect()->route('admin.intake.unit.assignment.mcq.show', $question->assignment_id)->with('success', 'MCQ updated');
    }
}
