<?php

namespace Modules\Location\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Location\Entities\Location;

class Level3Controller extends Controller
{
    /**
     * Display a listing of the level3.
     * @return Renderable
     */
    public function index($id)
    {
        if (checkRole('location', 'view') == true) {
            $level2 = Location::findorfail($id);
            $level3 = Location::where('location_id', $id)->orderBy('id', 'desc')->get();
            return view('location::admin.location.level2.level3.index', compact('level3', 'level2'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new level3.
     * @return Renderable
     */
    public function create($id)
    {
        if (checkRole('location', 'add') == true) {
            $level2 = Location::findorfail($id);
            return view('location::admin.location.level2.level3.create', compact('level2'));
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
        $local_body = Location::create($data);
        for ($i = 1; $i < 16; $i++) {
            Location::create([
                'name' => 'Ward no. ' . $i,
                'location_id' => $local_body->id,
                'status' => $data['status']
            ]);
        }
        return redirect()->route('admin.location.level3.index', $id)->with('success', 'Local Body has been added');
    }

    /**
     * Show the form for editing the specified level3.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('location', 'edit') == true) {
            $level3 = Location::findorfail($id);
            $level2 = Location::findorfail($level3->location_id);
            return view('location::admin.location.level2.level3.edit', compact('level2', 'level3'));
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
        $level3 = Location::where('id', $id)->first();
        $level3->update($data);
        $level2 = Location::find($level3->location_id);
        return redirect()->route('admin.location.level3.index', $level2->id)->with('success', 'Local Body has been updated');
    }

    /**
     * Remove the specified level2 from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('location', 'delete') == true) {
            $local_body = Location::where('id', $id)->first();
            $local_body->update(['status' => 2]);
            if (Location::where('location_id', $id)->count() > 0) {
                $wards = $local_body->locations;
                foreach ($wards as $ward) {
                    $ward->update(['status' => 2]);
                    $toles = $ward->locations;
                    foreach ($toles as $key => $value) {
                        $value->update(['status' => 2]);
                    }
                }
            }
            return redirect()->back()->with('success', 'Local Body has been deleted');
        } else {
            return abort(404);
        }
    }
}
