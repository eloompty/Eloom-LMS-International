<?php

namespace Modules\Trainer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Country\Entities\Country;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerQualification;
use Modules\University\Entities\University;
use Modules\University\Entities\UniversityQualification;

class TrainerQualificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the trainer qualification.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer = Trainer::find($id);
        if (checkRole('trainer_qualification', 'view') == true && $trainer) {
            $qualifications = TrainerQualification::where('trainer_id', $id)->orderBy('name', 'asc')->get();
            $name = userName('Trainer', $id);
            activityLog('Admin', $name . ' trainer qualification lists page opened');
            return view('trainer::qualification.index', compact('trainer', 'qualifications'))->with('no', 1);
        } else {
            return redirect()->route('admin.trainer.index')->with('failure', 'This user does not have permission to view trainer qaulification');
        }
    }

    /**
     * Show the form for creating a new trainer qualification.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer = Trainer::find($id);
        if (checkRole('trainer_qualification', 'add') == true && $trainer) {
            $universities = University::where('status', 1)->pluck('name', 'id');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            $name = userName('Trainer', $id);
            activityLog('Admin', $name . ' trainer qualification create page opened');
            return view('trainer::qualification.create', compact('trainer', 'universities', 'countries'));
        } else {
            return redirect()->route('admin.trainer.qualification.index', $id)->with('failure', 'This user does not have permission to add trainer qaulification');
        }
    }

    /**
     * Store a newly created trainer qualification in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['trainer_id'] = $id;
        $university = University::find($request->award_university);
        if ($university) {
            $data['award_university'] = $university->name;
            $data['country_id'] = $university->country_id;
        } else {
            $data['award_university'] = $data['university'];
            $university = University::create([
                'name' => $data['university'],
                'country_id' => $data['country_id']
            ]);
            activityLog('Admin', $university->name . ' created');
        }
        $qualification = UniversityQualification::find($request->name);
        if ($qualification) {
            $data['name'] = $qualification->name;
        } else {
            $data['university_id'] = $university->id;
            $data['name'] = $data['qualification'];
            $qualification = UniversityQualification::create($data);
            activityLog('Admin', $qualification->name . ' of ' . $university->name . ' created');
        }
        $trainerQualification = TrainerQualification::where('trainer_id', $id)->where('name', $data['name'])->where('award_university', $data['award_university'])->where('country_id', $data['country_id'])->first();
        if ($trainerQualification) {
            return redirect()->route('admin.trainer.qualification.index', $id)->with('failure', 'Trainer qualification already exists');
        } else {
            TrainerQualification::create($data);
            $name = userName('Trainer', $id);
            activityLog('Admin', $name . ' trainer qualification created');
            return redirect()->route('admin.trainer.qualification.index', $id)->with('success', 'Trainer Qualification has been added successfully');
        }
    }

    /**
     * Show the form for editing the specified trainer qualification.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $trainerQualification = TrainerQualification::find($id);
        if ($trainerQualification) {
            if (checkRole('trainer_qualification', 'edit') == true) {
                $university = University::where('name', $trainerQualification->award_university)->first();
                $universities = University::where('status', 1)->pluck('name', 'id');
                $countries = Country::where('status', 1)->pluck('name', 'id');
                $universityQualifications = UniversityQualification::where('university_id', $university->id)->pluck('name', 'id');
                $name = userName('Trainer', $trainerQualification->trainer_id);
                activityLog('Admin', $name . ' trainer qualification edit page opened');
                return view('trainer::qualification.edit', compact('trainerQualification', 'universities', 'countries', 'universityQualifications'));
            } else {
                return redirect()->route('admin.trainer.qualification.index', $trainerQualification->trainer_id)->with('failure', 'This user does not have permission to edit trainer qaulification');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified trainer qualification in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        if ($request->award_university != NULL) {
            $university = University::find($request->award_university);
            $data['award_university'] = $university->name;
            $data['country_id'] = $university->country_id;
        }
        if ($request->name != NULL) {
            $qualification = UniversityQualification::find($request->name);
            $data['name'] = $qualification->name;
        }
        TrainerQualification::where('id', $id)->update($data);
        $trainerQualification = TrainerQualification::find($id);
        $name = userName('Trainer', $trainerQualification->trainer_id);
        activityLog('Admin', $name . ' trainer qualification create page opened');
        return redirect()->route('admin.trainer.qualification.index', $id)->with('success', 'Trainer Qualification has been updated successfully');
    }

    /**
     * Remove the specified trainer qualification from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('trainer_qualification', 'delete') == true) {
            TrainerQualification::where('id', $id)->update(['status' => 2]);
            $trainerQualification = TrainerQualification::find($id);
            $name = userName('Trainer', $trainerQualification->trainer_id);
            activityLog('Admin', $name . ' trainer qualification status updated to deleted');
            return redirect()->back()->with('success', 'Trainer Qualification deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete trainer qaulification');
        }
    }

    public function university(Request $request)
    {
        $qualifications = UniversityQualification::where("university_id", $request->university_id)->where('status', 1)->pluck("name", "id");
        return response()->json($qualifications);
    }
}
