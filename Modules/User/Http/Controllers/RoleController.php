<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Modules\User\Entities\UserRole;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the role.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('role', 'view') == true) {
            activityLog('Admin', 'Opened Role Menu');
            $roles = Role::orderBy('id', 'desc')->get();
            return view('user::role.index', compact('roles'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new role.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('role', 'add') == true) {
            activityLog('Admin', 'Opened Create Role');
            return view('user::role.create');
        } else {
            return redirect()->route('admin.role.index')->with('failure', 'This user does not have permission to add role');
        }
    }

    /**
     * Store a newly created role in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $roleCount = Role::where('name', $data['name'])->count();
        if ($roleCount == 0) {
            $data['user_type'] = str_replace(" ", "_", strtolower($data['name']));
            foreach ($data['action'] as $name => $action) {
                foreach ($action as $value) {
                    UserRole::create([
                        'name' => $data['name'],
                        'user_type' => $data['user_type'],
                        'key' => $name,
                        'value' => $value,
                    ]);
                }
            }
            Role::create($data);
            activityLog('Admin', $data['name'] . ' Role created');
            return redirect()->route('admin.role.index')->with('success', 'Admin role has been added successfuly');
        } else {
            return redirect()->route('admin.role.index')->with('failure', $data['name'] . ' role already exists');
        }
    }

    /**
     * Show the form for editing the specified role.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $role = Role::find($id);
        if (checkRole('role', 'edit') == true && $role) {
            $userRoles = UserRole::where('name', $role->name)->get();
            foreach ($userRoles as $key => $value) {
                $user_role[] = $value->key . '.' . $value->value;
            }
            activityLog('Admin', $role->name . ' role edit page opened');
            return view('user::role.edit', compact('role', 'user_role'));
        } else {
            return redirect()->route('admin.user.index')->with('failure', 'This user does not have permission to edit role');
        }
    }

    /**
     * Update the specified role in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $role = Role::find($id);
        if ($role->user_type == 'super_admin') {
            return redirect()->route('admin.role.index')->with('failure', 'Super Admin role cannot be updated');
        } else {
            $data['user_type'] = str_replace(" ", "_", strtolower($data['name']));
            User::where('user_type', $role->user_type)->update(['user_type' => $data['user_type']]);
            UserRole::where('name', $role->name)->delete();
            $role->name = $data['name'];
            $role->user_type = $data['user_type'];
            $role->status = $data['status'];
            $role->save();
            foreach ($data['action'] as $name => $action) {
                foreach ($action as $value) {
                    UserRole::create([
                        'name' => $data['name'],
                        'user_type' => $data['user_type'],
                        'key' => $name,
                        'value' => $value,
                    ]);
                }
            }
            activityLog('Admin', $role->name . ' role updated');
            return redirect()->route('admin.role.index')->with('success', 'Admin role has been updated successfuly');
        }
    }

    public function addRole(Request $request)
    {
        $data = $request->all();
        $data['name'] = 'Super Admin';
        $data['user_type'] = 'super_admin';
        $check = UserRole::where('name', 'Super Admin')->where('user_type', 'super_admin')->where('key', $data['key'])->where('value', $data['value'])->first();
        if ($check) {
            return response()->json('Role already exists.');
        }
        UserRole::create($data);
        return response()->json('Role created');
    }
}
