<?php

namespace Modules\Assignment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Assignment\Entities\AssignmentAnswer;
use Modules\Assignment\Entities\AssignmentGrade;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Assignment\Entities\AssignmentSubmissionGrade;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeUnit;
use setasign\Fpdi\Fpdi;
use App\PDF\MyPDF;
use Modules\Assignment\Entities\AssignmentSubmissionFile;
use Modules\Student\Entities\StudentIntakeSubject;

class AssignmentSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the assignment submission.
     * @return Renderable
     */
    public function index($id)
    {
        if (checkRole('assignment_submission', 'view') == true) {
            activityLog('Admin', 'Opened Assignment Submission Menu');
            $submissions = AssignmentSubmission::where('assignment_id', $id)->orderBy('id', 'desc')->get();
            return view('assignment::submission.index', compact('submissions'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified assignment submission.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $submission = AssignmentSubmission::findorfail($id);
        $check = checkCourseDeliverySite($submission->assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('assignment_submission', 'edit') == true && $submission && $check == true) {
            $studentIntakeCourse = StudentIntakeCourse::where('student_id', $submission->student_id)->where('intake_course_id', $submission->assignment->intakeUnit->intake_course_id)->first();
            $studentIntakeSubject = StudentIntakeSubject::where('student_intake_course_id', $studentIntakeCourse->id)->where('intake_subject_id', $submission->assignment->intake_subject_id)->first();
            if ($studentIntakeSubject->is_complete == 1) {
                return redirect()->back()->with('failure', 'Student subject is already closed, submission cannot be edited.');
            } else {
                $grades = AssignmentGrade::where('status', 1)->pluck('name', 'id');
                if ($submission->assignmentSubmissionGrade == NULL) {
                    $submission_graded = 'themes/AdminLTE/dist/img/boxed-bg.png';
                    $show_to_student = 0;
                } else {
                    $submission_graded = $submission->assignmentSubmissionGrade->path;
                    $show_to_student = $submission->assignmentSubmissionGrade->show_to_student;
                }
                activityLog('Admin', $submission->assignment->name . ' assignment submission opened');
                return view('assignment::submission.edit', compact('submission', 'grades', 'submission_graded', 'show_to_student'));
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified assignment submission in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if (isset($data['show_to_student'])) $show = 1;
        else $show = 0;
        $data['graded_date'] = date('Y-m-d');
        $submission = AssignmentSubmission::where('id', $id)->first();
        $submission->update($data);
        if ($request->hasfile('grade_path')) {
            $imageName = time() . '.' . request()->grade_path->getClientOriginalExtension();
            request()->grade_path->move(public_path('/images/assignments/submissions/graded'), $imageName);
            $path = 'images/assignments/submissions/graded/' . $imageName;
            if ($submission->assignmentSubmissionGrade == NULL) {
                AssignmentSubmissionGrade::create([
                    'assignment_submission_id' => $id,
                    'path' => $path,
                    'show_to_student' => $show
                ]);
            } else {
                AssignmentSubmissionGrade::where('assignment_submission_id', $id)->update([
                    'path' => $path,
                    'show_to_student' => $show
                ]);
            }
        } else {
            if ($submission->assignmentSubmissionGrade) {
                AssignmentSubmissionGrade::where('assignment_submission_id', $id)->update([
                    'show_to_student' => $show
                ]);
            }
        }
        activityLog('Admin', $submission->assignment->name . ' assignment submission graded');
        return redirect()->route('admin.assignment.submission.index', $submission->assignment_id)->with('success', 'Assignment graded successfully');
    }

    /**
     * Display a listing of the assignment question submission.
     * @return Renderable
     */
    public function question($id)
    {
        $submission = AssignmentSubmission::findorfail($id);
        $check = checkCourseDeliverySite($submission->assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('assignment_submission', 'edit') == true && $submission && $check == true) {
            $answers = AssignmentAnswer::where('assignment_submission_id', $id)->get();
            activityLog('Admin', $submission->assignment->name . ' assignment question submission opened');
            return view('assignment::submission.question.index', compact('submission', 'answers'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Display a listing of the assignment multi choice question submission.
     * @return Renderable
     */
    public function mcq($id)
    {
        $submission = AssignmentSubmission::findorfail($id);
        $check = checkCourseDeliverySite($submission->assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('assignment_submission', 'edit') == true && $submission && $check == true) {
            $answers = AssignmentAnswer::where('assignment_submission_id', $id)->get();
            activityLog('Admin', $submission->assignment->name . ' assignment mcq submission opened');
            return view('assignment::submission.mcq.index', compact('submission', 'answers'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Display of the assignment pdf submission.
     * @return Renderable
     */
    public function pdf($id, $type)
    {
        $submission = AssignmentSubmission::find($id);
        if (checkRole('assignment_submission', 'edit') == true && $submission) {
            $studentIntakeCourse = StudentIntakeCourse::where('student_id', $submission->student_id)->where('intake_course_id', $submission->assignment->intakeUnit->intake_course_id)->first();
            $studentIntakeSubject = StudentIntakeSubject::where('student_intake_course_id', $studentIntakeCourse->id)->where('intake_subject_id', $submission->assignment->intake_subject_id)->first();
            if ($studentIntakeSubject->is_complete == 1) {
                return redirect()->back()->with('failure', 'Student subject is already closed, submission cannot be edited.');
            } else {
            $grades = AssignmentGrade::where('status', 1)->pluck('name', 'id');
            if ($submission->assignmentSubmissionGrade == NULL) {
                $submission_graded = 'themes/AdminLTE/dist/img/boxed-bg.png';
                $show_to_student = 0;
            } else {
                $submission_graded = $submission->assignmentSubmissionGrade->path;
                $show_to_student = $submission->assignmentSubmissionGrade->show_to_student;
            }
            if ($type == 'student') {
                $submission->path = $submission->path;
            } else {
                $submission->path = $submission->assignmentSubmissionGrade->path;
            }
            activityLog('Admin', $submission->assignment->name . ' assignment pdf submission opened');
            return view('assignment::submission.pdf.index', compact('submission', 'grades', 'submission_graded', 'show_to_student', 'type'));
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Display the assignment pdf save.
     * @return Renderable
     */
    public function pdfsave(Request $request, $id, $type)
    {
        $request->validate([
            // 'pdfFile' => 'required|mimes:pdf',
            'annotations' => 'required|json',
        ]);

        $data = $request->all();
        // $id = $data['id'];
        $data['graded_date'] = date('Y-m-d');
        if (isset($data['show_to_student'])) $show = 1;
        else $show = 0;
        $submission = AssignmentSubmission::where('id', $id)->first();
        $submission->update($data);
        Log::alert($request->all());
        // if ($request->hasfile('pdfFile')) {
        if ($request->pdfFile != null) {
            // $pdfFile = $request->file('pdfFile');
            // $annotations = json_decode($request->input('annotations'), true);

            // // Save the PDF file
            // $fileName = time() . '.' . $pdfFile->getClientOriginalExtension();
            // // $pdfFile->move(public_path('/images/assignments/submissions/graded'), $fileName);   // move the file to public folder 

            // //Grab the file from from server path
            $fileName = time() . '.' . 'pdf';
            $annotations = json_decode($request->input('annotations'), true);
            if ($type == 'student') {
                $submission->path = $submission->path;
            } else {
                $submission->path = $submission->assignmentSubmissionGrade->path;
            }
            $filePath = public_path($submission->path);

            // Apply annotations to the PDF
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($filePath);

            for ($pageNum = 1; $pageNum <= $pageCount; $pageNum++) {
                $tplIdx = $pdf->importPage($pageNum);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx);

                // Get page dimensions
                $size = $pdf->getTemplateSize($tplIdx);
                $pageHeight = $size['height'];
                $pageWidth = $size['width'];
                $scaleFactor = 2.85;

                if ($pageHeight <= 0) {
                    Log::error('Invalid page height', ['page' => $pageNum, 'height' => $pageHeight]);
                    continue;
                } else {
                    Log::error('Current Page height', ['page' => $pageNum, 'height' => $pageHeight]);
                }

                if (isset($annotations[$pageNum]['objects']) && is_array($annotations[$pageNum]['objects'])) {
                    $this->applyAnnotations($pdf, $annotations[$pageNum]['objects'], $pageHeight, $pageWidth, $scaleFactor);
                } else {
                    Log::warning('Annotations for page not found or invalid', ['page' => $pageNum, 'annotations' => $annotations[$pageNum] ?? null]);
                    Log::warning('Annotations', ['objects' => $annotations, 'annotations' => $annotations[$pageNum] ?? null]);
                }
            }

            $outputPath = public_path('/images/assignments/submissions/graded/' . $fileName);
            $pdf->Output($outputPath, 'F');
            Log::error('done');

            $path = 'images/assignments/submissions/graded/' . $fileName;
            if ($submission->assignmentSubmissionGrade == NULL) {
                AssignmentSubmissionGrade::create([
                    'assignment_submission_id' => $id,
                    'path' => $path,
                    'show_to_student' => $show
                ]);
            } else {
                AssignmentSubmissionGrade::where('assignment_submission_id', $id)->update([
                    'path' => $path,
                    'show_to_student' => $show
                ]);
            }
        } else {
            if ($submission->assignmentSubmissionGrade) {
                AssignmentSubmissionGrade::where('assignment_submission_id', $id)->update([
                    'show_to_student' => $show
                ]);
            }
        }
        return response()->json(['message' => 'File has been saved.']);
    }

    private function applyAnnotations($pdf, $annotations, $pageHeight, $pageWidth, $scaleFactor)
    {
        foreach ($annotations as $annotation) {
            Log::info('Processing annotation: ', $annotation); // Debug line to log each annotation
            if (isset($annotation['type'])) {
                if ($annotation['type'] === 'line') {
                    if (isset($annotation['strokeWidth'], $annotation['stroke'], $annotation['x1'], $annotation['x2'], $annotation['y1'], $annotation['y2'])) {
                        $pdf->SetLineWidth(($annotation['strokeWidth']) / $scaleFactor);
                        $rgbColor = $this->hex2rgb($annotation['stroke']);
                        $alpha = 0.5; // Adjust this value as needed
                        $pdf->SetDrawColor($rgbColor[0], $rgbColor[1], $rgbColor[2], $alpha);

                        // Transform coordinates to PDF's coordinate system
                        $x1 = ($annotation['left'] / $scaleFactor) + ($annotation['x1'] / $scaleFactor);
                        $y1 = (($annotation['top'] / $scaleFactor) + ($annotation['y1'] / $scaleFactor));
                        $x2 = ($annotation['left'] / $scaleFactor) + ($annotation['x2'] / $scaleFactor);
                        $y2 = (($annotation['top'] / $scaleFactor) + ($annotation['y2'] / $scaleFactor));
                        // $pdf->SetAlpha(0.5); // 0.5 opacity (0 = fully transparent, 1 = fully opaque)

                        // Draw the line
                        $pdf->Line($x1, $y1, $x2, $y2);
                    } else {
                        Log::error('Missing keys in line annotation', ['annotation' => $annotation]);
                    }
                } elseif ($annotation['type'] === 'i-text') {
                    $pdf->SetFont('times', '', $annotation['fontSize']);
                    $color = $this->hex2rgb($annotation['fill']);
                    $pdf->SetTextColor($color[0], $color[1], $color[2]);
                    $pdf->SetXY($annotation['left'] / $scaleFactor, $annotation['top'] / $scaleFactor);
                    $pdf->Write(0, $annotation['text']);
                } elseif ($annotation['type'] === 'rect') {
                    $pdf->SetLineWidth($annotation['strokeWidth']);
                    $color = $this->hex2rgb($annotation['stroke']);
                    $pdf->SetDrawColor($color[0], $color[1], $color[2]);
                    $pdf->Rect($annotation['left'] / $scaleFactor, $annotation['top'] / $scaleFactor, $annotation['width'] / $scaleFactor, $annotation['height'] / $scaleFactor);
                } else {
                    Log::warning('Unsupported annotation type', ['annotation' => $annotation]);
                }
            }
        }
    }

    private function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return [$r, $g, $b];
    }

    /**
     * Display a listing of the assignment files submission.
     * @return Renderable
     */
    public function files($id)
    {
        $submission = AssignmentSubmission::findorfail($id);
        $check = checkCourseDeliverySite($submission->assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('assignment_submission', 'edit') == true && $submission && $check == true) {
            $studentIntakeCourse = StudentIntakeCourse::where('student_id', $submission->student_id)->where('intake_course_id', $submission->assignment->intakeUnit->intake_course_id)->first();
            $studentIntakeSubject = StudentIntakeSubject::where('student_intake_course_id', $studentIntakeCourse->id)->where('intake_subject_id', $submission->assignment->intake_subject_id)->first();
            if ($studentIntakeSubject->is_complete == 1) {
                return redirect()->back()->with('failure', 'Student subject is already closed, submission cannot be edited.');
            } else {
                $grades = AssignmentGrade::where('status', 1)->pluck('name', 'id');
                $files = AssignmentSubmissionFile::where('assignment_submission_id', $id)->get();
                activityLog('Admin', $submission->assignment->name . ' assignment submission opened');
                return view('assignment::submission.files.index', compact('submission', 'files', 'grades'));
            }
        } else {
            return abort(404);
        }
    }

    public function filesEdit($id)
    {
        $file = AssignmentSubmissionFile::findorfail($id);
        $check = checkCourseDeliverySite($file->assignmentSubmission->assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('assignment_submission', 'edit') == true && $check == true) {
            activityLog('Admin', $file->assignmentSubmission->assignment->name . ' assignment file submission opened');
            return view('assignment::submission.files.edit', compact('file'));
        } else {
            return abort(404);
        }
    }

    public function filesUpdate(Request $request, $id)
    {
        $file = AssignmentSubmissionFile::findorfail($id);
        $imageName = time() . '.' . request()->grade_path->getClientOriginalExtension();
        request()->grade_path->move(public_path('/images/assignments/submissions/graded'), $imageName);
        $path = 'images/assignments/submissions/graded/' . $imageName;
        $file->update(['graded_file' => $path]);
        return redirect()->route('admin.assignment.submission.files', $file->assignment_submission_id);
    }
}
