<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Course\Entities\Unit;
use Modules\Course\Entities\UnitAssignment;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the unit assignmnet.
     * @return Renderable
     */
    public function index($id)
    {
        $unit = Unit::find($id);
        if (checkRole('unit_assignment', 'view') == true && $unit) {
            $assignments = UnitAssignment::where('unit_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened Assignment List of ' . $unit->name);
            return view('course::unit.assignment.index', compact('unit', 'assignments'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new unit assignmnet.
     * @return Renderable
     */
    public function create($id)
    {
        $unit = Unit::find($id);
        if (checkRole('unit_assignment', 'add') == true && $unit) {
            activityLog('Admin', 'Opened Assignment List of ' . $unit->name);
            return view('course::unit.assignment.create', compact('unit'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created unit assignmnet in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $user_id = Auth::guard('user')->user()->id;
        $unit = Unit::find($id);
        $data['subject_id'] = $unit->subject_id;
        $data['unit_id'] = $id;
        $data['user_id'] = $user_id;
        $assignment = UnitAssignment::create($data);
        if ($data['type'] == 'file') {
            if (request()->is('admin/course*')) {
                $route = 'admin.course.unit.assignment.file.create';
            } else {
                $route = 'admin.unregistered.unit.assignment.file.create';
            }
        } elseif ($data['type'] == 'multiple files') {
            if (request()->is('admin/course*')) {
                $route = 'admin.course.unit.assignment.files.create';
            } else {
                $route = 'admin.unregistered.unit.assignment.files.create';
            }
        } elseif ($data['type'] == 'question') {
            if (request()->is('admin/course*')) {
                $route = 'admin.course.unit.assignment.question.create';
            } else {
                $route = 'admin.unregistered.unit.assignment.question.create';
            }
        } else {
            if (request()->is('admin/course*')) {
                $route = 'admin.course.unit.assignment.mcq.create';
            } else {
                $route = 'admin.unregistered.unit.assignment.mcq.create';
            }
        }
        return redirect()->route($route, [$assignment->id])->with('assignment', $assignment);
    }

    /**
     * Show the form for editing the specified unit assignmnet.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $assignment = UnitAssignment::findorfail($id);
        if (checkRole('unit_assignment', 'edit') == true) {
            activityLog('Admin', $assignment->name . ' edit page opened');
            return view('course::unit.assignment.edit', compact('assignment'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified unit assignmnet in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->hasfile('path')) {
            $imageName = time() . '.' . request()->path->getClientOriginalExtension();
            request()->path->move(public_path('/images/assignments'), $imageName);
            $data['path'] = 'images/assignments/' . $imageName;
        }
        unset($data['_token']);
        $unitAssignment = UnitAssignment::where('id', $id)->first();
        $unitAssignment->update($data);
        Assignment::where('unit_assignment_id', $id)->update($data);
        activityLog('Admin', $data['name'] . ' assignment updated');
        if ($unitAssignment->unit->course->registered == 1) $route = 'admin.course.unit.assignment.index';
        else $route = 'admin.unregistered.unit.assignment.index';
        return redirect()->route($route, $unitAssignment->unit_id)->with('success', 'Unit assignment updated successfully');
    }

    /**
     * Remove the specified unit assignmnet from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('unit_assignment', 'delete') == true) {
            $unitAssingnment = UnitAssignment::where('id', $id)->first();
            $unitAssingnment->update(['status' => 2]);
            Assignment::where('unit_assignment_id', $id)->update(['status' => 2]);
            activityLog('Admin', $unitAssingnment->name . ' assignment updated status to deleted');
            return redirect()->back()->with('success', 'Assignment deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete unit resource');
        }
    }
}
