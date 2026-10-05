<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\FeeType;

class FeeTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the fee type.
     * @return Renderable
     */
    public function index()
    {
         if (checkRole('fee_type', 'view') == true) {
            activityLog('Admin', 'Opened Fee Types List');
            $types = FeeType::orderby('id', 'asc')->get();
            return view('fee::type.index', compact('types'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new fee type.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('fee_type', 'add') == true) {
            activityLog('Admin', 'Opened create Fee Type Page');
            return view('fee::type.create');
        } else {
            return redirect()->route('admin.fee-type.index')->with('failure', 'This user does not have permission to add Fee Type');
        }
    }

    /**
     * Store a newly created fee type in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['key'] = strtolower(str_replace(' ', '_', $data['name']));
        $type = FeeType::create($data);
        activityLog('Admin', 'fee_type_id: '. $type->id . ' Fee Type created');
        return redirect()->route('admin.fee-type.index')->with('success', 'Fee Type has been added successfully');
    }

    /**
     * Show the form for editing the specified fee type.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('fee_type', 'edit') == true) {
            $type = FeeType::findorfail($id);
            activityLog('Admin', 'lead_id: '. $type->id .  ' edit page opened');
            return view('fee::type.edit', compact('type'));
        } else {
            return redirect()->route('admin.fee-type.index')->with('failure', 'This user does not have permission to edit Fee Type');
        }
    }

    /**
     * Update the specified fee type in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['key'] = strtolower(str_replace(' ', '_', $data['name']));
        $type = FeeType::where('id', $id)->first();
        $type->update($data);
        activityLog('Admin', 'lead_id: '. $type->id . ' updated');
        return redirect()->route('admin.fee-type.index')->with('success', 'Fee Type has been updated successfully');
    }

    /**
     * Remove the specified fee type from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('fee_type', 'delete') == true) {
            $type = FeeType::where('id', $id)->first();
            $type->update(['status' => 2]);
            activityLog('Admin', 'Status of lead_id' . $type->id . ' updated to deleted');
            return redirect()->back()->with('success', 'Fee Type deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Fee Type');
        }
    }
}
