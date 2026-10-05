<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Social\Entities\Social;
use Modules\Social\Entities\SocialCategory;
use Modules\Student\Entities\Student;

class StudentSocialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }
    
    /**
     * Display a listing of the student social.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::find($id);
        if (checkRole('student_social', 'view') == true && $student) {
            $socials = Social::where('user_type', 'Student')->where('user_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Social Menu');
            return view('student::social.index', compact('student', 'socials'));
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to view student social');
        }
    }

    /**
     * Show the form for creating a new student social.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::find($id);
        if (checkRole('student_social', 'add') == true && $student) {
            $socials = SocialCategory::where('status', 1)->pluck('name', 'id');
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Create Social Page');
            return view('student::social.create', compact('student', 'socials'));
        } else {
            return redirect()->route('admin.student.social.index', $id)->with('failure', 'This user does not have permission to add student social');
        }
    }

    /**
     * Store a newly created student social in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['user_type'] = 'Student';
        $data['user_id'] = $id;
        $social = Social::create($data);
        activityLog('Admin', $social->social_category->name . ' of ' . userName('Student', $id) . ' created');
        return redirect()->route('admin.student.social.index', $id)->with('success', 'Student Social added successfully');
    }

    /**
     * Show the form for editing the specified student social.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $studentSocial = Social::find($id);
        if ($studentSocial) {
            if (checkRole('student_social', 'edit') == true && $studentSocial) {
                $socials = SocialCategory::where('status', 1)->pluck('name', 'id');
                activityLog('Admin', 'Opened ' . userName('Student', $studentSocial->user_id) . ' Edit Social Page');
                return view('student::social.edit', compact('studentSocial', 'socials'));
            } else {
                return redirect()->route('admin.student.social.index', $studentSocial->user_id)->with('failure', 'This user does not have permission to edit student social');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified student social in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        Social::where('id', $id)->update($data);
        $social = Social::find($id);
        activityLog('Admin', $social->social_category->name . ' of ' . userName('Student', $social->user_id) . ' updated');
        return redirect()->route('admin.student.social.index', $social->user_id)->with('success', 'Student Social updated successfully');
    }

    /**
     * Remove the specified student social from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $social = Social::find($id);
        if (checkRole('student_social', 'delete') == true) {
            Social::where('id', $id)->update(['status' => 2]);
            activityLog('Admin', $social->social_category->name . ' of ' . userName('Student', $social->user_id) . ' updated status to deleted');
            return redirect()->back()->with('success', 'Student Social deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete student social');
        }
    }
}
