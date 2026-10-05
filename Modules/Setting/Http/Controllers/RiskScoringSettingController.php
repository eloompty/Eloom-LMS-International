<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\RiskScoringSetting;

class RiskScoringSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the risk scoring setting.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('setting', 'view') == true) {
            activityLog('Admin', 'Opened Risk Scoring Setting Menu');

            $settings = RiskScoringSetting::first();
            if (!$settings) {
                $settings = new RiskScoringSetting([
                    'attendance_weight' => 30,
                    'assignment_weight' => 25,
                    'grade_weight' => 20,
                    'fee_weight' => 15,
                    'engagement_weight' => 10,
                ]);
            }

            return view('setting::risk_scoring.index', compact('settings'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the risk scoring settings in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        if (checkRole('setting', 'edit') == true) {
            $request->validate([
                'attendance_weight' => 'required|integer|min:0|max:100',
                'assignment_weight' => 'required|integer|min:0|max:100',
                'grade_weight' => 'required|integer|min:0|max:100',
                'fee_weight' => 'required|integer|min:0|max:100',
                'engagement_weight' => 'required|integer|min:0|max:100',
            ]);

            $total = $request->attendance_weight +
                $request->assignment_weight +
                $request->grade_weight +
                $request->fee_weight +
                $request->engagement_weight;

            if ($total !== 100) {
                return redirect()->back()->with('failure', 'The sum of all weights must equal 100%. Current sum: ' . $total . '%');
            }

            activityLog('Admin', 'Risk Scoring Settings Updated');

            $settings = RiskScoringSetting::first();
            if ($settings) {
                $settings->update($request->only([
                    'attendance_weight',
                    'assignment_weight',
                    'grade_weight',
                    'fee_weight',
                    'engagement_weight',
                ]));
            } else {
                RiskScoringSetting::create($request->only([
                    'attendance_weight',
                    'assignment_weight',
                    'grade_weight',
                    'fee_weight',
                    'engagement_weight',
                ]));
            }

            return redirect()->back()->with('success', 'Risk Scoring Settings updated successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to update settings');
        }
    }
}
