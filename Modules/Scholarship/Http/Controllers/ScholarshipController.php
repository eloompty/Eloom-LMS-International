<?php

namespace Modules\Scholarship\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Scholarship\Entities\Scholarship;

class ScholarshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        if (checkRole('scholarship', 'view') != true) {
            return abort(404);
        }

        activityLog('Admin', 'Opened Scholarship List');
        $scholarships = Scholarship::where('status', '!=', 2)->orderBy('id', 'asc')->get();
        return view('scholarship::scholarship.index', compact('scholarships'))->with('no', 1);
    }

    public function create()
    {
        if (checkRole('scholarship', 'add') != true) {
            return redirect()->route('admin.scholarship.index')->with('failure', 'You do not have permission to add scholarships');
        }

        activityLog('Admin', 'Opened Create Scholarship Page');
        return view('scholarship::scholarship.create');
    }

    public function store(Request $request)
    {
        if (checkRole('scholarship', 'add') != true) {
            return redirect()->route('admin.scholarship.index')->with('failure', 'You do not have permission to add scholarships');
        }

        $data = $this->scholarshipData($request);
        $scholarship = Scholarship::create($data);
        activityLog('Admin', 'scholarship_id: ' . $scholarship->id . ' Scholarship created');
        return redirect()->route('admin.scholarship.index')->with('success', 'Scholarship has been added successfully');
    }

    public function edit($id)
    {
        if (checkRole('scholarship', 'edit') != true) {
            return redirect()->route('admin.scholarship.index')->with('failure', 'You do not have permission to edit scholarships');
        }

        $scholarship = Scholarship::findOrFail($id);
        activityLog('Admin', 'scholarship_id: ' . $scholarship->id . ' Edit page opened');
        return view('scholarship::scholarship.edit', compact('scholarship'));
    }

    public function update(Request $request, $id)
    {
        if (checkRole('scholarship', 'edit') != true) {
            return redirect()->route('admin.scholarship.index')->with('failure', 'You do not have permission to edit scholarships');
        }

        $data = $this->scholarshipData($request);
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->update($data);
        activityLog('Admin', 'scholarship_id: ' . $scholarship->id . ' Scholarship updated');
        return redirect()->route('admin.scholarship.index')->with('success', 'Scholarship has been updated successfully');
    }

    /**
     * Build scholarship attributes, enforcing the award-structure invariants:
     *  - a "full" award is always 100% (percentage), no cap
     *  - maintenance only applies to per-semester awards
     */
    private function scholarshipData(Request $request): array
    {
        $data = $request->only([
            'name', 'type', 'award_scope', 'disbursement',
            'value_type', 'value', 'max_value', 'quota',
            'maintenance_min_percentage', 'max_semesters',
            'description', 'eligibility_criteria', 'status',
        ]);

        $data['award_scope'] = in_array($request->input('award_scope'), ['full', 'partial'], true) ? $request->input('award_scope') : 'partial';
        $data['disbursement'] = in_array($request->input('disbursement'), ['one_off', 'per_semester'], true) ? $request->input('disbursement') : 'one_off';
        $data['requires_maintenance'] = $request->boolean('requires_maintenance') ? 1 : 0;

        // A full award is a 100% percentage waiver with no cap.
        if ($data['award_scope'] === 'full') {
            $data['value_type'] = 'percentage';
            $data['value'] = 100;
            $data['max_value'] = null;
        }

        // Maintenance and the semester cap only make sense for per-semester awards.
        if ($data['disbursement'] !== 'per_semester') {
            $data['requires_maintenance'] = 0;
            $data['max_semesters'] = null;
        }
        if (!$data['requires_maintenance']) {
            $data['maintenance_min_percentage'] = null;
        }

        // Normalise blank optional numerics to null.
        foreach (['max_value', 'quota', 'maintenance_min_percentage', 'max_semesters'] as $field) {
            if (($data[$field] ?? '') === '') {
                $data[$field] = null;
            }
        }

        return $data;
    }

    public function destroy($id)
    {
        if (checkRole('scholarship', 'delete') != true) {
            return redirect()->back()->with('failure', 'You do not have permission to delete scholarships');
        }

        $scholarship = Scholarship::findOrFail($id);
        $scholarship->update(['status' => 2]);
        activityLog('Admin', 'scholarship_id: ' . $scholarship->id . ' Scholarship deleted');
        return redirect()->back()->with('success', 'Scholarship deleted successfully');
    }
}
