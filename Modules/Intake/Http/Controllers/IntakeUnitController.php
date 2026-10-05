<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake unit.
     * @return Renderable
     */
    public function index(Request $request, $id)
    {
        $intakeSubject = IntakeSubject::findorfail($id);
        $check = checkCourseDeliverySite($intakeSubject->course_id);
        if (checkRole('intake_course', 'view') == true && $intakeSubject && $check == true) {
            $status = $request->status;
            if ($status == NULL) $status_code = [0, 1, 3];
            else $status_code = [2];
            $units = IntakeUnit::where('intake_subject_id', $id)->whereIn('status', $status_code)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            foreach ($units as $key => $value) {
                # code...
                $trainerIntake = TrainerIntake::where('intake_unit_id', $value->id)->first();
                if ($trainerIntake) {
                    $units[$key]['trainer_id'] = $trainerIntake->trainer_id;
                } else {
                    $units[$key]['trainer_id'] = 0;
                }
            }
            activityLog('Admin', $intakeSubject->reference_name . ' unit lists opened');
            return view('intake::course.unit.index', compact('intakeSubject', 'units', 'status'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified intake unit.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeUnit = IntakeUnit::findorfail($id);
        $check = checkCourseDeliverySite($intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'edit') == true && $intakeUnit && $check == true) {
            $trainers = Trainer::where('status', 1)->get();
            $trainerIntake = TrainerIntake::where('intake_unit_id', $id)->first();
            if ($trainerIntake) {
                $trainer_id = $trainerIntake->trainer_id;
            } else {
                $trainer_id = 0;
            }
            activityLog('Admin', $intakeUnit->unit->name . ' unit updated');
            return view('intake::course.unit.edit', compact('intakeUnit', 'trainer_id', 'trainers'));
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
        unset($data['trainer_id']);
        // Update Intake Unit
        $intakeUnit = IntakeUnit::where('id', $id)->first();
        $intakeUnit->update($data);
        // Update Student Intake
        StudentIntakeUnit::where('intake_unit_id', $id)->update($data);
        // Check if trainer is assigned
        $trainer = TrainerIntake::where('intake_unit_id', $id)->first();
        if ($trainer) {
            TrainerIntake::where('intake_unit_id', $id)->update([
                'trainer_id' => $request->trainer_id,
                'starting_date' => $request->starting_date,
                'sequence' => $request->sequence,
                'status' => $request->status,
            ]);
        } else {
            $intakeUnit = IntakeUnit::find($id);
            $data['trainer_id'] = $request->trainer_id;
            $data['intake_course_id'] = $intakeUnit->intake_course_id;
            $data['intake_semester_id'] = $intakeUnit->intake_semester_id;
            $data['intake_subject_id'] = $intakeUnit->intake_subject_id;
            $data['intake_unit_id'] = $id;
            $data['duration'] = $intakeUnit->unit->duration;
            TrainerIntake::create($data);
        }
        $assignments = Assignment::where('intake_unit_id', $id)->get();
        foreach ($assignments as $key => $value) {
            if ($value->due_date == NULL) {
                $assigment['due_date'] = $request->due_date;
            }
            $assigment['trainer_id'] = $request->trainer_id;
            $value->update($assigment);
        }
        // Assignment::where('intake_unit_id', $id)->update(['trainer_id' => $request->trainer_id, 'due_date' => $request->due_date]);
        activityLog('Admin', 'Intake unit id:' . $intakeUnit->id . ' Updated');
        return redirect()->route('admin.intake.unit.index', $intakeUnit->intake_subject_id)->with('success', 'Intake Unit update successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake_course', 'edit') == true) {
            IntakeUnit::where('id', $id)->update(['status' => 2]);
            $intakeUnit = IntakeUnit::find($id);
            activityLog('Admin', 'Status of intake unit id:' . $intakeUnit->id . ' Updated to deleted');
            return redirect()->back()->with('success', 'Intake Unit deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete intake unit');
        }
    }
}
