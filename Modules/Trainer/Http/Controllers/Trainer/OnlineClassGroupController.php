<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\OnlineClass\Entities\OnlineClassGroup;
use Modules\OnlineClass\Entities\OnlineClassGroupStudent;
use Modules\OnlineClass\Entities\OnlineClassGroupTrainer;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\TrainerIntake;

class OnlineClassGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the online group classes.
     * @return Renderable
     */
    public function index()
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $groups = OnlineClassGroupTrainer::where('trainer_id', $trainer_id)->get();
        activityLog('Trainer', 'Opened Online Group from web');
        return view('trainer::trainer.onlinegroup.index', compact('groups'))->with('no', 1);
    }

    /**
     * Show the form for creating a new online group classes.
     * @return Renderable
     */
    public function create()
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntakes = TrainerIntake::where('trainer_id', $trainer_id)->whereIn('status', [1, 3])->distinct()->get(['intake_course_id']);
        foreach ($trainerIntakes as $key => $value) {
            $trainerIntakes[$key]['student_intake_course'] = StudentIntakeCourse::where('intake_course_id', $value->intake_course_id)->get();
        }
        activityLog('Trainer', 'Opened Create Online Group from web');
        return view('trainer::trainer.onlinegroup.create', compact('trainerIntakes'));
    }

    /**
     * Store a newly created online group classes in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $trainer = Auth::guard('trainer')->user();
        $data = $request->all();
        $group = OnlineClassGroup::create($data);
        OnlineClassGroupTrainer::create([
            'online_class_group_id' => $group->id,
            'trainer_id' => $trainer->id,
        ]);
        foreach ($data['student_id'] as $key => $value) {
            OnlineClassGroupStudent::create([
                'online_class_group_id' => $group->id,
                'student_id' => $value
            ]);
        }
        activityLog('Trainer', 'Online Student Group created from web');
        return redirect()->route('trainer.onlineclass.group.index')->with('success', 'Student group has been created');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $trainer = Auth::guard('trainer')->user();
        $trainerGroup = OnlineClassGroupTrainer::where('trainer_id', $trainer->id)->where('id', $id)->first();
        if ($trainerGroup) {
            activityLog('Trainer', 'Opended Online Student Group edit page from web');
            $students = OnlineClassGroupStudent::where('online_class_group_id', $trainerGroup->online_class_group_id)->get();
            return view('trainer::trainer.onlinegroup.edit', compact('trainerGroup', 'students'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $trainer = Auth::guard('trainer')->user();
        $data = $request->all();
        unset($data['_token']);
        $trainerGroup = OnlineClassGroupTrainer::where('trainer_id', $trainer->id)->where('id', $id)->first();
        $trainerGroup->update($data);
        $onlineGroup = OnlineClassGroup::where('id', $trainerGroup->online_class_group_id)->first();
        $onlineGroup->update($data);
        unset($data['name']);
        OnlineClassGroupStudent::where('online_class_group_id', $trainerGroup->online_class_group_id)->update($data);
        activityLog('Trainer', $onlineGroup->name. ' Group has been updated');
        return redirect()->route('trainer.onlineclass.group.index')->with('success', 'Student Group has been updated successfully');
    }
}
