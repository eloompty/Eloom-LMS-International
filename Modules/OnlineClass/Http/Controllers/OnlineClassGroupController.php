<?php

namespace Modules\OnlineClass\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\OnlineClass\Entities\OnlineClassGroup;
use Modules\OnlineClass\Entities\OnlineClassGroupStudent;
use Modules\OnlineClass\Entities\OnlineClassGroupTrainer;
use Modules\Trainer\Entities\Trainer;

class OnlineClassGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the online class group.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('online_class_group', 'view') == true) {
            activityLog('Admin', 'Opened Online Class Group Page');
            $groups = OnlineClassGroup::get();
            foreach ($groups as $key => $value) {
                $trainer = OnlineClassGroupTrainer::where('online_class_group_id', $value->id)->first();
                $groups[$key]['trainer_id'] = $trainer->trainer_id;
            }
            return view('onlineclass::group.index', compact('groups'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new online class group.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('online_class_group', 'add') == true) {
            activityLog('Admin', 'Opened Online Class Group Create Page');
            $trainers = Trainer::where('status', 1)->get();
            $intakes = Intake::where('status', 1)->get();
            $ids = getDeliverySiteIds();
            foreach ($intakes as $key => $value) {
                $intakes[$key]['courses'] = IntakeCourse::join('courses', 'courses.id', '=', 'intake_courses.course_id')
                ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('intake_courses.*')
                ->where('courses.status', 1)
                ->where('intake_courses.intake_id', $value->id)->orderBy('intake_courses.id', 'desc')->get();
            }
            return view('onlineclass::group.create', compact('trainers', 'intakes'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created online class group in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $group = OnlineClassGroup::create($data);
        OnlineClassGroupTrainer::create([
            'online_class_group_id' => $group->id,
            'trainer_id' => $data['trainer_id'],
        ]);
        foreach ($data['student_id'] as $key => $value) {
            $student = OnlineClassGroupStudent::where('online_class_group_id', $group->id)->where('student_id', $value)->first();
            if (!$student) {
                OnlineClassGroupStudent::create([
                    'online_class_group_id' => $group->id,
                    'student_id' => $value
                ]);
            }
        }
        activityLog('Admin', 'Online Student Group created from web');
        return redirect()->route('admin.onlineclass.group.index')->with('success', 'Student group has been created');
    }

    /**
     * Show the form for editing the specified online class group.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $onlineGroup = OnlineClassGroup::find($id);
        if (checkRole('online_class_group', 'edit') == true && $onlineGroup) {
            activityLog('Admin', 'Opened Online Class Group Menu');
            $trainerGroup = OnlineClassGroupTrainer::where('online_class_group_id', $id)->first();
            $trainers = Trainer::where('status', 1)->get();
            $intakes = Intake::where('status', 1)->get();
            foreach ($intakes as $key => $value) {
                $courses = $value->course;
                foreach ($courses as $num => $course) {
                    $students = $course->studentIntake;
                    foreach ($students as $index => $student) {
                        $studentGroup = OnlineClassGroupStudent::where('online_class_group_id', $id)->where('student_id', $student->student_id)->first();
                        if ($studentGroup) {
                            $intakes[$key]['course'][$num]['studentIntake'][$index]['intake_course_student_id'] = $student->student_id;
                        } else {
                            $intakes[$key]['course'][$num]['studentIntake'][$index]['intake_course_student_id'] = 0;
                        }
                    }
                }
            }
            activityLog('Admin', 'Opened Online Student Group edit page from web');
            return view('onlineclass::group.edit', compact('onlineGroup', 'trainerGroup', 'trainers', 'intakes'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified online class group in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $onlineGroup = OnlineClassGroup::where('id', $id)->first();
        $onlineGroup->update([
            'name' => $data['name'],
            'status' => $data['status']
        ]);
        OnlineClassGroupTrainer::where('online_class_group_id', $id)->update([
            'trainer_id' => $data['trainer_id'],
            'status' => $data['status']
        ]);
        $studentGroups = OnlineClassGroupStudent::where('online_class_group_id', $id)->get();
        foreach ($studentGroups as $key => $value) {
            $student_group[] = $value->student_id;
        }
        $deleteStudent = array_diff($student_group, $data['student_id']);
        foreach ($deleteStudent as $key => $value) {
            $studentGroup = OnlineClassGroupStudent::where('online_class_group_id', $id)->where('student_id', $value)->delete();
        }
        foreach ($data['student_id'] as $key => $value) {
            $studentGroup = OnlineClassGroupStudent::where('online_class_group_id', $id)->where('student_id', $value)->first();
            if ($studentGroup) {
                $studentGroup->update(['status' => $data['status']]);
            } else {
                OnlineClassGroupStudent::create([
                    'online_class_group_id' => $id,
                    'student_id' => $value,
                    'status' => $data['status']
                ]);
            }
        }
        activityLog('Admin', 'Online Student Group updated from web');
        return redirect()->route('admin.onlineclass.group.index')->with('success', 'Student group has been updated');
    }
}
