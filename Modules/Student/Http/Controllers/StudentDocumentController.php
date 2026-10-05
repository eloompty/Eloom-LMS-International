<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Entities\StudentDocumentType;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentDocument;

class StudentDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student document.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::findorfail($id);
        if (checkRole('student_document', 'view') == true && $student) {
            $documents = StudentDocument::where('student_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Documents Menu');
            return view('student::document.index', compact('student', 'documents'));
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to view student document');
        }
    }

    /**
     * Show the form for creating a new student document.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::findorfail($id);
        if (checkRole('student_document', 'add') == true && $student) {
            $types = StudentDocumentType::where('status', 1)->get();
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Create Student Document Page');
            return view('student::document.create', compact('student', 'types'));
        } else {
            return redirect()->route('admin.student.document.index', $id)->with('failure', 'This user does not have permission to add student document');
        }
    }

    /**
     * Store a newly created student document in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['student_id'] = $id;
        $imageName = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('/images/students/documents'), $imageName);
        $data['path'] = 'images/students/documents/' . $imageName;
        $document = StudentDocument::create($data);
        activityLog('Admin', $document->name . ' of ' . userName('Student', $id) . ' created');
        return redirect()->route('admin.student.document.index', $id)->with('success', 'Student document added successfully');
    }

    /**
     * Show the form for editing the specified student document.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $studentDocument = StudentDocument::findorfail($id);
        if (checkRole('student_document', 'edit') == true && $studentDocument) {
            $types = StudentDocumentType::where('status', 1)->get();
            activityLog('Admin', 'Opened ' . userName('Student', $studentDocument->student_id) . ' Edit Document Page');
            return view('student::document.edit', compact('studentDocument', 'types'));
        } else {
            return redirect()->route('admin.student.document.index', $studentDocument->student_id)->with('failure', 'This user does not have permission to edit student document');
        }
    }

    /**
     * Update the specified student document in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $document = StudentDocument::find($id);
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/students/documents'), $imageName);
            $data['path'] = 'images/students/documents/' . $imageName;
        }
        $document->update($data);
        activityLog('Admin', $document->name . ' of ' . userName('Student', $document->student_id) . ' updated');
        return redirect()->route('admin.student.document.index', $document->student_id)->with('success', 'Student document updated successfully');
    }

    /**
     * Remove the specified student document from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $document = StudentDocument::findorfail($id);
        if (checkRole('student_document', 'delete') == true) {
            $document->update(['status' => 2]);
            activityLog('Admin', $document->name . ' of ' . userName('Student', $document->student_id) . ' updated status to deleted');
            return redirect()->back()->with('success', 'Student Social deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete student document');
        }
    }
}
