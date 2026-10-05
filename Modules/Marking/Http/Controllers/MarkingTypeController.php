<?php

namespace Modules\Marking\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Marking\Entities\MarkingType;

class MarkingTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the marking type.
     * @return Renderable
     */
    public function index()
    {
         if (checkRole('marking_type', 'view') == true) {
            activityLog('Admin', 'Opened Marking Types List');
            $types = MarkingType::orderby('id', 'asc')->get();
            return view('marking::index', compact('types'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new marking type.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('marking_type', 'add') == true) {
            activityLog('Admin', 'Opened create Marking Type Page');
            return view('marking::create');
        } else {
            return redirect()->route('admin.marking-type.index')->with('failure', 'This user does not have permission to add Marking Type');
        }
    }

    /**
     * Store a newly created marking type in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['key'] = strtolower(str_replace(' ', '_', $data['name']));
        $type = MarkingType::create($data);
        activityLog('Admin', 'marking_type_id: '. $type->id . ' Marking Type created');
        return redirect()->route('admin.marking-type.index')->with('success', 'Marking Type has been added successfully');
    }

    /**
     * Show the form for editing the specified marking type.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('marking_type', 'edit') == true) {
            $type = MarkingType::findorfail($id);
            activityLog('Admin', 'lead_id: '. $type->id .  ' edit page opened');
            return view('marking::edit', compact('type'));
        } else {
            return redirect()->route('admin.marking-type.index')->with('failure', 'This user does not have permission to edit Marking Type');
        }
    }

    /**
     * Update the specified marking type in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['key'] = strtolower(str_replace(' ', '_', $data['name']));
        $type = MarkingType::where('id', $id)->first();
        $type->update($data);
        activityLog('Admin', 'lead_id: '. $type->id . ' updated');
        return redirect()->route('admin.marking-type.index')->with('success', 'Marking Type has been updated successfully');
    }

    /**
     * Remove the specified marking type from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('marking_type', 'delete') == true) {
            $type = MarkingType::where('id', $id)->first();
            $type->update(['status' => 2]);
            activityLog('Admin', 'Status of lead_id' . $type->id . ' updated to deleted');
            return redirect()->back()->with('success', 'Marking Type deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Marking Type');
        }
    }
}
