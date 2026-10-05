<?php

namespace Modules\Trainer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\Unit;
use Modules\Intake\Entities\Intake;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerIntake;

class TrainerIntakeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the trainer intake.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer = Trainer::find($id);
        if (checkRole('trainer_intake', 'view') == true && $trainer) {
            $intakes = TrainerIntake::where('trainer_id', $id)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            $name = userName('Trainer', $id);
            activityLog('Admin', $name . ' trainer intake page opened');
            return view('trainer::intake.index', compact('trainer', 'intakes'))->with('no', 1);
        } else {
            return redirect()->route('admin.trainer.index')->with('failure', 'This user does not have permission to view trainer intake');
        }
    }

    /**
     * Show the form for editing the specified trainer intake.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $trainerIntake = TrainerIntake::find($id);
        if ($trainerIntake) {
            if (checkRole('trainer_intake', 'edit') == true) {
                $name = userName('Trainer', $trainerIntake->trainer_id);
                activityLog('Admin', $name . ' trainer intake edit page opened');
                return view('trainer::intake.edit', compact('trainerIntake'));
            } else {
                return redirect()->route('admin.trainer.intake.index', $trainerIntake->trainer_id)->with('failure', 'This user does not have permission to edit trainer');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified trainer intake in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        TrainerIntake::where('id', $id)->update($data);
        $intakeTrainer = TrainerIntake::find($id);
        $name = userName('Trainer', $intakeTrainer->trainer_id);
        activityLog('Admin', $name . ' trainer intake updated');
        return redirect()->route('admin.trainer.intake.index', $intakeTrainer->trainer_id)->with('success', 'Trainer Intake updated successfully');
    }

    /**
     * Status of trainer intake updated to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('trainer_intake', 'delete') == true) {
            TrainerIntake::where('id', $id)->update(['status' => 2]);
            $intakeTrainer = TrainerIntake::find($id);
            $name = userName('Trainer', $intakeTrainer->trainer_id);
            activityLog('Admin', $name . ' status updated to deleted');
            return redirect()->back()->with('success', 'Trainer Intake deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete trainer intake');
        }
    }
}
