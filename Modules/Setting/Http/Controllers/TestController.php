<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('setting::test');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $imageName = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('/images/tests'), $imageName);
        return redirect()->back()->with('success', 'Image uploaded successfully successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function rdirectLogin(Request $request)
    {
        $data = $request->all();
        $url = env('RIDERCT_BASE_URL') . '/check/token?domain=' . $data['domain'] . '&key=' . $data['key'];
        $response = Http::get($url);
        if ($response->successful()) {
            if (Auth::guard('user')->loginUsingId(1)) {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }
}
