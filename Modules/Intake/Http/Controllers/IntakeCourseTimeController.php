<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseTime;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Intake\Entities\IntakeUnitTime;

class IntakeCourseTimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake course time.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeCourse = IntakeCourse::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourse->course_id);
        if (checkRole('intake_course_time_table', 'view') == true && $intakeCourse && $check == true) {
            $times = IntakeCourseTime::where('intake_course_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Time table of ' . $intakeCourse->reference_name . ' Intake Course');
            return view('intake::.course.coursetime.index', compact('intakeCourse', 'times'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new intake course time.
     * @return Renderable
     */
    public function create($id)
    {
        $intakeCourse = IntakeCourse::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourse->course_id);
        if (checkRole('intake_course_time_table', 'add') == true && $intakeCourse && $check == true) {
            activityLog('Admin', 'Opened Create Intake Course Time Table');
            return view('intake::course.coursetime.create', compact('intakeCourse'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created intake course time in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['intake_course_id'] = $id;
        foreach ($data['days'] as $day) {
            $data['day'] = $day;
            $intakeCourseTime = IntakeCourseTime::create($data);
            $intakeUnits = IntakeUnit::where('intake_course_id', $id)->get();
            // Created Time for invidual unit
            foreach ($intakeUnits as $key => $value) {
                $data['intake_course_time_id'] = $intakeCourseTime->id;
                $data['intake_unit_id'] = $value->id;
                IntakeUnitTime::create($data);
            }
        }
        $intakeCourse = IntakeCourse::find($id);
        activityLog('Admin', 'Time Table for ' . $intakeCourse->reference_name . ' created');
        return redirect()->route('admin.intake.course.time.index', $id)->with('success', 'Inake Course Time has been added successfully');
    }

    /**
     * Show the form for editing the specified intake course time.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeCourseTime = IntakeCourseTime::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourseTime->intakeCourse->course_id);
        if (checkRole('intake_course_time_table', 'edit') == true && $check == true) {
            $intakeUnitTimes = IntakeUnitTime::where('intake_course_time_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Time Table for ' . $intakeCourseTime->intakeCourse->reference_name . ' edit page opened');
            return view('intake::course.coursetime.edit', compact('intakeCourseTime', 'intakeUnitTimes'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $intakeCourseTime = IntakeCourseTime::where('id', $id)->first();
        $intakeCourseTime->update($data);
        $intakeUnitTimes = IntakeUnitTime::where('intake_course_time_id', $id)->orderBy('id', 'asc')->get();
        foreach ($intakeUnitTimes as $key => $value) {
            $value->update(['status' => $data['status']]);
        }
        activityLog('Admin', 'Time Table for ' . $intakeCourseTime->intakeCourse->reference_name . ' Updated');
        return redirect()->route('admin.intake.course.time.index', $intakeCourseTime->intake_course_id)->with('success', 'Inake Course Time has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake_course_time_table', 'delete') == true) {
            $intakeCourseTime = IntakeCourseTime::where('id', $id)->first();
            $intakeCourseTime->update(['status' => 2]);
            $intakeUnitTimes = IntakeUnitTime::where('intake_course_time_id', $id)->orderBy('id', 'asc')->get();
            foreach ($intakeUnitTimes as $key => $value) {
                $value->update(['status' => 2]);
            }
            activityLog('Admin', 'Status of intake course time id:' . $intakeCourseTime->id . ' Updated to deleted');
            return redirect()->back()->with('success', 'Intake Course Time deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete intake course time');
        }
    }
}
