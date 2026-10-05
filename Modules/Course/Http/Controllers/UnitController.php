<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Subject;
use Modules\Course\Entities\Unit;
use Modules\Course\Entities\UnitFee;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the units.
     * @return Renderable
     */
    public function index($id)
    {
        $subject = Subject::findorfail($id);
        if (checkRole('unit', 'view') == true) {
            $units = Unit::where('subject_id', $id)->orderBy('id', 'asc')->get();
            foreach ($units as $key => $value) {
                $unit_fee = UnitFee::where('unit_id', $value->id)->where('status', 1)->first();
                if ($unit_fee) $units[$key]['unit_fee'] = $unit_fee->fee;
                else $units[$key]['unit_fee'] = 0;
            }
            activityLog('Admin', 'Opened Course Unit Page');
            return view('course::unit.index', compact('subject', 'units'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new unit.
     * @return Renderable
     */
    public function create($id)
    {
        $subject = Subject::findorfail($id);
        if (checkRole('unit', 'add') == true ) {
            activityLog('Admin', 'Opened Create Unit Page of ' . $subject->name);
            return view('course::unit.create', compact('subject'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created unit in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $subject = Subject::find($id);
        $data['course_id'] = $subject->course_id;
        $data['semester_id'] = $subject->semester_id;
        $data['subject_id'] = $id;
        $data['type'] = $subject->type;
        $unit = Unit::create($data);
        activityLog('Admin', $unit->name . ' of ' . $unit->course->course_name . ' created',);
        if (request()->is('admin/course*')) $route = 'admin.course.unit.index';
        else $route = 'admin.unregistered.unit.index';
        return redirect()->route($route, $id)->with('success', 'Unit has been added successfully');
    }

    /**
     * Show the form for editing the specified unit.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $unit = Unit::findorfail($id);
        if (checkRole('unit', 'edit') == true) {
            activityLog('Admin', $unit->name . ' edit page opened',);
            return view('course::unit.edit', compact('unit'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified unit in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $unit = Unit::findorfail($id);
        $unit->update($data);
        activityLog('Admin', $unit->name . ' updated',);
        if ($unit->course->regsitered == 1) $route = 'admin.course.unit.index';
        else $route = 'admin.unregistered.unit.index';
        return redirect()->route($route, $unit->subject_id)->with('success', 'Unit has been updated successfully');
    }

    /**
     * Update the status of specified unit to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('unit', 'delete') == true) {
            $unit = Unit::findorfail($id);
            $unit->update(['status' => 2]);
            activityLog('Admin', $unit->name . ' updated to status deleted',);
            return redirect()->back()->with('success', 'Unit has been deleted successfully');
        } else {
            return abort(404);
        }
    }
}
