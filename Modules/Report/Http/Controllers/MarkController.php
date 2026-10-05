<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeSubjectMark;

class MarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the marking.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('marking_report', 'view') == true) {
            $data = $request->all();
            if (!isset($data['intake_subject_id'])) $id = 0;
            else $id = $data['intake_subject_id'];
            $from = NULL;
            $to = NULL;
            $studentInhtakSubjects = StudentIntakeSubject::where('intake_subject_id', $id)->get();
            $ids = [];
            $newIds = [];
            foreach ($studentInhtakSubjects as $studentInhtakeSubject) {
                $ids[] = $studentInhtakeSubject->id;
                // $studentID[] = $studentInhtakeSubject->studentIntakeCourse->student_id;
                $newIds[] = [
                    'student_intake_subject_id' => $studentInhtakeSubject->id,
                    'studentID' => $studentInhtakeSubject->studentIntakeCourse->student_id,
                    ];
            }
            // dd($newIds);

            $studentMarks = StudentIntakeSubjectMark::whereIn('student_intake_subject_id', $ids)->get()->groupBy('student_intake_subject_id');;

            // dd($studentMarks);
            // Convert the collection into an array with key-value pairs
            $resultArray = $studentMarks->map(function ($group) {
                return $group->map(function ($item) {
                    $item->studentIntakeSubject->studentIntakeCourse->student;
                    $studentName = $item->studentIntakeSubject->studentIntakeCourse->student->first_name;
                    $markDetails = [
                        'marks' => [
                            'name' => $item->name,
                            'full_marks' => $item->full_marks,
                            'pass_marks' => $item->pass_marks,
                            'obtain_marks' => $item->obtain_marks,
                        ],
                        'student_name' => $studentName,
                    ];
                    return $markDetails;
                })->toArray();
            })->toArray();
            dd($resultArray);


            // // Fetch all unique student_intake_subject_id grouped by name
            // $groupedRecords = StudentIntakeSubjectMark::select('*')
            // ->get()
            // ->groupBy('student_intake_subject_id');

            // // Convert the collection into an array with key-value pairs
            // $resultArray = $groupedRecords->map(function ($group) {
            //         return $group->map(function ($item) {
            //             $item->studentIntakeSubject->id;
            //             dd($item);
            //             return $item->toArray();
            //         })->toArray();
            //     })->toArray();

            // dd($resultArray);
            activityLog('Admin', 'Opened Intake Report Menu');
            return view('report::marking.index')->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
