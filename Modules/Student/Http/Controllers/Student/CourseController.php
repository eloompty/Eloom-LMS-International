<?php

namespace Modules\Student\Http\Controllers\Student;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentAnswer;
use Modules\Assignment\Entities\AssignmentFile;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Assignment\Entities\AssignmentResubmission;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Assignment\Entities\AssignmentSubmissionFile;
use Modules\Attendance\Entities\Attendance;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseTime;
use Modules\Intake\Entities\IntakeTime;
use Modules\Intake\Entities\IntakeUnitTime;
use Modules\OnlineClass\Entities\OnlineClass;
use Modules\OnlineClass\Entities\OnlineClassTeam;
use Modules\Resource\Entities\Resource;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeSemester;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeSubjectMark;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Student\Entities\StudentIntakeUnitMark;

class CourseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display a listing of the courses.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Student', 'Opened Course menu from web');
        $id = Auth::guard('student')->user()->id;
        $courses = StudentIntakeCourse::where('student_id', $id)->whereIn('status', [1, 3])->where('is_enrolled', 1)->get();
        foreach ($courses as $key => $value) {
            $courses[$key]['intakeCourseTime'] = IntakeCourseTime::where('intake_course_id', $value->intake_course_id)->where('status', 1)->get();
            $courses[$key]['trainer'] = IntakeCourse::where('id', $value->intake_course_id)->first();
        }
        return view('student::student.course.index', compact('courses'))->with('no', 1);
    }

    /* Student Semester List */
    public function semester($id)
    {
        $course = StudentIntakeCourse::where('id', $id)->whereIn('status', [1, 3])->where('is_enrolled', 1)->first();
        $student_id = Auth::guard('student')->user()->id;
        if ($course && $course->student_id == $student_id) {
            activityLog('Student', 'Opened semster lists page from web');
            $semesters = StudentIntakeSemester::where('student_intake_course_id', $id)->whereIn('status', [1, 3])->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            return view('student::student.semester.index', compact('semesters', 'course'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Subject List */
    public function subject($id)
    {
        $semester = StudentIntakeSemester::where('id', $id)->whereIn('status', [1, 3])->first();
        $student_id = Auth::guard('student')->user()->id;
        if ($semester && $semester->studentIntakeCourse->student_id == $student_id) {
            activityLog('Student', 'Opened subject lists page from web');
            $subjects = StudentIntakeSubject::where('student_intake_semester_id', $id)->whereIn('status', [1, 3])->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            return view('student::student.subject.index', compact('subjects', 'semester'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Subject Mark List */
    public function subjectMarks($id)
    {
        $subject = StudentIntakeSubject::where('id', $id)->whereIn('status', [1, 3])->first();
        $marking_type = $subject->studentIntakeCourse->intakeCourse->marking_type;
        $student_id = Auth::guard('student')->user()->id;
        if ($subject && $subject->studentIntakeCourse->student_id == $student_id && $marking_type == 'Subject') {
            activityLog('Student', 'Opened subject mark lists page from web');
            $marks = StudentIntakeSubjectMark::where('student_intake_subject_id', $id)->where('status', 1)->get();
            return view('student::student.subject.marking.index', compact('marks', 'subject'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit List */
    public function unit($id)
    {
        $subject = StudentIntakeSubject::where('id', $id)->whereIn('status', [1, 3])->first();
        $student_id = Auth::guard('student')->user()->id;
        if ($subject && $subject->studentIntakeCourse->student_id == $student_id) {
            activityLog('Student', 'Opened units list from web');
            $units = StudentIntakeUnit::where('student_intake_subject_id', $id)->whereIn('status', [1, 3])->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            foreach ($units as $key => $value) {
                $units[$key]['intakeUnitTime'] = IntakeUnitTime::where('intake_unit_id', $value->intake_unit_id)->where('status', 1)->get();
            }
            return view('student::student.unit.index', compact('units', 'subject'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Mark Mark List */
    public function unitMarks($id)
    {
        $unit = StudentIntakeUnit::where('id', $id)->whereIn('status', [1, 3])->first();
        $marking_type = $unit->studentIntakeCourse->intakeCourse->marking_type;
        $student_id = Auth::guard('student')->user()->id;
        if ($unit && $unit->studentIntakeCourse->student_id == $student_id && $marking_type == 'Unit') {
            activityLog('Student', 'Opened unit mark lists page from web');
            $marks = StudentIntakeUnitMark::where('student_intake_unit_id', $id)->where('status', 1)->get();
            return view('student::student.unit.marking.index', compact('marks', 'unit'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Resource List By Unit Id */
    public function resource($id)
    {
        $studentIntake = StudentIntakeUnit::where('id', $id)->first();
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $resources = Resource::where('unit_id', $studentIntake->intakeUnit->unit_id)->where('user_type', 'Student')->where('status', 1)->get();
            activityLog('Student', 'Opened resources of ' . $studentIntake->intakeUnit->unit->name . 'from web');
            return view('student::student.unit.resource.index', compact('resources', 'studentIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment List By Unit Id */
    public function assigment($id)
    {
        $studentIntake = StudentIntakeUnit::findorfail($id);
        $assignments = Assignment::where('intake_unit_id', $studentIntake->intake_unit_id)->where('status', 1)->orderBy('id', 'desc')->get();
        $due_date = '';
        $resubmission_due_date = false;
        foreach ($assignments as $key => $value) {
            $student_id = Auth::guard('student')->user()->id;
            if ($studentIntake->due_date != NULL) {
                $student_due_date = $studentIntake->due_date;
            } else {
                if ($studentIntake->intakeUnit->due_date != NULL) {
                    $student_due_date = $studentIntake->intakeUnit->due_date;
                } else {
                    $student_due_date = $value->due_date;
                }
            }
            $submission = AssignmentSubmission::where('assignment_id', $value->id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            if ($submission) {
                if ($submission->assignment_grade_id == NULL) {
                    $assignments[$key]['grade'] = 'Waiting to be Graded';
                } else {
                    $assignments[$key]['grade'] = $submission->assignmentGrade->name;
                }
                if ($submission->assignment_resubmission_idsion > 0) {
                    $resubmission = AssignmentResubmission::find($submission->assignment_resubmission_id);
                    if ($resubmission->due_date != NULL) {
                        $due_date = $resubmission->due_date;
                        $resubmission_due_date = true;
                    } else {
                        $due_date = $student_due_date;
                    }
                } else {
                    $due_date = $student_due_date;
                }
            } else {
                $due_date = $student_due_date;
                if ($due_date < date('Y-m-d')) {
                    $assignments[$key]['grade'] = 'Over Due';
                } else {
                    $assignments[$key]['grade'] = 'Due';
                }
            }
            $assignments[$key]['submission_count'] = AssignmentSubmission::where('assignment_id', $value->id)->where('student_id', $student_id)->orderBy('id', 'desc')->count();
            $assignments[$key]['resubmission'] = AssignmentResubmission::where('assignment_id', $value->id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
        }
        activityLog('Student', 'Opened assignments of ' . $studentIntake->intakeUnit->unit->name . 'from web');
        return view('student::student.unit.assignment.index', compact('assignments', 'studentIntake', 'due_date', 'resubmission_due_date'))->with('no', 1);
    }

    /* Student Unit Assignment Files List */
    public function assigmentFiles($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $assignment = Assignment::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $assignment->type == 'multiple files') {
            activityLog('Student', 'Opened assignment files list from web');
            $files = AssignmentFile::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            if ($resubmission) $resubmission_id = $resubmission->id;
            else $resubmission_id = 0;
            return view('student::student.unit.assignment.files.index', compact('assignment', 'files', 'studentIntakeUnit', 'resubmission_id'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment Questions List */
    public function assigmentQuestion($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $assignment = Assignment::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $assignment->type == 'question') {
            activityLog('Student', 'Opened assignment questions list from web');
            $questions = AssignmentQuestion::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            if ($resubmission) $resubmission_id = $resubmission->id;
            else $resubmission_id = 0;
            $integrityChecks = [
                'ai_detection' => getSettingValue('enable_student_assignment_ai_bot_checking') === 'enable',
                'paraphraser' => getSettingValue('enable_student_assignment_paraphrasing') === 'enable',
                'plagiarism' => getSettingValue('enable_student_assignment_plagiarism_test') === 'enable',
            ];
            return view('student::student.unit.assignment.question.index', compact('assignment', 'questions', 'studentIntakeUnit', 'resubmission_id', 'integrityChecks'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment MCQ List */
    public function assigmentMCQ($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $assignment = Assignment::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $assignment->type == 'mcq') {
            activityLog('Student', 'Opened assignment MCQ list from web');
            $questions = AssignmentQuestion::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            if ($resubmission) $resubmission_id = $resubmission->id;
            else $resubmission_id = 0;
            return view('student::student.unit.assignment.mcq.index', compact('assignment', 'questions', 'studentIntakeUnit', 'resubmission_id'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment Question Submit */
    public function submitAssignmentQuestion(Request $request, $id, $student_intake_id)
    {
        $data = $request->all();
        $answers = $data['answers'];
        $data['assignment_id'] = $id;
        $data['student_id'] = Auth::guard('student')->user()->id;
        $submission = AssignmentSubmission::create($data);
        foreach ($answers as $key => $value) {
            AssignmentAnswer::create([
                'assignment_question_id' => $key,
                'answer' => $value,
                'assignment_submission_id' => $submission->id
            ]);
        }
        if ($data['assignment_resubmission_id'] > 0) {
            AssignmentResubmission::where('id', $data['assignment_resubmission_id'])->update(['status' => 3]);
        }
        activityLog('Student', 'Assignment has been submitted');
        return redirect()->route('student.submission.index', [$id, $student_intake_id])->with('success', 'Assignment has been submitted');
    }

    /* Student Unit Assignment MCQ Submit */
    public function submitAssignmentMCQ(Request $request, $id, $student_intake_id)
    {
        $data = $request->all();
        $questions = $data['questions'];
        $data['assignment_id'] = $id;
        $data['student_id'] = Auth::guard('student')->user()->id;
        $submission = AssignmentSubmission::create($data);
        foreach ($questions as $key => $value) {
            AssignmentAnswer::create([
                'assignment_question_id' => $key,
                'answer' => $value,
                'assignment_submission_id' => $submission->id,
            ]);
        }
        if ($data['assignment_resubmission_id'] > 0) {
            AssignmentResubmission::where('id', $data['assignment_resubmission_id'])->update(['status' => 3]);
        }
        activityLog('Student', 'Assignment has been submitted');
        return redirect()->route('student.submission.mcq.index', [$submission->id, $student_intake_id])->with('success', 'Assignment has been submitted');
    }

    /* Student Unit Assignment Submission List By Unit Id */
    public function submission($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id) {
            activityLog('Student', 'Opened assignments submission list from web');
            $assignment = Assignment::find($id);
            $submissions = AssignmentSubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->get();
            foreach ($submissions as $key => $value) {
                if ($value->assignmentSubmissionGrade == NULL) {
                    $submissions[$key]['show_to_student'] = 0;
                } else {
                    $submissions[$key]['graded_file'] = $value->assignmentSubmissionGrade->path;
                    $submissions[$key]['show_to_student'] = $value->assignmentSubmissionGrade->show_to_student;
                }
            }
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            $last_submission = AssignmentSubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            if ($last_submission && $last_submission->assignment_grade_id != NULL) {
                if ($resubmission) {
                    if ($resubmission->status == 2 ||  $resubmission->status == 3) {
                        $resubmission_request = true;
                    } else {
                        $resubmission_request = false;
                    }
                } else {
                    $resubmission_request = true;
                }
            } else {
                $resubmission_request = false;
            }
            return view('student::student.unit.assignment.submission.index', compact('submissions', 'assignment', 'studentIntakeUnit', 'resubmission', 'resubmission_request'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment Question Submit List*/
    public function questionSubmission($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $submission = AssignmentSubmission::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $submission->student_id == $student_id && $submission->assignment->type == 'question') {
            activityLog('Student', 'Opened assignments submission list from web');
            $answers = AssignmentAnswer::where('assignment_submission_id', $id)->get();
            return view('student::student.unit.assignment.submission.question.index', compact('submission', 'studentIntakeUnit', 'answers'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Assignment MCQ Submit List*/
    public function mcqSubmission($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $submission = AssignmentSubmission::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $submission->student_id == $student_id && $submission->assignment->type == 'mcq') {
            activityLog('Student', 'Opened assignments submission list from web');
            $answers = AssignmentAnswer::where('assignment_submission_id', $id)->get();
            return view('student::student.unit.assignment.submission.mcq.index', compact('submission', 'studentIntakeUnit', 'answers'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Create Student Unit Assignment Submission */
    public function createSubmission($id, $student_intake_id)
    {
        $assignment = Assignment::find($id);
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        if ($assignment && $studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id) {
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            $today = date('Y-m-d');
            if ($resubmission) {
                $resubmission_id = $resubmission->id;
                $resubmission_date = $resubmission->due_date;
            } else {
                $resubmission_id = 0;
                $resubmission_date = NULL;
            }
            if ($resubmission_date != NULL) {
                $due_date = $resubmission_date;
            } else {
                if ($studentIntakeUnit->due_date != NULL) {
                    $due_date = $studentIntakeUnit->due_date;
                } else {
                    if ($studentIntakeUnit->intakeUnit->due_date != NULL) {
                        $due_date = $studentIntakeUnit->intakeUnit->due_date;
                    } else {
                        $due_date = $assignment->due_date;
                    }
                }
            }
            $student_submission_allow =  Auth::guard('student')->user()->allow_submission_after_due_date;
            if ($student_submission_allow == 'on') {
                $allow = true;
            } else {
                if ($due_date < $today) {
                    if ($resubmission_id > 0) {
                        $allow = true;
                    } else {
                        $allow = false;
                    }
                } else {
                    $allow = true;
                }
            }
            if ($allow == true) {
                activityLog('Student', 'Opened assignments submission add page');
                return view('student::student.unit.assignment.submission.create', compact('assignment', 'studentIntakeUnit', 'resubmission_id'));
            } else {
                return back()->with('failure', 'Due date has passed');
            }
        } else {
            return abort(404);
        }
    }

    /* Submit Student Unit Assignment */
    public function storeSubmission(Request $request, $id, $student_intake_id)
    {
        $data = $request->all();
        if ($request->has('path')) {
            $imageName = time() . '.' . request()->path->getClientOriginalExtension();
            request()->path->move(public_path('/images/assignments/submissions'), $imageName);
            $data['path'] = 'images/assignments/submissions/' . $imageName;
            $data['assignment_id'] = $id;
            $data['student_id'] = Auth::guard('student')->user()->id;
            AssignmentSubmission::create($data);
            if ($data['assignment_resubmission_id'] > 0) {
                AssignmentResubmission::where('id', $data['assignment_resubmission_id'])->update(['status' => 3]);
            }
            activityLog('Student', 'Assignment has been submitted');
            return redirect()->route('student.submission.index', [$id, $student_intake_id])->with('success', 'Assignment has been submitted');
        } else {
            return redirect()->back()->with('failure', 'Please Upload Assignment File');
        }
    }

    /* Student Unit Assignment Files Submit List*/
    public function filesSubmission($id, $student_intake_id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $submission = AssignmentSubmission::find($id);
        if ($studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id && $submission->student_id == $student_id && $submission->assignment->type == 'multiple files') {
            activityLog('Student', 'Opened assignments files submission list from web');
            $files = AssignmentSubmissionFile::where('assignment_submission_id', $id)->get();
            return view('student::student.unit.assignment.submission.files.index', compact('submission', 'studentIntakeUnit', 'files'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Create Student Unit Assignment Submission */
    public function createFilesSubmission($id, $student_intake_id)
    {
        $assignment = Assignment::find($id);
        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        if ($assignment && $studentIntakeUnit && $studentIntakeUnit->studentIntakeCourse->student_id == $student_id) {
            $resubmission = AssignmentResubmission::where('assignment_id', $id)->where('student_id', $student_id)->orderBy('id', 'desc')->first();
            $today = date('Y-m-d');
            if ($resubmission) {
                $resubmission_id = $resubmission->id;
                $resubmission_date = $resubmission->due_date;
            } else {
                $resubmission_id = 0;
                $resubmission_date = NULL;
            }
            if ($resubmission_date != NULL) {
                $due_date = $resubmission_date;
            } else {
                if ($studentIntakeUnit->due_date != NULL) {
                    $due_date = $studentIntakeUnit->due_date;
                } else {
                    if ($studentIntakeUnit->intakeUnit->due_date != NULL) {
                        $due_date = $studentIntakeUnit->intakeUnit->due_date;
                    } else {
                        $due_date = $assignment->due_date;
                    }
                }
            }
            $student_submission_allow =  Auth::guard('student')->user()->allow_submission_after_due_date;
            if ($student_submission_allow == 'on') {
                $allow = true;
            } else {
                if ($due_date < $today) {
                    if ($resubmission_id > 0) {
                        $allow = true;
                    } else {
                        $allow = false;
                    }
                } else {
                    $allow = true;
                }
            }
            if ($allow == true) {
                activityLog('Student', 'Opened assignments files submission add page');
                return view('student::student.unit.assignment.submission.files.create', compact('assignment', 'studentIntakeUnit', 'resubmission_id'));
            } else {
                return back()->with('failure', 'Due date has passed');
            }
        } else {
            return abort(404);
        }
    }

    /* Submit Student Unit Assignment */
    public function storeFilesSubmission(Request $request, $id, $student_intake_id)
    {
        $data = $request->all();
        if ($request->has('files')) {
            $data['assignment_id'] = $id;
            $data['student_id'] = Auth::guard('student')->user()->id;
            $submission = AssignmentSubmission::create($data);
            $files =  $request->file('files');
            foreach ($files as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/assignments/submissions/' . $id, $name);
                $data['file'] = 'images/assignments/submissions/' . $id . '/' . $name;
                $data['assignment_submission_id'] = $submission->id;
                AssignmentSubmissionFile::create($data);
            }
            if ($data['assignment_resubmission_id'] > 0) {
                AssignmentResubmission::where('id', $data['assignment_resubmission_id'])->update(['status' => 3]);
            }
            activityLog('Student', 'Assignment has been submitted');
            return redirect()->route('student.submission.index', [$id, $student_intake_id])->with('success', 'Assignment has been submitted');
        } else {
            return redirect()->back()->with('failure', 'Please upload atleast one file');
        }
    }

    /* Request for Unit Assignment Resubmission */
    public function requestResubmission($id)
    {
        $student_id = Auth::guard('student')->user()->id;
        AssignmentResubmission::create([
            'student_id' => $student_id,
            'assignment_id' => $id
        ]);
        activityLog('Student', 'Request for assignment resubmission created');
        return redirect()->back()->with('success', 'Request for assignment resubmission has been sent');
    }

    /* Student Unit Online Zoom Classes */
    public function onlineClass($id)
    {
        $studentIntake = StudentIntakeUnit::find($id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $zooms = OnlineClass::where('intake_unit_id', $studentIntake->intake_unit_id)->where('status', 1)->get();
            foreach ($zooms as $key => $value) {
                $recording = $value->recording;
                if ($recording) {
                    $zooms[$key]['recording'] = $recording->play_url;
                    $zooms[$key]['password'] = $recording->password;
                } else {
                    $zooms[$key]['recording'] = NULL;
                    $zooms[$key]['password'] = NULL;
                }
            }
            activityLog('Student', 'Opened zoom classes of ' . $studentIntake->intakeUnit->unit->name . 'from web');
            return view('student::student.unit.onlineclass.index', compact('zooms', 'studentIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Unit Online Team Classes */
    public function onlineClassTeam($id)
    {
        $studentIntake = StudentIntakeUnit::find($id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $teams = OnlineClassTeam::where('intake_unit_id', $studentIntake->intake_unit_id)->where('status', 1)->get();
            activityLog('Student', 'Opened teams classes of ' . $studentIntake->intakeUnit->unit->name . 'from web');
            return view('student::student.unit.teams.index', compact('teams', 'studentIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* List of student attendace by Intake Unit */
    public function attendance($id)
    {
        $studentIntake = StudentIntakeUnit::find($id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $records = Attendance::with('classSession')
                ->where('student_id', $student_id)
                ->where('intake_unit_id', $studentIntake->intake_unit_id)
                ->whereNull('intake_subject_id')
                ->orderBy('date', 'desc')->get();
            $summary = $this->attendanceSummary($records);
            activityLog('Student', 'Opened attendance of ' . $studentIntake->intakeUnit->unit->name . ' from web');
            return view('student::student.unit.attendance.index', compact('student_id', 'records', 'summary', 'studentIntake'));
        } else {
            return abort(404);
        }
    }

    /* List of student attendace by Intake Subject */
    public function subjectAttendance($id)
    {
        $studentIntake = StudentIntakeSubject::find($id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $records = Attendance::with('classSession')
                ->where('student_id', $student_id)
                ->where('intake_subject_id', $studentIntake->intake_subject_id)
                ->whereNull('intake_unit_id')
                ->orderBy('date', 'desc')->get();
            $summary = $this->attendanceSummary($records);
            activityLog('Student', 'Opened subject attendance of ' . $studentIntake->intakeSubject->subject->name . ' from web');
            return view('student::student.subject.attendance.index', compact('student_id', 'records', 'summary', 'studentIntake'));
        } else {
            return abort(404);
        }
    }

    /* Attendance percentage summary: excused excluded from both, late counts as attended */
    private function attendanceSummary($records)
    {
        $required = $records->where('attendance_status', '!=', 'excused')->count();
        $attended = $records->whereIn('attendance_status', ['present', 'late'])->count();
        return [
            'required' => $required,
            'attended' => $attended,
            'late' => $records->where('attendance_status', 'late')->count(),
            'excused' => $records->where('attendance_status', 'excused')->count(),
            'percentage' => $required > 0 ? round(($attended / $required) * 100, 1) : null,
        ];
    }

    /* Student Subject Resource List By Subject Id */
    public function subjectResource($id)
    {
        $studentIntake = StudentIntakeSubject::where('id', $id)->first();
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $resources = Resource::where('subject_id', $studentIntake->intakeSubject->subject_id)->where('user_type', 'Student')->where('status', 1)->get();
            activityLog('Student', 'Opened resources of ' . $studentIntake->intakeSubject->subject->name . 'from web');
            return view('student::student.subject.resource.index', compact('resources', 'studentIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Student Subject Resource List By Subject Id */
    public function subjectTimeTable($id)
    {
        $studentIntake = StudentIntakeSubject::where('id', $id)->first();
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student_id) {
            $intake_unit = $studentIntake->intakeSubject->intakeUnit->first();
            if ($intake_unit) {
                $intake_unit_id = $intake_unit->id;
            } else {
                $intake_unit_id = NULL;
            }
            $times = IntakeTime::where('intake_subject_id', $studentIntake->intake_subject_id)->where('intake_unit_id', $intake_unit_id)->orderBy('id', 'asc')->get();
            activityLog('Student', 'Opened time table of ' . $studentIntake->intakeSubject->subject->name . 'from web');
            return view('student::student.subject.timetable.index', compact('times', 'studentIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    public function questionIntegrityCheck(Request $request, $id, $student_intake_id)
    {
        $request->validate([
            'check_type' => 'required|in:ai_detection,paraphraser,plagiarism',
            'question_id' => 'required|integer',
            'text' => 'required|string|min:20',
        ]);

        $studentIntakeUnit = StudentIntakeUnit::find($student_intake_id);
        $student_id = Auth::guard('student')->user()->id;
        $assignment = Assignment::find($id);

        if (!$studentIntakeUnit || $studentIntakeUnit->studentIntakeCourse->student_id != $student_id || !$assignment || $assignment->type != 'question') {
            return response()->json(['message' => 'Assignment not found.'], 404);
        }

        $settingMap = [
            'ai_detection' => 'enable_student_assignment_ai_bot_checking',
            'paraphraser' => 'enable_student_assignment_paraphrasing',
            'plagiarism' => 'enable_student_assignment_plagiarism_test',
        ];
        $checkType = $request->input('check_type');
        if (getSettingValue($settingMap[$checkType]) !== 'enable') {
            return response()->json(['message' => 'This check is not enabled.'], 403);
        }

        $question = AssignmentQuestion::where('id', $request->input('question_id'))
            ->where('assignment_id', $assignment->id)
            ->first();

        if (!$question) {
            return response()->json(['message' => 'Question not found for this assignment.'], 404);
        }

        $text = trim(preg_replace('/\s+/', ' ', $request->input('text')));
        $text = substr($text, 0, 12000);
        $result = $this->runStudentDraftIntegrityCheck($checkType, $text, $assignment, $question, $student_id);

        activityLog('Student', 'Ran assignment draft ' . $result['title'] . ' before submission from web');

        return response()->json($result);
    }

    private function runStudentDraftIntegrityCheck($checkType, $text, Assignment $assignment, AssignmentQuestion $question, $studentId)
    {
        $labels = [
            'ai_detection' => 'AI Bot Checking',
            'paraphraser' => 'Paraphrasing Check',
            'plagiarism' => 'Plagiarism Test',
        ];

        $words = str_word_count(strtolower($text), 1);
        $wordCount = count($words);
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sentenceCount = max(count($sentences), 1);
        $avgSentenceLength = round($wordCount / $sentenceCount, 1);
        $uniqueWords = count(array_unique($words));
        $lexicalDiversity = $wordCount > 0 ? round(($uniqueWords / $wordCount) * 100, 1) : 0;
        $sentenceVariance = $this->studentDraftSentenceLengthVariance($sentences);
        $repeatedPhraseCount = $this->studentDraftRepeatedPhraseCount($text);
        $sources = [];

        if ($checkType === 'ai_detection') {
            $transitionRate = $this->studentDraftTransitionPhraseRate($text, $wordCount);
            $score = min(100, max(0, (int) round(
                ($sentenceVariance < 8 ? 30 : 12)
                + ($lexicalDiversity < 42 ? 25 : 8)
                + ($transitionRate > 3.5 ? 20 : 6)
                + ($avgSentenceLength > 24 ? 15 : 5)
            )));
            $metrics = [
                'Words checked' => $wordCount,
                'Average sentence length' => $avgSentenceLength,
                'Sentence length variance' => $sentenceVariance,
                'Lexical diversity' => $lexicalDiversity . '%',
                'AI-style transition phrase rate' => $transitionRate . '%',
            ];
            $summary = 'This draft was checked using local writing-pattern indicators before submission.';
        } elseif ($checkType === 'paraphraser') {
            $longSentenceRate = $this->studentDraftLongSentenceRate($sentences);
            $score = min(100, max(0, (int) round(
                ($longSentenceRate > 45 ? 30 : 12)
                + ($sentenceVariance > 18 ? 22 : 8)
                + ($lexicalDiversity > 55 ? 18 : 8)
                + min($repeatedPhraseCount * 5, 20)
            )));
            $metrics = [
                'Words checked' => $wordCount,
                'Long sentence rate' => $longSentenceRate . '%',
                'Sentence length variance' => $sentenceVariance,
                'Lexical diversity' => $lexicalDiversity . '%',
                'Repeated phrase groups' => $repeatedPhraseCount,
            ];
            $summary = 'This draft was checked for paraphrasing-style indicators before submission.';
        } else {
            $sources = $this->studentDraftAnswerMatches($text, $assignment, $question, $studentId);
            $matchedSources = $sources['matches'] ?? [];
            $score = min(100, max(0, (int) round(
                min($repeatedPhraseCount * 10, 35)
                + ($lexicalDiversity < 35 ? 15 : 5)
                + (count($matchedSources) > 0 ? 50 + (count($matchedSources) * 10) : 0)
            )));
            $metrics = [
                'Words checked' => $wordCount,
                'Stored answers checked' => $sources['checked'] ?? 0,
                'Stored matches found' => count($matchedSources),
                'Repeated phrase groups' => $repeatedPhraseCount,
                'Lexical diversity' => $lexicalDiversity . '%',
            ];
            $sources = $matchedSources;
            $summary = count($sources) > 0
                ? 'Possible matches were found in stored assignment answers. Review them before submitting.'
                : 'No strong stored-answer plagiarism indicators were found for this draft.';
        }

        return [
            'title' => $labels[$checkType],
            'score' => $score,
            'status' => $this->studentDraftRiskLabel($score),
            'summary' => $summary,
            'checked_at' => now()->format('Y-m-d H:i:s'),
            'metrics' => $metrics,
            'sources' => $sources,
            'provider' => 'Local draft checker',
            'note' => 'This pre-submission result is only a guide. Review your work and confirm before submitting.',
        ];
    }

    private function studentDraftAnswerMatches($text, Assignment $assignment, AssignmentQuestion $question, $studentId)
    {
        $matches = [];
        $checked = 0;
        $answers = AssignmentAnswer::with(['assignmentSubmission.student'])
            ->where('assignment_question_id', $question->id)
            ->whereNotNull('answer')
            ->whereHas('assignmentSubmission', function ($query) use ($assignment, $studentId) {
                $query->where('assignment_id', $assignment->id)
                    ->where('student_id', '!=', $studentId);
            })
            ->get();

        foreach ($answers as $answer) {
            $storedText = trim((string) $answer->answer);
            if ($storedText === '') continue;

            $checked++;
            $similarity = $this->studentDraftSimilarityScore($text, $storedText);
            if ($similarity < 18) continue;

            $matchedStudentId = optional($answer->assignmentSubmission)->student_id;
            $matchedStudentName = $matchedStudentId ? userName('Student', $matchedStudentId) : 'Matched student';
            $matches[] = [
                'title' => 'Matched with ' . $matchedStudentName,
                'student_name' => $matchedStudentName,
                'snippet' => 'Similarity with ' . $matchedStudentName . '\'s submitted answer: ' . $similarity . '%.',
                'score' => $similarity,
            ];
        }

        usort($matches, fn($a, $b) => ($b['score'] ?? 0) <=> ($a['score'] ?? 0));

        return ['checked' => $checked, 'matches' => array_slice($matches, 0, 6)];
    }

    private function studentDraftSimilarityScore($text, $storedText)
    {
        $a = $this->studentDraftWordShingles($text);
        $b = $this->studentDraftWordShingles($storedText);
        if (!$a || !$b) return 0;
        $intersection = count(array_intersect_key($a, $b));
        return round(($intersection / max(min(count($a), count($b)), 1)) * 100, 1);
    }

    private function studentDraftWordShingles($text)
    {
        $words = str_word_count(strtolower($text), 1);
        $shingles = [];
        for ($i = 0; $i < count($words) - 5; $i++) {
            $shingles[implode(' ', array_slice($words, $i, 6))] = true;
        }
        return $shingles;
    }

    private function studentDraftRepeatedPhraseCount($text)
    {
        $words = str_word_count(strtolower($text), 1);
        $phrases = [];
        for ($i = 0; $i < count($words) - 3; $i++) {
            $phrase = implode(' ', array_slice($words, $i, 4));
            $phrases[$phrase] = ($phrases[$phrase] ?? 0) + 1;
        }
        return count(array_filter($phrases, fn($c) => $c > 1));
    }

    private function studentDraftSentenceLengthVariance($sentences)
    {
        $lengths = array_map('str_word_count', $sentences);
        if (!$lengths) return 0;
        $avg = array_sum($lengths) / count($lengths);
        $variance = array_sum(array_map(fn($l) => pow($l - $avg, 2), $lengths)) / count($lengths);
        return round(sqrt($variance), 1);
    }

    private function studentDraftLongSentenceRate($sentences)
    {
        if (!$sentences) return 0;
        $long = count(array_filter($sentences, fn($s) => str_word_count($s) >= 28));
        return round(($long / count($sentences)) * 100, 1);
    }

    private function studentDraftTransitionPhraseRate($text, $wordCount)
    {
        if ($wordCount === 0) return 0;
        $phrases = ['moreover', 'furthermore', 'in conclusion', 'it is important to note', 'as a result', 'therefore', 'however', 'overall'];
        $lowerText = strtolower($text);
        $matches = array_sum(array_map(fn($p) => substr_count($lowerText, $p), $phrases));
        return round(($matches / $wordCount) * 100, 2);
    }

    private function studentDraftRiskLabel($score)
    {
        if ($score >= 70) return 'High';
        if ($score >= 40) return 'Medium';
        return 'Low';
    }
}
