<?php

namespace Modules\AgentBranchUser\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Address\Entities\Address;
use Modules\Agent\Entities\AgentStudent;
use Modules\Agent\Entities\AgentStudentStatus;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentAgent;

class AgentStudentStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent_branch_user');
    }

    /**
     * Display a listing of the agent student status.
     * @return Renderable
     */
    public function index($id)
    {
        $agent_student = AgentStudent::find($id);
        if ($agent_student) {
            $agent_student_statuses = AgentStudentStatus::where('agent_student_id', $id)->orderBy('id', 'desc')->get();
            return view('agentbranchuser::user.student.status.index', compact('agent_student_statuses'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified agent student status in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $agent_student = AgentStudent::find($id);
        $agent_student->status = $request->status;
        $agent_student->save();
        AgentStudentStatus::create([
            'agent_student_id' => $id,
            'status' => $request->status
        ]);
        if ($request->status == 5) {
            $company_address = Address::where('type', 'company')->first();
            $student = Student::create([
                'salutation' => $agent_student->salutation,
                'first_name' => $agent_student->first_name,
                'family_name' => $agent_student->family_name,
                'date_of_birth' => $agent_student->date_of_birth,
                'passport_no' => $agent_student->passport_no,
                'citizenship' => $agent_student->citizenship,
                'phone' => $agent_student->phone,
                'mobile' => $agent_student->mobile,
                'email' => $agent_student->email,
                'image' => $agent_student->image,
                'country_id' => $company_address->country_id,
                'overseas_address' => $agent_student->address,
                'overseas_country_id' => Auth::guard('agent_branch_user')->user()->branch->agent->country_id,
                'emergency_contact_person' => $agent_student->emergency_contact_person,
                'emergency_contact_number' => $agent_student->emergency_contact_number,
                'emergency_contact_relation' => $agent_student->emergency_contact_relation,
            ]);
            Address::create([
                'building_number' => $company_address->building_number,
                'street_address' => $company_address->street_address,
                'suburb' => $company_address->suburb,
                'state' => $company_address->state,
                'zip_code' => $company_address->zip_code,
                'country_id' => $company_address->country_id,
                'type' => 'student',
                'type_id' => $student->id,
                'is_primary' => true,
                'label' => 'Current',
            ]);
            StudentAgent::create([
                'student_id' => $student->id,
                'agent_id' => $agent_student->agent_id,
                'branch_id' => $agent_student->branch_id,
                'user_id' => $agent_student->user_id,
            ]);
        }
        return redirect()->back()->with('success', 'Status updated successfully');
    }
}
