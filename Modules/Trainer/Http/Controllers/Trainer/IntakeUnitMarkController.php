<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeUnitMark;
use Modules\Marking\Entities\MarkingType;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Student\Entities\StudentIntakeUnitMark;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeUnitMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the intake unit mark.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $id)->where('trainer_id', $trainer_id)->first();
        $marking_type = $trainerIntake->intakeCourse->marking_type;
        if ($trainerIntake && $marking_type == 'Unit') {
            $marks = IntakeUnitMark::where('intake_unit_id', $trainerIntake->intake_unit_id)->orderBy('id', 'asc')->get();
            activityLog('Trainer', 'Opened intake mark of ' . $trainerIntake->intakeUnit->unit->name . ' from web');
            return view('trainer::trainer.unit.marking.index', compact('trainerIntake', 'marks'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new intake unit mark.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $id)->where('trainer_id', $trainer_id)->first();
        $marking_type = $trainerIntake->intakeCourse->marking_type;
        if ($trainerIntake && $marking_type == 'Unit') {
            $types = MarkingType::where('status', 1)->orderBy('id', 'asc')->get();
            activityLog('Trainer', 'Opened create page intake mark of ' . $trainerIntake->intakeUnit->unit->name . ' from web');
            return view('trainer::trainer.unit.marking.create', compact('trainerIntake', 'types'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created intake unit mark in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $id)->where('trainer_id', $trainer_id)->first();
        $data = $request->all();
        foreach ($data['name'] as $key => $value) {
            $intakeUnitMark = IntakeUnitMark::create([
                'intake_unit_id' => $trainerIntake->intake_unit_id,
                'name' => $value,
                'full_marks' => $data['full_marks'][$key],
                'pass_marks' => $data['pass_marks'][$key],
                'user_type' => 'Trainer',
                'user_id' => Auth::guard('trainer')->user()->id,
                'status' => $data['status']
            ]);
            $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $id)->get();
            foreach ($studentIntakeUnits as $unit) {
                StudentIntakeUnitMark::create([
                    'student_intake_unit_id' => $unit->id,
                    'name' => $intakeUnitMark->name,
                    'full_marks' => $intakeUnitMark->full_marks,
                    'pass_marks' => $intakeUnitMark->pass_marks,
                    'user_type' => $intakeUnitMark->user_type,
                    'user_id' => $intakeUnitMark->user_id,
                    'status' =>  $intakeUnitMark->status
                ]);
            }
        }
        activityLog('Trainer', 'Intake Marking Created');
        return redirect()->route('trainer.unit.mark.index', $id)->with('success', 'Marking created successfully');
    }

    /**
     * Show the form for editing the specified intake unit mark.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeUnitMark = IntakeUnitMark::findorfail($id);
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_unit_id', $intakeUnitMark->intake_unit_id)->where('trainer_id', $trainer_id)->first();
        $marking_type = $trainerIntake->intakeCourse->marking_type;
        if ($trainerIntake && $marking_type == 'Unit') {
            activityLog('Trainer', 'Opened Edit Intake Unit Mark');
            return view('trainer::trainer.unit.marking.edit', compact('intakeUnitMark', 'trainerIntake'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified intake unit mark in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $intakeUnitmarking = IntakeUnitMark::findorfail($id);
        $data = $request->all();
        unset($data['_token']);
        $intakeUnitmarking->update($data);
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_unit_id', $intakeUnitmarking->intake_unit_id)->where('trainer_id', $trainer_id)->first();
        activityLog('Trainer', 'Intake Marking Updated');
        return redirect()->route('trainer.unit.mark.index', $trainerIntake->id)->with('success', 'Intake marking updated successfully');
    }

    /**
     * Remove the specified intake unit mark from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $intakeUnitMark = IntakeUnitMark::findorfail($id);
        $intakeUnitMark->update(['status' => 2]);
        activityLog('Trainer', $intakeUnitMark->id . ' Updated status to deleted');
        return redirect()->back()->with('success', 'Intake Course deleted successfully');
    }

    public function markStudent($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_unit_id', $id)->where('trainer_id', $trainer_id)->first();
        $marking_type = $trainerIntake->intakeCourse->marking_type;
        if ($trainerIntake && $marking_type == 'Unit') {
            $intakeUnitMark = IntakeUnitMark::where('intake_unit_id', $id)->first();
            $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $intakeUnitMark->intake_unit_id)->get();
            $studentIntakeUnitIds =[];
            foreach ($studentIntakeUnits as $studentIntakeUnit) {
                $studentIntakeUnitIds[] = $studentIntakeUnit->id;
            }
            $studentIntakeUnitMarks = StudentIntakeUnitMark::whereIn('student_intake_unit_id', $studentIntakeUnitIds)->where('name', $intakeUnitMark->name)->get();
            activityLog('Trainer', 'Opened intake unit mark of ' . $trainerIntake->intakeUnit->unit->name . ' from web');
            return view('trainer::trainer.unit.marking.student.index', compact('trainerIntake', 'studentIntakeUnitMarks', 'intakeUnitMark'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    public function markStudentUpdate(Request $request)
    {
        $data = $request->all();
        foreach ($data['obtain_marks'] as $key => $value) {
            StudentIntakeUnitMark::where('id', $key)->update(['obtain_marks' => $value]);
        }
        return redirect()->back()->with('success', 'Marks has been updated successfully');
    }
}
