<?php

namespace Modules\Assignment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\AssignmentResubmission;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;

class ResubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('assignment_submission', 'view') == true) {
            activityLog('Admin', 'Opened Assignment Resubmission Menu');
            $ids = getDeliverySiteIds();
            $resubmissions = AssignmentResubmission::join('assignments', 'assignments.id', '=', 'assignment_resubmissions.assignment_id')
                ->join('intake_units', 'intake_units.id', '=', 'assignments.intake_unit_id')
                ->join('intake_courses', 'intake_courses.id', '=', 'intake_units.intake_course_id')
                ->join('courses', 'courses.id', '=', 'intake_courses.course_id')
                ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('assignment_resubmissions.*')
                ->orderBy('assignment_resubmissions.id', 'desc')->get();
            return view('assignment::resubmission.index', compact('resubmissions'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $resubmission = AssignmentResubmission::findorfail($id);
        $check = checkCourseDeliverySite($resubmission->assignment->intakeUnit->intakeCourse->course_id);
        if (checkRole('assignment_submission', 'view') == true && $resubmission && $check == true) {
            activityLog('Admin', 'Opened Assignment Resubmission Edit Menu');
            return view('assignment::resubmission.edit', compact('resubmission'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['user_id'] = Auth::guard('user')->user()->id;
        $data['user_type'] = 'Admin';
        $resubmission = AssignmentResubmission::where('id', $id)->first();
        if ($data['status'] == 1) {
            $data['approved_date'] = date('Y-m-d');
            if ($data['due_date'] == NULL) {
                $data['due_date'] = date('Y-m-d', strtotime('+7 days', strtotime(date('Y-m-d'))));
            }
            $message = 'Resubmission request from ' . userName('Student', $resubmission->student_id) . ' for ' . $resubmission->assignment->name . ' assignment approved';
            $body = 'Resubmission Request has been accepted. Please submit new assignemt';
        } else {
            $message = 'Resubmission request from ' . userName('Student', $resubmission->student_id) . ' for ' . $resubmission->assignment->name . ' assignment rejected';
            $body = 'Resubmission Request has been rejected';
        }
        $resubmission->update($data);
        $title = 'Resubmission Request';
        $students = StudentDevice::where('student_id', $resubmission->student_id)->where('status', 1)->get();
        if ($students->count() > 0) {
            foreach ($students as $key => $student) {
                $fcmData = [
                    'registration_ids' => [$student->device_token],
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                    ],
                    "data" => [
                        "is_admin" => false,
                    ],
                ];
            }
            fcm($fcmData);
        }
        Notification::create([
            'user_type' => 'Student',
            'user_id' => $resubmission->student_id,
            'sender_type' => 'Admin',
            'sender_id' =>  $data['user_id'],
            'title' => $title,
            'body' => $body,
            'type' => 'Resubmission',
            'link' => $resubmission->assignment_id . ',' . $resubmission->assignment->intake_unit_id,
        ]);
        activityLog('Admin', $message);
        return redirect()->route('admin.resubmission.index')->with('success', $message);
    }
}
