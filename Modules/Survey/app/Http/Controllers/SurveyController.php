<?php

namespace Modules\Survey\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyInstance;
use Modules\Survey\Entities\SurveyResponse;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    // ── Templates ─────────────────────────────────────────────────────────────

    public function templateIndex()
    {
        $templates = SurveyTemplate::where('status', 1)->orderByDesc('id')->get();
        return view('survey::admin.template.index', compact('templates'));
    }

    public function templateCreate()
    {
        return view('survey::admin.template.create');
    }

    public function templateStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $template = SurveyTemplate::create([
            'name'             => $request->name,
            'description'      => $request->description,
            'is_anonymous'     => $request->boolean('is_anonymous'),
            'created_by_type'  => 'user',
            'created_by_id'    => auth('user')->id(),
            'status'           => 1,
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

        activityLog('Admin', "Survey template created: {$template->name}");
        return redirect()->route('admin.survey.template.index')->with('success', 'Survey template created');
    }

    public function templateDestroy($id)
    {
        SurveyTemplate::findOrFail($id)->update(['status' => 2]);
        activityLog('Admin', "Survey template_id: {$id} deleted");
        return redirect()->back()->with('success', 'Template deleted');
    }

    // ── Instances ─────────────────────────────────────────────────────────────

    public function instanceIndex()
    {
        $instances = SurveyInstance::with('template')->where('status', 1)->orderByDesc('id')->get();
        return view('survey::admin.instance.index', compact('instances'));
    }

    public function instanceCreate()
    {
        $templates = SurveyTemplate::where('status', 1)->get();
        return view('survey::admin.instance.create', compact('templates'));
    }

    public function instanceStore(Request $request)
    {
        $request->validate([
            'survey_template_id' => 'required|exists:survey_templates,id',
            'target_type'        => 'required|in:all,intake,course,event',
        ]);

        SurveyInstance::create([
            'survey_template_id' => $request->survey_template_id,
            'target_type'        => $request->target_type,
            'target_id'          => $request->target_id,
            'dispatch_at'        => $request->dispatch_at,
            'closes_at'          => $request->closes_at,
            'created_by_id'      => auth('user')->id(),
            'created_by_type'    => 'user',
            'status'             => 1,
        ]);

        activityLog('Admin', "Survey instance dispatched for template_id: {$request->survey_template_id}");
        return redirect()->route('admin.survey.instance.index')->with('success', 'Survey dispatched');
    }

    // ── Results ───────────────────────────────────────────────────────────────

    public function results($instanceId)
    {
        $instance  = SurveyInstance::with('template.questions')->findOrFail($instanceId);
        $responses = SurveyResponse::where('survey_instance_id', $instanceId)->get();

        // Aggregate answers per question
        $aggregated = [];
        foreach ($instance->template->questions as $q) {
            $answers = $responses->pluck('answers')
                ->map(fn ($a) => collect($a)->firstWhere('question_id', $q->id)['answer'] ?? null)
                ->filter()
                ->values();
            $aggregated[$q->id] = [
                'question' => $q->question,
                'type'     => $q->type,
                'answers'  => $answers,
                'count'    => $answers->count(),
                'avg'      => in_array($q->type, ['likert','rating','nps']) && $answers->count() > 0 ? round($answers->avg(), 1) : null,
            ];
        }

        return view('survey::admin.results', compact('instance', 'responses', 'aggregated'));
    }
}
