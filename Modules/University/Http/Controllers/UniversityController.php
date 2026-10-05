<?php

namespace Modules\University\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Country\Entities\Country;
use Modules\University\Entities\University;

class UniversityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the University.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('university', 'view') == true) {
            activityLog('Admin', 'Opened Universities Menu');
            $universities = University::orderBy('name', 'asc')->get();
            return view('university::index', compact('universities'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new University.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('university', 'add') == true) {
            activityLog('Admin', 'Opened Create University');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            return view('university::create', compact('countries'));
        } else {
            return redirect()->route('admin.university.index')->with('failure', 'This user does not have permission to add university');
        }
    }

    /**
     * Store a newly created University in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $university = University::create($data);
        activityLog('Admin', $university->name . ' created');
        return redirect()->route('admin.university.index')->with('success', 'University has been added successfully');
    }

    /**
     * Show the specified University.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('university::show');
    }

    /**
     * Show the form for editing the specified University.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('university::edit');
    }

    /**
     * Update the specified University in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified University from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
