<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Course\Entities\Subject;
use Modules\Course\Entities\UnitAssignment;

class SubjectAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the subject assignmnet.
     * @return Renderable
     */
    public function index($id)
    {
        $subject = Subject::find($id);
        if (checkRole('unit_assignment', 'view') == true && $subject) {
            $assignments = UnitAssignment::where('subject_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened Assignment List of ' . $subject->name);
            return view('course::subject.assignment.index', compact('subject', 'assignments'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new subject assignmnet.
     * @return Renderable
     */
    public function create($id)
    {
        $subject = Subject::findorfail($id);
        if (checkRole('unit_assignment', 'add') == true) {
            activityLog('Admin', 'Opened Assignment List of ' . $subject->name);
            return view('course::subject.assignment.create', compact('subject'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created subject assignmnet in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $user_id = Auth::guard('user')->user()->id;
        $data['subject_id'] = $id;
        $data['unit_id'] = 0;
        $data['user_id'] = $user_id;
        $assignment = UnitAssignment::create($data);
        if ($data['type'] == 'file') {
            if (request()->is('admin/course*')) {
                $route = 'admin.course.subject.assignment.file.create';
            } else {
                $route = 'admin.unregistered.subject.assignment.file.create';
            }
        } elseif ($data['type'] == 'multiple files') {
            if (request()->is('admin/course*')) {
                $route = 'admin.course.subject.assignment.files.create';
            } else {
                $route = 'admin.unregistered.subject.assignment.files.create';
            }
        } elseif ($data['type'] == 'question') {
            if (request()->is('admin/course*')) {
                $route = 'admin.course.subject.assignment.question.create';
            } else {
                $route = 'admin.unregistered.subject.assignment.question.create';
            }
        } else {
            if (request()->is('admin/course*')) {
                $route = 'admin.course.subject.assignment.mcq.create';
            } else {
                $route = 'admin.unregistered.subject.assignment.mcq.create';
            }
        }
        return redirect()->route($route, [$assignment->id])->with('assignment', $assignment);
    }

    /**
     * Show the form for editing the specified subject assignmnet.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $assignment = UnitAssignment::findorfail($id);
        if (checkRole('unit_assignment', 'edit') == true) {
            activityLog('Admin', $assignment->name . ' edit page opened');
            return view('course::subject.assignment.edit', compact('assignment'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified subject assignmnet in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->hasfile('path')) {
            $imageName = time() . '.' . request()->path->getClientOriginalExtension();
            request()->path->move(public_path('/images/assignments'), $imageName);
            $data['path'] = 'images/assignments/' . $imageName;
        }
        unset($data['_token']);
        $subjectAssignment = UnitAssignment::where('id', $id)->first();
        $subjectAssignment->update($data);
        Assignment::where('unit_assignment_id', $id)->update($data);
        activityLog('Admin', $data['name'] . ' assignment updated');
        if ($subjectAssignment->subject->course->registered == 1) $route = 'admin.course.subject.assignment.index';
        else $route = 'admin.unregistered.subject.assignment.index';
        return redirect()->route($route, $subjectAssignment->subject_id)->with('success', 'Subject assignment updated successfully');
    }

    /**
     * Remove the specified subject assignmnet from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('unit_assignment', 'delete') == true) {
            $subjectAssingnment = UnitAssignment::where('id', $id)->first();
            $subjectAssingnment->update(['status' => 2]);
            Assignment::where('unit_assignment_id', $id)->update(['status' => 2]);
            activityLog('Admin', $subjectAssingnment->name . ' assignment updated status to deleted');
            return redirect()->back()->with('success', 'Assignment deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete subject resource');
        }
    }
}
