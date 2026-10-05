<?php

namespace Modules\Location\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Location\Entities\Location;

class Level5Controller extends Controller
{
    /**
     * Display a listing of the level 5.
     * @return Renderable
     */
    public function index($id)
    {
        if (checkRole('location', 'view') == true) {
            $level4 = Location::findorfail($id);
            $level5 = Location::where('location_id', $id)->orderBy('id', 'desc')->get();
            return view('location::admin.location.level2.level3.level4.level5.index', compact('level4', 'level5'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new level 5.
     * @return Renderable
     */
    public function create($id)
    {
        if (checkRole('location', 'add') == true) {
            $level4 = Location::findorfail($id);
            return view('location::admin.location.level2.level3.level4.level5.create', compact('level4'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created level 5 in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['location_id'] = $id;
        Location::create($data);
        return redirect()->route('admin.location.level5.index', $id)->with('success', 'Tole has been added');
    }

    /**
     * Show the form for editing the specified level 5.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('location', 'edit') == true) {
            $level5 = Location::findorfail($id);
            $level4 = Location::findorfail($level5->location_id);
            return view('location::admin.location.level2.level3.level4.level5.edit', compact('level4', 'level5'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified level 5 in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $level5 = Location::where('id', $id)->first();
        $level5->update($data);
        $level4 = Location::find($level5->location_id);
        return redirect()->route('admin.location.level5.index', $level4->id)->with('success', 'Tole has been updated');
    }

    /**
     * Remove the specified level 5 from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('location', 'delete') == true) {
            $level5 = Location::where('id', $id)->first();
            $level5->update(['status' => 2]);
            $level4 = Location::find($level5->location_id);
            return redirect()->route('admin.location.level5.index', $level4->id)->with('success', 'Tole has been deleted');
        } else {
            return abort(404);
        }
    }
}
