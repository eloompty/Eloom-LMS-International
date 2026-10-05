<?php

namespace Modules\AgentBranchUser\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Course\Entities\Unit;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;
use Modules\Student\Entities\StudentIntakeUnit;

class StudentIntakeCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent_branch_user');
    }

    /**
     * Display a listing of the student intake course.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::findorfail($id);
        $intakes = StudentIntakeCourse::where('student_id', $id)->get();
        activityLog('Agent Branch User', 'Opened ' . userName('Student', $student->id) . ' Intake Page');
        return view('agentbranchuser::user.student.intake.index', compact('student', 'intakes'))->with('no', 1);
    }

    /**
     * Show the form for creating a new student intake course.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::findorfail($id);
        $intakes = Intake::where('status', 1)->pluck('name', 'id');
        activityLog('Agent Branch User', 'Opened ' . userName('Student', $student->id) . ' Intake Assign Page');
        return view('agentbranchuser::user.student.intake.create', compact('student', 'intakes'));
    }

    /**
     * Store a newly created student intake course in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $intakeCourse = IntakeCourse::find($request->intake_course_id);
        $student_unit_course = StudentIntakeCourse::create([
            'student_id' => $id,
            'intake_course_id' => $request->intake_course_id,
            'duration' => $intakeCourse->duration,
            'starting_date' => $intakeCourse->starting_date,
            'ending_date' => $intakeCourse->ending_date,
            'study_mode' => $intakeCourse->study_mode,
            'study_location' => $intakeCourse->study_location,
            'work_placement' => $intakeCourse->work_placement,
            'hours_per_week' => $intakeCourse->hours_per_week,
            'holiday_breaks' => $intakeCourse->holiday_breaks,
            'entry_requirements' => $intakeCourse->entry_requirements,
        ]);
        $intakeUnits = IntakeUnit::where('intake_course_id', $request->intake_course_id)->get();
        foreach ($intakeUnits as $key => $value) {
            $unit = Unit::find($value->unit_id);
            $student = Student::find($id);
            StudentIntakeUnit::create([
                'student_intake_course_id' => $student_unit_course->id,
                'intake_unit_id' => $value->id,
                'funding_source_national' => $student->funding_source_national,
                'funding_source_state_training_authority' => $student->funding_source_state_training_authority,
                'delivery_mode' => $intakeCourse->course->delivery_mode,
                'internal' => $intakeCourse->course->internal,
                'predominant_delivery_mode' => $intakeCourse->course->predominant_delivery_mode,
                'duration' => $unit->duration,
                'starting_date' => $value->starting_date,
                'ending_date' => $value->ending_date,
                'due_date' => $value->due_date,
                'sequence' => $value->sequence,
                'status' => $value->status,
            ]);
        }
        $intake_course_fee = IntakeCourseFee::find($request->fee_id);
        $student_intake_course_fee = StudentIntakeCourseFee::create([
            'student_id' => $id,
            'intake_course_id' => $request->intake_course_id,
            'name' => $intake_course_fee->name,
            'enrollment_fee' => $intake_course_fee->enrollment_fee,
            'enrollment_fee_wavier' => $intake_course_fee->enrollment_fee_wavier,
            'material_fee' => $intake_course_fee->material_fee,
            'material_fee_wavier' => $intake_course_fee->material_fee_wavier,
            'fee' => $intake_course_fee->fee,
            'type' => $intake_course_fee->type,
            'due_date' => $intake_course_fee->due_date,
            'status' => $intake_course_fee->status
        ]);
        $intake_course_fee_installments = IntakeCourseFeeInstallment::where('intake_course_fee_id', $intake_course_fee->id)->get();
        foreach ($intake_course_fee_installments as $key => $value) {
            StudentIntakeCourseFeeInstallment::create([
                'student_intake_course_fee_id' => $student_intake_course_fee->id,
                'name' => $value->name,
                'enrollment_fee' => $value->enrollment_fee,
                'material_fee' => $value->material_fee,
                'amount' => $value->amount,
                'due_date' => $value->due_date,
                'status' => $value->status
            ]);
        }
        activityLog('Agent Branch User', 'Intake for ' . userName('Student', $id) . ' has been assigned');
        return redirect()->route('branch-user.student.intake.index', $id)->with('success', 'Student Intake added successfully');
    }

    /**
     * Show the form for editing the specified student intake course.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('agentbranchuser::edit');
    }

    /**
     * Update the specified student intake course in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /* Get Course By Intake Id */
    public function getCourseByIntake(Request $request)
    {
        $courses = IntakeCourse::join('courses', 'courses.id', '=', 'intake_courses.course_id')
            ->where('intake_courses.intake_id', $request->intake_id)->where('intake_courses.status', 1)
            ->select('intake_courses.id', DB::raw("CONCAT(courses.course_name, ' (', DATE_FORMAT(intake_courses.starting_date,'%d/%m/%Y'), ' - ',  DATE_FORMAT(intake_courses.ending_date,'%d/%m/%Y'),')') as course"))
            ->pluck('course', 'intake_courses.id');
        return response()->json($courses);
    }

     /* Get Intake Course Fee By Intake Course Id */
     function getIntakeCourseFee(Request $request)
     {
         $fees = IntakeCourseFee::where('intake_course_id', $request->intake_course_id)->where('status', 1)->pluck('name', 'id');
         return response()->json($fees);
     }
}
