<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeSemester;
use Modules\Student\Entities\StudentIntakeSemester;

class IntakeSemesterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake semester.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeCourse = IntakeCourse::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourse->course_id);
        if (checkRole('intake_course', 'view') == true && $intakeCourse && $check == true) {
            $semesters = IntakeSemester::where('intake_course_id', $id)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            activityLog('Admin', $intakeCourse->reference_name . ' semester lists opened');
            return view('intake::course.semester.index', compact('intakeCourse', 'semesters'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified intake semester.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeSemester = IntakeSemester::findorfail($id);
        $check = checkCourseDeliverySite($intakeSemester->intakeCourse->course_id);
        if (checkRole('intake_course', 'edit') == true && $intakeSemester && $check == true) {
            activityLog('Admin', $intakeSemester->semester->name . ' semester updated');
            return view('intake::course.semester.edit', compact('intakeSemester'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified intake semester in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        // Update Intake Semester
        $intakeSemester = IntakeSemester::findorfail($id);
        $intakeSemester->update($data);
        // Update Student Intake
        StudentIntakeSemester::where('intake_semester_id', $id)->update($data);
        activityLog('Admin', 'Intake semester id:' . $intakeSemester->id . ' Updated');
        return redirect()->route('admin.intake.semester.index', $intakeSemester->intake_course_id)->with('success', 'Intake Semester update successfully');
    }
}
