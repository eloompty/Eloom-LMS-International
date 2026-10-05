<?php

namespace Modules\Template\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\Student;
use Modules\Template\Entities\Template;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the template.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('template', 'view') == true) {
            $templates = Template::orderBy('id', 'desc')->get();
            return view('template::index', compact('templates'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new template.
     * @return Renderable
     */
    public function create()
    {
        return view('template::create');
    }

    /**
     * Store a newly created template in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified template.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified template.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('template::edit');
    }

    /**
     * Update the specified template in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified template from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
