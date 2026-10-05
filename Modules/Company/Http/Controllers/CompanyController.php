<?php

namespace Modules\Company\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Address\Entities\Address;
use Modules\Company\Entities\Company;
use Modules\Country\Entities\Country;
use Modules\Location\Entities\Location;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Company Profile.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('company', 'view') == true) {
            activityLog('Admin', 'Opened Company Menu');
            $company = Company::first();
            $districts = Location::where('status', 1)->where('location_id', $company->address->province)->pluck('name', 'id');
            $local_bodies = Location::where('status', 1)->where('location_id', $company->address->district)->pluck('name', 'id');
            $wards = Location::where('status', 1)->where('location_id', $company->address->local_body)->pluck('name', 'id');
            $toles = Location::where('status', 1)->where('location_id', $company->address->ward)->pluck('name', 'id');
            return view('company::index', compact('company', 'districts', 'local_bodies', 'wards', 'toles'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the company profile.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (checkRole('company', 'edit') == true) {
            if ($request->has('image')) {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('/images/company'), $imageName);
                $logo = 'images/company/' . $imageName;
                Company::where('id', $id)->update(['logo' => $logo]);
            }
            Company::where('id', $id)->update([
                'company_name' => $request->company_name,
                'company_ceo' => $request->company_ceo,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            Address::updateOrCreate(
                ['type' => 'company', 'type_id' => $id],
                addressRequestData($request, 'company_')
            );
            activityLog('Admin', 'Updated Company Details');
            return redirect()->back()->with('success', 'Company updated successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to update company');
        }
    }

    public function menu()
    {
        if (checkRole('company', 'view') == true) {
            activityLog('Admin',  'Opened Company Menu Page');
            return view('company::menu');
        } else {
            return abort(404);
        }
    }
}
