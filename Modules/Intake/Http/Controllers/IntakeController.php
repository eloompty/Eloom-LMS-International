<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentChoice;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Course\Entities\CourseFee;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;
use Modules\Intake\Entities\IntakeCourseTime;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Intake\Entities\IntakeUnitTime;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;

class IntakeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intakes.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('intake', 'view') == true) {
            activityLog('Admin', 'Opened Intake Menu');
            $status = $request->status;
            if ($status == NULL) $status_code = [0, 1];
            else $status_code = [2];
            $intakes = Intake::whereIn('status', $status_code)->orderby('id', 'desc')->get();
            return view('intake::index', compact('intakes', 'status'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new intake.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('intake', 'add') == true) {
            activityLog('Admin', 'Opened Create Intake Menu');
            return view('intake::create');
        } else {
            return redirect()->route('admin.intake.index')->with('failure', 'This user does not have permission to add intake');
        }
    }

    /**
     * Store a newly created intake in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $intake = Intake::create($data);
        activityLog('Admin', $intake->name . 'created');
        return redirect()->route('admin.intake.index')->with('success', 'Intake has been added successfully');
    }

    /**
     * Show the form for editing the specified intake.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('intake', 'edit') == true) {
            $intake = Intake::find($id);
            activityLog('Admin', $intake->name . ' edit page opened');
            return view('intake::edit', compact('intake'));
        } else {
            return redirect()->route('admin.intake.index')->with('failure', 'This user does not have permission to edit intake');
        }
    }

    /**
     * Update the specified intake in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        Intake::where('id', $id)->update($data);
        $intakeCourses = IntakeCourse::where('intake_id', $id)->get();
        foreach ($intakeCourses as $intakeCourse) {
            $studentIntakeCourse = StudentIntakeCourse::where('intake_course_id', $intakeCourse->id)->get();
            foreach ($studentIntakeCourse as $key => $value) {
                Student::where('id', $value->student_id)->update(['allow_submission_after_due_date' => $data['allow_submission_after_due_date']]);
            }
        }
        activityLog('Admin', $data['name'] . 'updated');
        return redirect()->route('admin.intake.index')->with('success', 'Intake has been updated successfully');
    }

    /**
     * Update status of intake to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake', 'delete') == true) {
            Intake::where('id', $id)->update(['status' => 2]);
            $intake = Intake::find($id);
            activityLog('Admin', $intake->name . ' updated status to deleted');
            return redirect()->back()->with('success', 'Intake deleted successfully');
        } else {
            return redirect()->route('admin.intake.index')->with('failure', 'This user does not have permission to delete intake');
        }
    }

    /**
     * Show the form for copying the specified intake.
     * @param int $id
     * @return Renderable
     */
    public function copy($id)
    {
        if (checkRole('intake', 'edit') == true) {
            $intake = Intake::find($id);
            activityLog('Admin', $intake->name . ' copy page opened');
            return view('intake::copy', compact('intake'));
        } else {
            return redirect()->route('admin.intake.index')->with('failure', 'This user does not have permission to edit intake');
        }
    }

    /**
     * Update the specified intake in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function copyCreate(Request $request, $id)
    {
        $data = $request->all();
        $old_intake = Intake::find($id);
        $intake = Intake::create($data);
        $old_intake_courses = IntakeCourse::where('intake_id', $id)->get();
        foreach ($old_intake_courses as $old_intake_course) {
            $intake_course = IntakeCourse::create([
                'intake_id' => $intake->id,
                'course_id' => $old_intake_course->course_id,
                'reference_name' => $old_intake_course->reference_name,
                'starting_date' => $old_intake_course->starting_date,
                'ending_date' => $old_intake_course->ending_date,
                'duration' => $old_intake_course->duration,
            ]);
            $old_intake_units = IntakeUnit::where('intake_course_id', $old_intake_course->id)->get();
            foreach ($old_intake_units as $old_intake_unit) {
                $intake_unit = IntakeUnit::create([
                    'intake_course_id' => $intake_course->id,
                    'unit_id' => $old_intake_unit->unit_id,
                    'starting_date' => $old_intake_unit->starting_date,
                    'ending_date' => $old_intake_unit->ending_date,
                    'due_date' => $old_intake_unit->due_date,
                    'sequence' => $old_intake_unit->sequence,
                ]);
                $old_assignments = Assignment::where('intake_unit_id', $old_intake_unit->id)->get();
                foreach ($old_assignments as $old_assignment) {
                    $assignment = Assignment::create([
                        'name' => $old_assignment->name,
                        'unit_assignment_id' => $old_assignment->unit_assignment_id,
                        'intake_subject_id' => $intake_unit->intake_subject_id,
                        'intake_unit_id' => $intake_unit->id,
                        'type' => $old_assignment->type,
                        'path' => $old_assignment->path,
                        'due_date' => $old_assignment->due_date,
                        'uploaded_by' => $old_assignment->uploaded_by,
                        'uploaded_user_id' => $old_assignment->uploaded_user_id,
                        'status' => $old_assignment->status,
                    ]);
                    $old_assignment_questions = AssignmentQuestion::where('assignment_id', $old_assignment->id)->get();
                    foreach ($old_assignment_questions as $old_assignment_question) {
                        $assignment_question = AssignmentQuestion::create([
                            'assignment_id' => $assignment->id,
                            'question' => $old_assignment_question->question,
                            'status' => $old_assignment_question->status,
                        ]);
                        $old_assignment_choices = AssignmentChoice::where('assignment_question_id', $old_assignment_question->id)->get();
                        foreach ($old_assignment_choices as $old_assignment_choice) {
                            AssignmentChoice::create([
                                'assignment_question_id' => $assignment_question->id,
                                'choice' => $old_assignment_choice->choice,
                                'is_correct' => $old_assignment_choice->is_correct,
                                'status' => $old_assignment_choice->status,
                            ]);
                        }
                    }
                }
            }
            $old_intake_course_times = IntakeCourseTime::where('intake_course_id', $old_intake_course->id)->get();
            foreach ($old_intake_course_times as $old_intake_course_time) {
                $intake_course_time = IntakeCourseTime::create([
                    'intake_course_id' => $intake_course->id,
                    'day' => $old_intake_course_time->day,
                    'from' => $old_intake_course_time->from,
                    'to'  => $old_intake_course_time->to,
                ]);
                $intakeUnits = IntakeUnit::where('intake_course_id', $intake_course->id)->get();
                foreach ($intakeUnits as $intakeUnit) {
                    IntakeUnitTime::create([
                        'intake_course_time_id' => $intake_course_time->id,
                        'intake_unit_id' => $intakeUnit->id,
                        'day' => $intake_course_time->day,
                        'from' => $intake_course_time->from,
                        'to' => $intake_course_time->to,
                    ]);
                }
            }
            $course_fees = CourseFee::where('course_id', $old_intake_course->course_id)->get();
            if (count($course_fees) > 0) {
                foreach ($course_fees as $key => $value) {
                    $intake_course_fee = IntakeCourseFee::create([
                        'intake_course_id' => $intake_course->id,
                        'name' => $value->name,
                        'enrollment_fee' => $value->enrollment_fee,
                        'enrollment_fee_wavier' => $value->enrollment_fee_wavier,
                        'material_fee' => $value->material_fee,
                        'material_fee_wavier' => $value->material_fee_wavier,
                        'fee' => $value->fee,
                        'type' => $value->type,
                        'due_date' => $intake_course->ending_date,
                        'status' => $value->status
                    ]);
                    $amount = $value->enrollment_fee + $value->material_fee + $value->fee;
                    IntakeCourseFeeInstallment::create([
                        'intake_course_fee_id' => $intake_course_fee->id,
                        'name' => 'First Installment',
                        'enrollment_fee' => $value->enrollment_fee,
                        'material_fee' => $value->material_fee,
                        'amount' => $amount,
                        'due_date' => $intake_course->starting_date,
                    ]);
                }
            }
        }
        activityLog('Admin', $intake->name . 'copied from ' . $old_intake->name);
        return redirect()->route('admin.intake.index')->with('success', 'Intake has been copied successfully');
    }
}
