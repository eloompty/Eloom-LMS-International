<?php

namespace Modules\Location\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Location\Entities\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the location.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('location', 'view') == true) {
            $locations = Location::where('location_id', 0)->orderBy('id', 'desc')->get();
            return view('location::admin.location.index', compact('locations'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new location.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('location', 'add') == true) {
            return view('location::admin.location.create');
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created location in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        Location::create($data);
        return redirect()->route('admin.location.index')->with('success', 'Province added successfully');
    }

    /**
     * Show the form for editing the specified location.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('location', 'edit') == true) {
            $location = Location::find($id);
            return view('location::admin.location.edit', compact('location'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified location in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        Location::where('id', $id)->update($data);
        return redirect()->route('admin.location.index')->with('success', 'Province has been updated');
    }

    /**
     * Remove the specified location from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('location', 'delete') == true) {
            $province = Location::where('id', $id)->first();
            $province->update(['status' => 2]);
            $districts = $province->locations;
            foreach ($districts as $district) {
                $district->update(['status' => 2]);
                $local_bodies = $district->locations;
                foreach ($local_bodies as $local_body) {
                    $local_body->update(['status' => 2]);
                    $wards = $local_body->locations;
                    foreach ($wards as $ward) {
                        $ward->update(['status' => 2]);
                        $toles = $ward->locations;
                        foreach ($toles as $key => $value) {
                            $value->update(['status' => 2]);
                        }
                    }
                }
            }
            return redirect()->route('admin.location.index')->with('success', 'Province has been deleted');
        } else {
            return abort(404);
        }
    }

    /* Get sub location by location id */
    public function getSubLocations(Request $request)
    {
        $sub_locations = Location::where('location_id', $request->location_id)->where('status', 1)->pluck('name', 'id');
        return response()->json($sub_locations);
    }
}
