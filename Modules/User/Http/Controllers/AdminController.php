<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Country\Entities\Country;
use Modules\Log\Entities\Log;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the admin.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('user', 'view') == true) {
            activityLog('Admin', 'Opened User Menu');
            $admins = User::get();
            return view('user::admin.index', compact('admins'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new admin.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('user', 'add') == true) {
            activityLog('Admin', 'Opened Create User');
            $roles = Role::where('status', 1)->orderBy('name', 'asc')->get();
            $countries = Country::where('status', 1)->pluck('name', 'id');
            return view('user::admin.create', compact('roles', 'countries'));
        } else {
            return redirect()->route('admin.user.index')->with('failure', 'This user does not have permission to add user');
        }
    }

    /**
     * Store a newly created admin in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/users'), $imageName);
            $data['image'] = 'images/users/' . $imageName;
        } else {
            $data['image'] = 'files/avatar.png';
        }
        $data['password'] = Hash::make($data['password']);
        $data['theme'] = 'theme3';
        User::create($data);
        activityLog('Admin', $data['first_name'] . ' ' . $data['family_name'] . ' user created');
        return redirect()->route('admin.user.index')->with('success', 'User has been added successfully');
    }

    /**
     * Show the specified admin.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified admin.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (checkRole('user', 'edit') == true && $user) {
            activityLog('Admin', 'Opened Create User');
            $roles = Role::where('status', 1)->orderBy('name', 'asc')->get();
            $countries = Country::where('status', 1)->pluck('name', 'id');
            return view('user::admin.edit', compact('user', 'roles', 'countries'));
        } else {
            return redirect()->route('admin.user.index')->with('failure', 'This user does not have permission to add user');
        }
    }

    /**
     * Update the specified admin in storage.
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
            request()->image->move(public_path('/images/users'), $imageName);
            $data['image'] = 'images/users/' . $imageName;
        }
        if ($data['password'] == NULL) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        User::where('id', $id)->update($data);
        activityLog('Admin', userName('User', $id) . ' admin updated');
        return redirect()->route('admin.user.index')->with('success', 'User has been updated successfully');
    }

    /**
     * Show the list for logs the specified admin.
     * @param int $id
     * @return Renderable
     */

    public function log($id)
    {
        $user = User::find($id);
        if (checkRole('user', 'view') == true && $user) {
            activityLog('Admin', 'Opened User log Menu');
            $logs = Log::where('user_id', $id)->where('user_type', 'Admin')->orderBy('id', 'desc')->get();
            return view('user::admin.logs.index', compact('user', 'logs'))->with('no', 1);
        } else {
            return redirect()->route('admin.user.index')->with('failure', 'This user does not have permission to view user logs');
        }
    }
}
