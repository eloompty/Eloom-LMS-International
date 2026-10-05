<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Agent\Entities\Agent;
use Modules\Company\Entities\CompanyDeliverySite;
use Modules\Country\Entities\Country;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentStatus;

class StudentOfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student offers.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('student', 'view') == true) {
            activityLog('Admin', 'Opened Student Offer Menu');
            $status = $request->status;
            $ids = getDeliverySiteIds();
            if ($status == NULL) $status_code = [0, 1];
            else $status_code = [2];
            $students = Student::leftJoin('student_delivery_sites', 'student_delivery_sites.student_id', '=', 'students.id')
                ->leftJoin('student_intake_courses', 'student_intake_courses.student_id', '=', 'students.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('student_delivery_sites.student_id')
                        ->orWhereIn('student_delivery_sites.company_delivery_site_id', $ids);
                })
                ->where(function ($enroll) {
                    $enroll->where('student_intake_courses.is_enrolled', 0)
                        ->orWhere('students.is_enrolled', 0);
                })
                ->select('students.*')
                ->distinct('students.id')
                ->whereIn('students.status', $status_code)->orderBy('students.id', 'desc')->get();
            foreach ($students as $key => $value) {
                $site = $value->deliverySite->where('status', 1)->first();
                if ($site) {
                    $students[$key]['site'] = $site->companyDeliverySite->site_name;
                } else {
                    $students[$key]['site'] = "Not Assigned";
                }
                $status = StudentStatus::where('student_id', $value->id)->orderBy('id', 'desc')->first();
                $students[$key]['student_status'] = $status ? $status->status : 'Enquired';
            }
            $table = 'offer';
            return view('student::index', compact('students', 'status', 'table'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student offers.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('student', 'add') == true) {
            activityLog('Admin', 'Opened Create Student Offer Page');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            $delivery_sites = getDeliverySites();
            $agents = Agent::where('status', 1)->orderby('company_name', 'asc')->pluck('company_name', 'id');
            $table = 'offer';
            return view('student::create', compact('countries', 'delivery_sites', 'agents', 'table'));
        } else {
            return redirect()->route('admin.student.offer.index')->with('failure', 'This user does not have permission to add student');
        }
    }

    public function enroll(Request $request, $id)
    {
        $student = Student::findorfail($id);
        $student->is_enrolled = 1;
        $student->remarks = $request->remarks;
        if ($request->has('file')) {
            $imageName = time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move(public_path('/images/students/files'), $imageName);
            $student->file = 'images/students/files/' . $imageName;
        }
        $student->save();
        StudentIntakeCourse::where('student_id', $id)->where('intake_course_id', $request->intake_course_id)->update(['is_enrolled' => 1]);
        return redirect()->route('admin.student.index')->with('success', 'Offer student moved to enrolled list');
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->all();
        $student = Student::findorfail($id);
        $data['user_id'] = Auth::guard('user')->user()->id;
        $data['user_type'] = 'Admin';
        $data['student_id'] = $id;
        StudentStatus::create($data);
        return redirect()->back()->with('success', 'Student Status Updated');
    }
}
