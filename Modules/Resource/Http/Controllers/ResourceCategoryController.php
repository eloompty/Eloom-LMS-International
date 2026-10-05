<?php

namespace Modules\Resource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Resource\Entities\ResourceCategory;

class ResourceCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource categories.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('resource_category', 'view') == true) {
            activityLog('Admin', 'Opened Resource Categories Menu');
            $categories = ResourceCategory::orderBy('name', 'asc')->get();
            return view('resource::category.index', compact('categories'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource category.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('resource_category', 'add') == true) {
            activityLog('Admin', 'Opened Create Resource Category Menu');
            return view('resource::category.create');
        } else {
            return redirect()->route('admin.resource.category.index')->with('failure', 'This user does not have permission to add resource category');
        }
    }

    /**
     * Store a newly created resource category in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $resource = ResourceCategory::create($data);
        activityLog('Admin', $resource->name . ' created for ' . $resource->user_type);
        return redirect()->route('admin.resource.category.index')->with('success', 'Resource Category has been added successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('resource::show');
    }

    /**
     * Show the form for editing the specified resource category.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('resource_category', 'edit') == true) {
            $category = ResourceCategory::find($id);
            activityLog('Admin', $category->name . ' edit page opened');
            return view('resource::category.edit', compact('category'));
        } else {
            return redirect()->route('admin.resource.category.index')->with('failure', 'This user does not have permission to add resource category');
        }
    }

    /**
     * Update the specified resource category in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        ResourceCategory::where('id', $id)->update($data);
        $category = ResourceCategory::find($id);
        activityLog('Admin', $category->name . ' updated');
        return redirect()->route('admin.resource.category.index')->with('success', 'Resource Category has been updated successfully');
    }

    /**
     * Update status of specified resource category to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('resource_category', 'delete') == true) {
            ResourceCategory::where('id', $id)->update(['status' => 2]);
            $category = ResourceCategory::find($id);
            activityLog('Admin', 'Status of ' . $category->name . ' updated to deleted');
            return redirect()->back()->with('success', 'Resource Category deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to add resource category');
        }
    }
}
