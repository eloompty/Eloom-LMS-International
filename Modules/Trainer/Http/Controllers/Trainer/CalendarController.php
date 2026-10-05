<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeTime;
use Modules\Trainer\Entities\TrainerIntake;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Trainer', 'Opened Calendar menu from web');
        return view('trainer::trainer.calendar.index');
    }

    /* Get Time Table of assigned Intake Unit */
    public function getTrainerTimeTable()
    {
        $id = Auth::guard('trainer')->user()->id;
        $courses = TrainerIntake::where('trainer_id', $id)->whereIn('status', [1, 3])->get();
        $data = [];
        $addedTimes = [];
        foreach ($courses as $key => $value) {
            $intakeTimes = IntakeTime::where('intake_course_id', $value->intake_course_id)->where('intake_semester_id', $value->intake_semester_id)
                ->where('intake_subject_id', $value->intake_subject_id)
                ->where(function ($query) use ($value) {
                    $query->whereNull('intake_unit_id')->orWhere('intake_unit_id', 0);
                    if ($value->intake_unit_id) {
                        $query->orWhere('intake_unit_id', $value->intake_unit_id);
                    }
                })
                ->where('status', 1)->get();
            foreach ($intakeTimes as $time) {
                if (isset($addedTimes[$time->id])) {
                    continue;
                }
                $addedTimes[$time->id] = true;
                $date = strtotime($time->date);
                $data[] = [
                    'title' => $time->intakeSubject->subject->name,
                    'start' => date('Y-m-d', $date) . ' ' . $time->from,
                    'end' => date('Y-m-d', $date) . ' ' . $time->to,
                    'allDay' => false,
                    'backgroundColor' => '#0073b7', //Blue
                    'borderColor' => '#0073b7' //Blue
                ];
            }
        }
        return response()->json($data);
    }
}
