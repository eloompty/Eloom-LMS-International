<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\FeeType;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;
use Modules\Intake\Entities\IntakeCourseFeeType;

class IntakeCourseFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake course fee.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeCourse = IntakeCourse::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourse->course_id);
        if (checkRole('intake_course_fee', 'view') == true && $intakeCourse && $check == true) {
            $fees = IntakeCourseFee::where('intake_course_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', $intakeCourse->reference_name . ' fee lists opened');
            return view('intake::course.fee.index', compact('intakeCourse', 'fees'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified intake course fee.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeCourseFee = IntakeCourseFee::findorfail($id);
        $feeTypes = FeeType::where('status', 1)->orderBy('id', 'asc')->get();
        $first_fee = FeeType::where('status', 1)->orderBy('id', 'asc')->first();
        $check = checkCourseDeliverySite($intakeCourseFee->intakeCourse->course_id);
        if (checkRole('intake_course_fee', 'edit') == true && $intakeCourseFee && $check == true) {
            activityLog('Admin', $intakeCourseFee->name . ' intake course fee edit page opened');
            return view('intake::course.fee.edit', compact('intakeCourseFee', 'feeTypes', 'first_fee'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified intake course fee in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $intakeCourseFee = IntakeCourseFee::where('id', $id)->first();
        IntakeCourseFeeType::where('intake_course_fee_id', $id)->delete();
        $next_installment = $data['fees']['semester_fee'];
        foreach ($data['fees'] as $index => $course_fee) {
            if ($index == 'semester_fee') {
                $type = 'recurring';
                $course_fee = $next_installment;
            } else {
                $type = 'one time';
            }
            IntakeCourseFeeType::create([
                'intake_course_fee_id' => $intakeCourseFee->id,
                'key' => $index,
                'value' => $course_fee,
                'type' => $type
            ]);
        }
        $semester_amount =  $data['fees']['semester_fee'];

        $installments = IntakeCourseFeeInstallment::where('intake_course_fee_id', $id)->get();
        foreach ($installments as $key => $value) {
            if ($value->name == 'First Installment') {
                $amount = array_sum($data['fees']);
                $value->update(['amount' => $amount]);
            } else {
                $value->update(['amount' => $semester_amount]);
            }
        }
        $data['fees']['semester_fee'] = $data['fees']['semester_fee'] * $intakeCourseFee->installments;
        $data['fee'] = array_sum($data['fees']);
        $intakeCourseFee->update($data);
        activityLog('Admin', $intakeCourseFee->name . ' intake course fee updated');
        return redirect()->route('admin.intake.course.fee.index', $intakeCourseFee->intake_course_id)->with('success', 'Intake Course Fee updated successfully');

    //    $datanew = $data[["9installment"["extra1", "extra2", "extra3"]], ["10installment"["row34", "row34", "row34"]]];
    //    foreach ($datanew as $key => $value) {
    //         $id = $datanew[0] ;  // insert into installment table and grab its id
    //         foreach ($data[1] as $key => $value){
    //             //insert into same table with parent id = $id ;
    //         }
    //    }
    }

    /**
     * Remove the specified intake course fee from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
