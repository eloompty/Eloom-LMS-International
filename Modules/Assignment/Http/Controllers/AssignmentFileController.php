<?php

namespace Modules\Assignment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentFile;

class AssignmentFileController extends Controller
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
        $assignment = Assignment::findorfail($id);
        if (checkRole('assignment', 'view') == true) {
            activityLog('Admin', 'Opened Assignment Files Menu');
            $files = AssignmentFile::where('assignment_id', $id)->get();
            return view('assignment::files.index', compact('files', 'assignment'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
