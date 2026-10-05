<?php

namespace Modules\Resource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\Semester;
use Modules\Course\Entities\Subject;
use Modules\Course\Entities\Unit;
use Modules\Resource\Entities\Resource;
use Modules\Resource\Entities\ResourceCategory;

class ResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('resource', 'view') == true) {
            activityLog('Admin', 'Opened Resource Menu');
            $ids = getDeliverySiteIds();
            $resources = Resource::join('courses', 'courses.id', '=', 'resources.course_id')
                ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('resources.*')
                ->orderBy('resources.id', 'desc')->get();
            return view('resource::index', compact('resources'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('resource', 'add') == true) {
            activityLog('Admin', 'Opened Create Resource Page');
            $categories = ResourceCategory::where('status', 1)->pluck('name', 'id');
            $ids = getDeliverySiteIds();
            $courses = Course::leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('courses.*')
                ->where('courses.status', 1)->pluck('course_name', 'id');
            return view('resource::create', compact('categories', 'courses'));
        } else {
            return redirect()->route('admin.resource.index')->with('failure', 'This user does not have permission to add resource');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('files')) {
            $files =  $request->file('files');
            foreach ($files as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/resources', $name);
                $data['path'] = 'images/resources/' . $name;
                $category = ResourceCategory::find($request->resource_category_id);
                $data['user_type'] = $category->user_type;
                $data['uploaded_by'] = 'Admin';
                $data['uploaded_user_id'] = Auth::guard('user')->user()->id;
                Resource::create($data);
            }
        }
        activityLog('Admin', 'Rescources created');
        return redirect()->route('admin.resource.index')->with('success', 'Resource has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $resource = Resource::findorfail($id);
        $check = checkCourseDeliverySite($resource->course_id);
        if (checkRole('resource', 'edit') == true && $check == true) {
            $categories = ResourceCategory::where('status', 1)->pluck('name', 'id');
            $ids = getDeliverySiteIds();
            $courses = Course::leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                ->where(function ($query) use ($ids) {
                    $query->whereNull('course_delivery_sites.course_id')
                        ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                })
                ->select('courses.*')
                ->where('courses.status', 1)->pluck('course_name', 'id');
            $semesters = Semester::where('course_id', $resource->course_id)->where('status', 1)->pluck('name', 'id');
            $subjects = Subject::where('semester_id', $resource->semester_id)->where('status', 1)->pluck('name', 'id');
            $units = Unit::where('subject_id', $resource->subject_id)->where('status', 1)->pluck('name', 'id');
            activityLog('Admin', $resource->name . ' edit page opened');
            return view('resource::edit', compact('resource', 'categories', 'courses', 'semesters', 'subjects', 'units'));
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
        if ($request->hasfile('file')) {
            $imageName = time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move(public_path('/images/resources'), $imageName);
            $data['path'] = 'images/resources/' . $imageName;
        }
        $category = ResourceCategory::find($request->resource_category_id);
        $data['user_type'] = $category->user_type;
        unset($data['_token']);
        unset($data['file']);
        $resource = Resource::find($id);
        $resource->update($data);
        activityLog('Admin', $resource->name . ' updated');
        return redirect()->route('admin.resource.index')->with('success', 'Resource has been updated successfully');
    }

    /**
     * Update status of specified resource to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('resource', 'delete') == true) {
            Resource::where('id', $id)->update(['status' => 2]);
            $resource = Resource::find($id);
            activityLog('Admin', 'Status of' . $resource->name . ' updated to deleted');
            return redirect()->back()->with('success', 'Resource deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete resource');
        }
    }

    public function menu()
    {
        if (checkRole('resource', 'view') == true) {
            activityLog('Admin', 'Opened Resources Menu Page');
            return view('resource::menu');
        } else {
            return abort(404);
        }
    }
}
