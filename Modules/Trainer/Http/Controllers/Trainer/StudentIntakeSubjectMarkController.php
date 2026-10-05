<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeSubjectMark;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeSubjectMark;
use Modules\Trainer\Entities\TrainerIntake;

class StudentIntakeSubjectMarkController extends Controller
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
        $trainerIntake = TrainerIntake::where('intake_subject_id', $id)->where('trainer_id', $trainer_id)->first();
        $marking_type = $trainerIntake->intakeCourse->marking_type;
        if ($trainerIntake && $marking_type == 'Subject') {
            $marks = IntakeSubjectMark::where('intake_subject_id', $trainerIntake->intake_subject_id)->orderBy('id', 'asc')->get();
            foreach ($marks as $key => $mark) {
                $marks[$key]['subjects'] = StudentIntakeSubject::where('intake_subject_id', $mark->intake_subject_id)->get();
            }
            activityLog('Trainer', 'Opened student intake subject mark of ' . $trainerIntake->intakeSubject->subject->name . ' from web');
            return view('trainer::trainer.subject.marking.index', compact('trainerIntake', 'marks'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('trainer::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('trainer::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('trainer::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
