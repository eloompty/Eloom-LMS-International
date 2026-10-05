<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Report\Entities\ReportTemplate;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the report template.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('report_template', 'view') == true) {
            activityLog('Admin', 'Opened Report Template Menu');
            $templates = ReportTemplate::orderBy('id', 'desc')->get();
            return view('report::template.index', compact('templates'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new report template.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('report_template', 'add') == true) {
            activityLog('Admin', 'Opened Report Template Add Page');
            return view('report::template.create');
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created report template in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $imageName = time() . '.' . request()->logo->getClientOriginalExtension();
        request()->logo->move(public_path('/images/reports/templates'), $imageName);
        $data['logo']   = 'images/reports/templates/' . $imageName;
        $data['header'] = $data['header'] ?? '';
        $data['footer'] = $data['footer'] ?? '';
        ReportTemplate::create($data);
        activityLog('Admin', $data['name'] .' report template created');
        return redirect()->route('admin.report.template.index')->with('success', 'Template has been added successfully');
    }

    /**
     * Show the specified report template.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('report::show');
    }

    /**
     * Show the form for editing the specified report template.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('report_template', 'add') == true) {
            activityLog('Admin', 'Opened Report Template Edit Page');
            $template = ReportTemplate::findorfail($id);
            return view('report::template.edit', compact('template'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified report template in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        if ($request->hasFile('logo')) {
            $imageName = time() . '.' . request()->logo->getClientOriginalExtension();
            request()->logo->move(public_path('/images/reports/templates'), $imageName);
            $data['logo'] = 'images/reports/templates/' . $imageName;
        } else {
            unset($data['logo']);
        }
        $data['header'] = $data['header'] ?? '';
        $data['footer'] = $data['footer'] ?? '';
        ReportTemplate::where('id', $id)->update($data);
        activityLog('Admin', $data['name'] .' report template updated');
        return redirect()->route('admin.report.template.index')->with('success', 'Template has been updated successfully');
    }

    /**
     * Remove the specified report template from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
