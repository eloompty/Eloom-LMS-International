<?php

namespace Modules\Country\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Country\Entities\Country;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the country.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('country', 'view') == true) {
            activityLog('Admin', 'Country Menu Opened');
            $countries = Country::orderBy('status', 'desc')->get();
            return view('country::index', compact('countries'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified country in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        activityLog('Admin', 'Country Status updated');
        Country::where('id', $id)->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Country updated successfully');
    }
    
    /**
     * Update the bulk country in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateBulk(Request $request)
    {
        activityLog('Admin', 'Bulk Country Status updated');
        $data = $request->all();
        if (isset($data['id'])) {
            Country::whereIn('id', $data['id'])->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'All selected countries have been updated');
        } else {
            return redirect()->back()->with('failure', 'Choose atleast one row to update');
        }
    }
}
