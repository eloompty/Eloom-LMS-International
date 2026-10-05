<?php

namespace Modules\Agent\Http\Controllers\Agent;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Address\Entities\Address;
use Modules\Company\Entities\CompanyDeliverySite;
use Modules\Country\Entities\Country;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentAgent;
use Modules\Student\Entities\StudentDeliverySite;

class AgentStudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent');
    }

    /**
     * Display a listing of the agent student.
     * @return Renderable
     */
    public function index()
    {
        $agent_id = Auth::guard('agent')->user()->id;
        $students = StudentAgent::with(['student'])->where(function ($query) {
            $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 0));
        })->where('agent_id', $agent_id)->orderBy('id', 'desc')->get();
        activityLog('Agent', 'Opened Student Menu from web');
        return view('agent::agent.student.index', compact('students'))->with('no', 1);
    }

    /**
     * Show the form for creating a new agent student.
     * @return Renderable
     */
    public function create()
    {
        activityLog('Agent', 'Opened create student page from web');
        $countries = Country::where('status', 1)->pluck('name', 'id');
        $delivery_sites = CompanyDeliverySite::where('status', 1)->pluck('site_name', 'id');
        return view('agent::agent.student.create', compact('countries', 'delivery_sites'));
    }

    /**
     * Store a newly created agent student in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $enrolled = Student::where('email', $data['email'])->count();
        if ($enrolled == 0) {
            if ($request->has('image')) {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('/images/students'), $imageName);
                $data['image'] = 'images/students/' . $imageName;
            } else {
                $data['image'] = 'themes/AdminLTE/dist/img/boxed-bg.png';
            }
            if (!isset($data['phone']) || $data['phone'] == NULL) {
                $data['phone'] = "";
            }
            if (!isset($data['citizenship_country']) || $data['citizenship_country'] == NULL) {
                unset($data['citizenship_country']);
            }
            $data['password'] = Hash::make($data['email']);
            $data['is_enrolled'] = 0;
            if (!isset($data['country_id']) || $data['country_id'] == NULL) {
                $data['country_id'] = $data['overseas_country_id'];
            }
            $student = Student::create($data);
            Address::create(array_merge(addressRequestData($request), [
                'type_id' => $student->id,
                'type' => 'student',
                'is_primary' => true,
                'label' => 'Current',
            ]));
            $data['student_id'] = $student->id;
            StudentDeliverySite::create($data);
            $data['agent_id'] = Auth::guard('agent')->user()->id;
            $data['branch_id'] = Auth::guard('agent')->user()->main_branch->id;
            StudentAgent::create($data);
            activityLog('Agent', userName('Student', $student->id) . ' created from web');
            return redirect()->route('agent.student.index')->with('success', 'Student created successfully');
        } else {
            return redirect()->back()->with('failure', 'Email already exsists');
        }
    }

    /**
     * Show the form for editing the specified agent student.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $student = Student::findorfail($id);
        $countries = Country::where('status', 1)->pluck('name', 'id');
        $delivery_sites = CompanyDeliverySite::where('status', 1)->pluck('site_name', 'id');
        activityLog('Agent', userName('Student', $student->id) . ' edit page opened from web');
        $site = $student->deliverySite->where('status', 1)->first();
        if ($site) {
            $site_id = $site->company_delivery_site_id;
        } else {
            $site_id = 0;
        }
        return view('agent::agent.student.edit', compact('student', 'countries', 'delivery_sites', 'site_id'));
    }

    /**
     * Update the specified agent student in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/students'), $imageName);
            $data['image'] = 'images/students/' . $imageName;
        }
        if (!isset($data['phone']) || $data['phone'] == NULL) {
            $data['phone'] = "";
        }
        if (!isset($data['citizenship_country']) || $data['citizenship_country'] == NULL) {
            unset($data['citizenship_country']);
        }
        if (!isset($data['country_id']) || $data['country_id'] == NULL) {
            $data['country_id'] = $data['overseas_country_id'];
        }
        $student = Student::where('id', $id)->first();
        $student->update($data);
        $address = Address::where('type', 'student')->where('type_id', $id)
            ->orderByDesc('is_primary')->first();
        $addressData = addressRequestData($request);
        if ($address) {
            $address->fill(array_merge($addressData, ['is_primary' => true, 'label' => $address->label ?: 'Current']))->save();
        } else {
            Address::create(array_merge($addressData, [
                'type_id' => $student->id,
                'type' => 'student',
                'is_primary' => true,
                'label' => 'Current',
            ]));
        }
        activityLog('Agent', userName('Student', $student->id) . ' data has been updated from web');
        return redirect()->route('agent.student.index')->with('success', 'Student updated successfully');
    }
}
