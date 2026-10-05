<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Course\Entities\Unit;
use Modules\Course\Entities\UnitAssignment;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeSemester;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class MissingUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $unit = Unit::findorfail($id);
        if (checkRole('unit', 'add') == true) {
            $intakeCourses = IntakeCourse::where('course_id', $unit->course_id)->where('status', 1)->get();
            return view('course::unit.missing.index', compact('unit', 'intakeCourses'));
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
        $unit = Unit::findorfail($id);
        $intake_course = IntakeCourse::find($data['intake_course_id']);
        $intake_unit = IntakeUnit::where('intake_course_id', $data['intake_course_id'])->where('unit_id', $id)->first();
        if ($intake_unit) {
            return redirect()->back()->with('failure', 'This unit already exsits in intake course');
        } else {
            $data['status'] = 3;
            $data['unit_id'] = $id;
            $intake_unit = IntakeUnit::create($data);
            if ($intake_course->trainer_id != NULL) {
                TrainerIntake::create([
                    'trainer_id' => $intake_course->trainer_id,
                    'intake_course_id' => $data['intake_course_id'],
                    'intake_semester_id' => $intake_unit->intake_semester_id,
                    'intake_subject_id' => $intake_unit->intake_subject_id,
                    'intake_unit_id' => $intake_unit->id,
                    'duration' => $unit->duration,
                    'status' => 3
                ]);
            }
            $studentIntakeCourse = StudentIntakeCourse::where('intake_course_id', $data['intake_course_id'])->get();
            foreach ($studentIntakeCourse as $key => $value) {
                $student_intake_semester = StudentIntakeSemester::where('student_intake_course_id', $$value->id)->where('intake_semester_id', $intake_unit->intake_semester_id)->first();
                $student_intake_subject = StudentIntakeSubject::where('student_intake_course_id', $$value->id)->where('student_intake_semester_id', $student_intake_semester->id)->where('intake_subject_id', $intake_unit->intake_subject_id)->first();
                StudentIntakeUnit::create([
                    'student_intake_course_id' => $value->id,
                    'student_intake_semester_id' => $student_intake_semester->id,
                    'student_intake_subject_id' => $student_intake_subject->id,
                    'intake_unit_id' => $intake_unit->id,
                    'duration' => $unit->duration,
                    'status' => 3
                ]);
            }
            $unit_assignments = UnitAssignment::where('unit_id', $id)->get();
            foreach ($unit_assignments as $key => $unit_assignment) {
                $trainerIntake = TrainerIntake::where('intake_unit_id', $intake_unit->id)->first();
                if ($trainerIntake) $trainer_id = $trainerIntake->trainer_id;
                else $trainer_id = NULL;
                Assignment::create([
                    'name' => $unit_assignment->name,
                    'unit_assignment_id' => $unit_assignment->id,
                    'intake_subject__id' => $intake_unit->intake_subject_id,
                    'intake_unit_id' => $intake_unit->id,
                    'trainer_id' => $trainer_id,
                    'type' => $unit_assignment->type,
                    'path' => $unit_assignment->path,
                    'due_date' => $unit_assignment->due_date,
                    'uploaded_by' => 'Admin',
                    'uploaded_user_id' => $unit_assignment->user_id,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Unit has been added to the intake course');
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
