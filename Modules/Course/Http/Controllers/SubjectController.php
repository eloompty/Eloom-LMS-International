<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Semester;
use Modules\Course\Entities\Subject;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the subject.
     * @return Renderable
     */
    public function index($id)
    {
        $semester = Semester::findorfail($id);
        if (checkRole('subject', 'view') == true) {
            $subjects = Subject::where('semester_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Opened Course Subject Page');
            return view('course::subject.index', compact('semester', 'subjects'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new subject.
     * @return Renderable
     */
    public function create($id)
    {
        $semester = Semester::findorfail($id);
        if (checkRole('subject', 'add') == true ) {
            activityLog('Admin', 'Opened Create Unit Page of ' . $semester->name);
            return view('course::subject.create', compact('semester'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created subject in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $semester = Semester::find($id);
        $data['course_id'] = $semester->course_id;
        $data['semester_id'] = $id;
        $subject = Subject::create($data);
        activityLog('Admin', $subject->name . ' of ' . $subject->course->course_name . ' created',);
        if (request()->is('admin/course*')) $route = 'admin.course.subject.index';
        else $route = 'admin.unregistered.subject.index';
        return redirect()->route($route, $id)->with('success', 'Subject has been added successfully');
    }


    /**
     * Show the form for editing the specified subject.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $subject = Subject::findorfail($id);
        if (checkRole('subject', 'edit') == true) {
            activityLog('Admin', $subject->name . ' edit page opened',);
            return view('course::subject.edit', compact('subject'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified subject in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $subject = Subject::findorfail($id);
        $subject->update($data);
        activityLog('Admin', $subject->name . ' updated',);
        if ($subject->course->regsitered == 1) $route = 'admin.course.subject.index';
        else $route = 'admin.unregistered.subject.index';
        return redirect()->route($route, $subject->semester_id)->with('success', 'Subject has been updated successfully');
    }

    /**
     * Remove the specified subject from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('subject', 'delete') == true) {
            $subject = Subject::findorfail($id);
            $subject->update(['status' => 2]);
            activityLog('Admin', $subject->name . ' updated to status deleted',);
            return redirect()->back()->with('success', 'Subject has been deleted successfully');
        } else {
            return abort(404);
        }
    }
}
