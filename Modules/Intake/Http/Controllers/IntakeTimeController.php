<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeTime;
use Modules\Intake\Entities\IntakeUnit;

class IntakeTimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake time.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeSubject = IntakeSubject::findorfail($id);
        $check = checkCourseDeliverySite($intakeSubject->intakeCourse->course_id);
        if (checkRole('intake_course_time_table', 'view') == true && $check == true) {
            $intake_unit = $intakeSubject->intakeUnit->first();
            if ($intake_unit) {
                $intake_unit_id = $intake_unit->id;
            } else {
                $intake_unit_id = NULL;
            }
            $times = IntakeTime::where('intake_subject_id', $id)->where('intake_unit_id', $intake_unit_id)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Time table of ' . $intakeSubject->subject->name);
            return view('intake::course.subject.timetable.index', compact('intakeSubject', 'times'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new intake time.
     * @return Renderable
     */
    public function create($id)
    {
        $intakeSubject = IntakeSubject::findorfail($id);
        $check = checkCourseDeliverySite($intakeSubject->intakeCourse->course_id);
        if (checkRole('intake_course_time_table', 'add') == true && $check == true) {
            activityLog('Admin', 'Opened Create Intake Time Table');
            return view('intake::course.subject.timetable.create', compact('intakeSubject'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created intake time in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $intakeSubject = IntakeSubject::findorfail($id);
        $data = $request->all();
        foreach ($data['days'] as $day) {
            $data['day'] = $day;
            $dates = getDaysBetweenDates($data['from_date'], $data['to_date'], $day);
            if (count($dates) == 0) {
                return redirect()->back()->with('failure', 'Selected days are not found within selected dates');
            } else {
                foreach ($dates as $date) {
                    $intakeUnits = IntakeUnit::where('intake_subject_id', $id)->get();
                    if (count($intakeUnits) > 0) {
                        // Created Time for invidual unit
                        foreach ($intakeUnits as $key => $value) {
                            $data['intake_course_id'] = $intakeSubject->intake_course_id;
                            $data['intake_semester_id'] = $intakeSubject->intake_semester_id;
                            $data['intake_subject_id'] = $intakeSubject->id;
                            $data['intake_unit_id'] = $value->id;
                            $data['date'] = $date;
                            $data['day'] = $day;
                            IntakeTime::create($data);
                        }
                    } else {
                        $data['intake_course_id'] = $intakeSubject->intake_course_id;
                        $data['intake_semester_id'] = $intakeSubject->intake_semester_id;
                        $data['intake_subject_id'] = $intakeSubject->id;
                        $data['date'] = $date;
                        $data['day'] = $day;
                        IntakeTime::create($data);
                    }
                }
            }
        }
        activityLog('Admin', 'Time Table for ' . $intakeSubject->subject->name . ' created');
        return redirect()->route('admin.intake.subject.time.index', $id)->with('success', 'Inake Time has been added successfully');
    }

    /**
     * Show the form for editing the specified intake time.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeTime = IntakeTime::findorfail($id);
        $check = checkCourseDeliverySite($intakeTime->intakeCourse->course_id);
        if (checkRole('intake_course_time_table', 'edit') == true && $check == true) {
            activityLog('Admin', 'Opened Edit Intake Time Table');
            return view('intake::course.subject.timetable.edit', compact('intakeTime'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified intake time in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $intakeTime = IntakeTime::findorfail($id);
        $data = $request->all();
        unset($data['_token']);
        $intakeTime->update($data);
        $intakeUnitTimes = IntakeTime::where('intake_course_id', $intakeTime->intake_course_id)->where('intake_semester_id', $intakeTime->intake_semester_id)->where('intake_subject_id', $intakeTime->intake_subject_id)
            ->where('date', $intakeTime->date)->where('day', $intakeTime->day)->where('from', $intakeTime->from)->where('to', $intakeTime->to)->get();
        foreach ($intakeUnitTimes as $key => $value) {
            $value->update($data);
        }
        activityLog('Admin', 'Intake Time Updated');
        return redirect()->route('admin.intake.subject.time.index', $intakeTime->intake_subject_id)->with('success', 'Intake Time updated successfully');
    }

    /**
     * Remove the specified intake time from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake_course_time_table', 'delete') == true) {
            $intakeTime = IntakeTime::findorfail($id);
            $intakeTime->update(['status' => 2]);
            $intakeUnitTimes = IntakeTime::where('intake_course_id', $intakeTime->intake_course_id)->where('intake_semester_id', $intakeTime->intake_semester_id)->where('intake_subject_id', $intakeTime->intake_subject_id)
                ->where('date', $intakeTime->date)->where('day', $intakeTime->day)->where('from', $intakeTime->from)->where('to', $intakeTime->to)->get();
            foreach ($intakeUnitTimes as $key => $value) {
                $value->update(['status' => 2]);
            }
            activityLog('Admin', 'Status of intake time id:' . $intakeTime->id . ' Updated to deleted');
            return redirect()->back()->with('success', 'Intake Time deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete intake time');
        }
    }
}
