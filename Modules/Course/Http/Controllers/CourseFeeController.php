<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseFee;
use Modules\Course\Entities\CourseFeeType;
use Modules\Fee\Entities\FeeType;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;
use Modules\Intake\Entities\IntakeCourseFeeType;

class CourseFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the course fee.
     * @return Renderable
     */
    public function index($id)
    {
        $course = Course::findorfail($id);
        if (checkRole('course_fee', 'view') == true && $course) {
            $fees = CourseFee::where('course_id', $id)->get();
            activityLog('Admin', 'Opened Course Fee List Page');
            return view('course::fee.index', compact('course', 'fees'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new course fee.
     * @return Renderable
     */
    public function create($id)
    {
        $course = Course::find($id);
        if (checkRole('course_fee', 'add') == true && $course) {
            $feeTypes = FeeType::where('status', 1)->orderBy('id', 'asc')->get();
            $first_fee = FeeType::where('status', 1)->orderBy('id', 'asc')->first();
            activityLog('Admin', 'Opened Create Fee Page of ' . $course->course_name);
            return view('course::fee.create', compact('course', 'feeTypes', 'first_fee'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created course fee in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        // dd($data);
        $count = CourseFee::where('course_id', $id)->where('name', $data['name'])->count();
        if ($count == 0) {
            $data['course_id'] = $id;
            $course = Course::findorfail($id);
            $data['installments'] = $course->study_period;
            $first_installment = array_sum($data['fees']);
            $next_installment = $data['fees']['semester_fee'];
            $data['fees']['semester_fee'] = $data['fees']['semester_fee'] * $course->study_period;
            $data['fee'] = array_sum($data['fees']);

            $fee = CourseFee::create($data);
            foreach ($data['fees'] as $index => $course_fee) {
                if ($index == 'semester_fee') {
                    $type = 'recurring';
                    $course_fee = $next_installment;
                } else {
                    $type = 'one time';
                }
                CourseFeeType::create([
                    'course_fee_id' => $fee->id,
                    'key' => $index,
                    'value' => $course_fee,
                    'type' => $type
                ]);
            }
            $intake_courses = IntakeCourse::where('course_id', $id)->get();
            if (count($intake_courses) > 0) {
                foreach ($intake_courses as $key => $value) {
                    $data['intake_course_id'] = $value->id;
                    $data['due_date'] = $value->ending_date;
                    $intake_course_fee = IntakeCourseFee::create($data);
                    foreach ($data['fees'] as $index => $course_fee) {
                        if ($index == 'semester_fee') {
                            $type = 'recurring';
                            $course_fee = $data['fees']['semester_fee'] / $course->study_period;;
                        } else {
                            $type = 'one time';
                        }
                        IntakeCourseFeeType::create([
                            'intake_course_fee_id' => $intake_course_fee->id,
                            'key' => $index,
                            'value' => $course_fee,
                            'type' => $type
                        ]);
                    }

                    $installment = $course->study_period;

                    for ($i = 1; $i <= $installment; $i++) {
                        if ($i == 1) $name = 'First';
                        elseif ($i == 2) $name = 'Second';
                        elseif ($i == 3) $name = 'Third';
                        elseif ($i == 4) $name = 'Fourth';
                        elseif ($i == 5) $name = 'Fifth';
                        elseif ($i == 6) $name = 'Sixth';
                        elseif ($i == 7) $name = 'Seventh';
                        elseif ($i == 8) $name = 'Eighth';
                        elseif ($i == 9) $name = 'Ninth';
                        elseif ($i == 10) $name = 'Tenth';
                        else $name = $i;

                        $starting_date = $value->starting_date;
                        $month = $i * 6;
                        $starting_date_timestamp = $starting_date ? strtotime($starting_date) : false;
                        $next_due_date = $starting_date_timestamp === false ? $value->ending_date : date('Y-m-d', strtotime('+' . $month . " months", $starting_date_timestamp));

                        if ($i == 1) {
                            $installment_amount = $first_installment;
                        } else {
                            $installment_amount = $next_installment;
                        }

                        IntakeCourseFeeInstallment::create([
                            'intake_course_fee_id' => $intake_course_fee->id,
                            'name' => $name . ' Installment',
                            'amount' => $installment_amount,
                            'due_date' => $next_due_date,
                        ]);
                    }
                }
            }
            activityLog('Admin', $fee->name . ' fee of ' . $fee->course->course_name . ' created',);
            if (request()->is('admin/course*')) $route = 'admin.course.fee.index';
            else $route = 'admin.unregistered.fee.index';
            return redirect()->route($route, $id)->with('success', 'Course Fee added successfully');
        } else {
            return redirect()->back()->with('failure', 'Fee Name already exsists, please enter another name');
        }
    }

    /**
     * Show the form for editing the specified course fee.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $fee = CourseFee::findorfail($id);
        if (checkRole('course_fee', 'edit') == true) {
            $feeTypes = FeeType::where('status', 1)->orderBy('id', 'asc')->get();
            $first_fee = FeeType::where('status', 1)->orderBy('id', 'asc')->first();
            activityLog('Admin', $fee->name . ' edit page opened',);
            return view('course::fee.edit', compact('fee', 'feeTypes', 'first_fee'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified course fee in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $fee = CourseFee::where('id', $id)->first();
        $count_fee = Coursefee::where('id', '!=', $id)->where('course_id', $id)->where('name', $request->name)->count();
        if ($count_fee == 0) {
            CourseFeeType::where('course_fee_id', $id)->delete();
            $next_installment = $data['fees']['semester_fee'];
            foreach ($data['fees'] as $index => $course_fee) {
                if ($index == 'semester_fee') {
                    $type = 'recurring';
                    $course_fee = $next_installment;
                } else {
                    $type = 'one time';
                }
                CourseFeeType::create([
                    'course_fee_id' => $fee->id,
                    'key' => $index,
                    'value' => $course_fee,
                    'type' => $type
                ]);
            }
            $data['fees']['semester_fee'] = $data['fees']['semester_fee'] * $fee->installments;
            $data['fee'] = array_sum($data['fees']);
            $fee->update($data);
            activityLog('Admin', $fee->name . ' fee updated',);
            if ($fee->course->registered == 1) $route = 'admin.course.fee.index';
            else $route = 'admin.unregistered.fee.index';
            return redirect()->route($route, $fee->course_id)->with('success', 'Course fee has been updated successfully');
        } else {
            return redirect()->back()->with('failure', 'Fee Name already exsists, please enter another name');
        }
    }

    /**
     * Remove the specified course fee from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('course_fee', 'delete') == true) {
            $fee = CourseFee::where('id', $id)->first();
            $fee->update(['status' => 2]);
            activityLog('Admin', $fee->name . ' of ' . $fee->course->course_name . ' has been deleted');
            return redirect()->back()->with('success', 'Course Fee has been deleted successfully');
        } else {
            return abort(404);
        }
    }
}
