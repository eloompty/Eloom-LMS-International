<?php

namespace Modules\OfferStatus\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\OfferStatus\Entities\OfferStatus;

class OfferStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the offer status.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('offer_status', 'view') == true) {
            activityLog('Admin', 'Opened Offer Status List');
            $statuses = OfferStatus::orderby('id', 'desc')->get();
            return view('offerstatus::index', compact('statuses'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new offer status.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('offer_status', 'add') == true) {
            activityLog('Admin', 'Opened create Offer status Page');
            return view('offerstatus::create');
        } else {
            return redirect()->route('admin.offer.status.index')->with('failure', 'This user does not have permission to add offer status');
        }
        return view('offerstatus::create');
    }

    /**
     * Store a newly created offer status in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $offer = OfferStatus::create($data);
        activityLog('Admin', $offer->name . ' offer status created');
        return redirect()->route('admin.offer.status.index')->with('success', 'Offer Status has been added successfully');
    }

    /**
     * Show the form for editing the specified offer status.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('offer_status', 'edit') == true) {
            $status = OfferStatus::findorfail($id);
            activityLog('Admin', $status->name .  'edit page opened');
            return view('offerstatus::edit', compact('status'));
        } else {
            return redirect()->route('admin.offer.status.index')->with('failure', 'This user does not have permission to edit offer status');
        }
        return view('offerstatus::edit');
    }

    /**
     * Update the specified offer status in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $status = OfferStatus::where('id', $id)->first();
        $status->update($data);
        activityLog('Admin', $status->name . ' updated');
        return redirect()->route('admin.offer.status.index')->with('success', 'Offer Status has been updated successfully');
    }

    /**
     * Remove the specified offer status from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('offer_status', 'delete') == true) {
            $status = OfferStatus::where('id', $id)->first();
            $status->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $status->name . ' updated to deleted');
            return redirect()->back()->with('success', 'Offer Status deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete offer status');
        }
    }
}
