<?php

namespace Modules\Template\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Template\Entities\Template;
use Modules\Template\Entities\TemplateData;

class TemplateDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the template data.
     * @return Renderable
     */
    public function index($id)
    {
        if (checkRole('template', 'view') == true) {
            $template = Template::findorfail($id);
            return view('template::data.index', compact('template'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified template data in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        foreach ($data as $key => $value) {
            if ($value != NULL) {
                if ($key == 'logo' || $key == 'regards_signature') {
                    $imageName = time() . '.' . request()->$key->getClientOriginalExtension();
                    request()->$key->move(public_path('images/template/'  . $id . '/'  . $key), $imageName);
                    $value = 'images/template/'  . $id . '/'  . $key . '/' . $imageName;
                }
                TemplateData::where('template_id', $id)->where('key', $key)->update(['value' => $value]);
            }
        }
        return redirect()->back()->with('success', 'Template data has been updated');
    }
}
