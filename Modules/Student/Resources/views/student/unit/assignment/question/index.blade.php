@extends('student::student.layouts.master')
@section('title', 'Student | Question Assignment')

@section('content')
<style>
    .submission-page { color: #233142; }
    .submission-page .content-header { padding-bottom: 8px; }
    .submission-hero { align-items: flex-start; display: flex; gap: 18px; justify-content: space-between; }
    .submission-title h1 { color: #17202a; font-size: 26px; font-weight: 700; letter-spacing: 0; margin: 0 0 6px; }
    .submission-title p { color: #5f6f82; font-size: 14px; margin: 0; }
    .submission-breadcrumb { background: transparent; font-size: 13px; margin: 0; padding: 0; }
    .submission-shell { display: grid; gap: 18px; grid-template-columns: minmax(0, 1fr) 300px; }
    .submission-main, .submission-sidebar, .question-panel { background: #ffffff; border: 1px solid #dce3ea; border-radius: 8px; box-shadow: 0 10px 24px rgba(20,32,50,.05); }
    .submission-main-header { align-items: center; border-bottom: 1px solid #e7edf3; display: flex; gap: 12px; justify-content: space-between; padding: 16px 18px; }
    .submission-main-header h2, .submission-sidebar h2 { color: #17202a; font-size: 18px; font-weight: 700; letter-spacing: 0; margin: 0; }
    .submission-main-header span { color: #69798b; font-size: 13px; }
    .question-list { display: grid; gap: 14px; padding: 18px; }
    .question-panel { box-shadow: none; overflow: hidden; }
    .question-panel-header { align-items: flex-start; background: #f7f9fb; border-bottom: 1px solid #e7edf3; display: grid; gap: 12px; grid-template-columns: auto minmax(0, 1fr); padding: 14px 16px; }
    .question-number { align-items: center; background: #1f6feb; border-radius: 999px; color: #ffffff; display: inline-flex; font-size: 13px; font-weight: 700; height: 30px; justify-content: center; min-width: 30px; padding: 0 9px; }
    .question-text { color: #1f2b38; font-size: 15px; font-weight: 650; line-height: 1.45; margin: 3px 0 0; overflow-wrap: anywhere; }
    .question-panel-body { padding: 16px; }
    .field-row { margin: 0; }
    .field-label { color: #4d5f73; display: flex; font-size: 12px; font-weight: 700; justify-content: space-between; letter-spacing: .02em; margin-bottom: 8px; text-transform: uppercase; }
    .answer-input { background: #fbfcfe; border: 1px solid #d7e0ea; border-radius: 6px; color: #22303f; font-size: 14px; line-height: 1.55; min-height: 190px; padding: 12px; resize: vertical; }
    .answer-input:focus { border-color: #1f6feb; box-shadow: 0 0 0 .2rem rgba(31,111,235,.12); }
    .answer-meta { color: #6a7b8f; display: flex; font-size: 12px; justify-content: flex-end; margin-top: 7px; }
    .draft-check-panel { background: #f8fafc; border: 1px solid #dce5ee; border-radius: 8px; margin-top: 14px; padding: 12px; }
    .draft-check-header { align-items: center; display: flex; gap: 8px; justify-content: space-between; margin-bottom: 10px; }
    .draft-check-header strong { color: #304258; font-size: 13px; }
    .draft-check-header span { color: #6a7b8f; font-size: 12px; }
    .draft-check-actions { display: grid; gap: 8px; grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .draft-check-actions .btn { align-items: center; display: inline-flex; gap: 7px; justify-content: center; min-height: 36px; white-space: normal; }
    .draft-check-status { display: block; margin-top: 9px; }
    .integrity-score { align-items: center; border: 1px solid #dee2e6; border-radius: 6px; display: flex; height: 74px; justify-content: center; margin-bottom: 10px; }
    .integrity-score strong { font-size: 28px; line-height: 1; }
    .integrity-result-meta { color: #6c757d; font-size: 13px; }
    .integrity-sources { max-height: 220px; overflow-y: auto; }
    .submission-sidebar { align-self: start; position: sticky; top: 82px; }
    .sidebar-section { border-bottom: 1px solid #e7edf3; padding: 16px; }
    .sidebar-section:last-child { border-bottom: 0; }
    .meta-list { display: grid; gap: 10px; margin-top: 12px; }
    .meta-item { display: flex; gap: 12px; justify-content: space-between; }
    .meta-item span { color: #6a7b8f; font-size: 13px; }
    .meta-item strong { color: #253447; font-size: 13px; font-weight: 700; text-align: right; }
    .stat-grid { display: grid; gap: 10px; grid-template-columns: repeat(2, minmax(0, 1fr)); margin-top: 12px; }
    .stat-box { background: #f8fafc; border: 1px solid #e0e7ef; border-radius: 6px; padding: 10px; }
    .stat-box strong { color: #17202a; display: block; font-size: 22px; line-height: 1.1; }
    .stat-box span { color: #6a7b8f; display: block; font-size: 12px; margin-top: 4px; }
    .submission-actions { display: grid; gap: 10px; }
    .submission-actions .btn { min-height: 40px; }
    .empty-state { color: #6a7b8f; padding: 28px 18px; text-align: center; }
    @media (max-width: 1199.98px) {
        .submission-shell { grid-template-columns: minmax(0, 1fr); }
        .submission-sidebar { order: -1; position: static; }
        .sidebar-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .sidebar-section { border-bottom: 0; border-right: 1px solid #e7edf3; }
        .sidebar-section:last-child { border-right: 0; }
    }
    @media (max-width: 991.98px) {
        .submission-hero, .submission-main-header { align-items: flex-start; flex-direction: column; }
        .sidebar-grid { grid-template-columns: minmax(0, 1fr); }
        .sidebar-section { border-bottom: 1px solid #e7edf3; border-right: 0; }
        .sidebar-section:last-child { border-bottom: 0; }
    }
    @media (max-width: 575.98px) {
        .submission-title h1 { font-size: 22px; }
        .question-list, .question-panel-body, .submission-main-header { padding: 14px; }
        .stat-grid { grid-template-columns: minmax(0, 1fr); }
        .draft-check-actions { grid-template-columns: minmax(0, 1fr); }
        .answer-input { min-height: 160px; }
    }
</style>

<div class="submission-page">
    @if ($text = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>{{ $text }}</strong>
    </div>
    @elseif ($text = Session::get('failure'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>{{ $text }}</strong>
    </div>
    @endif

    <section class="content-header">
        <div class="container-fluid">
            <div class="submission-hero">
                <div class="submission-title">
                    <h1>Question Assignment</h1>
                    <p>{{ $assignment->name }}</p>
                </div>
                <ol class="breadcrumb submission-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntakeUnit->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntakeUnit->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.unit.index', $studentIntakeUnit->student_intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.assignment.index', $studentIntakeUnit->intake_unit_id) }}">Assignments</a></li>
                    <li class="breadcrumb-item active">Question Assignment</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form id="submitQuestionAssignment" method="POST" action="{{ route('student.assignment.question.submit.index', [$assignment->id, $studentIntakeUnit->id]) }}">
                @csrf
                <input type="hidden" name="assignment_resubmission_id" value="{{ $resubmission_id }}">

                <div class="submission-shell">
                    <main class="submission-main">
                        <div class="submission-main-header">
                            <div>
                                <h2>Answers</h2>
                                <span>{{ count($questions) }} question{{ count($questions) == 1 ? '' : 's' }}</span>
                            </div>
                            <a href="{{ route('student.assignment.index', $studentIntakeUnit->intake_unit_id) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Assignments
                            </a>
                        </div>

                        <div class="question-list">
                            @forelse($questions as $question)
                            <article class="question-panel">
                                <div class="question-panel-header">
                                    <span class="question-number">{{ $no++ }}</span>
                                    <p class="question-text">{{ $question->question }}</p>
                                </div>
                                <div class="question-panel-body">
                                    <div class="form-group field-row">
                                        <label class="field-label" for="answer-{{ $question->id }}">
                                            <span>Answer</span>
                                            <span class="answer-count" data-answer-count-for="{{ $question->id }}">0 words</span>
                                        </label>
                                        <textarea id="answer-{{ $question->id }}" name="answers[{{ $question->id }}]" class="form-control answer-input" placeholder="Enter your answer" required></textarea>
                                        <div class="answer-meta">
                                            <span class="character-count" data-character-count-for="{{ $question->id }}">0 characters</span>
                                        </div>
                                        @if($integrityChecks['ai_detection'] || $integrityChecks['paraphraser'] || $integrityChecks['plagiarism'])
                                        <div class="draft-check-panel">
                                            <div class="draft-check-header">
                                                <strong>Pre-submit Checks</strong>
                                                <span>Review results before submitting</span>
                                            </div>
                                            <div class="draft-check-actions">
                                                @if($integrityChecks['ai_detection'])
                                                <button type="button" class="btn btn-outline-primary btn-sm draft-check-btn" data-check-type="ai_detection" data-question-id="{{ $question->id }}">
                                                    <i class="fas fa-robot"></i> AI Bot
                                                </button>
                                                @endif
                                                @if($integrityChecks['paraphraser'])
                                                <button type="button" class="btn btn-outline-primary btn-sm draft-check-btn" data-check-type="paraphraser" data-question-id="{{ $question->id }}">
                                                    <i class="fas fa-random"></i> Paraphrasing
                                                </button>
                                                @endif
                                                @if($integrityChecks['plagiarism'])
                                                <button type="button" class="btn btn-outline-primary btn-sm draft-check-btn" data-check-type="plagiarism" data-question-id="{{ $question->id }}">
                                                    <i class="fas fa-search"></i> Plagiarism
                                                </button>
                                                @endif
                                            </div>
                                            <small class="form-text text-muted draft-check-status" data-question-id="{{ $question->id }}"></small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </article>
                            @empty
                            <div class="empty-state">No questions are available for this assignment.</div>
                            @endforelse
                        </div>
                    </main>

                    <aside class="submission-sidebar">
                        <div class="sidebar-grid">
                            <section class="sidebar-section">
                                <h2>Assignment</h2>
                                <div class="meta-list">
                                    <div class="meta-item">
                                        <span>Name</span>
                                        <strong>{{ $assignment->name }}</strong>
                                    </div>
                                    <div class="meta-item">
                                        <span>Type</span>
                                        <strong>{{ ucfirst($assignment->type) }}</strong>
                                    </div>
                                    <div class="meta-item">
                                        <span>Resubmission</span>
                                        <strong>{{ $resubmission_id > 0 ? 'Yes' : 'No' }}</strong>
                                    </div>
                                </div>
                            </section>

                            <section class="sidebar-section">
                                <h2>Progress</h2>
                                <div class="stat-grid">
                                    <div class="stat-box">
                                        <strong>{{ count($questions) }}</strong>
                                        <span>Questions</span>
                                    </div>
                                    <div class="stat-box">
                                        <strong id="answered-count">0</strong>
                                        <span>Answered</span>
                                    </div>
                                </div>
                            </section>

                            <section class="sidebar-section">
                                <div class="submission-actions">
                                    <button type="submit" class="btn btn-primary" @if(count($questions) == 0) disabled @endif>
                                        <i class="fas fa-paper-plane"></i> Submit Answers
                                    </button>
                                    <a href="{{ route('student.assignment.index', $studentIntakeUnit->intake_unit_id) }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </section>
                        </div>
                    </aside>
                </div>
            </form>
        </div>
    </section>
</div>

<div class="modal fade" id="draftCheckResultModal" tabindex="-1" role="dialog" aria-labelledby="draftCheckResultTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="draftCheckResultTitle">Draft Check Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="draft-check-result-body">
                <div class="text-muted">No result available yet.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm Review</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        const draftCheckLabels = {
            ai_detection: 'AI Bot Checking',
            paraphraser: 'Paraphrasing Check',
            plagiarism: 'Plagiarism Test'
        };

        function updateAnswerMetrics(textarea) {
            let value = $(textarea).val().trim();
            let questionId = textarea.id.replace('answer-', '');
            let words = value === '' ? 0 : value.split(/\s+/).length;
            $('[data-answer-count-for="' + questionId + '"]').text(words + ' word' + (words === 1 ? '' : 's'));
            $('[data-character-count-for="' + questionId + '"]').text($(textarea).val().length + ' character' + ($(textarea).val().length === 1 ? '' : 's'));
        }

        function updateAnsweredCount() {
            let answered = 0;
            $('.answer-input').each(function() {
                if ($(this).val().trim() !== '') answered++;
            });
            $('#answered-count').text(answered);
        }

        $('.answer-input').each(function() {
            this.setAttribute('style', 'height:' + Math.max(this.scrollHeight, 190) + 'px;overflow-y:hidden;');
            updateAnswerMetrics(this);
        }).on('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.max(this.scrollHeight, 190) + 'px';
            updateAnswerMetrics(this);
            updateAnsweredCount();
        });

        $('#submitQuestionAssignment').validate({
            ignore: [],
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback d-block');
                element.closest('.field-row').append(error);
            },
            highlight: function(element) { $(element).addClass('is-invalid'); },
            unhighlight: function(element) { $(element).removeClass('is-invalid'); }
        });

        $('.answer-input').each(function() {
            $(this).rules('add', { required: true, messages: { required: 'Please enter an answer.' } });
        });

        $('.draft-check-btn').on('click', function() {
            runDraftCheck($(this).data('check-type'), $(this).data('question-id'), $(this));
        });

        updateAnsweredCount();

        function runDraftCheck(checkType, questionId, button) {
            let textarea = $('#answer-' + questionId);
            let text = textarea.val().trim();
            let status = $('.draft-check-status[data-question-id="' + questionId + '"]');
            let buttons = button.closest('.draft-check-panel').find('.draft-check-btn');
            let originalButtonHtml = button.html();

            if (text.length < 20) {
                status.removeClass('text-muted text-success').addClass('text-danger').text('Please enter at least 20 characters before running this check.');
                return;
            }

            buttons.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin"></i> Checking...');
            status.removeClass('text-danger text-success').addClass('text-muted').text('Running ' + draftCheckLabels[checkType] + '...');

            $.ajax({
                url: "{{ route('student.unit.assignment.question.integrity-check', [$assignment->id, $studentIntakeUnit->id]) }}",
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { _token: "{{ csrf_token() }}", check_type: checkType, question_id: questionId, text: text },
                success: function(response) {
                    status.removeClass('text-muted text-danger').addClass('text-success').text(response.title + ' completed. Review the result before submitting.');
                    showDraftCheckResult(response);
                },
                error: function(xhr) {
                    let message = 'Failed to run the check.';
                    if (xhr.responseJSON && xhr.responseJSON.message) message = xhr.responseJSON.message;
                    status.removeClass('text-muted text-success').addClass('text-danger').text(message);
                },
                complete: function() {
                    buttons.prop('disabled', false);
                    button.html(originalButtonHtml);
                }
            });
        }

        function showDraftCheckResult(result) {
            $('#draftCheckResultTitle').text(result.title || 'Draft Check Result');
            $('#draft-check-result-body').html(buildDraftCheckResultHtml(result));
            if ($.fn.modal) {
                $('#draftCheckResultModal').modal('show');
                return;
            }
            $('#draftCheckResultModal').addClass('show').show().attr('aria-modal', 'true').removeAttr('aria-hidden');
            $('body').addClass('modal-open').append('<div class="modal-backdrop fade show draft-check-backdrop"></div>');
        }

        $(document).on('click', '#draftCheckResultModal [data-dismiss="modal"], .draft-check-backdrop', function() {
            if ($.fn.modal) { $('#draftCheckResultModal').modal('hide'); return; }
            $('#draftCheckResultModal').removeClass('show').hide().removeAttr('aria-modal').attr('aria-hidden', 'true');
            $('.draft-check-backdrop').remove();
            $('body').removeClass('modal-open');
        });

        function buildDraftCheckResultHtml(result) {
            let score = result.score === undefined || result.score === null ? '-' : result.score + '%';
            let metrics = result.metrics || {};
            let sources = result.sources || [];
            let metricRows = Object.keys(metrics).map(function(key) {
                return '<tr><th style="width:45%;">' + escapeHtml(key) + '</th><td>' + escapeHtml(metrics[key]) + '</td></tr>';
            }).join('');
            let sourceRows = sources.length ? sources.map(function(source) {
                if (typeof source === 'string') source = { title: source };
                let title = escapeHtml(source.title || 'Matched source');
                let snippet = source.snippet ? '<div class="text-muted small">' + escapeHtml(source.snippet) + '</div>' : '';
                let studentName = source.student_name ? '<div class="small"><strong>Matched student:</strong> ' + escapeHtml(source.student_name) + '</div>' : '';
                let scoreText = source.score ? '<div class="small">Similarity: ' + escapeHtml(source.score) + '%</div>' : '';
                return '<li class="mb-2">' + title + studentName + snippet + scoreText + '</li>';
            }).join('') : '<li class="text-muted">No matching sources returned.</li>';
            let note = result.note ? '<div class="alert alert-warning mt-3 mb-0">' + escapeHtml(result.note) + '</div>' : '';

            return '<div class="row">' +
                '<div class="col-md-3">' +
                    '<div class="integrity-score"><div class="text-center"><strong>' + score + '</strong><div>' + escapeHtml(result.status || '') + '</div></div></div>' +
                    '<div class="integrity-result-meta">Checked at: ' + escapeHtml(result.checked_at || '-') + '<br>Provider: ' + escapeHtml(result.provider || '-') + '</div>' +
                '</div>' +
                '<div class="col-md-9">' +
                    '<p>' + escapeHtml(result.summary || 'Check completed.') + '</p>' +
                    '<table class="table table-sm table-bordered">' + metricRows + '</table>' +
                    '<h6>Matches / Sources</h6>' +
                    '<ul class="integrity-sources pl-3">' + sourceRows + '</ul>' +
                    note +
                '</div>' +
            '</div>';
        }

        function escapeHtml(value) {
            return String(value === undefined || value === null ? '' : value)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }
    });
</script>
@endsection
