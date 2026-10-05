<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Address\Entities\Address;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\AgentBranch;
use Modules\AgentBranchUser\Entities\AgentBranchUser;
use Modules\Country\Entities\Country;
use Modules\Location\Entities\Location;
use Modules\Log\Entities\Log;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentAgent;
use Modules\Student\Entities\StudentDeliverySite;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Student\Entities\StudentStatus;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the students.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('student', 'view') == true) {
            activityLog('Admin', 'Opened Student Menu');
            $status = $request->status;
            $ids = getDeliverySiteIds();
            if ($status == NULL) $status_code = [0, 1];
            else $status_code = [2];
            $all_students = Student::leftJoin('student_delivery_sites', 'student_delivery_sites.student_id', '=', 'students.id')
                ->leftJoin('student_intake_courses', 'student_intake_courses.student_id', '=', 'students.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('student_delivery_sites.student_id')
                        ->orWhereIn('student_delivery_sites.company_delivery_site_id', $ids)
                        ->where('student_delivery_sites.status', 1);
                })
                ->where(function ($enroll) {
                    $enroll->where('student_intake_courses.is_enrolled', 1)
                        ->orWhere('students.is_enrolled', 1);
                })
                ->select('students.*')
                ->distinct('students.id')
                ->whereIn('students.status', $status_code);
            $citizenship_country = $request->citizenship_country;
            if ($citizenship_country != NULL) {
                $country = Country::where('name', $citizenship_country)->first();
                $students = $all_students->where('citizenship_country', $country->id)->orderBy('students.id', 'desc')->get();
            } else {
                $students = $all_students->orderBy('students.id', 'desc')->get();
            }
            foreach ($students as $key => $value) {
                $site = $value->deliverySite->where('status', 1)->first();
                if ($site) {
                    $students[$key]['site'] = $site->companyDeliverySite->site_name;
                } else {
                    $students[$key]['site'] = "Not Assigned";
                }
            }
            $table = 'students';
            return view('student::index', compact('students', 'status', 'table'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('student', 'add') == true) {
            activityLog('Admin', 'Opened Create Student Page');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            $delivery_sites = getDeliverySites();
            $agents = Agent::where('status', 1)->orderby('company_name', 'asc')->pluck('company_name', 'id');
            $table = 'students';
            $student_id_readonly = studentIdIsAutomatic();
            $student_id_value = $student_id_readonly ? reserveAutomaticStudentId() : '';
            return view('student::create', compact('countries', 'delivery_sites', 'agents', 'table', 'student_id_readonly', 'student_id_value'));
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to add student');
        }
    }

    /**
     * Render the address fields for a chosen country's format (AJAX, for the
     * additional-addresses list on the student form).
     */
    public function addressFields(Request $request)
    {
        $index = (int) $request->input('index', 0);
        $countryId = $request->input('country_id');
        $format = addressFormatForCountry($countryId);

        return view('partials.address-fields', [
            'address' => null,
            'addressFormat' => $format,
            'addressArrayName' => 'addresses',
            'addressIndex' => $index,
            'addressIdPrefix' => 'address_' . $index,
            'hideCountrySelect' => true,
        ]);
    }

    /**
     * Create/update/remove a student's non-primary addresses from the submitted list.
     */
    private function syncAdditionalAddresses(Request $request, $studentId)
    {
        $submitted = $request->input('addresses', []);
        $keptIds = [];

        foreach ($submitted as $block) {
            if (!is_array($block) || empty($block['country_id'])) {
                continue;
            }

            $format = addressFormatForCountry($block['country_id']);
            $addressData = array_merge(addressRequestDataFromArray($block, $format), [
                'label' => $block['label'] ?? null,
                'is_primary' => false,
                'type' => 'student',
                'type_id' => $studentId,
            ]);

            $existing = !empty($block['id'])
                ? Address::where('id', $block['id'])->where('type', 'student')->where('type_id', $studentId)->first()
                : null;

            if ($existing) {
                $existing->fill($addressData)->save();
                $keptIds[] = $existing->id;
            } else {
                $keptIds[] = Address::create($addressData)->id;
            }
        }

        // Remove non-primary addresses the user deleted in the UI.
        Address::where('type', 'student')
            ->where('type_id', $studentId)
            ->where('is_primary', false)
            ->whereNotIn('id', $keptIds ?: [0])
            ->delete();
    }

    /**
     * Store a newly created student in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/students'), $imageName);
            $data['image'] = 'images/students/' . $imageName;
        } else {
            $data['image'] = 'files/avatar.png';
        }
        $data['password'] = Hash::make($data['password']);
        if ($data['table'] == 'students') {
            $data['is_enrolled'] = 1;
        }
        if (!isset($data['phone']) || $data['phone'] == NULL) {
            $data['phone'] = $data['mobile'];
        }
        if (studentIdIsAutomatic() && (empty($data['id_no']) || Student::where('id_no', $data['id_no'])->exists())) {
            $data['id_no'] = reserveAutomaticStudentId();
        }
        $student = Student::create($data);
        if ($data['table'] == 'offer') {
            $statusdata['user_id'] = Auth::guard('user')->user()->id;
            $statusdata['user_type'] = 'Admin';
            $statusdata['student_id'] = $student->id;
            $statusdata['status'] = 'Enquired';
            StudentStatus::create($statusdata);
        }
        foreach ($data['name'] as $key => $value) {
            StudentGuardian::create([
                'student_id' => $student->id,
                'name' => $data['name'][$key],
                'contact_no' => $data['contact_no'][$key],
                'occupation' => $data['occupation'][$key],
                'relation' => $data['relation'][$key],
                'email' => $data['info_email'][$key],
            ]);
        }
        $data['student_id'] = $student->id;
        StudentDeliverySite::create($data);
        $data['type_id'] = $student->id;
        $data['type'] = 'student';
        Address::create(array_merge($data, addressRequestData($request), ['is_primary' => true, 'label' => 'Current']));
        $this->syncAdditionalAddresses($request, $student->id);
        if (isset($data['agent_id']) != NULL) {
            $data['student_id'] = $student->id;
            StudentAgent::create($data);
        }
        activityLog('Admin', userName('Student', $student->id) . ' created');
        if ($data['table'] == 'offer') {
            return redirect()->route('admin.student.offer.index')->with('success', 'Enquiry Student has been added successfully');
        } else {
            return redirect()->route('admin.student.index')->with('success', 'Student has been added successfully');
        }
    }

    /**
     * Show the specified student.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (checkRole('student', 'view') == true) {
            $student = Student::findorfail($id);
            $delivery_site = $student->deliverySite->first();
            if ($delivery_site) {
                $ids = getDeliverySiteIds();
                $sites = StudentDeliverySite::whereIn('company_delivery_site_id', $ids)->where('student_id', $id)->get();
                if (count($sites) == 0) $show = false;
                else $show = true;
            } else {
                $show = true;
            }
            if ($show == true) {
                $site = $student->deliverySite->where('status', 1)->first();
                if ($site) {
                    $student->delivery_site = $site->companyDeliverySite->site_name;
                } else {
                    $student->delivery_site = 'Not Assigned';
                }
                $studentIntakeCourses = StudentIntakeCourse::where('student_id', $id)->where('status', 1)->get();
                $intake_course_ids = [];
                foreach ($studentIntakeCourses as $studentIntakeCourse) {
                    $intake_course_ids[] = $studentIntakeCourse->intake_course_id;
                }
                $fees = StudentIntakeCourseFee::where('student_id', $id)->whereIn('intake_course_id', $intake_course_ids)->where('status', 1)->orderBy('id', 'asc')->get();
                foreach ($fees as $key => $value) {
                    $fees[$key]['installments'] = $value->fee_installments;
                    if ($value->enrollment_fee_wavier == 1) $fees[$key]['enrollment_fee'] = 0;
                    else $fees[$key]['enrollment_fee'] = $value->enrollment_fee;
                    if ($value->material_fee_wavier == 1) $fees[$key]['material_fee'] = 0;
                    else $fees[$key]['material_fee'] = $value->material_fee;
                    $fees[$key]['total_fee'] = $fees[$key]['enrollment_fee'] + $fees[$key]['material_fee'] + $value->amount;
                    $fees[$key]['paid'] = $value->fee_installments->where('status', 2)->sum('amount');
                    $fees[$key]['remaining'] = $value->fee_installments->where('status', 1)->sum('amount');
                }
                activityLog('Admin', userName('Student', $student->id) . ' detail page opened');
                return view('student::show', compact('student', 'fees'));
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified student.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('student', 'edit') == true) {
            $student = Student::findorfail($id);
            $delivery_site = $student->deliverySite->first();
            if ($delivery_site) {
                $ids = getDeliverySiteIds();
                $sites = StudentDeliverySite::whereIn('company_delivery_site_id', $ids)->where('student_id', $id)->get();
                if (count($sites) == 0) $edit = false;
                else $edit = true;
            } else {
                $edit = true;
            }
            if ($edit == true) {
                $countries = Country::where('status', 1)->pluck('name', 'id');
                $delivery_sites = getDeliverySites();
                $site = $student->deliverySite->where('status', 1)->first();
                if ($site) {
                    $site_id = $site->company_delivery_site_id;
                } else {
                    $site_id = 0;
                }
                $districts = Location::where('status', 1)->where('location_id', $student->address->province)->pluck('name', 'id');
                $local_bodies = Location::where('status', 1)->where('location_id', $student->address->district)->pluck('name', 'id');
                $wards = Location::where('status', 1)->where('location_id', $student->address->local_body)->pluck('name', 'id');
                $toles = Location::where('status', 1)->where('location_id', $student->address->ward)->pluck('name', 'id');
                $student_agent = StudentAgent::where('student_id', $id)->first();
                $agents = Agent::where('status', 1)->orderby('company_name', 'asc')->pluck('company_name', 'id');
                if ($student_agent) {
                    $branches = AgentBranch::where('agent_id', $student_agent->agent_id)->pluck('name', 'id');
                    $users = AgentBranchUser::where('branch_id', $student_agent->branch_id)->pluck('name', 'id');
                    $agent_id = $student_agent->agent_id;
                    $branch_id = $student_agent->branch_id;
                    $user_id = $student_agent->user_id;
                } else {
                    $branches = [];
                    $users = [];
                    $agent_id = 0;
                    $branch_id = 0;
                    $user_id = 0;
                }
                $student_id_readonly = studentIdIsAutomatic();
                $student_id_value = $student->id_no;
                if ($student_id_readonly && $student_id_value == NULL) {
                    $student_id_value = reserveAutomaticStudentId();
                }
                activityLog('Admin', userName('Student', $student->id) . ' edit page opened');
                return view('student::edit', compact('student', 'countries', 'delivery_sites', 'districts', 'local_bodies', 'wards', 'toles', 'site_id', 'student_agent', 'agents', 'branches', 'users', 'agent_id', 'branch_id', 'user_id', 'student_id_readonly', 'student_id_value'));
            } else {
                return abort(404);
            }
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to edit student');
        }
    }

    /**
     * Update the specified student in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/students'), $imageName);
            $data['image'] = 'images/students/' . $imageName;
        }
        if ($data['password'] == NULL) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $student = Student::find($id);
        if (!isset($data['country_id']) || $data['country_id'] == NULL) {
            $data['country_id'] = $data['citizenship_country'];
        }
        if (!isset($data['phone']) || $data['phone'] == NULL) {
            $data['phone'] = $data['mobile'];
        }
        if (studentIdIsAutomatic()) {
            if ($student->id_no != NULL) {
                $data['id_no'] = $student->id_no;
            } elseif (empty($data['id_no']) || Student::where('id_no', $data['id_no'])->where('id', '<>', $id)->exists()) {
                $data['id_no'] = reserveAutomaticStudentId();
            }
        }
        $student->fill($data)->save();

        // Clear existing relations
        $student->guardians()->delete();

        // Re-add guardians
        foreach ($request->name as $key => $value) {
            $student->guardians()->create([
                'name' => $request->name[$key],
                'contact_no' => $request->contact_no[$key],
                'occupation' => $request->occupation[$key],
                'relation' => $request->relation[$key],
            ]);
        }

        $site = $student->deliverySite->where('status', 1)->first();
        if ($site) {
            unset($data['status']);
            $site->fill($data)->save();
        } else {
            $data['student_id'] = $id;
            StudentDeliverySite::create($data);
        }
        if (isset($data['agent_id']) != NULL) {
            $student_agent = StudentAgent::where('student_id', $id)->first();
            if ($student_agent) {
                unset($data['status']);
                $student_agent->fill($data)->save();
            } else {
                $data['student_id'] = $id;
                StudentAgent::create($data);
            }
        }
        $address = Address::where('type', 'student')->where('type_id', $id)
            ->orderByDesc('is_primary')->first();
        $addressData = addressRequestData($request);
        if ($address) {
            $address->fill(array_merge($addressData, ['is_primary' => true, 'label' => $address->label ?: 'Current']))->save();
        } else {
            Address::create(array_merge($addressData, ['type' => 'student', 'type_id' => $id, 'is_primary' => true, 'label' => 'Current']));
        }
        $this->syncAdditionalAddresses($request, $id);
        activityLog('Admin', userName('Student', $student->id) . ' updated');
        if (strpos($data['url'], 'show')) {
            return redirect()->route('admin.student.show', $id)->with('success', 'Student has been updated successfully');
        } elseif (strpos($data['url'], 'offer')) {
            return redirect()->route('admin.student.offer.index')->with('success', 'Student has been updated successfully');
        } else {
            return redirect()->route('admin.student.index')->with('success', 'Student has been updated successfully');
        }
    }

    /**
     * Remove the specified student from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('student', 'delete') == true) {
            Student::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Student has been deleted successfully');
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to delete student');
        }
    }

    /* Get branch by agent */
    public function getBranches(Request $request)
    {
        $branches = AgentBranch::where('agent_id', $request->agent_id)->where('status', 1)->pluck('name', 'id');
        return response()->json($branches);
    }

    /* Get branch by agent */
    public function getBranchUser(Request $request)
    {
        $users = AgentBranchUser::where('branch_id', $request->branch_id)->where('status', 1)->pluck('name', 'id');
        return response()->json($users);
    }

    /* Login in Student Dashboard */
    public function dashboard(Request $request, $id)
    {
        if (checkRole('student_dashboard', 'view') == true) {
            activityLog('Admin', 'Opened Student Dashboard');
            Log::create([
                'user_type' => 'Student',
                'user_id' => $id,
                'action' => 'Logged In by Admin'
            ]);
            Log::create([
                'user_type' => 'Student',
                'user_id' => $id,
                'action' => 'Logged In from web'
            ]);
            $student = Student::findorfail($id);
            if (Auth::guard('student')->loginUsingId($student->id)) {
                return redirect()->intended(route('student.dashboard'));
            }
        } else {
            return abort(404);
        }
    }
}
