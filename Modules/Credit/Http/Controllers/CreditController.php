<?php

namespace Modules\Credit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Credit\Entities\Credit;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the Credit.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('credit', 'view') == true) {
            activityLog('Admin', 'Opened Credit List');
            $credits = Credit::orderby('id', 'desc')->get();
            return view('credit::index', compact('credits'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new Credit.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('credit', 'add') == true) {
            activityLog('Admin', 'Opened create Credit Page');
            return view('credit::create');
        } else {
            return redirect()->route('admin.credit.index')->with('failure', 'This user does not have permission to add Credit');
        }
    }

    /**
     * Store a newly created Credit in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $credit = Credit::create($data);
        activityLog('Admin', $credit->title . ' Credit created');
        return redirect()->route('admin.credit.index')->with('success', 'Credit has been added successfully');
    }

    /**
     * Show the form for editing the specified Credit.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('credit', 'edit') == true) {
            $credit = Credit::findorfail($id);
            activityLog('Admin', $credit->name .  'edit page opened');
            return view('credit::edit', compact('credit'));
        } else {
            return redirect()->route('admin.credit.index')->with('failure', 'This user does not have permission to edit Credit');
        }
    }

    /**
     * Update the specified Credit in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $credit = Credit::where('id', $id)->first();
        $credit->update($data);
        activityLog('Admin', $credit->title . ' updated');
        return redirect()->route('admin.credit.index')->with('success', 'Credit has been updated successfully');
    }

    /**
     * Remove the specified Credit from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('credit', 'delete') == true) {
            $credit = Credit::where('id', $id)->first();
            $credit->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $credit->title . ' updated to deleted');
            return redirect()->back()->with('success', 'Credit deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Credit');
        }
    }
}
