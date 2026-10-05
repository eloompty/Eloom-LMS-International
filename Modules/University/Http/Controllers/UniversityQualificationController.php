<?php

namespace Modules\University\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\University\Entities\University;
use Modules\University\Entities\UniversityQualification;

class UniversityQualificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the university qualification.
     * @return Renderable
     */
    public function index($id)
    {
        $university = University::find($id);
        if (checkRole('university_qualification', 'add') == true && $university) {
        $qualifications = UniversityQualification::where('university_id', $id)->orderBy('name', 'asc')->get();
        activityLog('Admin', 'Qualifications list of ' . $university->name . ' opened');
        return view('university::qualification.index', compact('university', 'qualifications'))->with('no', 1);
    } else {
        return redirect()->route('admin.university.index')->with('failure', 'This user does not have permission to view university qualification');
    }
    }

    /**
     * Show the form for creating a new university qualification.
     * @return Renderable
     */
    public function create($id)
    {
        $university = University::find($id);
        if (checkRole('university_qualification', 'add') == true && $university) {
            activityLog('Admin', 'Qualification create page of ' . $university->name . ' opened');
            return view('university::qualification.create', compact('university'));
        } else {
            return redirect()->route('admin.university.qualification.index', $id)->with('failure', 'This user does not have permission to add university qualification');
        }
    }

    /**
     * Store a newly created university qualification in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['university_id'] = $id;
        $qualification = UniversityQualification::create($data);
        $university = University::find($id);
        activityLog('Admin', $qualification->name . ' of ' . $university->name . ' created');
        return redirect()->route('admin.university.qualification.index', $id)->with('success', 'Univerisity Qualification has been added successfully');
    }

    /**
     * Show the specified university qualification.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('university::show');
    }

    /**
     * Show the form for editing the specified university qualification.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('university::qualification.edit');
    }

    /**
     * Update the specified university qualification in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified university qualification from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
