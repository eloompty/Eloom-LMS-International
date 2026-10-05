<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Entities\StudentDocumentType;

class StudentDocumentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student document type.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('student_document_type', 'view') == true) {
            activityLog('Admin', 'Opened Student Document Type List');
            $types = StudentDocumentType::orderby('id', 'asc')->get();
            return view('document::studentType.index', compact('types'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student document type.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('student_document_type', 'add') == true) {
            activityLog('Admin', 'Opened Create Student Document Type Page');
            return view('document::studentType.create');
        } else {
            return redirect()->route('admin.document.type.student.index')->with('failure', 'This user does not have permission to add Student Document Type');
        }
    }

    /**
     * Store a newly created student document type in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $type = StudentDocumentType::create($data);
        activityLog('Admin', $type->title . ' Student Document Type created');
        return redirect()->route('admin.document.type.index')->with('success', 'Student Document Type has been added successfully');
    }

    /**
     * Show the form for editing the specified student document type.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('student_document_type', 'edit') == true) {
            $type = StudentDocumentType::findorfail($id);
            activityLog('Admin', $type->name .  'edit page opened');
            return view('document::studentType.edit', compact('type'));
        } else {
            return redirect()->route('admin.document.type.student.index')->with('failure', 'This user does not have permission to edit Student Document Type');
        }
    }

    /**
     * Update the specified student document type in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $type = StudentDocumentType::where('id', $id)->first();
        $type->update($data);
        activityLog('Admin', $type->title . ' updated');
        return redirect()->route('admin.document.type.student.index')->with('success', 'Student Document Type has been updated successfully');
    }

    /**
     * Remove the specified student document type from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('student_document_type', 'delete') == true) {
            $type = StudentDocumentType::where('id', $id)->first();
            $type->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $type->title . ' updated to deleted');
            return redirect()->back()->with('success', 'Document Type deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Student Document Type');
        }
    }
}
