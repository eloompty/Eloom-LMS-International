@extends('student::student.layouts.master')
@section('title', 'Student | Take Survey')

@section('header-script')
<style>
.survey-rating-label { display:inline-block; cursor:pointer; }
.survey-rating-label input[type="radio"] { display:none; }
.survey-rating-btn {
    display:inline-flex; align-items:center; justify-content:center;
    width:40px; height:40px; border-radius:8px; border:1.5px solid #d1d5db;
    background:#fff; font-size:13px; font-weight:800; color:#374151;
    cursor:pointer; transition:background .15s, border-color .15s, color .15s;
    user-select:none;
}
.survey-rating-label input[type="radio"]:checked + .survey-rating-btn {
    background:#2563eb; border-color:#2563eb; color:#fff;
    box-shadow:0 3px 8px rgba(37,99,235,.25);
}
.survey-rating-label:hover .survey-rating-btn {
    border-color:#93c5fd; background:#eff6ff; color:#1d4ed8;
}
.survey-mcq-label { display:flex; align-items:center; gap:10px; cursor:pointer; padding:9px 12px; border-radius:7px; border:1.5px solid #e5e7eb; background:#fff; margin-bottom:6px; transition:border-color .15s, background .15s; }
.survey-mcq-label:hover { border-color:#93c5fd; background:#f8fbff; }
.survey-mcq-label input[type="radio"] { display:none; }
.survey-mcq-radio {
    flex:0 0 18px; width:18px; height:18px; border-radius:50%; border:2px solid #d1d5db;
    display:inline-flex; align-items:center; justify-content:center; transition:border-color .15s, background .15s;
}
.survey-mcq-radio::after {
    content:''; display:block; width:8px; height:8px; border-radius:50%; background:transparent; transition:background .15s;
}
.survey-mcq-label input[type="radio"]:checked ~ .survey-mcq-text { color:#1d4ed8; font-weight:800; }
.survey-mcq-label input[type="radio"]:checked ~ .survey-mcq-radio { display:none; }
.survey-mcq-label:has(input[type="radio"]:checked) {
    border-color:#2563eb; background:#eff6ff;
}
.survey-mcq-label:has(input[type="radio"]:checked) .survey-mcq-radio {
    border-color:#2563eb; background:#2563eb;
}
.survey-mcq-label:has(input[type="radio"]:checked) .survey-mcq-radio::after {
    background:#fff;
}
</style>
@endsection

@section('content')
{{-- Page Header --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1>{{ optional($instance->template)->name ?? 'Survey' }}</h1>
            </div>
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.survey.index') }}">Surveys</a></li>
                    <li class="breadcrumb-item active">Take Survey</li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                {{-- Survey intro --}}
                @if(optional($instance->template)->description)
                <div class="dashboard-panel mb-4" style="background:linear-gradient(135deg,#eff6ff 0%,#fff 60%);">
                    <div class="card-body" style="padding:18px 20px;">
                        <div class="d-flex align-items-start" style="gap:12px;">
                            <div style="flex:0 0 36px; width:36px; height:36px; border-radius:8px; background:#dbeafe; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-info-circle" style="color:#2563eb; font-size:15px;"></i>
                            </div>
                            <div>
                                <p class="mb-0" style="font-size:13px; color:#374151; font-weight:700; line-height:1.6;">
                                    {{ $instance->template->description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('student.survey.submit', $instance->id) }}">
                    @csrf

                    @foreach($instance->template->questions as $question)
                    <div class="dashboard-panel mb-3">
                        <div class="card-body" style="padding:20px;">

                            {{-- Question label --}}
                            <p class="mb-3" style="font-size:14px; font-weight:800; color:#111827; line-height:1.5; margin:0 0 16px;">
                                <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:6px; background:#eff6ff; color:#2563eb; font-size:11px; font-weight:800; margin-right:8px; flex-shrink:0; vertical-align:middle;">{{ $loop->iteration }}</span>{{ $question->question }}@if($question->required)<span style="color:#dc2626; margin-left:3px;">*</span>@endif
                            </p>

                            {{-- Text --}}
                            @if($question->type === 'text')
                            <textarea name="answers[{{ $question->id }}]"
                                      class="form-control"
                                      rows="4"
                                      {{ $question->required ? 'required' : '' }}
                                      placeholder="Type your answer here..."
                                      style="border-radius:7px; border-color:#d1d5db; font-size:13px; resize:vertical;"></textarea>

                            {{-- MCQ --}}
                            @elseif($question->type === 'mcq')
                            <div>
                                @foreach($question->options ?? [] as $opt)
                                <label class="survey-mcq-label">
                                    <input type="radio"
                                           name="answers[{{ $question->id }}]"
                                           value="{{ $opt }}"
                                           {{ $question->required ? 'required' : '' }}>
                                    <span class="survey-mcq-radio"></span>
                                    <span class="survey-mcq-text" style="font-size:13px; font-weight:700; color:#374151;">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Likert / Rating / NPS --}}
                            @elseif(in_array($question->type, ['likert', 'rating', 'nps']))
                            @php
                                $max = $question->type === 'likert' ? 5 : 10;
                                $min = $question->type === 'nps'    ? 0 : 1;
                                $isNps = $question->type === 'nps';
                            @endphp
                            @if($isNps)
                            <div class="d-flex justify-content-between mb-1" style="padding:0 2px;">
                                <span style="font-size:11px; font-weight:700; color:#6b7280;">Not at all likely</span>
                                <span style="font-size:11px; font-weight:700; color:#6b7280;">Extremely likely</span>
                            </div>
                            @endif
                            <div class="d-flex flex-wrap" style="gap:6px;">
                                @for($i = $min; $i <= $max; $i++)
                                <label class="survey-rating-label">
                                    <input type="radio"
                                           name="answers[{{ $question->id }}]"
                                           value="{{ $i }}"
                                           {{ $question->required ? 'required' : '' }}>
                                    <span class="survey-rating-btn">{{ $i }}</span>
                                </label>
                                @endfor
                            </div>
                            @if($question->type === 'likert')
                            <div class="d-flex justify-content-between mt-1" style="padding:0 2px;">
                                <span style="font-size:11px; font-weight:700; color:#6b7280;">Strongly disagree</span>
                                <span style="font-size:11px; font-weight:700; color:#6b7280;">Strongly agree</span>
                            </div>
                            @endif

                            @endif
                        </div>
                    </div>
                    @endforeach

                    {{-- Actions --}}
                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-4 mb-4" style="gap:10px;">
                        <a href="{{ route('student.survey.index') }}" class="panel-action">
                            <i class="fas fa-arrow-left"></i> Back to Surveys
                        </a>
                        <button type="submit" class="btn btn-primary"
                                style="font-weight:800; font-size:14px; border-radius:7px; padding:9px 24px;">
                            <i class="fas fa-paper-plane mr-1"></i> Submit Survey
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection
