<?php

namespace Modules\Classroom\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Classroom\Entities\Classroom;
use Modules\Classroom\Entities\ClassroomTimeTable;

class TimeTableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $classroom = Classroom::findorfail($id);
        if (checkRole('classroom', 'view') == true){
            $times = ClassroomTimeTable::where('classroom_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Time table of ' . $classroom->name . ' classroom opened');
            return view('classroom::timetable.index', compact('classroom', 'times'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $classroom = Classroom::findorfail($id);
        if (checkRole('classroom', 'add') == true){
            activityLog('Admin', 'Time table of ' . $classroom->name . ' classroom create page opened');
            return view('classroom::timetable.create', compact('classroom'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['classroom_id'] = $id;
        foreach ($data['days'] as $day) {
            $data['day'] = $day;
            ClassroomTimeTable::create($data);
        }
        $classroom = Classroom::find($id);
        activityLog('Admin', 'Time Table for ' . $classroom->name . ' created');
        return redirect()->route('admin.classroom.time.index', $id)->with('success', 'Classroom time table has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $time = ClassroomTimeTable::findorfail($id);
        if (checkRole('classroom', 'edit') == true) {
            activityLog('Admin', 'Time Table for ' . $time->classroom->name . ' edit page opened');
            return view('classroom::timetable.edit', compact('time'));
        } else {
            return abort(404);
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
        $data = $request->all();
        unset($data['_token']);
        $time = ClassroomTimeTable::where('id', $id)->first();
        $time->update($data);
        activityLog('Admin', 'Time Table for ' . $time->classroom->time . ' Updated');
        return redirect()->route('admin.classroom.time.index', $time->classroom_id)->with('success', 'Classroom Time Table has been updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
