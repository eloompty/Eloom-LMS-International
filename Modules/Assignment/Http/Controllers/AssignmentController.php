<?php

namespace Modules\Assignment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentQuestion;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the assignment.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('assignment', 'view') == true) {
            activityLog('Admin', 'Opened Assignment Menu');
            $ids = getDeliverySiteIds();
            $assignments = Assignment::join('intake_subjects', 'intake_subjects.id', '=', 'assignments.intake_subject_id')
                ->join('intake_courses', 'intake_courses.id', '=', 'intake_subjects.intake_course_id')
                ->join('courses', 'courses.id', '=', 'intake_courses.course_id')
                ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('assignments.*')
                ->orderBy('assignments.id', 'desc')->get();
            return view('assignment::index', compact('assignments'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified assignment.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $assignment = Assignment::findorfail($id);
        $check = checkCourseDeliverySite($assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('assignment', 'edit') == true && $assignment && $check == true) {
            activityLog('Admin', 'Opened Assignment Edit Page');
            return view('assignment::edit', compact('assignment'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified assignment in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $assignment = Assignment::where('id', $id)->first();
        if ($request->hasfile('path')) {
            $imageName = time() . '.' . request()->path->getClientOriginalExtension();
            request()->path->move(public_path('/images/assignments'), $imageName);
            $data['path'] = 'images/assignments/' . $imageName;
        }
        $assignment->update($data);
        activityLog('Admin', $assignment->name . ' assignment updated');
        return redirect()->route('admin.assignment.index')->with('success', 'Assignment updated successfully');
    }

    /**
     * Display a listing of the assignment question.
     * @return Renderable
     */
    public function question($id)
    {
        $assignment = Assignment::findorfail($id);
        $check = checkCourseDeliverySite($assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('assignment', 'view') == true && $assignment && $assignment->type == 'question' && $check == true) {
            activityLog('Admin', 'Opened Question Assignment Page');
            $questions = AssignmentQuestion::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', $assignment->name . ' assignment question detail page opened from assignment menu');
            return view('assignment::question.index', compact('assignment', 'questions'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Display a listing of the assignment multi choice question.
     * @return Renderable
     */
    public function mcq($id)
    {
        $assignment = Assignment::findorfail($id);
        $check = checkCourseDeliverySite($assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('assignment', 'view') == true && $assignment && $assignment->type == 'mcq' && $check == true) {
            activityLog('Admin', 'Opened Question Assignment Page');
            $questions = AssignmentQuestion::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            foreach ($questions as $key => $value) {
                $questions[$key]['choices'] = $value->choice;
            }
            activityLog('Admin', $assignment->name . ' assignment question detail page opened from assignment menu');
            return view('assignment::mcq.index', compact('assignment', 'questions'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    public function menu()
    {
        if (checkRole('assignment', 'view') == true) {
            activityLog('Admin',  'Opened Assignments Menu Page');
            return view('assignment::menu');
        } else {
            return abort(404);
        }
    }
}
