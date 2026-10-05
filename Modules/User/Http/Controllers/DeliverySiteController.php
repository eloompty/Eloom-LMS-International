<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\User;
use Modules\User\Entities\UserDeliverySite;

class DeliverySiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the user_delivery_site.
     * @return Renderable
     */
    public function index($id)
    {
        if (checkRole('user', 'view') == true) {
            activityLog('Admin', 'Opened User Delivery Site Page');
            $admin = User::findorfail($id);
            $delivery_sites = UserDeliverySite::where('user_id', $id)->orderBy('id', 'asc')->get();
            return view('user::admin.delivery_site.index', compact('admin', 'delivery_sites'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new user_delivery_site.
     * @return Renderable
     */
    public function create($id)
    {
        if (checkRole('user', 'add') == true) {
            activityLog('Admin', 'Opened User Delivery Site Create Page');
            $admin = User::findorfail($id);
            $sites = getDeliverySites();
            return view('user::admin.delivery_site.create', compact('admin', 'sites'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created user_delivery_site in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $site = UserDeliverySite::where('user_id', $id)->where('company_delivery_site_id', $data['company_delivery_site_id'])->first();
        if ($site) {
            return redirect()->back()->with('failure', 'User Delivery Site has already been added, please choose another delivery site');
        }
        $data['user_id'] = $id;
        UserDeliverySite::create($data);
        activityLog('Admin', 'Delivery site user has been created');
        return redirect()->route('admin.user.delivery.index', $id)->with('success', 'User Delivery Site has been added');
    }

    /**
     * Show the form for editing the specified user_delivery_site.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('user', 'edit') == true) {
            activityLog('Admin', 'Opened User Delivery Site Edit Page');
            $delivery_site = UserDeliverySite::findorfail($id);
            $sites = getDeliverySites();
            return view('user::admin.delivery_site.edit', compact('delivery_site', 'sites'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified user_delivery_site in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $site = UserDeliverySite::where('id', $id)->first();
        $site_check = UserDeliverySite::where('user_id', $site->user_id)->where('company_delivery_site_id', $data['company_delivery_site_id'])->where('id', '!=', $id)->count();
        if ($site_check > 0) {
            return redirect()->back()->with('failure', 'User Delivery Site has already been added, please choose another delivery site');
        } else {
            $site->update($data);
        }
        return redirect()->route('admin.user.delivery.index', $site->user_id)->with('success', 'User Delivery Site has been updated');
    }

    /**
     * Remove the specified user_delivery_site from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
