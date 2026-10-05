<?php

namespace Modules\Trainer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\WorkPlacement;
use Modules\Trainer\Entities\Trainer;

class TrainerWorkPlacementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the trainer work placement.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer = Trainer::find($id);
        if (checkRole('trainer_placement', 'view') == true && $trainer) {
            $works = WorkPlacement::where('type', 'Trainer')->where('type_id', $id)->orderBy('id', 'desc')->get();
            $name = userName('Trainer', $id);
            activityLog('Admin', $name . ' trainer work placements opened');
            return view('trainer::workplacement.index', compact('trainer', 'works'))->with('no', 1);
        } else {
            return redirect()->route('admin.trainer.index')->with('failure', 'This user does not have permission to view trainer work placement');
        }
    }

    /**
     * Show the form for creating a new trainer work placement.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer = Trainer::find($id);
        if (checkRole('trainer_placement', 'add') == true && $trainer) {
            $name = userName('Trainer', $id);
            activityLog('Admin', $name . ' trainer work placement create page opened');
            return view('trainer::workplacement.create', compact('trainer'));
        } else {
            return redirect()->route('admin.trainer.workplacement.index', $id)->with('failure', 'This user does not have permission to add trainer work placement');
        }
    }

    /**
     * Store a newly created trainer work placement in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['type'] = 'Trainer';
        $data['type_id'] = $id;
        $workpalcement = WorkPlacement::create($data);
        $name = userName('Trainer', $id);
        activityLog('Admin', $name . ' trainer ' . $workpalcement->placement_company_name . ' created');
        return redirect()->route('admin.trainer.workplacement.index', $id)->with('success', 'Trainer Work Placement has been added successfully');
    }

    /**
     * Show the form for editing the specified trainer work placement.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $trainerWork = WorkPlacement::find($id);
        if ($trainerWork) {
            if (checkRole('trainer_placement', 'edit') == true) {
                activityLog('Admin', $trainerWork->placement_company_name . ' edit page opened');
                return view('trainer::workplacement.edit', compact('trainerWork'));
            } else {
                return redirect()->route('admin.trainer.workplacement.index', $trainerWork->type_id)->with('failure', 'This user does not have permission to edit trainer work placement');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified trainer work placement in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        WorkPlacement::where('type_id', $id)->where('type', 'Trainer')->update($data);
        $trainerWork = WorkPlacement::find($id);
        activityLog('Admin', $trainerWork->placement_company_name . ' updated');
        return redirect()->route('admin.trainer.workplacement.index', $id)->with('success', 'Trainer Work Placement has been updated successfully');
    }

    /**
     * Remove the specified trainer work placement from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('trainer_placement', 'delete') == true) {
            WorkPlacement::where('id', $id)->update(['status' => 2]);
            $trainerWork = WorkPlacement::find($id);
            activityLog('Admin', $trainerWork->placement_company_name . ' updated status to deleted');
            return redirect()->back()->with('success', 'Trainer Work Placement deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete trainer work placement');
        }
    }
}
