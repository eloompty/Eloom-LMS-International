<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the email setting.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Email Setting Menu');
            return view('setting::email.index');
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified email setting in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        if (checkRole('setting', 'edit') == true) {
            activityLog('Admin', 'Email Settings Updated');
            $data = $request->all();
            unset($data['_token']);
            foreach ($data as $key => $value) {
                if ($value != NULL) {
                    updateSettingValue($key, $value);
                }
            }
            return redirect()->back()->with('success', 'Email settings updated successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to update settings');
        }
    }
}
