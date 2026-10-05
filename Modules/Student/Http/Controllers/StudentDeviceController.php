<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentDevice;

class StudentDeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student device.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::findorfail($id);
        if (checkRole('student', 'view') == true) {
            $devices = StudentDevice::where('student_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Device List');
            return view('student::device.index', compact('student', 'devices'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
