@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Questions')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Questions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.index', $trainerIntake->intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.submission.index', [$submission->assignment_id, $trainerIntake->id]) }}">Submissions</a></li>
                    <li class="breadcrumb-item active">Questions</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $submission->assignment->name }}'s Answer Submissions</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('trainer.submission.question.remarks', [$submission->id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-body">
                                    @foreach($answers as $answer)
                                    <div class="card mb-3">
                                        <div class="card-header mcq-question">{{ $no++}}. {{ $answer->assignmentQuestion->question }}</div>

                                        <div class="card-body">
                                            <label for="answer">Answer</label>
                                            <textarea class="form-control" readonly>{{ $answer->answer }}</textarea>
                                        </div>

                                        <div class="card-body pb-1">
                                            <div class="d-flex align-items-center flex-wrap gap-1 mb-2">
                                                <strong class="mr-2">Originality Checks:</strong>
                                                <button type="button" class="btn btn-outline-primary btn-sm integrity-check-btn mr-1"
                                                    data-check-type="ai_detection" data-answer-id="{{ $answer->id }}">
                                                    <i class="fas fa-robot"></i> AI Detection
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm integrity-check-btn mr-1"
                                                    data-check-type="paraphraser" data-answer-id="{{ $answer->id }}">
                                                    <i class="fas fa-random"></i> Paraphraser
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm integrity-check-btn mr-1"
                                                    data-check-type="plagiarism" data-answer-id="{{ $answer->id }}">
                                                    <i class="fas fa-search"></i> Plagiarism
                                                </button>
                                                <button type="button" class="btn btn-info btn-sm view-integrity-result" data-answer-id="{{ $answer->id }}" style="display:none;">
                                                    <i class="fas fa-eye"></i> Result View
                                                </button>
                                            </div>
                                            <small class="form-text text-muted integrity-check-status" data-answer-id="{{ $answer->id }}"></small>
                                        </div>

                                        <div class="card-body">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control" name="answers[{{ $answer->id }}]">{{ $answer->remarks }}</textarea>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<div class="modal fade" id="integrityResultModal" tabindex="-1" role="dialog" aria-labelledby="integrityResultTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="integrityResultTitle">Answer Check Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="integrity-result-body">
                <div class="text-muted">No result available yet.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<form id="integrity-source-export-form" method="POST" action="{{ route('trainer.submission.pdf.integrity-source.export', $submission->id) }}" target="_blank" style="display:none;">
    @csrf
    <input type="hidden" name="title" id="export-source-title">
    <input type="hidden" name="link" id="export-source-link">
    <input type="hidden" name="snippet" id="export-source-snippet">
    <input type="hidden" name="score" id="export-source-score">
</form>

@endsection
@section('scripts')
<script>
    $('textarea').each(function() {
        this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
    }).on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    let latestIntegrityResult = null;
    let latestIntegritySources = [];
    let integrityResultsByAnswer = {};

    const integrityCheckLabels = {
        ai_detection: 'AI Detection',
        paraphraser: 'Paraphraser Checking',
        plagiarism: 'Plagiarism Checking'
    };

    $('.integrity-check-btn').on('click', function() {
        runIntegrityCheck($(this).data('check-type'), $(this).data('answer-id'), $(this));
    });

    $(document).on('click', '.view-integrity-result', function() {
        let answerId = $(this).data('answer-id');
        let result = integrityResultsByAnswer[answerId] || latestIntegrityResult;
        if (result) {
            showIntegrityResult(result);
        }
    });

    $(document).on('click', '.export-source-pdf', function() {
        let source = latestIntegritySources[$(this).data('source-index')];
        if (source) {
            exportIntegritySource(source);
        }
    });

    function runIntegrityCheck(checkType, answerId, button) {
        let status = $('.integrity-check-status[data-answer-id="' + answerId + '"]');
        let answerPanel = button.closest('.card-body');
        let buttons = answerPanel.find('.integrity-check-btn');
        let originalButtonHtml = button.html();

        buttons.prop('disabled', true);
        button.html('<i class="fas fa-spinner fa-spin"></i> Checking...');
        status.removeClass('text-danger text-success').addClass('text-muted').text('Running ' + integrityCheckLabels[checkType] + '...');

        $.ajax({
            url: "{{ route('trainer.submission.question.integrity-check', $submission->id) }}",
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { _token: "{{ csrf_token() }}", check_type: checkType, answer_id: answerId },
            success: function(response) {
                latestIntegrityResult = response;
                integrityResultsByAnswer[answerId] = response;
                answerPanel.find('.view-integrity-result[data-answer-id="' + answerId + '"]').show();
                status.removeClass('text-muted text-danger').addClass('text-success').text(response.title + ' completed. Click Result View.');
            },
            error: function(xhr) {
                let message = 'Failed to run the check.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                status.removeClass('text-muted text-success').addClass('text-danger').text(message);
            },
            complete: function() {
                buttons.prop('disabled', false);
                button.html(originalButtonHtml);
            }
        });
    }

    function showIntegrityResult(result) {
        $('#integrityResultTitle').text(result.title || 'Answer Check Result');
        $('#integrity-result-body').html(buildIntegrityResultHtml(result));
        if ($.fn.modal) {
            $('#integrityResultModal').modal('show');
            return;
        }
        $('#integrityResultModal').addClass('show').show().attr('aria-modal', 'true').removeAttr('aria-hidden');
        $('body').addClass('modal-open').append('<div class="modal-backdrop fade show integrity-result-backdrop"></div>');
    }

    $(document).on('click', '#integrityResultModal [data-dismiss="modal"], .integrity-result-backdrop', function() {
        if ($.fn.modal) {
            $('#integrityResultModal').modal('hide');
            return;
        }
        $('#integrityResultModal').removeClass('show').hide().removeAttr('aria-modal').attr('aria-hidden', 'true');
        $('.integrity-result-backdrop').remove();
        $('body').removeClass('modal-open');
    });

    function buildIntegrityResultHtml(result) {
        let score = result.score === undefined || result.score === null ? '-' : result.score + '%';
        let metrics = result.metrics || {};
        let sources = result.sources || [];
        latestIntegritySources = sources;
        let question = result.question ? '<p class="text-muted"><strong>Question:</strong> ' + escapeHtml(result.question) + '</p>' : '';
        let metricRows = Object.keys(metrics).map(function(key) {
            return '<tr><th style="width:45%;">' + escapeHtml(key) + '</th><td>' + escapeHtml(metrics[key]) + '</td></tr>';
        }).join('');
        let sourceRows = sources.length ? sources.map(function(source, index) {
            if (typeof source === 'string') source = { title: source };
            let title = escapeHtml(source.title || 'Matched source');
            let snippet = source.snippet ? '<div class="text-muted small">' + escapeHtml(source.snippet) + '</div>' : '';
            let link = source.link ? '<a href="' + escapeAttribute(source.link) + '" target="_blank" rel="noopener">' + title + '</a>' : title;
            let matchedTexts = buildMatchedTextHtml(source.matched_texts || [], index);
            let exportButton = '<div class="mt-1"><button type="button" class="btn btn-sm btn-outline-secondary export-source-pdf" data-source-index="' + index + '"><i class="fas fa-file-pdf"></i> Export PDF</button></div>';
            return '<li class="mb-2">' + link + snippet + exportButton + matchedTexts + '</li>';
        }).join('') : '<li class="text-muted">No matching sources returned.</li>';
        let note = result.note ? '<div class="alert alert-warning mt-3 mb-0">' + escapeHtml(result.note) + '</div>' : '';

        return '<div class="row">' +
            '<div class="col-md-3">' +
                '<div style="background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:16px;text-align:center;font-size:28px;font-weight:bold;">' + score + '<div style="font-size:14px;">' + escapeHtml(result.status || '') + '</div></div>' +
                '<div style="font-size:11px;color:#888;margin-top:6px;">Checked at: ' + escapeHtml(result.checked_at || '-') + '<br>Provider: ' + escapeHtml(result.provider || '-') + '</div>' +
            '</div>' +
            '<div class="col-md-9">' +
                question +
                '<p>' + escapeHtml(result.summary || 'Check completed.') + '</p>' +
                '<table class="table table-sm table-bordered">' + metricRows + '</table>' +
                '<h6>Matches / Sources</h6>' +
                '<ul style="list-style:disc;padding-left:20px;">' + sourceRows + '</ul>' +
                note +
            '</div>' +
        '</div>';
    }

    function exportIntegritySource(source) {
        let form = $('#integrity-source-export-form');
        form.find('.export-matched-text-input').remove();
        $('#export-source-title').val(source.title || 'Matched source');
        $('#export-source-link').val(source.link || '');
        $('#export-source-snippet').val(source.snippet || '');
        $('#export-source-score').val(source.score || '');

        (source.matched_texts || []).forEach(function(match, index) {
            appendExportInput(form, 'matched_texts[' + index + '][matched_phrase]', match.matched_phrase || '');
            appendExportInput(form, 'matched_texts[' + index + '][current_text]', match.current_text || '');
            appendExportInput(form, 'matched_texts[' + index + '][matched_submission_text]', match.matched_submission_text || '');
        });

        form.trigger('submit');
    }

    function appendExportInput(form, name, value) {
        $('<input>').attr('type', 'hidden').attr('name', name).addClass('export-matched-text-input').val(value).appendTo(form);
    }

    function buildMatchedTextHtml(matches, sourceIndex) {
        if (!matches.length) return '';

        let matchRows = matches.map(function(match) {
            return '<div style="background:#fffde7;border:1px solid #ffe082;border-radius:4px;padding:8px;margin-top:6px;font-size:12px;">' +
                '<div class="small text-muted mb-1">Matched phrase</div>' +
                '<div><mark>' + escapeHtml(match.matched_phrase || '') + '</mark></div>' +
                '<div class="row mt-2">' +
                    '<div class="col-md-6"><strong class="small">Current answer text</strong><div class="small">' + escapeHtml(match.current_text || '') + '</div></div>' +
                    '<div class="col-md-6"><strong class="small">Matched stored submission text</strong><div class="small">' + escapeHtml(match.matched_submission_text || '') + '</div></div>' +
                '</div></div>';
        }).join('');

        return '<details class="mt-2"><summary class="btn btn-sm btn-outline-warning">View copied texts</summary>' +
            '<div id="matched-text-' + sourceIndex + '">' + matchRows + '</div></details>';
    }

    function escapeHtml(value) {
        return String(value === undefined || value === null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function escapeAttribute(value) { return escapeHtml(value).replace(/`/g, '&#096;'); }
</script>
@endsection
