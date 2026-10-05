<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the fee setting.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Fee Settings Menu');
            return view('setting::fee.index');
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified fee setting in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        if (checkRole('setting', 'edit') == true) {
            activityLog('Admin', 'Fee Settings Updated');
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
