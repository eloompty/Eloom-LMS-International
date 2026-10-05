<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Entities\Subject;
use Modules\Resource\Entities\Resource;
use Modules\Resource\Entities\ResourceCategory;

class SubjectResourceController extends Controller
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
        $subject = Subject::findorfail($id);
        if (checkRole('subject', 'view') == true) {
            $resources = Resource::where('subject_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened Resources List of ' . $subject->name);
            return view('course::subject.resource.index', compact('subject', 'resources'));
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
        $subject = Subject::findorfail($id);
        if (checkRole('subject', 'add') == true) {
            $categories = ResourceCategory::where('status', 1)->pluck('name', 'id');
            activityLog('Admin', 'Opened Resources List of ' . $subject->name);
            return view('course::subject.resource.create', compact('subject', 'categories'));
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
        $subject = Subject::find($id);
        if ($request->hasfile('files')) {
            $files =  $request->file('files');
            foreach ($files as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/resources', $name);
                $data['path'] = 'images/resources/' . $name;
                $category = ResourceCategory::find($request->resource_category_id);
                $data['course_id'] = $subject->course_id;
                $data['semester_id'] = $subject->semester_id;
                $data['subject_id'] = $id;
                $data['user_type'] = $category->user_type;
                $data['uploaded_by'] = 'Admin';
                $data['uploaded_user_id'] = Auth::guard('user')->user()->id;
                Resource::create($data);
            }
        }
        activityLog('Admin', 'Resources of ' . $subject->name . ' added');
        if ($subject->course->registered == 1) $route = 'admin.course.subject.resource.index';
        else $route = 'admin.unregistered.subject.resource.index';
        return redirect()->route($route, $id)->with('success', 'Resource has been added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $resource = Resource::findorfail($id);
        if (checkRole('subject', 'edit') == true) {
            $categories = ResourceCategory::where('status', 1)->pluck('name', 'id');
            activityLog('Admin', $resource->name . ' edit page opened');
            return view('course::subject.resource.edit', compact('resource', 'categories'));
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
        $resource = Resource::where('id', $id)->first();
        $resource->update($data);
        activityLog('Admin', $resource->name . ' updated');
        if ($resource->subject->course->registered == 1) $route = 'admin.course.subject.resource.index';
        else $route = 'admin.unregistered.subject.resource.index';
        return redirect()->route($route, $resource->subject_id)->with('success', 'Resource has been updated successfully');
    }

    /**
     * Update the status of specified resource to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('subject', 'delete') == true) {
            Resource::where('id', $id)->update(['status' => 2]);
            $resource = Resource::find($id);
            activityLog('Admin', $resource->name . ' updated status to deleted');
            return redirect()->back()->with('success', 'Resource deleted successfully');
        } else {
            return abort(404);
        }
    }
}
