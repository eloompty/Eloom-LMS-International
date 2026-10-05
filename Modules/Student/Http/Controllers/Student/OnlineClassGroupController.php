<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\OnlineClass\Entities\OnlineClass;
use Modules\OnlineClass\Entities\OnlineClassGroup;
use Modules\OnlineClass\Entities\OnlineClassGroupStudent;
use Modules\OnlineClass\Entities\OnlineClassGroupTrainer;
use Modules\OnlineClass\Entities\OnlineClassTeam;

class OnlineClassGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display a listing of the online class group.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Student', 'Opened Online Class Group menu from web');
        $id = Auth::guard('student')->user()->id;
        $groups = OnlineClassGroupStudent::where('student_id', $id)->where('status', 1)->orderBy('id', 'desc')->get();
        foreach ($groups as $key => $value) {
            $trainerGroup = OnlineClassGroupTrainer::where('online_class_group_id', $value->online_class_group_id)->first();
            $groups[$key]['trainer_id'] = $trainerGroup->trainer_id;
        }
        return view('student::student.onlineclassgroup.index', compact('groups'))->with('no', 1);
    }

    /* List of online classes (zoom) by group */
    public function onlineClass($id)
    {
        $student = Auth::guard('student')->user();
        $onlineGroup = OnlineClassGroup::findorfail($id);
        activityLog('Student', 'Opened Online Class Group Zoom menu from web');
        $zooms = OnlineClass::where('online_class_group_id', $id)->where('intake_unit_id', 0)->where('status', 1)->get();
        foreach ($zooms as $key => $value) {
            $recording = $value->recording;
            if ($recording) {
                $zooms[$key]['recording'] = $recording->play_url;
                $zooms[$key]['password'] = $recording->password;
            } else {
                $zooms[$key]['recording'] = NULL;
                $zooms[$key]['password'] = NULL;
            }
        }
        return view('student::student.onlineclassgroup.onlineclass.index', compact('onlineGroup', 'zooms'))->with('no', 1);
    }

    /* List of online classes (teams) by group */
    public function onlineClassTeams($id)
    {
        $student = Auth::guard('student')->user();
        $onlineGroup = OnlineClassGroup::findorfail($id);
        activityLog('Student', 'Opened Online Class Group Zoom menu from web');
        $teams = OnlineClassTeam::where('online_class_group_id', $id)->where('intake_unit_id', 0)->where('status', 1)->get();
        return view('student::student.onlineclassgroup.teams.index', compact('onlineGroup', 'teams'))->with('no', 1);
    }
}
