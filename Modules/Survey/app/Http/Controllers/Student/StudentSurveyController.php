<?php

namespace Modules\Survey\Http\Controllers\Student;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Survey\Entities\SurveyInstance;
use Modules\Survey\Entities\SurveyResponse;

class StudentSurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    public function index()
    {
        $student = auth('student')->user();

        $answeredIds = SurveyResponse::where('respondent_type', 'student')
            ->where('respondent_id', $student->id)
            ->pluck('survey_instance_id')
            ->toArray();

        $surveys = SurveyInstance::with('template')
            ->where('status', 1)
            ->where(fn ($q) => $q->where('target_type', 'all')->orWhere('target_type', 'student'))
            ->get()
            ->filter(fn ($instance) => $instance->isOpen())
            ->values();

        return view('survey::student.index', compact('surveys', 'answeredIds'));
    }

    public function take($instanceId)
    {
        $student  = auth('student')->user();
        $instance = SurveyInstance::with('template.questions')->findOrFail($instanceId);

        if (!$instance->isOpen()) {
            return redirect()->route('student.survey.index')->with('failure', 'This survey is no longer open');
        }

        $alreadyResponded = SurveyResponse::where('survey_instance_id', $instanceId)
            ->where('respondent_type', 'student')
            ->where('respondent_id', $student->id)
            ->exists();

        if ($alreadyResponded) {
            return redirect()->route('student.survey.index')->with('info', 'You have already completed this survey');
        }

        return view('survey::student.take', compact('instance'));
    }

    public function submit(Request $request, $instanceId)
    {
        $student  = auth('student')->user();
        $instance = SurveyInstance::with('template.questions')->findOrFail($instanceId);

        if (!$instance->isOpen()) {
            return redirect()->route('student.survey.index')->with('failure', 'Survey is closed');
        }

        $answers = [];
        foreach ($instance->template->questions as $question) {
            $answers[] = [
                'question_id' => $question->id,
                'answer'      => $request->input("answers.{$question->id}", ''),
            ];
        }

        SurveyResponse::create([
            'survey_instance_id' => $instanceId,
            'respondent_type'    => 'student',
            'respondent_id'      => $student->id,
            'answers'            => $answers,
            'submitted_at'       => now(),
        ]);

        return redirect()->route('student.survey.index')->with('success', 'Thank you — survey submitted!');
    }
}
