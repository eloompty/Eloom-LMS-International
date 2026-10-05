<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Entities\Unit;
use Modules\Resource\Entities\Resource;
use Modules\Resource\Entities\ResourceCategory;
use Modules\Trainer\Entities\TrainerIntake;

class ResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display Trainer Resource List By Unit Id
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $unit = Unit::find($trainerIntake->intakeUnit->unit_id);
            $resources = Resource::where('unit_id', $unit->id)->where('status', 1)->get();
            activityLog('Trainer', 'Opened resources of ' . $unit->name . ' from web');
            return view('trainer::trainer.unit.resource.index', compact('trainerIntake', 'unit', 'resources'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id, $trainer_intake_id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $trainer_intake_id)->where('trainer_id', $trainer_id)->first();
        $unit = Unit::find($id);
        if ($trainerIntake && $unit) {
            $categories = ResourceCategory::where('status', 1)->pluck('name', 'id');
            activityLog('Trainer', 'Opened Resources List of ' . $unit->name . 'from web');
            return view('trainer::trainer.unit.resource.create', compact('unit', 'categories', 'trainerIntake', 'trainer_intake_id'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id, $trainer_intake_id)
    {
        $data = $request->all();
        $unit = Unit::find($id);
        if ($request->hasfile('files')) {
            $files =  $request->file('files');
            foreach ($files as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/resources', $name);
                $data['path'] = 'images/resources/' . $name;
                $category = ResourceCategory::find($request->resource_category_id);
                $data['course_id'] = $unit->course_id;
                $data['semester_id'] = $unit->semester_id;
                $data['subject_id'] = $unit->subject_id;
                $data['unit_id'] = $id;
                $data['user_type'] = $category->user_type;
                $data['uploaded_by'] = 'Trainer';
                $data['uploaded_user_id'] = Auth::guard('trainer')->user()->id;
                Resource::create($data);
            }
        }
        activityLog('Trainer', 'Resources of ' . $unit->name . ' added');
        return redirect()->route('trainer.resource.index', $trainer_intake_id)->with('success', 'Resource has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id, $trainer_intake_id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $trainer_intake_id)->where('trainer_id', $trainer_id)->first();
        $resource = Resource::find($id);
        $categories = ResourceCategory::where('status', 1)->pluck('name', 'id');
        activityLog('Trainer', $resource->name . ' edit page opened from web');
        return view('trainer::trainer.unit.resource.edit', compact('resource', 'categories', 'trainerIntake'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->hasfile('file')) {
            $imageName = time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move(public_path('/images/resources'), $imageName);
            $data['path'] = 'images/resources/' . $imageName;
        }
        $category = ResourceCategory::find($request->resource_category_id);
        $data['user_type'] = $category->user_type;
        unset($data['_token']);
        unset($data['file']);
        $trainer_intake_id = $data['trainer_intake_id'];
        unset($data['trainer_intake_id']);
        $resource = Resource::find($id);
        $resource->update($data);
        activityLog('Trainer', $resource->name . ' updated');
        return redirect()->route('trainer.resource.index', $trainer_intake_id)->with('success', 'Resource has been updated successfully');
    }
}
