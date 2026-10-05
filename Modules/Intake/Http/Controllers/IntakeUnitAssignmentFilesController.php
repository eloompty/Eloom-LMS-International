<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentFile;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentIntakeUnit;

class IntakeUnitAssignmentFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Show the form for creating a new intake unit assignment files.
     * @return Renderable
     */
    public function create($id)
    {
        $assignment = Assignment::findorfail($id);
        if (checkRole('assignment', 'add') == true && $assignment) {
            activityLog('Admin', $assignment->name . ' assignment files create page opened');
            return view('intake::course.unit.assignment.files.create', compact('assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created intake unit assignment files in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $assignment = Assignment::findorfail($id);
        $user_id = Auth::guard('user')->user()->id;
        $data = $request->all();
        if ($request->hasfile('files')) {
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
                $body = 'New Assignment of ' . $value->intakeUnit->unit->name;
                if ($students->count() > 0) {
                    foreach ($students as $key => $student) {
                        $fcmData = [
                            'registration_ids' => [$student->device_token],
                            "notification" => [
                                "title" => $title,
                                "body" => $body,
                                "click_action" => route('student.assignment.index',  $assignment->intake_unit_id),
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
                    'sender_type' => 'Admin',
                    'sender_id' => $user_id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'Assignment',
                    'link' => $assignment->intake_unit_id,
                ]);
            }
            activityLog('Admin', $assignment->name . ' assignment file created');
            return redirect()->route('admin.intake.unit.assignment.index', $assignment->intake_unit_id)->with('success', 'Unit assignment created successfully');
        } else {
            $intake_unit_id = $assignment->intake_unit_id;
            $assignment->delete();
            return redirect()->route('admin.intake.unit.assignment.index', $intake_unit_id)->with('failure', 'Unit assignment cannot be created');
        }
    }

    /**
     * Show the form for editing the specified intake unit assignment files.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $assignment = Assignment::findorfail($id);
        if (checkRole('assignment', 'edit') == true) {
            $files = AssignmentFile::where('assignment_id', $id)->get();
            activityLog('Admin', $assignment->name . ' assignment files edit page opened');
            return view('intake::course.unit.assignment.files.edit', compact('assignment', 'files'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified intake unit assignment files in storage.
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
        if (isset($data['files'][0])) {
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
