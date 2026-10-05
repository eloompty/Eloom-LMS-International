<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeUnitTime;

class IntakeUnitTimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Update the specified Intake Unit Time in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $intakeUnitTime = IntakeUnitTime::find($id);
        $intakeUnitTime->update($data);
        activityLog('Admin', 'Time Table for intake unit time id:' . $intakeUnitTime->id . ' Updated');
        return redirect()->back()->with('success', 'Unit time has been update');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake_course_time_table', 'edit') == true) {
            IntakeUnitTime::where('id', $id)->update(['status' => 2]);
            $intakeUnitTime = IntakeUnitTime::find($id);
            activityLog('Admin', 'Status of intake unit time id:' . $intakeUnitTime->id . ' Updated to deleted');
            return redirect()->back()->with('success', 'Intake Unit Time deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to add intake course time');
        }
    }
}
