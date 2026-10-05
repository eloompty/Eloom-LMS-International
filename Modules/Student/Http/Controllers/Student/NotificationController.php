<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Notification\Entities\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }
    
    /**
     * Display a listing of the student notification.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Student', 'Opened Notification menu from web');
        $id = $id = Auth::guard('student')->user()->id;
        $notifications = Notification::where('user_type', 'Student')->where('user_id', $id)->orderBy('id', 'desc')->get();
        return view('student::student.notification.index', compact('notifications'))->with('no', 1);
    }
}
