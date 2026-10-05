<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentFile;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class AssignmentFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $assignment = Assignment::find($id);
        if ($assignment && $assignment->trainer_id == $trainer_id && $assignment->type == 'multiple files') {
            return view('trainer::trainer.unit.assignment..files.create', compact('assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $assignment = Assignment::where('id', $id)->first();
        $files =  $request->file('files');
            foreach ($files as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/assignments/' . $id, $name);
                $data['path'] = 'images/assignments/' . $id . '/' . $name;
                $data['assignment_id'] = $id;
                AssignmentFile::create($data);
            }
        $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $assignment->intake_unit_id)->where('status', 1)->get();
        foreach ($studentIntakeUnits as $key => $value) {
            $students = StudentDevice::where('student_id', $value->studentIntakeCourse->student_id)->where('status', 1)->get();
            $title = 'New Assignment';
            $body = 'New Assignment of ' . $assignment->intakeUnit->unit->name;
            if ($students->count() > 0) {
                foreach ($students as $key => $student) {
                    $fcmData = [
                        'registration_ids' => [$student->device_token],
                        "notification" => [
                            "title" => $title,
                            "body" => $body,
                            "click_action" => route('student.assignment.index', $assignment->intake_unit_id),
                        ],
                        "data" => [
                            "is_admin" => false,
                        ],
                    ];
                }
                fcm($fcmData);
            }
            Notification::create([
                'user_type' => 'Student',
                'user_id' => $value->studentIntakeCourse->student_id,
                'sender_type' => 'Trainer',
                'sender_id' => $trainer_id,
                'title' => $title,
                'body'=> $body,
                'type' => 'Assignment',
                'link' => $assignment->intake_unit_id,
            ]);
        }
        activityLog('Trainer', $assignment->name . ' assignment created');
        $trainerIntake = TrainerIntake::where('intake_unit_id', $assignment->intake_unit_id)->first();
        return redirect()->route('trainer.assignment.index', $trainerIntake->id)->with('success', 'Unit assignment created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $assignment = Assignment::findorfail($id);
        $files = AssignmentFile::where('assignment_id', $id)->get();
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('trainer_id', $trainer_id)->where('intake_unit_id', $assignment->intake_unit_id)->first();
        activityLog('Admin', $assignment->name . ' assignment files edit page opened');
        return view('trainer::trainer.unit.assignment..files.edit', compact('assignment', 'files', 'trainerIntake'))->with('no', 1);
    
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
        $assignment = Assignment::findorfail($id);
        foreach ($data['files'] as $key => $value) {
            $ids[] = $key;
        }
        AssignmentFile::where('assignment_id', $id)->whereNotIn('id', $ids)->delete();
        if (isset($data['files'][0])){
            foreach ($data['files'][0] as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/assignments/' . $id, $name);
                $file_date['path'] = 'images/assignments/' . $id . '/' . $name;
                $file_date['assignment_id'] = $id;
                AssignmentFile::create($file_date);
            }
        }
        return redirect()->back()->with('success', 'Unit Assignment Files have been updated');
    }
}
