<?php

namespace Modules\Assignment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\AssignmentGrade;

class AssignmentGradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the assignment grades.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('assignment_grade', 'view') == true) {
            activityLog('Admin', 'Opened Assignment Grade Menu');
            $grades = AssignmentGrade::orderBy('name', 'asc')->get();
            return view('assignment::grade.index', compact('grades'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new assignment grade.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('assignment_grade', 'add') == true) {
            activityLog('Admin', 'Opened Create Assignment Grade Menu');
            return view('assignment::grade.create');
        } else {
            return redirect()->route('admin.assignment.grade.index')->with('failure', 'This user does not have permission to add assignment grade');
        }
    }

    /**
     * Store a newly created assignment grade in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        AssignmentGrade::create($data);
        activityLog('Admin', $data['name'] . ' created');
        return redirect()->route('admin.assignment.grade.index')->with('success', 'Assignment Grade has been added successfully');
    }

    /**
     * Show the form for editing the specified assignment grade.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('assignment_grade', 'edit') == true) {
            $grade = AssignmentGrade::findorfail($id);
            activityLog('Admin', $grade->name . ' edit page opened');
            return view('assignment::grade.edit', compact('grade'));
        } else {
            return redirect()->route('admin.assignment.grade.index')->with('failure', 'This user does not have permission to edit assignment grade');
        }
    }

    /**
     * Update the specified assignment grade in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        AssignmentGrade::where('id', $id)->update($data);
        activityLog('Admin', $data['name'] . ' updated');
        return redirect()->route('admin.assignment.grade.index')->with('success', 'Assignment Grade has been updated successfully');
    }

    /**
     * Remove the specified assignment grade from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('assignment_grade', 'delete') == true) {
            AssignmentGrade::where('id', $id)->update(['status' => 2]);
            $grade = AssignmentGrade::find($id);
            activityLog('Admin', 'Status of ' . $grade->name . ' updated to deleted');
            return redirect()->back()->with('success', 'Assignment Grade deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete assignment grade');
        }
    }
}
