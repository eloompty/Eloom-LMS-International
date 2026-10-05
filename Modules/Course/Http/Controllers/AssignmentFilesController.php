<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentFile;
use Modules\Course\Entities\UnitAssignment;
use Modules\Course\Entities\UnitAssignmentFile;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class AssignmentFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Show the form for creating a new unit assignment files.
     * @return Renderable
     */
    public function create($id)
    {
        $unit_assignment = UnitAssignment::findorfail($id);
        if (checkRole('unit_assignment', 'add') == true && $unit_assignment) {
            activityLog('Admin', $unit_assignment->name . ' assignment files create page opened');
            return view('course::unit.assignment.files.create', compact('unit_assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created unit assignment files in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $unit_assignment = UnitAssignment::findorfail($id);
        $data = $request->all();
        if ($unit_assignment->unit->course->registered == 1) $route = 'admin.course.unit.assignment.index';
        else $route = 'admin.unregistered.unit.assignment.index';
        if ($request->hasfile('files')) {
            $files =  $request->file('files');
            foreach ($files as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/assignments/' . $id, $name);
                $data['path'] = 'images/assignments/' . $id . '/' . $name;
                $data['unit_assignment_id'] = $id;
                UnitAssignmentFile::create($data);
            }
            $intakeUnits = IntakeUnit::where('unit_id', $unit_assignment->unit_id)->get();
            foreach ($intakeUnits as $key => $value) {
                $trainerIntake = TrainerIntake::where('intake_unit_id', $value->id)->first();
                if ($trainerIntake) {
                    $assignment_data['trainer_id'] = $trainerIntake->trainer_id;
                }
                $assignment_data['name'] = $unit_assignment->name;
                $assignment_data['type'] = $unit_assignment->type;
                $assignment_data['due_date'] = $value->due_date;
                $assignment_data['unit_assignment_id'] = $unit_assignment->id;
                $assignment_data['intake_subject_id'] = $value->intake_subject_id;
                $assignment_data['intake_unit_id'] = $value->id;
                $assignment_data['uploaded_by'] = 'Admin';
                $assignment_data['uploaded_user_id'] = $unit_assignment->user_id;
                $assignment = Assignment::create($assignment_data);
                $unit_assignment_files = UnitAssignmentFile::where('unit_assignment_id', $id)->get();
                foreach ($unit_assignment_files as $unit_assignment_file) {
                    AssignmentFile::create([
                        'assignment_id' => $assignment->id,
                        'path' => $unit_assignment_file->path
                    ]);
                }
            }
            activityLog('Admin', $unit_assignment->name . ' assignment multiple files created from course menu');
            return redirect()->route($route, $unit_assignment->unit_id)->with('success', 'Unit assignment created successfully');
        } else {
            $unit_assignment->delete();
            return redirect()->route($route, $unit_assignment->unit_id)->with('failure', 'Unit assignment cannot be created');
        }
    }

    /**
     * Show the form for editing the specified unit assignment files.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $unit_assignment = UnitAssignment::findorfail($id);
        if (checkRole('unit_assignment', 'edit') == true) {
            $files = UnitAssignmentFile::where('unit_assignment_id', $id)->get();
            activityLog('Admin', $unit_assignment->unit->name . ' assignment mcq edit page opened');
            return view('course::unit.assignment.files.edit', compact('unit_assignment', 'files'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified unit assignment files in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $unit_assignment = UnitAssignment::findorfail($id);
        foreach ($data['files'] as $key => $value) {
            $ids[] = $key;
        }
        UnitAssignmentFile::where('unit_assignment_id', $id)->whereNotIn('id', $ids)->delete();
        if (isset($data['files'][0])) {
            foreach ($data['files'][0] as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/assignments/' . $id, $name);
                $file_date['path'] = 'images/assignments/' . $id . '/' . $name;
                $file_date['unit_assignment_id'] = $id;
                UnitAssignmentFile::create($file_date);
            }
        }
        return redirect()->back()->with('success', 'Unit Assignment Files have been updated');
    }
}
