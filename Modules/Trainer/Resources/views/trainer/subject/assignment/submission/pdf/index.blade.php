@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | PDF')

@section('content')
<style>
    #pdf-container {
        position: relative;
        width: fit-content;
        margin: auto;
    }

    #pdf-canvas {
        border: 1px solid black;
        z-index: 0;
    }

    #pdf-container .canvas-container {
        position: absolute !important;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
    }

    #text-layer {
        position: absolute !important;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 55;
        pointer-events: none;
    }

    #fabric-canvas {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
        pointer-events: none;
    }

    #annotation-tools {
        margin-top: 10px;
    }

    .annotation-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
        padding: 6px 0;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 6px;
    }

    .annotation-toolbar .tool-label {
        font-size: 11px;
        color: #888;
        margin: 0 4px 0 8px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .annotation-toolbar input[type="color"] {
        height: 28px;
        width: 28px;
        padding: 2px;
        border: 1px solid #ccc;
        border-radius: 3px;
        cursor: pointer;
    }

    .integrity-check-panel {
        padding: 10px 0;
        border-top: 1px solid #e9ecef;
        margin-top: 10px;
    }

    .integrity-check-panel .btn {
        margin: 2px 0;
        display: block;
        width: 100%;
        text-align: left;
    }

    .matched-text-block {
        background: #fffde7;
        border: 1px solid #ffe082;
        border-radius: 4px;
        padding: 8px;
        margin-top: 6px;
        font-size: 12px;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Assignment PDF Submission</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.submission.index', [$submission->assignment_id, $trainerIntake->id]) }}">Submissions</a></li>
                    <li class="breadcrumb-item active">PDF</li>
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
                        <h3 class="card-title">{{ $submission->assignment->name }}'s PDF</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="card-body">
                                    <div>
                                        <div id="annotation-tools">
                                            <div class="annotation-toolbar">
                                                <span class="tool-label">Text</span>
                                                <input type="color" id="text-color" value="#ff0000" title="Text color">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addText()" title="Add text"><i class="fa fa-font"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="increaseFontSize()" title="Increase text size"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="decreaseFontSize()" title="Decrease text size"><i class="fa fa-minus"></i></button>

                                                <span class="tool-label">Mark</span>
                                                <input type="color" id="object-color" value="#ff0000" title="Annotation color">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="drawLine()" title="Strike line"><i class="fa fa-strikethrough"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="drawFreehand()" title="Freehand pen"><i class="fa fa-pencil-alt"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addHighlight()" title="Highlight area"><i class="fa fa-highlighter"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addRect()" title="Rectangle"><i class="far fa-square"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addCircle()" title="Circle"><i class="far fa-circle"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="drawArrow()" title="Arrow"><i class="fa fa-long-arrow-alt-right"></i></button>
                                                <button type="button" class="btn btn-outline-success btn-sm" onclick="addStamp('OK')" title="OK stamp"><i class="fa fa-check"></i></button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="addStamp('X')" title="Needs attention stamp"><i class="fa fa-times"></i></button>

                                                <span class="tool-label">Edit</span>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="undoAnnotation()" title="Undo"><i class="fa fa-undo"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteAnnotation()" title="Delete selected"><i class="fa fa-trash"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearCurrentPage()" title="Clear page"><i class="fa fa-eraser"></i></button>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="saveAnnotations()" title="Save graded PDF"><i class="fa fa-save"></i> Save</button>
                                            </div>

                                            <div id="page-navigation" style="margin: 8px 0;">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToFirstPage()">First</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="prevPage()">Prev</button>
                                                <button type="button" class="btn btn-light btn-sm" id="page-num"></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="nextPage()">Next</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToLastPage()">Last</button>
                                            </div>
                                        </div>
                                        <div id="pdf-container">
                                            <canvas id="pdf-canvas"></canvas>
                                            <canvas id="fabric-canvas"></canvas>
                                            <div id="text-layer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-lg-10 -->

                        <div class="col-lg-2">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="m-0 saved_date">Student Name : <span>{{ userName('Student', $submission->student_id) }}</span></h5>
                                </div>
                                <div class="card-body" style="padding:10px;">
                                    <div class="integrity-check-panel">
                                        <label class="d-block mb-2"><strong>PDF Checks</strong></label>
                                        <button type="button" class="btn btn-outline-primary btn-sm integrity-check-btn" data-check-type="ai_detection">
                                            <i class="fas fa-robot"></i> AI Detection
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm integrity-check-btn" data-check-type="paraphraser">
                                            <i class="fas fa-random"></i> Paraphraser Checking
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm integrity-check-btn" data-check-type="plagiarism">
                                            <i class="fas fa-search"></i> Plagiarism Checking
                                        </button>
                                        <button type="button" id="view-integrity-result" class="btn btn-info btn-sm mt-1" style="display:none;">
                                            <i class="fas fa-eye"></i> Result View
                                        </button>
                                        <small id="integrity-check-status" class="form-text text-muted"></small>
                                    </div>

                                    <!-- form start -->
                                    <form id="editsubmission" action="{{ route('admin.assignment.submission.update', $submission->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="grade">Grade</label> <span class="required">*</span>
                                            <select class="form-control" name="assignment_grade_id">
                                                <option value="" selected disabled>-- Select Grade --</option>
                                                @foreach ($grades as $key => $value)
                                                <option value="{{ $key }}" @if ($key==$submission->assignment_grade_id) selected @endif>{{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label> <span class="required">*</span>
                                            <textarea class="form-control" name="remarks">{{ $submission->remarks }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="credits">Credits</label> <span class="required">*</span>
                                            <input type="number" step="0.01" name="credits" class="form-control" id="credits" value="{{ $submission->credits }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Graded File</label>
                                        </div>
                                        <div class="form-group">
                                            <a href="{{ route('trainer.subject.submission.pdf', [$submission->id, 'graded']) }}"><img src="{{ asset(filePath($submission_graded)) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;"></a>
                                        </div>
                                        <div class="form-group">
                                            <label>Submitted File</label>
                                        </div>
                                        <div class="form-group">
                                            <a href="{{ route('trainer.subject.submission.pdf', [$submission->id, 'student']) }}"><img src="{{ asset(filePath($submission->path)) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;"></a>
                                        </div>
                                        <div class="form-group">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary" name="show_to_student" @if($show_to_student==1) checked @endif value="1">
                                                <label for="checkboxPrimary" class="check view">Show Graded File to Student</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-lg-2 -->
                    </div>
                    <!-- /.row -->
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
                <h5 class="modal-title" id="integrityResultTitle">PDF Check Result</h5>
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

<form id="integrity-source-export-form" method="POST" action="{{ route('trainer.subject.submission.pdf.integrity-source.export', $submission->id) }}" target="_blank" style="display:none;">
    @csrf
    <input type="hidden" name="title" id="export-source-title">
    <input type="hidden" name="link" id="export-source-link">
    <input type="hidden" name="snippet" id="export-source-snippet">
    <input type="hidden" name="score" id="export-source-score">
</form>

@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.4.0/fabric.min.js"></script>

<script>
    $(function() {
        if (!$.fn.validate) return;

        $('#editsubmission').validate({
            rules: {
                assignment_grade_id: { required: true },
                remarks: { required: true },
                credits: { required: true },
            },
            messages: {
                assignment_grade_id: "Please choose one grade",
                remarks: "Please enter remarks",
                credits: "Please enter credits",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) { $(element).addClass('is-invalid'); },
            unhighlight: function(element) { $(element).removeClass('is-invalid'); }
        });
    });

    $(document).on('submit', '#editsubmission', function(event) {
        event.preventDefault();
        if ($.fn.validate && !$(this).valid()) return;
        saveAnnotations();
    });

    let pdfDoc = null,
        pageNum = 1,
        pdfCanvas = document.getElementById('pdf-canvas'),
        fabricCanvasElement = document.getElementById('fabric-canvas'),
        fabricCanvas = new fabric.Canvas('fabric-canvas'),
        textLayer = document.getElementById('text-layer'),
        file = null,
        annotations = {};

    fabricCanvas.selection = false;

    let isDrawing = false;
    let lines = [];
    let activeDrawTool = null;
    let activeDrawObject = null;

    window.onload = function(e) {
        let filePath = '{{ asset($submission->path) }}';

        fetch(filePath)
            .then(response => {
                if (response.ok) {
                    file = response;
                    return response.arrayBuffer();
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .then(arrayBuffer => {
                pdfjsLib.getDocument(new Uint8Array(arrayBuffer)).promise.then(function(pdf) {
                    pdfDoc = pdf;
                    renderPage(pageNum);
                });
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    };

    function renderPage(num) {
        pdfDoc.getPage(num).then(function(page) {
            let viewport = page.getViewport({ scale: 1 });
            pdfCanvas.height = viewport.height;
            pdfCanvas.width = viewport.width;
            fabricCanvasElement.height = viewport.height;
            fabricCanvasElement.width = viewport.width;

            let context = pdfCanvas.getContext('2d');
            page.render({ canvasContext: context, viewport: viewport }).promise.then(function() {
                fabricCanvas.setWidth(viewport.width);
                fabricCanvas.setHeight(viewport.height);
                fabricCanvas.clear();
                fabricCanvas.renderAll();

                if (annotations[pageNum]) {
                    fabricCanvas.loadFromJSON(annotations[pageNum], fabricCanvas.renderAll.bind(fabricCanvas));
                }
            });

            document.getElementById('page-num').textContent = pageNum;
        });
    }

    function prevPage() {
        if (pageNum <= 1) return;
        stopActiveTool(); saveCurrentPageAnnotations(); pageNum--; renderPage(pageNum);
    }

    function nextPage() {
        if (pageNum >= pdfDoc.numPages) return;
        stopActiveTool(); saveCurrentPageAnnotations(); pageNum++; renderPage(pageNum);
    }

    function goToFirstPage() {
        if (!pdfDoc) return;
        stopActiveTool(); saveCurrentPageAnnotations(); pageNum = 1; renderPage(pageNum);
    }

    function goToLastPage() {
        if (!pdfDoc) return;
        stopActiveTool(); saveCurrentPageAnnotations(); pageNum = pdfDoc.numPages; renderPage(pageNum);
    }

    function addText() {
        stopActiveTool();
        let color = document.getElementById('text-color').value;
        let text = new fabric.IText('Sample Text', { left: 50, top: 50, fill: color, fontSize: 18, annotationType: 'text' });
        text.set({ selectable: true });
        fabricCanvas.add(text); fabricCanvas.setActiveObject(text); fabricCanvas.renderAll();
    }

    function addRect() {
        stopActiveTool();
        let color = document.getElementById('object-color').value;
        let rect = new fabric.Rect({ left: 100, top: 100, width: 100, height: 100, fill: 'rgba(0,0,0,0)', stroke: color, strokeWidth: 2, annotationType: 'rect' });
        rect.set({ selectable: true });
        fabricCanvas.add(rect); fabricCanvas.setActiveObject(rect); fabricCanvas.renderAll();
    }

    function addHighlight() {
        stopActiveTool();
        let rect = new fabric.Rect({ left: 100, top: 100, width: 180, height: 28, fill: 'rgba(255, 235, 59, 0.35)', stroke: '#f5c542', strokeWidth: 1, annotationType: 'highlight' });
        rect.set({ selectable: true });
        fabricCanvas.add(rect); fabricCanvas.setActiveObject(rect); fabricCanvas.renderAll();
    }

    function addCircle() {
        stopActiveTool();
        let color = document.getElementById('object-color').value;
        let circle = new fabric.Ellipse({ left: 110, top: 110, rx: 55, ry: 35, fill: 'rgba(0,0,0,0)', stroke: color, strokeWidth: 2, annotationType: 'ellipse' });
        circle.set({ selectable: true });
        fabricCanvas.add(circle); fabricCanvas.setActiveObject(circle); fabricCanvas.renderAll();
    }

    function addStamp(text) {
        stopActiveTool();
        let color = text === 'OK' ? '#198754' : '#dc3545';
        let stamp = new fabric.IText(text, { left: 80, top: 80, fill: color, fontSize: 28, fontWeight: 'bold', annotationType: 'stamp' });
        stamp.set({ selectable: true });
        fabricCanvas.add(stamp); fabricCanvas.setActiveObject(stamp); fabricCanvas.renderAll();
    }

    function drawLine() { activateLineTool('line'); }
    function drawArrow() { activateLineTool('arrow'); }

    function activateLineTool(tool) {
        stopActiveTool();
        activeDrawTool = tool;
        isDrawing = false;
        fabricCanvas.selection = false;
        fabricCanvas.defaultCursor = 'crosshair';
        fabricCanvas.on('mouse:down', startLineAnnotation);
        fabricCanvas.on('mouse:move', resizeLineAnnotation);
        fabricCanvas.on('mouse:up', finishLineAnnotation);
    }

    function startLineAnnotation(o) {
        if (!activeDrawTool || (activeDrawTool !== 'line' && activeDrawTool !== 'arrow')) return;
        isDrawing = true;
        let pointer = fabricCanvas.getPointer(o.e);
        let color = document.getElementById('object-color').value;
        activeDrawObject = new fabric.Line([pointer.x, pointer.y, pointer.x, pointer.y], { strokeWidth: 2.85, stroke: color, originX: 'center', originY: 'center', selectable: true, annotationType: activeDrawTool });
        fabricCanvas.add(activeDrawObject);
    }

    function resizeLineAnnotation(o) {
        if (!isDrawing || !activeDrawObject) return;
        let pointer = fabricCanvas.getPointer(o.e);
        activeDrawObject.set({ x2: pointer.x, y2: pointer.y });
        fabricCanvas.renderAll();
    }

    function finishLineAnnotation() {
        if (!isDrawing || !activeDrawObject) return;
        activeDrawObject.setCoords();
        lines.push(activeDrawObject);
        activeDrawObject = null;
        isDrawing = false;
    }

    function drawFreehand() {
        stopActiveTool();
        activeDrawTool = 'freehand';
        fabricCanvas.isDrawingMode = true;
        fabricCanvas.freeDrawingBrush.width = 2.5;
        fabricCanvas.freeDrawingBrush.color = document.getElementById('object-color').value;
    }

    fabricCanvas.on('path:created', function(event) {
        if (event.path) {
            event.path.set({ annotationType: 'freehand', selectable: true });
        }
    });

    function stopActiveTool() {
        fabricCanvas.isDrawingMode = false;
        isDrawing = false; activeDrawTool = null; activeDrawObject = null;
        fabricCanvas.selection = false; fabricCanvas.defaultCursor = 'default';
        fabricCanvas.off('mouse:down', startLineAnnotation);
        fabricCanvas.off('mouse:move', resizeLineAnnotation);
        fabricCanvas.off('mouse:up', finishLineAnnotation);
    }

    function stopLineDraw() { stopActiveTool(); }

    function deleteAnnotation() {
        stopActiveTool();
        let activeObject = fabricCanvas.getActiveObject();
        if (activeObject) { fabricCanvas.remove(activeObject); fabricCanvas.discardActiveObject(); fabricCanvas.renderAll(); }
    }

    function undoAnnotation() {
        stopActiveTool();
        let objects = fabricCanvas.getObjects();
        if (objects.length === 0) return;
        fabricCanvas.remove(objects[objects.length - 1]);
        fabricCanvas.renderAll();
    }

    function clearCurrentPage() {
        stopActiveTool();
        if (!confirm('Clear all annotations on this page?')) return;
        fabricCanvas.clear();
        delete annotations[pageNum];
        fabricCanvas.renderAll();
    }

    function increaseFontSize() {
        stopActiveTool();
        let activeObject = fabricCanvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') { activeObject.set('fontSize', activeObject.fontSize + 2); fabricCanvas.renderAll(); }
    }

    function decreaseFontSize() {
        stopActiveTool();
        let activeObject = fabricCanvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') { activeObject.set('fontSize', activeObject.fontSize - 2); fabricCanvas.renderAll(); }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Delete') deleteAnnotation();
    });

    // --- Integrity checks ---

    let latestIntegrityResult = null;
    let latestIntegritySources = [];

    const integrityCheckLabels = {
        ai_detection: 'AI Detection',
        paraphraser: 'Paraphraser Checking',
        plagiarism: 'Plagiarism Checking'
    };

    $('.integrity-check-btn').on('click', function() {
        runIntegrityCheck($(this).data('check-type'), $(this));
    });

    $(document).on('click', '#view-integrity-result', function() {
        if (latestIntegrityResult) showIntegrityResult(latestIntegrityResult);
    });

    $(document).on('click', '.export-source-pdf', function() {
        let source = latestIntegritySources[$(this).data('source-index')];
        if (source) exportIntegritySource(source);
    });

    async function extractPdfText() {
        if (!pdfDoc) throw new Error('PDF is still loading. Please try again in a moment.');
        let pages = [];
        for (let pageIndex = 1; pageIndex <= pdfDoc.numPages; pageIndex++) {
            let page = await pdfDoc.getPage(pageIndex);
            let textContent = await page.getTextContent();
            pages.push(textContent.items.map(function(item) { return item.str; }).join(' '));
        }
        return pages.join("\n").replace(/\s+/g, ' ').trim();
    }

    async function runIntegrityCheck(checkType, button) {
        let status = $('#integrity-check-status');
        let buttons = $('.integrity-check-btn');
        let originalButtonHtml = button.html();

        try {
            buttons.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin"></i> Checking...');
            status.removeClass('text-danger text-success').addClass('text-muted').text('Extracting text from PDF...');

            let extractedText = await extractPdfText();
            if (!extractedText || extractedText.length < 20) throw new Error('No readable text was found in this PDF.');

            status.text('Running ' + integrityCheckLabels[checkType] + '...');

            $.ajax({
                url: "{{ route('trainer.subject.submission.pdf.integrity-check', $submission->id) }}",
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { check_type: checkType, text: extractedText },
                success: function(response) {
                    latestIntegrityResult = response;
                    $('#view-integrity-result').show();
                    status.removeClass('text-muted text-danger').addClass('text-success').text(response.title + ' completed. Click Result View.');
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
        } catch (error) {
            status.removeClass('text-muted text-success').addClass('text-danger').text(error.message);
            buttons.prop('disabled', false);
            button.html(originalButtonHtml);
        }
    }

    function showIntegrityResult(result) {
        $('#integrityResultTitle').text(result.title || 'PDF Check Result');
        $('#integrity-result-body').html(buildIntegrityResultHtml(result));
        if ($.fn.modal) { $('#integrityResultModal').modal('show'); return; }
        $('#integrityResultModal').addClass('show').show().attr('aria-modal', 'true').removeAttr('aria-hidden');
        $('body').addClass('modal-open').append('<div class="modal-backdrop fade show integrity-result-backdrop"></div>');
    }

    $(document).on('click', '#integrityResultModal [data-dismiss="modal"], .integrity-result-backdrop', function() {
        if ($.fn.modal) { $('#integrityResultModal').modal('hide'); return; }
        $('#integrityResultModal').removeClass('show').hide().removeAttr('aria-modal').attr('aria-hidden', 'true');
        $('.integrity-result-backdrop').remove();
        $('body').removeClass('modal-open');
    });

    function buildIntegrityResultHtml(result) {
        let score = result.score === undefined || result.score === null ? '-' : result.score + '%';
        let metrics = result.metrics || {};
        let sources = result.sources || [];
        latestIntegritySources = sources;
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
        }).join('') : '<li class="text-muted">No matching sources found.</li>';
        let note = result.note ? '<div class="alert alert-warning mt-3 mb-0">' + escapeHtml(result.note) + '</div>' : '';

        return '<div class="row">' +
            '<div class="col-md-3">' +
                '<div style="background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:16px;text-align:center;font-size:28px;font-weight:bold;">' + score + '<div style="font-size:14px;">' + escapeHtml(result.status || '') + '</div></div>' +
                '<div style="font-size:11px;color:#888;margin-top:6px;">Checked at: ' + escapeHtml(result.checked_at || '-') + '<br>Provider: ' + escapeHtml(result.provider || '-') + '</div>' +
            '</div>' +
            '<div class="col-md-9">' +
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
                    '<div class="col-md-6"><strong class="small">Current PDF text</strong><div class="small">' + escapeHtml(match.current_text || '') + '</div></div>' +
                    '<div class="col-md-6"><strong class="small">Matched stored submission text</strong><div class="small">' + escapeHtml(match.matched_submission_text || '') + '</div></div>' +
                '</div></div>';
        }).join('');
        return '<details class="mt-2"><summary class="btn btn-sm btn-outline-warning">View copied texts</summary><div id="matched-text-' + sourceIndex + '">' + matchRows + '</div></details>';
    }

    function escapeHtml(value) {
        return String(value === undefined || value === null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function escapeAttribute(value) { return escapeHtml(value).replace(/`/g, '&#096;'); }

    // --- Save annotations ---

    function saveAnnotations() {
        stopActiveTool();
        saveCurrentPageAnnotations();
        let data = JSON.stringify(annotations);
        let formData = new FormData();
        formData.append('annotations', data);
        $('#editsubmission').serializeArray().forEach(function(field) {
            if (field.name !== '_token') formData.append(field.name, field.value);
        });
        var id = "{{ $submission->id }}";
        var type = "{{ $type }}";
        var url = "{{ route('trainer.subject.submission.pdfsave', ['id' => ':id', 'type' => ':type']) }}";
        url = url.replace(':id', id);
        url = url.replace(':type', type);

        $.ajax({
            url: url,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#editsubmission button[type="submit"]').prop('disabled', true).text('Saving...');
            },
            success: function(response) {
                alert('Annotations and PDF file saved successfully!');
                var redirect_url = "{{ route('trainer.subject.submission.pdf', ['id' => ':id', 'type' => 'graded']) }}";
                redirect_url = redirect_url.replace(':id', id);
                window.location.replace(redirect_url);
            },
            error: function(xhr, status, error) {
                let message = error;
                if (xhr.responseJSON && xhr.responseJSON.message) message = xhr.responseJSON.message;
                alert('Failed to save annotations and PDF file: ' + message);
            },
            complete: function() {
                $('#editsubmission button[type="submit"]').prop('disabled', false).text('Submit');
            }
        });
    }

    function saveCurrentPageAnnotations() {
        annotations[pageNum] = {
            objects: fabricCanvas.toJSON(['annotationType', 'pathOffset']).objects.slice()
        };
    }
</script>
@endsection
