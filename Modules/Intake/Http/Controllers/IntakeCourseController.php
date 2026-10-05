<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentChoice;
use Modules\Assignment\Entities\AssignmentFile;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseFee;
use Modules\Course\Entities\CourseFeeType;
use Modules\Course\Entities\Semester;
use Modules\Course\Entities\Subject;
use Modules\Course\Entities\Unit;
use Modules\Course\Entities\UnitAssignment;
use Modules\Course\Entities\UnitAssignmentChoice;
use Modules\Course\Entities\UnitAssignmentFile;
use Modules\Course\Entities\UnitAssignmentQuestion;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;
use Modules\Intake\Entities\IntakeCourseFeeType;
use Modules\Intake\Entities\IntakeSemester;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeSubjectMark;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Intake\Entities\IntakeUnitMark;
use Modules\Marking\Entities\MarkingType;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerIntake;
use Illuminate\Support\Facades\Auth;

class IntakeCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the assigned intake courses.
     * @return Renderable
     */
    public function index(Request $request, $id)
    {
        $intake = Intake::find($id);
        if (checkRole('intake_course', 'view') == true && $intake) {
            $ids = getDeliverySiteIds();
            $status = $request->status;
            if ($status == NULL) $status_code = [0, 1];
            else $status_code = [2];
            $courses = IntakeCourse::join('courses', 'courses.id', '=', 'intake_courses.course_id')
                ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('intake_courses.*')
                ->where('courses.status', 1)
                ->where('intake_courses.intake_id', $id)->whereIn('intake_courses.status', $status_code)->orderBy('intake_courses.id', 'desc')->get();
            foreach ($courses as $key => $value) {
                $courses[$key]['enrolled_student'] = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')
                    ->where('intake_course_id', $value->id)
                    ->whereIn('students.status', [0, 1])->where('students.is_enrolled', 1)->count();
            }
            activityLog('Admin', 'Course list of ' . $intake->name . ' Intake');
            return view('intake::course.index', compact('intake', 'courses', 'status'))->with('no', 1);
        } else {
            return redirect()->route('admin.intake.index')->with('failure', 'This user does not have permission to view intake course');
        }
    }

    /**
     * Show the form for assign course and trainer to intake.
     * @return Renderable
     */
    public function create(Request $request, $id)
    {
        $intake = Intake::find($id);
        if (checkRole('intake_course', 'add') == true && $intake) {
            $ids = getDeliverySiteIds();
            $courses = Course::leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('courses.*')
                ->where('courses.status', 1)->pluck('course_name', 'id');
            $units = Unit::where('course_id', $request->course_id)->where('status', 1)->get();
            $trainers = Trainer::where('status', 1)->get();
            activityLog('Admin', 'Course list of ' . $intake->name . ' Intake');
            return view('intake::course.create', compact('intake', 'courses', 'units', 'trainers'));
        } else {
            return redirect()->route('admin.intake.course.index', $id)->with('failure', 'This user does not have permission to add intake course');
        }
    }

    /**
     * Assign trainer and trainer to intake
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $intake = Intake::find($id);
        $course = Course::find($request->course_id);
        $markingType = $request->marking_type;
        $activeUnits = Unit::where('course_id', $request->course_id)->where('status', 1)->count();
        $activeSubjects = Subject::join('semesters', 'semesters.id', '=', 'subjects.semester_id')
            ->where('subjects.course_id', $request->course_id)
            ->where('subjects.status', 1)
            ->where('semesters.status', 1)
            ->count();

        if ($markingType == 'Unit' && $activeUnits == 0) {
            return redirect()->route('admin.intake.course.index', $id)->with('failure', 'Course with no units cannot be added to intake');
        } elseif ($markingType == 'Subject' && $activeSubjects == 0) {
            return redirect()->route('admin.intake.course.index', $id)->with('failure', 'Course with no active subjects cannot be added to intake');
        } else {
            $data['intake_id'] = $id;
            $data['duration'] = $course->duration;
            // Inherit offer-letter course details from the base course when not overridden on the intake course form.
            foreach (['study_mode', 'study_location', 'work_placement', 'hours_per_week', 'holiday_breaks', 'entry_requirements'] as $field) {
                $data[$field] = $request->filled($field) ? $request->$field : $course->$field;
            }
            // Create Intakecourse
            $intakeCourse = IntakeCourse::create($data);
            $semester_id = $data['semester_id'];
            $starting_date = $data['semester_starting_date'];
            $ending_date = $data['semester_ending_date'];
            $due_date = $data['semester_due_date'];
            $semester_status = $data['semester_status'];
            $sequence = $data['semester_sequence'];
            // Make array of semesters
            foreach ($semester_id as $i => $val) {
                $semesters[] = array($val, $starting_date[$i], $ending_date[$i], $due_date[$i], $semester_status[$i], $sequence[$i]);
            }
            // Insert all the semesters
            foreach ($semesters as $key => $value) {
                IntakeSemester::create([
                    'intake_course_id' => $intakeCourse->id,
                    'semester_id' => $value[0],
                    'starting_date' => $value[1],
                    'ending_date' => $value[2],
                    'due_date' => $value[3],
                    'status' => $value[4],
                    'sequence' => $value[5],
                ]);
            }
            foreach ($data['semester'] as $index => $sems) {
                $intake_semester = IntakeSemester::where('intake_course_id', $intakeCourse->id)->where('semester_id', $index)->first();
                foreach ($sems as $sem) {
                    $intakeSubject = IntakeSubject::create([
                        'intake_course_id' => $intakeCourse->id,
                        'intake_semester_id' => $intake_semester->id,
                        'subject_id' => $sem['subject_id'],
                        'starting_date' => $sem['subject_starting_date'],
                        'ending_date' => $sem['subject_ending_date'],
                        'due_date' => $sem['subject_due_date'],
                        'sequence' => $sem['subject_sequence'],
                        'status' => $intake_semester->status
                    ]);
                    if ($markingType == 'Subject') {
                        $this->addDefaultSubjectMarks($intakeSubject);
                    }
                    $subject = Subject::find($intakeSubject->subject_id);
                    foreach ($subject->units as $subjectUnit) {
                        $intakeUnit = IntakeUnit::create([
                            'intake_course_id' => $intakeCourse->id,
                            'intake_semester_id' => $intake_semester->id,
                            'intake_subject_id' => $intakeSubject->id,
                            'unit_id' => $subjectUnit->id,
                            'starting_date' => $sem['subject_starting_date'],
                            'ending_date' => $sem['subject_ending_date'],
                            'due_date' => $sem['subject_due_date'],
                            'status' => $intake_semester->status
                        ]);
                        if ($markingType == 'Unit') {
                            $this->addDefaultUnitMarks($intakeUnit);
                        }
                    }
                }
            }
            $intakeSubjects = IntakeSubject::where('intake_course_id', $intakeCourse->id)->get();
            // Assign trainer to subjects of the course
            if ($request->trainer_id != NULL) {
                foreach ($intakeSubjects as $index => $intakeSubject) {
                    $subject = Subject::find($intakeSubject->subject_id);
                    if ($subject->units->count() == 0) {
                        TrainerIntake::create([
                            'trainer_id' => $request->trainer_id,
                            'intake_course_id' => $intakeSubject->intake_course_id,
                            'intake_semester_id' => $intakeSubject->intake_semester_id,
                            'intake_subject_id' => $intakeSubject->id,
                            'intake_unit_id' => 0,
                            'duration' => 0,
                            'starting_date' => $intakeSubject->starting_date,
                            'status' => $intakeSubject->status,
                        ]);
                    }
                }
                $trainer_name = userName('Trainer', $request->trainer_id);
                $log = $course->course_name . ' and ' . $trainer_name . ' assigned to ' . $intake->name . ' Intake';
            } else {
                $log = $course->course_name . ' assigned to ' . $intake->name . ' Intake';
            }

            // Add Assignment to intake subject
            foreach ($intakeSubjects as $key => $value) {
                $unit_assignments = UnitAssignment::where('subject_id', $value->subject_id)->where('status', 1)->get();
                foreach ($unit_assignments as $key => $unit_assignment) {
                    $trainerIntake = TrainerIntake::where('intake_subject_id', $value->id)->first();
                    if ($trainerIntake) {
                        $data['trainer_id'] = $trainerIntake->trainer_id;
                    }
                    $data['name'] = $unit_assignment->name;
                    $data['type'] = $unit_assignment->type;
                    $data['path'] = $unit_assignment->path;
                    $data['due_date'] = $unit_assignment->due_date;
                    $data['unit_assignment_id'] = $unit_assignment->id;
                    $data['intake_subject_id'] = $value->id;
                    $data['intake_unit_id'] = 0;
                    $data['uploaded_by'] = 'Admin';
                    $data['uploaded_user_id'] = $unit_assignment->user_id;
                    $data['status'] = $unit_assignment->status;
                    $assignment = Assignment::create($data);
                    $unit_assignment_questions = UnitAssignmentQuestion::where('unit_assignment_id', $unit_assignment->id)->get();
                    if (count($unit_assignment_questions) > 0) {
                        foreach ($unit_assignment_questions as $question) {
                            $assignment_question = AssignmentQuestion::create([
                                'assignment_id' => $assignment->id,
                                'question' => $question->question,
                                'status' => $question->status,
                            ]);

                            $unit_assignment_choices = UnitAssignmentChoice::where('unit_assignment_question_id', $question->id)->get();
                            if (count($unit_assignment_choices) > 0) {
                                foreach ($unit_assignment_choices as $choice) {
                                    AssignmentChoice::create([
                                        'assignment_question_id' => $assignment_question->id,
                                        'choice' => $choice->choice,
                                        'is_correct' => $choice->is_correct,
                                        'status' => $choice->status,
                                    ]);
                                }
                            }
                        }
                    }
                    $unit_assignment_files = UnitAssignmentFile::where('unit_assignment_id', $unit_assignment->id)->get();
                    if (count($unit_assignment_files)) {
                        foreach ($unit_assignment_files as $file) {
                            AssignmentFile::create([
                                'assignment_id' => $assignment->id,
                                'path' => $file->path,
                                'status' => $file->status,
                            ]);
                        }
                    }
                }
            }

            $intakeUnits = IntakeUnit::where('intake_course_id', $intakeCourse->id)->get();
            if (count($intakeUnits) > 0) {
                // Assign trainer to units of the course
                if ($request->trainer_id != NULL) {
                    foreach ($intakeUnits as $key => $intakeUnit) {
                        $unit = Unit::find($intakeUnit->unit_id);
                        TrainerIntake::create([
                            'trainer_id' => $request->trainer_id,
                            'intake_course_id' => $intakeUnit->intake_course_id,
                            'intake_semester_id' => $intakeUnit->intake_semester_id,
                            'intake_subject_id' => $intakeUnit->intake_subject_id,
                            'intake_unit_id' => $intakeUnit->id,
                            'duration' => $unit->duration,
                            'starting_date' => $intakeUnit->starting_date,
                            'status' => $intakeUnit->status,
                        ]);
                    }
                    $trainer_name = userName('Trainer', $request->trainer_id);
                    $log = $course->course_name . ' and ' . $trainer_name . ' assigned to ' . $intake->name . ' Intake';
                } else {
                    $log = $course->course_name . ' assigned to ' . $intake->name . ' Intake';
                }
                // Add Assignment to intake unit
                foreach ($intakeUnits as $key => $value) {
                    $unit_assignments = UnitAssignment::where('unit_id', $value->unit_id)->where('status', 1)->get();
                    foreach ($unit_assignments as $key => $unit_assignment) {
                        $trainerIntake = TrainerIntake::where('intake_unit_id', $value->id)->first();
                        if ($trainerIntake) {
                            $data['trainer_id'] = $trainerIntake->trainer_id;
                        }
                        $data['name'] = $unit_assignment->name;
                        $data['type'] = $unit_assignment->type;
                        $data['path'] = $unit_assignment->path;
                        $data['due_date'] = $unit_assignment->due_date;
                        $data['unit_assignment_id'] = $unit_assignment->id;
                        $data['intake_subject_id'] = $value->intake_subject_id;
                        $data['intake_unit_id'] = $value->id;
                        $data['uploaded_by'] = 'Admin';
                        $data['uploaded_user_id'] = $unit_assignment->user_id;
                        $data['status'] = $unit_assignment->status;
                        $assignment = Assignment::create($data);
                        $unit_assignment_questions = UnitAssignmentQuestion::where('unit_assignment_id', $unit_assignment->id)->get();
                        if (count($unit_assignment_questions) > 0) {
                            foreach ($unit_assignment_questions as $question) {
                                $assignment_question = AssignmentQuestion::create([
                                    'assignment_id' => $assignment->id,
                                    'question' => $question->question,
                                    'status' => $question->status,
                                ]);

                                $unit_assignment_choices = UnitAssignmentChoice::where('unit_assignment_question_id', $question->id)->get();
                                if (count($unit_assignment_choices) > 0) {
                                    foreach ($unit_assignment_choices as $choice) {
                                        AssignmentChoice::create([
                                            'assignment_question_id' => $assignment_question->id,
                                            'choice' => $choice->choice,
                                            'is_correct' => $choice->is_correct,
                                            'status' => $choice->status,
                                        ]);
                                    }
                                }
                            }
                        }
                        $unit_assignment_files = UnitAssignmentFile::where('unit_assignment_id', $unit_assignment->id)->get();
                        if (count($unit_assignment_files)) {
                            foreach ($unit_assignment_files as $file) {
                                AssignmentFile::create([
                                    'assignment_id' => $assignment->id,
                                    'path' => $file->path,
                                    'status' => $file->status,
                                ]);
                            }
                        }
                    }
                }
            }
            // Add Intake Course Fee
            $course_fees = CourseFee::where('course_id', $request->course_id)->where('status', 1)->get();
            if (count($course_fees) > 0) {
                foreach ($course_fees as $key => $value) {
                    $intake_course_fee = IntakeCourseFee::create([
                        'intake_course_id' => $intakeCourse->id,
                        'name' => $value->name,
                        'fee' => $value->fee,
                        'installments' => $value->installments,
                        'due_date' => $intakeCourse->ending_date,
                        'status' => $value->status
                    ]);
                    $course_fee_types = CourseFeeType::where('course_fee_id', $value->id)->where('status', 1)->get();
                    foreach ($course_fee_types as $fee_type) {
                        IntakeCourseFeeType::create([
                            'intake_course_fee_id' => $intake_course_fee->id,
                            'key' => $fee_type->key,
                            'value' => $fee_type->value,
                            'type' => $fee_type->type
                        ]);
                    }
                    $installment = $course->study_period;

                    for ($i = 1; $i <= $installment; $i++) {
                        if ($i == 1) $name = 'First';
                        elseif ($i == 2) $name = 'Second';
                        elseif ($i == 3) $name = 'Third';
                        elseif ($i == 4) $name = 'Fourth';
                        elseif ($i == 5) $name = 'Fifth';
                        elseif ($i == 6) $name = 'Sixth';
                        elseif ($i == 7) $name = 'Seventh';
                        elseif ($i == 8) $name = 'Eighth';
                        elseif ($i == 9) $name = 'Ninth';
                        elseif ($i == 10) $name = 'Tenth';
                        else $name = $i;

                        $starting_date = $intakeCourse->starting_date;
                        $month = $i * 6;
                        $starting_date_timestamp = $starting_date ? strtotime($starting_date) : false;
                        $next_due_date = $starting_date_timestamp === false ? $intakeCourse->ending_date : date('Y-m-d', strtotime('+' . $month . " months", $starting_date_timestamp));
                        $first_installment = CourseFeeType::where('course_fee_id', $value->id)->where('status', 1)->sum('value');
                        $semester_amount = CourseFeeType::where('course_fee_id', $value->id)->where('status', 1)->where('key', 'semester_fee')->first();
                        if ($i == 1) {
                            $installment_amount = $first_installment;
                        } else {
                            $installment_amount = $semester_amount->value;
                        }

                        IntakeCourseFeeInstallment::create([
                            'intake_course_fee_id' => $intake_course_fee->id,
                            'name' => $name . ' Installment',
                            'amount' => $installment_amount,
                            'due_date' => $next_due_date,
                        ]);
                    }
                }
            }
            activityLog('Admin', $log);
            return redirect()->route('admin.intake.course.index', $id)->with('success', 'Intake Course has been added successfully');
        }
    }

    private function addDefaultSubjectMarks(IntakeSubject $intakeSubject)
    {
        $userId = Auth::guard('user')->check() ? Auth::guard('user')->user()->id : null;
        $markingTypes = MarkingType::where('status', 1)->orderBy('id', 'asc')->get();

        foreach ($markingTypes as $markingType) {
            IntakeSubjectMark::create([
                'intake_subject_id' => $intakeSubject->id,
                'name' => $markingType->name,
                'full_marks' => $markingType->full_marks,
                'pass_marks' => $markingType->pass_marks,
                'user_type' => 'Admin',
                'user_id' => $userId,
                'status' => $markingType->status
            ]);
        }
    }

    private function addDefaultUnitMarks(IntakeUnit $intakeUnit)
    {
        $userId = Auth::guard('user')->check() ? Auth::guard('user')->user()->id : null;
        $markingTypes = MarkingType::where('status', 1)->orderBy('id', 'asc')->get();

        foreach ($markingTypes as $markingType) {
            IntakeUnitMark::create([
                'intake_unit_id' => $intakeUnit->id,
                'name' => $markingType->name,
                'full_marks' => $markingType->full_marks,
                'pass_marks' => $markingType->pass_marks,
                'user_type' => 'Admin',
                'user_id' => $userId,
                'status' => $markingType->status
            ]);
        }
    }

    /**
     * Show the form for editing the specified intake course.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeCourse = IntakeCourse::findorfail($id);
        if (checkRole('intake_course', 'edit') == true) {
            // List of assigned unit and trainer
            $check = checkCourseDeliverySite($intakeCourse->course_id);
            if ($check == true) {
                $intakeSemesters = IntakeSemester::where('intake_course_id', $id)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
                $intakeUnits = IntakeUnit::where('intake_course_id', $id)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
                foreach ($intakeUnits as $key => $value) {
                    $intakeTrainer = TrainerIntake::where('intake_unit_id', $value->id)->first();
                    if ($intakeTrainer) {
                        $intakeUnits[$key]['trainer_id'] = $intakeTrainer->trainer_id;
                    } else {
                        $intakeUnits[$key]['trainer_id'] = NULL;
                    }
                }
                $trainers = Trainer::where('status', 1)->get();
                activityLog('Admin', $intakeCourse->reference_name . ' edit page opened');
                return view('intake::course.edit', compact('intakeCourse', 'intakeSemesters', 'intakeUnits', 'trainers'))->with('no', 1);
            } else {
                return abort(404);
            }
        } else {
            return redirect()->route('admin.intake.course.index', $intakeCourse->intake_id)->with('failure', 'This user does not have permission to edit intake course');
        }
    }

    /**
     * Update the specified intake course in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        IntakeCourse::where('id', $id)->update($data);
        StudentIntakeCourse::where('intake_course_id', $id)->update([
            'starting_date' => $data['starting_date'],
            'ending_date' => $data['ending_date'],
            'study_mode' => $data['study_mode'] ?? null,
            'study_location' => $data['study_location'] ?? null,
            'work_placement' => $data['work_placement'] ?? null,
            'hours_per_week' => $data['hours_per_week'] ?? null,
            'holiday_breaks' => $data['holiday_breaks'] ?? null,
            'entry_requirements' => $data['entry_requirements'] ?? null,
        ]);
        $intakeCourse = IntakeCourse::find($id);
        activityLog('Admin', $intakeCourse->reference_name . ' Updated');
        return redirect()->route('admin.intake.course.index', $intakeCourse->intake_id)->with('success', 'Intake Course has been updated successfully');
    }

    /**
     * Update status of intake course to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake_course', 'delete') == true) {
            IntakeCourse::where('id', $id)->update(['status' => 2]);
            $intakeCourse = IntakeCourse::find($id);
            activityLog('Admin', $intakeCourse->reference_name . ' Updated status to deleted');
            return redirect()->back()->with('success', 'Intake Course deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to add intake course');
        }
    }

    /* get unit list by course id */
    public function getSemester(Request $request)
    {
        $semesters = Semester::where('course_id', $request->course_id)->where('status', 1)->get();
        foreach ($semesters as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'name' => $value->name,
                'credits' => $value->credits,
                'status' => 3,
            ];
        }
        return response()->json($data);
    }

    /* get unit list by course id */
    public function getSubject(Request $request)
    {
        $subjects = Subject::where('semester_id', $request->semester_id)->where('status', 1)->get();
        foreach ($subjects as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'name' => $value->name,
                'credits' => $value->credits,
                'status' => 3,
            ];
        }
        return response()->json($data);
    }

    /* get unit list by course id */
    public function getUnit(Request $request)
    {
        $units = Unit::where('subject_id', $request->subject_id)->where('status', 1)->get();
        foreach ($units as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'code' => $value->code,
                'name' => $value->name,
                'status' => 3,
            ];
        }
        return response()->json($data);
    }

    /* API to add missing assignment from course to the unit */
    public function addMissingAssignmentToIntake()
    {
        $intake_units = IntakeUnit::get();
        foreach ($intake_units as $key => $intake_unit) {
            $unit_assignments = UnitAssignment::where('unit_id', $intake_unit->unit_id)->where('status', 1)->get();
            foreach ($unit_assignments as $unit_assignment) {
                $assignment =  Assignment::where('unit_assignment_id', $unit_assignment->id)->where('intake_unit_id', $intake_unit->id)->first();
                if ($assignment == NULL) {
                    $trainerIntake = TrainerIntake::where('intake_unit_id', $intake_unit->id)->first();
                    if ($trainerIntake) {
                        $data['trainer_id'] = $trainerIntake->trainer_id;
                    }
                    $data['name'] = $unit_assignment->name;
                    $data['type'] = $unit_assignment->type;
                    $data['path'] = $unit_assignment->path;
                    $data['due_date'] = $unit_assignment->due_date;
                    $data['unit_assignment_id'] = $unit_assignment->id;
                    $data['intake_subject_id'] = $intake_unit->subject_id;
                    $data['intake_unit_id'] = $intake_unit->id;
                    $data['uploaded_by'] = 'Admin';
                    $data['uploaded_user_id'] = $unit_assignment->user_id;
                    $data['status'] = $unit_assignment->status;
                    $assignment = Assignment::create($data);
                    $unit_assignment_questions = UnitAssignmentQuestion::where('unit_assignment_id', $unit_assignment->id)->get();
                    if (count($unit_assignment_questions) > 0) {
                        foreach ($unit_assignment_questions as $question) {
                            $assignment_question = AssignmentQuestion::create([
                                'assignment_id' => $assignment->id,
                                'question' => $question->question,
                                'status' => $question->status,
                            ]);

                            $unit_assignment_choices = UnitAssignmentChoice::where('unit_assignment_question_id', $question->id)->get();
                            if (count($unit_assignment_choices) > 0) {
                                foreach ($unit_assignment_choices as $choice) {
                                    AssignmentChoice::create([
                                        'assignment_question_id' => $assignment_question->id,
                                        'choice' => $choice->choice,
                                        'is_correct' => $choice->is_correct,
                                        'status' => $choice->status,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return response()->json(['message' => 'All the assignments added']);
    }
}
