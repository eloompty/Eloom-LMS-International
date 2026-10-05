<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Entities\DocumentType;

class DocumentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the Document Type.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('document_type', 'view') == true) {
            activityLog('Admin', 'Opened Document Type List');
            $types = DocumentType::orderby('id', 'asc')->get();
            return view('document::type.index', compact('types'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new Document Type.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('document_type', 'add') == true) {
            activityLog('Admin', 'Opened create Document Type Page');
            return view('document::type.create');
        } else {
            return redirect()->route('admin.document.type.index')->with('failure', 'This user does not have permission to add Document Type');
        }
    }

    /**
     * Store a newly created Document Type in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $type = DocumentType::create($data);
        activityLog('Admin', $type->title . ' DocumentType created');
        return redirect()->route('admin.document.type.index')->with('success', 'Document Type has been added successfully');
    }

    /**
     * Show the form for editing the specified Document Type.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('document_type', 'edit') == true) {
            $type = DocumentType::findorfail($id);
            activityLog('Admin', $type->name .  'edit page opened');
            return view('document::type.edit', compact('type'));
        } else {
            return redirect()->route('admin.document.type.index')->with('failure', 'This user does not have permission to edit Document Type');
        }
    }

    /**
     * Update the specified Document Type in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $type = DocumentType::where('id', $id)->first();
        $type->update($data);
        activityLog('Admin', $type->title . ' updated');
        return redirect()->route('admin.document.type.index')->with('success', 'Document Type has been updated successfully');
    }

    /**
     * Remove the specified Document Type from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('document_type', 'delete') == true) {
            $type = DocumentType::where('id', $id)->first();
            $type->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $type->title . ' updated to deleted');
            return redirect()->back()->with('success', 'Document Type deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Document Type');
        }
    }
}
