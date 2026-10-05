<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentNote;

class StudentNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }
    
    /**
     * Show the form for creating a new student note.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::find($id);
        if (checkRole('student', 'view') == true && $student) {
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Create note page');
            return view('student::note.create', compact('student'));
        } else {
            return redirect()->route('admin.student.show', $id)->with('failure', 'This user does not have permission to add student note');
        }
    }

    /**
     * Store a newly created student note in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['student_id'] = $id;
        $data['user_id'] = Auth::guard('user')->user()->id;
        $data['user_type'] = 'Admin';
        StudentNote::create($data);
        activityLog('Admin', userName('Student', $id) . ' note created');
        return redirect()->route('admin.student.show', $id)->with('success', 'Student Note added successfully');
    }

    /**
     * Show the form for editing the specified student note.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $note = StudentNote::find($id);
        if ($note) {
            if (checkRole('student', 'view') == true) {
                activityLog('Admin', 'Opened ' . userName('Student', $note->student_id) . ' Edit Note Page');
                return view('student::note.edit', compact('note'));
            } else {
                return redirect()->route('admin.student.show', $note->student_id)->with('failure', 'This user does not have permission to edit student note');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified student note in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $note = StudentNote::where('id', $id)->first();
        $note->update($data);
        return redirect()->route('admin.student.show', $note->student_id)->with('success', 'Student note updated successfully');
    }

    /**
     * Remove the specified student note from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('student', 'view') == true) {
            StudentNote::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Student note has been deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete student note');
        }
    }
}
