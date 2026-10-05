<?php

namespace Modules\Setting\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Course\Entities\UnitAssignment;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class SettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the setting.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Setting Menu');
            return view('setting::index');
        } else {
            return abort(404);
        }
    }

    /**
     * Display a listing of the dashboard setting.
     * @return Renderable
     */
    public function dashboardSetting()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Dashboard Setting Menu');
            return view('setting::dashboard.index');
        } else {
            return abort(404);
        }
    }

    /**
     * Display a listing of the online class setting.
     * @return Renderable
     */
    public function onlineclassSetting()
    {
        if (checkRole('setting', 'view') == true) {
            $filename = 'files/TeamsTimezone.json';
            $data = file_get_contents($filename); //data read from json file
            $data = json_decode($data);
            $timeZones = $data->value;
            activityLog('Admin', 'Opened Online Class Setting Menu');
            return view('setting::onlineclass.index', compact('timeZones'));
        } else {
            return abort(404);
        }
    }

    /**
     * Display a listing of the notirfication setting.
     * @return Renderable
     */
    public function notificationSetting()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Notification Setting Menu');
            return view('setting::notification.index');
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified setting in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        if (checkRole('setting', 'edit') == true) {
            activityLog('Admin', 'Settings Updated');
            $data = $request->all();
            unset($data['_token']);
            $blankAllowedSettings = ['student_id_prefix', 'student_id_suffix', 'teacher_id_prefix', 'teacher_id_suffix'];
            foreach ($data as $key => $value) {
                if ($value != NULL || in_array($key, $blankAllowedSettings)) {
                    if ($key == 'logo' || $key == 'fav_icon') {
                        $imageName = time() . '.' . request()->$key->getClientOriginalExtension();
                        request()->$key->move(public_path('/images/' . $key), $imageName);
                        $value = 'images/' . $key . '/' . $imageName;
                    }
                    updateSettingValue($key, $value ?? '');
                }
            }
            return redirect()->back()->with('success', 'Settings updated successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to update settings');
        }
    }

    public function menu()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin',  'Opened Setting Menu Page');
            return view('setting::menu');
        } else {
            return abort(404);
        }
    }

    public function theme()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin',  'Opened Theme Select Page');
            return view('setting::theme.index');
        } else {
            return abort(404);
        }
    }

    public function updateTheme($theme)
    {
        $id = Auth::guard('user')->user()->id;
        User::where('id', $id)->update(['theme' => $theme]);
        return redirect()->back()->with('success', 'Themes updated successfully');
    }

    public function zohoSetting()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Zoho Setting Menu');
            return view('setting::zoho.index');
        } else {
            return abort(404);
        }
    }
}
