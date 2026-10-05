<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Course\Entities\UnitAssignment;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Trainer\Entities\TrainerIntake;

class SubjectAssignmentFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $unit_assignment = UnitAssignment::find($id);
        if (checkRole('unit_assignment', 'add') == true && $unit_assignment) {
            activityLog('Admin', $unit_assignment->name . ' assignment file create page opened');
            return view('course::subject.assignment.file.create', compact('unit_assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $unit_assignment = UnitAssignment::where('id', $id)->first();
        $imageName = time() . '.' . request()->path->getClientOriginalExtension();
        request()->path->move(public_path('/images/assignments'), $imageName);
        $data['path'] = 'images/assignments/' . $imageName;
        $unit_assignment->update($data);
        $intakeSubjects = IntakeSubject::where('subject_id', $unit_assignment->subject_id)->get();
        foreach ($intakeSubjects as $key => $value) {
            $trainerIntake = TrainerIntake::where('intake_subject_id', $value->id)->first();
            if ($trainerIntake) {
                $data['trainer_id'] = $trainerIntake->trainer_id;
            }
            $data['name'] = $unit_assignment->name;
            $data['type'] = $unit_assignment->type;
            $data['due_date'] = $value->due_date;
            $data['unit_assignment_id'] = $unit_assignment->id;
            $data['intake_subject_id'] = $value->id;
            $data['intake_unit_id'] = 0;
            $data['uploaded_by'] = 'Admin';
            $data['uploaded_user_id'] = $unit_assignment->user_id;
            Assignment::create($data);
        }
        activityLog('Admin', $unit_assignment->name . ' assignment file created from course menu');
        if ($unit_assignment->subject->course->registered == 1) $route = 'admin.course.subject.assignment.index';
        else $route = 'admin.unregistered.subject.assignment.index';
        return redirect()->route($route,  $unit_assignment->subject_id)->with('success', 'Subject assignment created successfully');
    }
}
