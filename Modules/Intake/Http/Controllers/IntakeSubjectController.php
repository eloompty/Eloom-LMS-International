<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeSemester;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeSubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, $id)
    {
        $intakeSemester = IntakeSemester::findorfail($id);
        $check = checkCourseDeliverySite($intakeSemester->course_id);
        if (checkRole('intake_course', 'view') == true && $check == true) {
            $subjects = IntakeSubject::where('intake_semester_id', $id)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            foreach ($subjects as $key => $value) {
                # code...
                $trainerIntake = TrainerIntake::where('intake_subject_id', $value->id)->first();
                if ($trainerIntake) {
                    $subjects[$key]['trainer_id'] = $trainerIntake->trainer_id;
                } else {
                    $subjects[$key]['trainer_id'] = 0;
                }
            }
            activityLog('Admin', $intakeSemester->intakeCourse->reference_name . ' subject lists opened');
            return view('intake::course.subject.index', compact('intakeSemester', 'subjects'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeSubject = IntakeSubject::findorfail($id);
        $check = checkCourseDeliverySite($intakeSubject->intakeCourse->course_id);
        if (checkRole('intake_course', 'edit') == true && $intakeSubject && $check == true) {
            $trainers = Trainer::where('status', 1)->get();
            $trainerIntake = TrainerIntake::where('intake_subject_id', $id)->first();
            if ($trainerIntake) {
                $trainer_id = $trainerIntake->trainer_id;
            } else {
                $trainer_id = 0;
            }
            activityLog('Admin', $intakeSubject->subject->name . ' subject updated');
            return view('intake::course.subject.edit', compact('intakeSubject', 'trainer_id', 'trainers'));
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
        unset($data['trainer_id']);
        // Update Intake Semester
        $intakeSubject = IntakeSubject::findorfail($id);
        $intakeSubject->update($data);
        // Update Trainer
        $intakeUnits = $intakeSubject->intakeUnit;
        if ($request->filled('trainer_id')) {
            if ($intakeUnits->count() > 0) {
                foreach ($intakeUnits as $intakeUnit) {
                    TrainerIntake::updateOrCreate(
                        ['intake_unit_id' => $intakeUnit->id],
                        [
                            'trainer_id' => $request->trainer_id,
                            'intake_course_id' => $intakeUnit->intake_course_id,
                            'intake_semester_id' => $intakeUnit->intake_semester_id,
                            'intake_subject_id' => $intakeUnit->intake_subject_id,
                            'duration' => 0,
                            'starting_date' => $request->starting_date,
                            'sequence' => $request->sequence,
                            'status' => $request->status,
                        ]
                    );
                }
            } else {
                TrainerIntake::updateOrCreate(
                    [
                        'intake_subject_id' => $intakeSubject->id,
                        'intake_unit_id' => 0,
                    ],
                    [
                        'trainer_id' => $request->trainer_id,
                        'intake_course_id' => $intakeSubject->intake_course_id,
                        'intake_semester_id' => $intakeSubject->intake_semester_id,
                        'duration' => 0,
                        'starting_date' => $request->starting_date,
                        'sequence' => $request->sequence,
                        'status' => $request->status,
                    ]
                );
            }
        } else {
            TrainerIntake::where('intake_subject_id', $intakeSubject->id)->delete();
        }
        // Update Student Intake
        StudentIntakeSubject::where('intake_subject_id', $id)->update($data);
        activityLog('Admin', 'Intake subject id:' . $intakeSubject->id . ' Updated');
        return redirect()->route('admin.intake.subject.index', $intakeSubject->intake_semester_id)->with('success', 'Intake Subject update successfully');
    }
}
