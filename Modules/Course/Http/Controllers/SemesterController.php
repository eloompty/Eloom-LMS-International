<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\Semester;

class SemesterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the semester.
     * @return Renderable
     */
    public function index($id)
    {
        $course = Course::findorfail($id);
        if (checkRole('semester', 'view') == true && $course) {
            $semesters = Semester::where('course_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Opened Course Semester Page');
            return view('course::semester.index', compact('course', 'semesters'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new semester.
     * @return Renderable
     */
    public function create($id)
    {
        $course = Course::findorfail($id);
        if (checkRole('semester', 'add') == true && $course) {
            activityLog('Admin', 'Opened Create Semester Page of ' . $course->course_name);
            return view('course::semester.create', compact('course'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created semester in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['course_id'] = $id;
        $semester = Semester::create($data);
        activityLog('Admin', $semester->name . ' of ' . $semester->course->course_name . ' created',);
        if (request()->is('admin/course*')) $route = 'admin.course.semester.index';
        else $route = 'admin.unregistered.semester.index';
        return redirect()->route($route, $id)->with('success', 'Semester has been added successfully');
    }

    /**
     * Show the form for editing the specified semester.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $semester = Semester::findorfail($id);
        if (checkRole('semester', 'edit') == true) {
            activityLog('Admin', $semester->name . ' edit page opened',);
            return view('course::semester.edit', compact('semester'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified semester in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $semester = Semester::findorfail($id);
        $semester->update($data);
        activityLog('Admin', $semester->name . ' updated',);
        if ($semester->course->regsitered == 1) $route = 'admin.course.semester.index';
        else $route = 'admin.unregistered.semester.index';
        return redirect()->route($route, $semester->course_id)->with('success', 'Semester has been updated successfully');
    }

    /**
     * Remove the specified semester from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('semester', 'delete') == true) {
            $semester = Semester::findorfail($id);
            $semester->update(['status' => 2]);
            activityLog('Admin', $semester->name . ' updated to status deleted',);
            return redirect()->back()->with('success', 'Semester has been deleted successfully');
        } else {
            return abort(404);
        }
    }
}
