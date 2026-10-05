<?php

namespace Modules\Location\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Location\Entities\Location;

class Level4Controller extends Controller
{
    /**
     * Display a listing of the level4.
     * @return Renderable
     */
    public function index($id)
    {
        if (checkRole('location', 'view') == true) {
            $level3 = Location::findorfail($id);
            $level4 = Location::where('location_id', $id)->orderBy('id', 'desc')->get();
            return view('location::admin.location.level2.level3.level4.index', compact('level3', 'level4'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new level4.
     * @return Renderable
     */
    public function create($id)
    {
        if (checkRole('location', 'add') == true) {
            $level3 = Location::findorfail($id);
            return view('location::admin.location.level2.level3.level4.create', compact('level3'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created level4 in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['location_id'] = $id;
        Location::create($data);
        return redirect()->route('admin.location.level4.index', $id)->with('success', 'Ward has been added');
    }

    /**
     * Show the form for editing the specified level4.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('location', 'edit') == true) {
            $level4 = Location::findorfail($id);
            $level3 = Location::findorfail($level4->location_id);
            return view('location::admin.location.level2.level3.level4.edit', compact('level3', 'level4'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified level4 in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $level4 = Location::where('id', $id)->first();
        $level4->update($data);
        $level3 = Location::find($level4->location_id);
        return redirect()->route('admin.location.level4.index', $level3->id)->with('success', 'Ward has been updated');
    }

    /**
     * Remove the specified level4 from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('location', 'delete') == true) {
            $ward = Location::where('id', $id)->first();
            $ward->update(['status' => 2]);
            if (Location::where('location_id', $id)->count() > 0) {
                $toles = $ward->locations;
                foreach ($toles as $key => $value) {
                    $value->update(['status' => 2]);
                }
            }
            return redirect()->back()->with('success', 'Tole has been deleted');
        } else {
            return abort(404);
        }
    }
}
