<?php

namespace Modules\Condition\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Condition\Entities\Condition;

class ConditionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the Condition.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('condition', 'view') == true) {
            activityLog('Admin', 'Opened Condition List');
            $conditions = Condition::orderby('id', 'desc')->get();
            return view('condition::index', compact('conditions'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new Condition.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('condition', 'add') == true) {
            activityLog('Admin', 'Opened create Condition Page');
            return view('condition::create');
        } else {
            return redirect()->route('admin.condition.index')->with('failure', 'This user does not have permission to add Condition');
        }
    }

    /**
     * Store a newly created Condition in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $condition = Condition::create($data);
        activityLog('Admin', $condition->title . ' Condition created');
        return redirect()->route('admin.condition.index')->with('success', 'Condition has been added successfully');
    }

    /**
     * Show the form for editing the specified Condition.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('condition', 'edit') == true) {
            $condition = Condition::findorfail($id);
            activityLog('Admin', $condition->name .  'edit page opened');
            return view('condition::edit', compact('condition'));
        } else {
            return redirect()->route('admin.condition.index')->with('failure', 'This user does not have permission to edit Condition');
        }
    }

    /**
     * Update the specified Condition in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $condition = Condition::where('id', $id)->first();
        $condition->update($data);
        activityLog('Admin', $condition->title . ' updated');
        return redirect()->route('admin.condition.index')->with('success', 'Condition has been updated successfully');
    }

    /**
     * Remove the specified Condition from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('condition', 'delete') == true) {
            $condition = Condition::where('id', $id)->first();
            $condition->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $condition->title . ' updated to deleted');
            return redirect()->back()->with('success', 'Condition deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Condition');
        }
    }
}
