<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Course\Entities\UnitAssignment;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class AssignmentFileController extends Controller
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
            return view('course::unit.assignment.file.create', compact('unit_assignment'));
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
        $intakeUnits = IntakeUnit::where('unit_id', $unit_assignment->unit_id)->get();
        foreach ($intakeUnits as $key => $value) {
            $trainerIntake = TrainerIntake::where('intake_unit_id', $value->id)->first();
            if ($trainerIntake) {
                $data['trainer_id'] = $trainerIntake->trainer_id;
            }
            $data['name'] = $unit_assignment->name;
            $data['type'] = $unit_assignment->type;
            $data['due_date'] = $value->due_date;
            $data['unit_assignment_id'] = $unit_assignment->id;
            $data['intake_subject_id'] = $value->intake_subject_id;
            $data['intake_unit_id'] = $value->id;
            $data['uploaded_by'] = 'Admin';
            $data['uploaded_user_id'] = $unit_assignment->user_id;
            Assignment::create($data);
        }
        activityLog('Admin', $unit_assignment->name . ' assignment file created from course menu');
        if ($unit_assignment->unit->course->registered == 1) $route = 'admin.course.unit.assignment.index';
        else $route = 'admin.unregistered.unit.assignment.index';
        return redirect()->route($route,  $unit_assignment->unit_id)->with('success', 'Unit assignment created successfully');
    }
}
