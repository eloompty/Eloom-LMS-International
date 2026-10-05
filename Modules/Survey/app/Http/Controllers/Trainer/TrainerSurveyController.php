<?php

namespace Modules\Survey\Http\Controllers\Trainer;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyInstance;
use Modules\Intake\Entities\Intake;

class TrainerSurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    public function templateIndex()
    {
        $trainer   = auth('trainer')->user();
        $templates = SurveyTemplate::where('status', 1)
            ->where(fn ($q) => $q->where('created_by_type', 'user')
                ->orWhere(fn ($q2) => $q2->where('created_by_type', 'trainer')->where('created_by_id', $trainer->id)))
            ->orderByDesc('id')
            ->get();

        $assignedIntakeCourseIds = $trainer->intake()->pluck('intake_course_id')->unique();
        $intakes = Intake::whereHas('intakeCourses', function ($q) use ($assignedIntakeCourseIds) {
            $q->whereIn('id', $assignedIntakeCourseIds);
        })->orderBy('name')->get();

        return view('survey::trainer.template.index', compact('templates', 'intakes'));
    }

    public function templateCreate()
    {
        return view('survey::trainer.template.create');
    }

    public function templateStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $trainer = auth('trainer')->user();

        $template = SurveyTemplate::create([
            'name'            => $request->name,
            'description'     => $request->description,
            'is_anonymous'    => $request->boolean('is_anonymous'),
            'created_by_type' => 'trainer',
            'created_by_id'   => $trainer->id,
            'status'          => 1,
        ]);

        if ($request->has('questions')) {
            foreach ($request->questions as $i => $q) {
                if (empty($q['question'])) {
                    continue;
                }
                SurveyQuestion::create([
                    'survey_template_id' => $template->id,
                    'question'           => $q['question'],
                    'type'               => $q['type'] ?? 'text',
                    'options'            => !empty($q['options']) ? array_filter(explode("\n", $q['options'])) : null,
                    'sequence'           => $i,
                    'required'           => !empty($q['required']),
                ]);
            }
        }

        return redirect()->route('trainer.survey.template.index')->with('success', 'Survey template created');
    }

    public function dispatch(Request $request)
    {
        $request->validate([
            'survey_template_id' => 'required|exists:survey_templates,id',
            'target_type'        => 'required|in:all,intake,course',
        ]);

        $trainer = auth('trainer')->user();

        SurveyInstance::create([
            'survey_template_id' => $request->survey_template_id,
            'target_type'        => $request->target_type,
            'target_id'          => $request->target_id,
            'dispatch_at'        => now(),
            'closes_at'          => $request->closes_at,
            'created_by_id'      => $trainer->id,
            'created_by_type'    => 'trainer',
            'status'             => 1,
        ]);

        return redirect()->route('trainer.survey.template.index')->with('success', 'Survey dispatched to students');
    }
}
