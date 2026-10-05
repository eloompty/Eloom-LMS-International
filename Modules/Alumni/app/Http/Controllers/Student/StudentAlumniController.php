<?php

namespace Modules\Alumni\Http\Controllers\Student;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Alumni\Entities\AlumniProfile;

class StudentAlumniController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    public function profile()
    {
        $student = auth('student')->user();
        $alumni  = AlumniProfile::where('student_id', $student->id)->first();
        return view('alumni::student.profile', compact('alumni'));
    }

    public function updateEmployment(Request $request)
    {
        $student = auth('student')->user();
        $alumni  = AlumniProfile::where('student_id', $student->id)->firstOrFail();

        $alumni->update([
            'employer_name'          => $request->employer_name,
            'job_title'              => $request->job_title,
            'employment_start_date'  => $request->employment_start_date,
            'industry'               => $request->industry,
            'directory_visible'      => $request->boolean('directory_visible'),
        ]);

        return redirect()->back()->with('success', 'Employment details updated');
    }

    public function expressReenrollmentInterest(Request $request)
    {
        $student = auth('student')->user();
        $alumni  = AlumniProfile::where('student_id', $student->id)->firstOrFail();

        $alumni->update([
            'interested_in_reenrollment' => true,
            'reenrollment_notes'         => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Interest registered — the admissions team will be in touch');
    }
}
