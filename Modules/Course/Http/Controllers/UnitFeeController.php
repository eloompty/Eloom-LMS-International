<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\Unit;
use Modules\Course\Entities\UnitFee;

class UnitFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the unit fee.
     * @return Renderable
     */
    public function index($id)
    {
        $unit = Unit::findorfail($id);
        if (checkRole('unit_fee', 'view') == true) {
            $fees = UnitFee::where('unit_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened Fees List of ' . $unit->name);
            return view('course::unit.fee.index', compact('unit', 'fees'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new unit fee.
     * @return Renderable
     */
    public function create($id)
    {
        $unit = Unit::findorfail($id);
        if (checkRole('unit_fee', 'add') == true) {
            activityLog('Admin', 'Opened Fees List of ' . $unit->name);
            return view('course::unit.fee.create', compact('unit'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created unit fee in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $unit = Unit::findorfail($id);
        $data['unit_id'] = $id;
        UnitFee::create($data);
        activityLog('Admin', 'Fees of ' . $unit->name . ' added');
        if ($unit->course->registered == 1) $route = 'admin.course.unit.fee.index';
        else $route = 'admin.unregistered.unit.fee.index';
        return redirect()->route($route, $id)->with('success', 'Fee has been added successfully');
    }

    /**
     * Show the form for editing the specified unit fee.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $fee = UnitFee::findorfail($id);
        if (checkRole('unit_fee', 'edit') == true) {
            activityLog('Admin', $fee->name . ' edit page opened');
            return view('course::unit.fee.edit', compact('fee'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified unit fee in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $fee = UnitFee::where('id', $id)->first();
        $fee->update($data);
        activityLog('Admin', $fee->name . ' updated');
        if ($fee->unit->course->registered == 1) $route = 'admin.course.unit.fee.index';
        else $route = 'admin.unregistered.unit.fee.index';
        return redirect()->route($route, $fee->unit_id)->with('success', 'Fee has been updated successfully');
    }

    /**
     * Remove the specified unit fee from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('unit_fee', 'delete') == true) {
            $fee = UnitFee::where('id', $id)->first();
            $fee->update(['status' => 2]);
            activityLog('Admin', $fee->name . ' updated status to deleted');
            return redirect()->back()->with('success', 'Fee deleted successfully');
        } else {
            return abort(404);
        }
    }

    public function bulkUpdate(Request $request, $id)
    {
        if (checkRole('unit_fee', 'delete') == true) {
            $course = Course::findorfail($id);
            $data = $request->all();
            unset($data['_token']);
            foreach ($data as $key => $value) {
                if ($value['unit_fee'] > 0) {
                    $unit_fee = UnitFee::where('unit_id', $value['id'])->first();
                    if ($unit_fee) {
                        $unit_fee->update(['fee' => $value['unit_fee']]);
                    } else {
                        $unit = Unit::find($value['id']);
                        UnitFee::create([
                            'unit_id' => $value['id'],
                            'name' => $unit->name,
                            'fee' => $value['unit_fee'],
                        ]);
                    }
                }
            }
            activityLog('Admin', 'The units of ' . $course->course_name . ' updated');
            return redirect()->back()->with('success', 'Fees updated successfully');
        } else {
            return abort(404);
        }
    }
}
