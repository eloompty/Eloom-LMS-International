<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseDeliverySite;
use Modules\Course\Entities\WorkPlacement;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the courses.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('course', 'view') == true) {
            activityLog('Admin', 'Opened Courses Menu');
            if (request()->is('admin/course*')) $registered = 1;
            else $registered = 0;
            $ids = getDeliverySiteIds();
            $courses = Course::leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
            ->where(function ($query) use ($ids) {
                $query->whereNull('course_delivery_sites.course_id')
                    ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
            })
            ->select('courses.*')
            ->withCount(['semester as active_semesters_count' => function ($query) {
                $query->where('status', 1);
            }])
            ->where('courses.registered', $registered)->orderBy('courses.id', 'desc')->get();
            return view('course::index', compact('courses', 'registered'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new course.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('course', 'add') == true) {
            activityLog('Admin', 'Opened Course Create Menu');
            if (request()->is('admin/course*')) $registered = 1;
            else $registered = 0;
            $delivery_sites = getDeliverySites();
            return view('course::create', compact('delivery_sites', 'registered'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created course in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (request()->is('admin/course*')) {
            $data['registered'] = 1;
            $route = 'admin.course.index';
        } else {
            $data['registered'] = 0;
            $route = 'admin.unregistered.index';
        }
        $data['study_break'] = 0;
        $course = Course::create($data);
        $course_id = $course->id;
        $data['course_id'] = $course_id;
        CourseDeliverySite::create($data);
        activityLog('Admin', $course->course_name . ' created');
        return redirect()->route($route)->with('success', 'Course has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $course = Course::find($id);
        if (checkRole('course', 'edit') == true && $course) {
            if (request()->is('admin/course*')) $registered = 1;
            else $registered = 0;
            $work_placement = WorkPlacement::where('type', 'Course')->where('type_id', $id)->get();
            $course_delivery_site = CourseDeliverySite::where('course_id', $id)->first();
            $delivery_sites = getDeliverySites();
            activityLog('Admin', $course->course_name . ' edit page opened');
            return view('course::edit', compact('course', 'registered', 'work_placement', 'course_delivery_site', 'delivery_sites'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified course in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $course = Course::find($id);
       
        $course_delivery_site = CourseDeliverySite::where('course_id', $id)->first();
        if ($course_delivery_site == NULL || $course_delivery_site->company_delivery_site_id != $request->company_delivery_site_id) {
            CourseDeliverySite::where('course_id', $id)->delete();
            $data['course_id'] = $id;
            CourseDeliverySite::create($data);
        }
        $course->fill($data)->save();
        activityLog('Admin', $course->course_name . ' updated');
        if (request()->is('admin/course*')) {
            $route = 'admin.course.index';
        } else {
            $route = 'admin.unregistered.index';
        }
        return redirect()->route($route)->with('success', 'Course has been updated successfully');
    }

    /**
     * Update the specified course status to deleted
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('course', 'delete') == true) {
            $course = Course::findorfail($id);
            $course->update(['status' => 2]);
            activityLog('Admin',  $course->course_name . ' updated status to deleted');
            return redirect()->back()->with('success', 'Course has been deleted successfully');
        } else {
            return abort(404);
        }
    }

    public function menu()
    {
        if (checkRole('course', 'view') == true) {
            activityLog('Admin',  'Opened Courses Menu Page');
            return view('course::menu');
        } else {
            return abort(404);
        }
    }
}
