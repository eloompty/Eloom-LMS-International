<?php

namespace Modules\Social\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Social\Entities\SocialCategory;

class SocialCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the social categories.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('social_category', 'view') == true) {
            activityLog('Admin', 'Opened Social Category Menu');
            $categories = SocialCategory::orderBy('id', 'desc')->get();
            return view('social::category.index', compact('categories'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new social category.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('social_category', 'add') == true) {
            activityLog('Admin', 'Opened create Social Category Page');
            return view('social::category.create');
        } else {
            return redirect()->route('admin.social.category.index')->with('failure', 'This user does not have permission to add social category');
        }
    }

    /**
     * Store a newly created social category in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $imageName = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('/images/social/categories'), $imageName);
        $data['image'] = 'images/social/categories/' . $imageName;
        $category = SocialCategory::create($data);
        activityLog('Admin', $category->name . ' category created');
        return redirect()->route('admin.social.category.index')->with('success', 'Social Category has been added successfully');
    }

    /**
     * Show the form for editing the specified social category.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('social_category', 'edit') == true) {
            $category = SocialCategory::find($id);
            activityLog('Admin', $category->name .  'edit page opened');
            return view('social::category.edit', compact('category'));
        } else {
            return redirect()->route('admin.social.category.index')->with('failure', 'This user does not have permission to edit social category');
        }
    }

    /**
     * Update the specified social category in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/social/categories'), $imageName);
            $data['image'] = 'images/social/categories/' . $imageName;
        }
        SocialCategory::where('id', $id)->update($data);
        $category = SocialCategory::find($id);
        activityLog('Admin', $category->name . ' updated');
        return redirect()->route('admin.social.category.index')->with('success', 'Social Category has been updated successfully');
    }

    /**
     * Update status of social category  to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('social_category', 'delete') == true) {
            SocialCategory::where('id', $id)->update(['status' => 2]);
            $category = SocialCategory::find($id);
            activityLog('Admin', 'Status of ' . $category->name . ' updated to deleted');
            return redirect()->back()->with('success', 'Social Category deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete social category');
        }
    }
}
