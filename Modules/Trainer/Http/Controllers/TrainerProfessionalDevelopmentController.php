<?php

namespace Modules\Trainer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerProfessionalDevelopment;

class TrainerProfessionalDevelopmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the trainer professional developement.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer = Trainer::find($id);
        if (checkRole('trainer_professional_development', 'view') == true && $trainer) {
            $professions = TrainerProfessionalDevelopment::where('trainer_id', $id)->orderBy('title', 'asc')->get();
            $name = userName('Trainer', $id);
            activityLog('Admin', $name . ' teacher professional developments lists page opened');
            return view('trainer::profession.index', compact('trainer', 'professions'))->with('no', 1);
        } else {
            return redirect()->route('admin.trainer.index')->with('failure', 'This user does not have permission to view teacher professional developmen');
        }
    }

    /**
     * Show the form for creating a new trainer professional developement.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer = Trainer::find($id);
        if (checkRole('trainer_professional_development', 'add') == true && $trainer) {
            $name = userName('Trainer', $id);
            activityLog('Admin', $name . ' teacher professional development create page page opened');
            return view('trainer::profession.create', compact('trainer'));
        } else {
            return redirect()->route('admin.trainer.profession.index', $id)->with('failure', 'This user does not have permission to add teacher professional development');
        }
    }

    /**
     * Store a newly created trainer professional developement in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['trainer_id'] = $id;
        TrainerProfessionalDevelopment::create($data);
        $name = userName('Trainer', $id);
        activityLog('Admin', $name . ' teacher professional development added');
        return redirect()->route('admin.trainer.profession.index', $id)->with('success', 'Teacher Professional Development has been successfully');
    }

    /**
     * Show the form for editing the specified trainer professional developement.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $profession = TrainerProfessionalDevelopment::find($id);
        if ($profession) {
            if (checkRole('trainer_professional_development', 'edit') == true) {
                $name = userName('Trainer', $profession->trainer_id);
                activityLog('Admin', $name . ' teacher professional development edit page opened');
                return view('trainer::profession.edit', compact('profession'));
            } else {
                return redirect()->route('admin.trainer.profession.index', $profession->trainer_id)->with('failure', 'This user does not have permission to edit teacher professional development');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified trainer professional developement in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        TrainerProfessionalDevelopment::where('id', $id)->update($data);
        $profession = TrainerProfessionalDevelopment::find($id);
        $name = userName('Trainer', $profession->trainer_id);
        activityLog('Admin', $name . ' trainer professional development updated');
        return redirect()->route('admin.trainer.profession.index', $id)->with('success', 'Teacher Professionnal Developement updated successfully');
    }

    /**
     * Remove the specified trainer professional developement from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('trainer_professional_development', 'delete') == true) {
            $profession = TrainerProfessionalDevelopment::find($id);
            $name = userName('Trainer', $$profession->trainer_id);
            activityLog('Admin', $name . ' trainer qaulification status updated to deleted');
            TrainerProfessionalDevelopment::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Teacher Professionnal Developement deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete teacher professional development');
        }
    }
}
