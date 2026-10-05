<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Course\Entities\UnitAssignment;
use Modules\Course\Entities\UnitAssignmentQuestion;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class AssignmentQuestionController extends Controller
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
        $unit_assignment = UnitAssignment::find($id);
        if (checkRole('unit_assignment', 'add') == true && $unit_assignment) {
            activityLog('Admin', $unit_assignment->name . ' assignment question create page opened');
            return view('course::unit.assignment.question.create', compact('unit_assignment'));
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
        $unit_assignment = UnitAssignment::where('id', $id)->first();
        $count = UnitAssignmentQuestion::where('unit_assignment_id', $id)->count();
        $questions = $request->question;
        foreach ($questions as $key => $value) {
            if ($value != NULL) {
                UnitAssignmentQuestion::create([
                    'unit_assignment_id' => $unit_assignment->id,
                    'question' => $value,
                ]);
            }
        }
        if ($count == 0) {
            $intakeUnits = IntakeUnit::where('unit_id', $unit_assignment->unit_id)->get();
            foreach ($intakeUnits as $key => $value) {
                $trainerIntake = TrainerIntake::where('intake_unit_id', $value->id)->first();
                if ($trainerIntake) {
                    $data['trainer_id'] = $trainerIntake->trainer_id;
                }
                $data['name'] = $unit_assignment->name;
                $data['type'] = $unit_assignment->type;
                $data['due_date'] = $unit_assignment->due_date;
                $data['unit_assignment_id'] = $unit_assignment->id;
                $data['intake_subject_id'] = $value->intake_subject_id;
                $data['intake_unit_id'] = $value->id;
                $data['uploaded_by'] = 'Admin';
                $data['uploaded_user_id'] = $unit_assignment->user_id;
                $assignment = Assignment::create($data);

                $unit_assignment_questions = UnitAssignmentQuestion::where('unit_assignment_id', $unit_assignment->id)->get();
                foreach ($unit_assignment_questions as $question) {
                    AssignmentQuestion::create([
                        'assignment_id' => $assignment->id,
                        'question' => $question->question,
                        'status' => $question->status,
                    ]);
                }
            }
        }
        activityLog('Admin', $unit_assignment->name . ' assignment question created from course menu');
        if ($unit_assignment->unit->course->registered == 1) $route = 'admin.course.unit.assignment.index';
        else $route = 'admin.unregistered.unit.assignment.index';
        return redirect()->route($route, $unit_assignment->unit_id)->with('success', 'Unit assignment created successfully');
    }

    /**
     * Show the specified assignment question.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $unit_assignment = UnitAssignment::find($id);
        if (checkRole('unit_assignment', 'add') == true && $unit_assignment) {
            $questions = UnitAssignmentQuestion::where('unit_assignment_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', $unit_assignment->name . ' assignment question detail page opened');
            return view('course::unit.assignment.question.show', compact('unit_assignment', 'questions'))->with('no', 1);
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
        $assignmentQuestion = UnitAssignmentQuestion::where('id', $id)->first();
        $assignmentQuestion->update($data);
        activityLog('Admin', '"' . $assignmentQuestion->unitAssignment->name . '"' . ' assignment with question of ' . '"' . $assignmentQuestion->question . '"' . ' updated');
        return redirect()->back()->with('success', 'Question updated successfully');
    }
}
