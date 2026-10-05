<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StripeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the stripe setting.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Stripe Settings Menu');
            return view('setting::stripe.index');
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified stripe setting in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        if (checkRole('setting', 'edit') == true) {
            activityLog('Admin', 'Stripe Settings Updated');
            $data = $request->all();
            unset($data['_token']);
            foreach ($data as $key => $value) {
                if ($value != NULL) {
                    updateSettingValue($key, $value);
                }
            }
            return redirect()->back()->with('success', 'Fee settings updated successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to update settings');
        }
    }
}
