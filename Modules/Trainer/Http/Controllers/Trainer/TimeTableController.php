<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeTime;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class TimeTableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $id)->where('trainer_id', $trainer_id)->where('status', 1)->first();
        if ($trainerIntake) {
            $times = IntakeTime::where('intake_subject_id', $trainerIntake->intake_subject_id)
                ->where(function ($query) use ($trainerIntake) {
                    $query->whereNull('intake_unit_id')->orWhere('intake_unit_id', 0);
                    if ($trainerIntake->intake_unit_id) {
                        $query->orWhere('intake_unit_id', $trainerIntake->intake_unit_id);
                    }
                })
                ->orderBy('id', 'asc')->get();
            activityLog('Trainer', 'Opened time table of ' . $trainerIntake->intakeSubject->subject->name . ' from web');
            return view('trainer::trainer.subject.time-table.index', compact('trainerIntake', 'times'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            activityLog('Trainer', 'Opened create page of time table of ' . $trainerIntake->intakeSubject->subject->name . ' from web');
            return view('trainer::trainer.subject.time-table.create', compact('trainerIntake'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
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
        activityLog('Trainer', 'Time Table for ' . $intakeSubject->subject->name . ' created');
        return redirect()->route('trainer.time.index', $id)->with('success', 'Intake Time has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeTime = IntakeTime::findorfail($id);
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $intakeTime->intake_subject_id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            activityLog('Trainer', 'Opened Edit Intake Time Table');
            return view('trainer::trainer.subject.time-table.edit', compact('intakeTime'));
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
        $intakeTime = IntakeTime::findorfail($id);
        $data = $request->all();
        unset($data['_token']);
        $intakeTime->update($data);
        $intakeUnitTimes = IntakeTime::where('intake_course_id', $intakeTime->intake_course_id)->where('intake_semester_id', $intakeTime->intake_semester_id)->where('intake_subject_id', $intakeTime->intake_subject_id)
            ->where('date', $intakeTime->date)->where('day', $intakeTime->day)->where('from', $intakeTime->from)->where('to', $intakeTime->to)->get();
        foreach ($intakeUnitTimes as $key => $value) {
            $value->update($data);
        }
        activityLog('Trainer', 'Intake Time Updated');
        return redirect()->route('trainer.time.index', $intakeTime->intake_subject_id)->with('success', 'Intake Time updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $intakeTime = IntakeTime::findorfail($id);
        $intakeTime->update(['status' => 2]);
        $intakeUnitTimes = IntakeTime::where('intake_course_id', $intakeTime->intake_course_id)->where('intake_semester_id', $intakeTime->intake_semester_id)->where('intake_subject_id', $intakeTime->intake_subject_id)
            ->where('date', $intakeTime->date)->where('day', $intakeTime->day)->where('from', $intakeTime->from)->where('to', $intakeTime->to)->get();
        foreach ($intakeUnitTimes as $key => $value) {
            $value->update(['status' => 2]);
        }
        activityLog('Admin', 'Status of intake time id:' . $intakeTime->id . ' Updated to deleted');
        return redirect()->back()->with('success', 'Intake Time deleted successfully');
    }
}
