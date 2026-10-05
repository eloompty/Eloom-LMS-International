<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\Intake;
use Modules\Student\Entities\Student;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the assignment setting.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Offer Settings Menu');
            return view('setting::assignment.index');
        } else {
            return abort(404);
        }
    }


    /**
     * Update the specified resource in assignment setting.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        if (checkRole('setting', 'edit') == true) {
            activityLog('Admin', 'Assignment Settings Updated');
            $data = $request->all();
            unset($data['_token']);
            foreach ($data as $key => $value) {
                if ($value != NULL) {
                    updateSettingValue($key, $value);
                    Intake::query()->update(['allow_submission_after_due_date' => $value]);
                    Student::query()->update(['allow_submission_after_due_date' => $value]);
                }
            }
            return redirect()->back()->with('success', 'Assignment settings pdated successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to update settings');
        }
    }
}
