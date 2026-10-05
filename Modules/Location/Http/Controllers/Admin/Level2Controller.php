<?php

namespace Modules\Location\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Location\Entities\Location;

class Level2Controller extends Controller
{
    /**
     * Display a listing of the level2.
     * @return Renderable
     */
    public function index($id)
    {
        if (checkRole('location', 'view') == true) {
            $location = Location::findorfail($id);
            $level2 = Location::where('location_id', $id)->orderBy('id', 'desc')->get();
            return view('location::admin.location.level2.index', compact('location', 'level2'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new level2.
     * @return Renderable
     */
    public function create($id)
    {
        if (checkRole('location', 'add') == true) {
            $location = Location::findorfail($id);
            return view('location::admin.location.level2.create', compact('location'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created level2 in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['location_id'] = $id;
        Location::create($data);
        return redirect()->route('admin.location.level2.index', $id)->with('success', 'District has been added');
    }

    /**
     * Show the form for editing the specified level2.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('location', 'edit') == true) {
            $level2 = Location::findorfail($id);
            $location = Location::findorfail($level2->location_id);
            return view('location::admin.location.level2.edit', compact('level2', 'location'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified level2 in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $level2 = Location::where('id', $id)->first();
        $level2->update($data);
        $location = Location::find($level2->location_id);
        return redirect()->route('admin.location.level2.index', $location->id)->with('success', 'District has been updated');
    }

    /**
     * Remove the specified level2 from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('location', 'delete') == true) {
            $district = Location::where('id', $id)->first();
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
            return redirect()->back()->with('success', 'District has been deleted');
        } else {
            return abort(404);
        }
    }
}
