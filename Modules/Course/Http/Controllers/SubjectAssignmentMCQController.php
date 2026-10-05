<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentChoice;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Course\Entities\UnitAssignment;
use Modules\Course\Entities\UnitAssignmentChoice;
use Modules\Course\Entities\UnitAssignmentQuestion;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Trainer\Entities\TrainerIntake;

class SubjectAssignmentMCQController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Show the form for creating a new multi choice question.
     * @return Renderable
     */
    public function create($id)
    {
        $unit_assignment = UnitAssignment::find($id);
        if (checkRole('unit_assignment', 'add') == true && $unit_assignment) {
            activityLog('Admin', $unit_assignment->name . ' assignment mcq create page opened');
            return view('course::subject.assignment.mcq.create', compact('unit_assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created multi choice question in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $unit_assignment = UnitAssignment::where('id', $id)->first();
        $question = UnitAssignmentQuestion::create([
            'unit_assignment_id' => $unit_assignment->id,
            'question' => $request->question,
        ]);
        $choices = $request->choice;
        $is_corrects = $request->is_correct;
        foreach ($choices as $key => $value) {
            UnitAssignmentChoice::create([
                'unit_assignment_question_id' => $question->id,
                'choice' => $value,
                'is_correct' => $is_corrects[$key]
            ]);
        }
        return redirect()->back()->with('success', 'Question has been added');
    }

    /**
     * Store a newly created multi choice question in storage and redirect to index.
     * @param Request $request
     * @return Renderable
     */
    public function finish(Request $request, $id)
    {
        $unit_assignment = UnitAssignment::where('id', $id)->first();
        $question = UnitAssignmentQuestion::create([
            'unit_assignment_id' => $unit_assignment->id,
            'question' => $request->question,
        ]);
        $choices = $request->choice;
        $is_corrects = $request->is_correct;
        foreach ($choices as $key => $value) {
            UnitAssignmentChoice::create([
                'unit_assignment_question_id' => $question->id,
                'choice' => $value,
                'is_correct' => $is_corrects[$key]
            ]);
        }
        $count = Assignment::where('unit_assignment_id', $id)->count();
        if ($count == 0) {
            $intakeSubjects = IntakeSubject::where('subject_id', $unit_assignment->subject_id)->get();
            foreach ($intakeSubjects as $key => $value) {
                $trainerIntake = TrainerIntake::where('intake_subject_id', $value->id)->first();
                if ($trainerIntake) {
                    $data['trainer_id'] = $trainerIntake->trainer_id;
                }
                $data['name'] = $unit_assignment->name;
                $data['type'] = $unit_assignment->type;
                $data['due_date'] = $unit_assignment->due_date;
                $data['unit_assignment_id'] = $unit_assignment->id;
                $data['intake_subject_id'] = $value->id;
                $data['intake_unit_id'] = 0;
                $data['uploaded_by'] = 'Admin';
                $data['uploaded_user_id'] = $unit_assignment->user_id;
                $assignment = Assignment::create($data);

                $unit_assignment_questions = UnitAssignmentQuestion::where('unit_assignment_id', $unit_assignment->id)->get();
                foreach ($unit_assignment_questions as $question) {
                    $assignment_question = AssignmentQuestion::create([
                        'assignment_id' => $assignment->id,
                        'question' => $question->question,
                        'status' => $question->status,
                    ]);

                    $unit_assignment_choices = UnitAssignmentChoice::where('unit_assignment_question_id', $question->id)->get();
                    foreach ($unit_assignment_choices as $choice) {
                        AssignmentChoice::create([
                            'assignment_question_id' => $assignment_question->id,
                            'choice' => $choice->choice,
                            'is_correct' => $choice->is_correct,
                            'status' => $choice->status,
                        ]);
                    }
                }
            }
        }
        activityLog('Admin', $unit_assignment->name . ' assignment mcq created from course menu');
        if ($unit_assignment->subject->course->registered == 1) $route = 'admin.course.subject.assignment.index';
        $route = 'admin.unregistered.subject.assignment.index';
        return redirect()->route($route,  $unit_assignment->subject_id)->with('success', 'Subject assignment created successfully');
    }

    /**
     * Show the specified multi choice question.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $unit_assignment = UnitAssignment::find($id);
        if (checkRole('unit_assignment', 'add') == true && $unit_assignment) {
            $questions = UnitAssignmentQuestion::where('unit_assignment_id', $id)->orderBy('id', 'asc')->get();
            foreach ($questions as $key => $value) {
                $questions[$key]['choices'] = $value->choice;
            }
            activityLog('Admin', $unit_assignment->name . ' assignment mcq detail page opened');
            return view('course::subject.assignment.mcq.show', compact('unit_assignment', 'questions'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified multi choice question.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $question = UnitAssignmentQuestion::find($id);
        if (checkRole('unit_assignment', 'add') == true && $question) {
            $choices = $question->unitAssignmentChoices;
            activityLog('Admin', $question->unitAssignment->name . ' assignment mcq edit page opened');
            return view('course::subject.assignment.mcq.edit', compact('question', 'choices'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified multi choice question in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $question = UnitAssignmentQuestion::where('id', $id)->first();
        $question->update([
            'question' => $data['question'],
            'status' => $data['status']
        ]);

        $assignmentChoices = $question->unitAssignmentChoices;
        foreach ($assignmentChoices as $key => $value) {
            $assignment_choice[] = $value->id;
        }
        $deleteChoice = array_diff($assignment_choice, $data['choice_id']);
        foreach ($deleteChoice as $key => $value) {
            $choice = UnitAssignmentChoice::where('id', $value)->delete();
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
            $choice = UnitAssignmentChoice::where('id', $data['choice_id'][$key])->first();
            if ($choice) {
                $choice->update([
                    'choice' => $value,
                    'is_correct' => $data['is_correct'][$key]
                ]);
            } else {
                UnitAssignmentChoice::create([
                    'assignment_question_id' => $question->id,
                    'choice' => $value,
                    'is_correct' => $data['is_correct'][$key]
                ]);
            }
        }
        activityLog('Admin', '"' . $question->unitAssignment->name . '"' . ' assignment with question of ' . '"' . $question->question . '"' . ' updated');
        if ($question->unitAssignment->subject->course->registered == 1) $route = 'admin.course.subject.assignment.mcq.show';
        else $route = 'admin.unregistered.subject.assignment.mcq.show';
        return redirect()->route($route, $question->unit_assignment_id)->with('success', 'MCQ updated');
    }
}
