@extends('user::layouts.master')
@section('title', 'Admin | Assignment PDF Submission')

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

    .highlight {
        background-color: yellow;
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

    #annotation-tools{
        color: #6e6b6b;
    }
    input{
        height: 30px;
        width: 28px;
        padding: 4px;
        border: 1px solid #ccc;
        border-radius: 2px;
        color: #6e6b6b;
    }
    button i{
        font-size: 16px !important;
        color: #6e6b6b;
    }
    #annotation-tools button svg{
        height: 25px;
        width: 25px;
    }
    label{
        margin: 0;
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.submission.index') }}">Submissions</a></li>
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
                                            <label for="text-color">Text Color:</label>
                                            <input type="color" id="text-color" value="#ff0000">
                                            <button onclick="addText()"><i class="fa fa-font" style="font-size:20px"></i></button>
                                            <button onclick="increaseFontSize()"><i class="fa fa-font" style="font-size:20px"></i>+</button>
                                            <button onclick="decreaseFontSize()"><i class="fa fa-font" style="font-size:20px"></i>-</button>
                                            <label for="object-color">Object Color:</label>
                                            <input type="color" id="object-color" value="#ff0000">
                                            <!-- <button onclick="drawLine()"><i class="fa fa-strikethrough" style="font-size:20px"></i></button> -->
                                            <button onclick="drawLine()"><svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 20 20"><path fill="currentColor" d="M15.82 12.25c.26 0 .5-.02.74-.07c.23-.05.48-.12.73-.2v.84c-.46.17-.99.26-1.58.26c-.88 0-1.54-.26-2.01-.79c-.39-.44-.62-1.04-.68-1.79h-.94c.12.21.18.48.18.79c0 .54-.18.95-.55 1.26q-.57.45-1.56.45H8v-2.5H6.59l.93 2.5H6.49l-.59-1.67H3.62L3.04 13H2l.93-2.5H2v-1h1.31l.93-2.49H5.3l.92 2.49H8V7h1.77c1 0 1.41.17 1.77.41c.37.24.55.62.55 1.13q0 .525-.27.87l-.08.09h1.29c.05-.4.15-.77.31-1.1c.23-.46.55-.82.98-1.06c.43-.25.93-.37 1.51-.37c.61 0 1.17.12 1.69.38l-.35.81c-.2-.1-.42-.18-.64-.25s-.46-.11-.71-.11c-.55 0-.99.2-1.31.59c-.23.29-.38.66-.44 1.11H17v1h-2.95c.06.5.2.9.44 1.19c.3.37.75.56 1.33.56M4.44 8.96l-.18.54H5.3l-.22-.61c-.04-.11-.09-.28-.17-.51c-.07-.24-.12-.41-.14-.51c-.08.33-.18.69-.33 1.09m4.53-1.09V9.5h1.19c.28-.02.49-.09.64-.18c.19-.13.28-.35.28-.66c0-.28-.1-.48-.3-.61c-.2-.12-.53-.18-.97-.18zm-3.33 2.64v-.01H3.91v.01zm5.28.01l-.03-.02H8.97v1.68h1.04c.4 0 .71-.08.92-.23c.21-.16.31-.4.31-.74c0-.31-.11-.54-.32-.69"/></svg></button>
                                            <!-- <button onclick="stopLineDraw()">Stop S̶t̶r̶i̶k̶e̶</button> -->
                                            <button onclick="deleteLines()">Delete All <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 20 20"><path fill="currentColor" d="M15.82 12.25c.26 0 .5-.02.74-.07c.23-.05.48-.12.73-.2v.84c-.46.17-.99.26-1.58.26c-.88 0-1.54-.26-2.01-.79c-.39-.44-.62-1.04-.68-1.79h-.94c.12.21.18.48.18.79c0 .54-.18.95-.55 1.26q-.57.45-1.56.45H8v-2.5H6.59l.93 2.5H6.49l-.59-1.67H3.62L3.04 13H2l.93-2.5H2v-1h1.31l.93-2.49H5.3l.92 2.49H8V7h1.77c1 0 1.41.17 1.77.41c.37.24.55.62.55 1.13q0 .525-.27.87l-.08.09h1.29c.05-.4.15-.77.31-1.1c.23-.46.55-.82.98-1.06c.43-.25.93-.37 1.51-.37c.61 0 1.17.12 1.69.38l-.35.81c-.2-.1-.42-.18-.64-.25s-.46-.11-.71-.11c-.55 0-.99.2-1.31.59c-.23.29-.38.66-.44 1.11H17v1h-2.95c.06.5.2.9.44 1.19c.3.37.75.56 1.33.56M4.44 8.96l-.18.54H5.3l-.22-.61c-.04-.11-.09-.28-.17-.51c-.07-.24-.12-.41-.14-.51c-.08.33-.18.69-.33 1.09m4.53-1.09V9.5h1.19c.28-.02.49-.09.64-.18c.19-.13.28-.35.28-.66c0-.28-.1-.48-.3-.61c-.2-.12-.53-.18-.97-.18zm-3.33 2.64v-.01H3.91v.01zm5.28.01l-.03-.02H8.97v1.68h1.04c.4 0 .71-.08.92-.23c.21-.16.31-.4.31-.74c0-.31-.11-.54-.32-.69"/></svg></button>
                                            <!-- <button onclick="SelectText()">Select Text</button> -->
                                            <button onclick="addRect()"><svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" fill="none" stroke="currentColor" stroke-width="2" rx="2"/></svg></button>
                                            <button onclick="deleteAnnotation()">Delete Selected Annotation</button>
                                            <button onclick="saveAnnotations()"><i class="fa fa-save" style="font-size:20px"></i></button>
                                            <!-- <button onclick="saveAsPDF()">Save PDF</button> -->

                                            <div id="page-navigation" style="margin: 8px 0;">
                                                <button onclick="goToFirstPage()"><<</button>
                                                <button onclick="prevPage()"><</button>
                                                <button id="page-num" ></button>
                                                <button onclick="nextPage()">></button>
                                                <button onclick="goToLastPage()">>></button>
                                            </div>
                                        </div>
                                        <div id="pdf-container">
                                            <canvas id="pdf-canvas"></canvas>
                                            <!-- <div id="text-layer" class="textLayer"></div> -->
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
                                            <label for="credits">Graded File</label>
                                        </div>
                                        <div class="form-group">
                                            <a href="{{ route('admin.submission.pdf', [$submission->id, 'graded']) }}"><img src="{{ asset(filePath($submission_graded)) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;"></a>
                                        </div>
                                        <div class="form-group">
                                            <label for="credits">Submitted File</label>
                                        </div>
                                        <div class="form-group">
                                            <a href="{{ route('admin.submission.pdf', [$submission->id, 'student']) }}"><img src="{{ asset(filePath($submission->path)) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;"></a>
                                        </div>
                                        <div class="form-group">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary" name="show_to_student" @if($show_to_student==1) checked @endif value="1">
                                                <label for="checkboxPrimary" class="check view">Show Graded File to Student</label>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <!-- <div class="card-footer"> -->
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <!-- </div> -->
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

@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.4.0/fabric.min.js"></script>

<script>
    $(function() {
        $('#editsubmission').validate({
            rules: {
                assignment_grade_id: {
                    required: true,
                },
                remarks: {
                    required: true,
                },
                credits: {
                    required: true,
                },
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
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    let pdfDoc = null,
        pageNum = 1,
        pdfCanvas = document.getElementById('pdf-canvas'),
        fabricCanvasElement = document.getElementById('fabric-canvas'),
        fabricCanvas = new fabric.Canvas('fabric-canvas'),
        textLayer = document.getElementById('text-layer'),
        file = null, // Variable to store uploaded file data
        annotations = {}; // Object to store annotations for each page

    fabricCanvas.selection = false;

    let isDrawing = false; // Flag to track drawing state
    let lines = []; // Array to store lines across pages

    let selectedLine = null; // Variable to store the selected line

    window.onload = function(e) {
        let filePath = '{{ asset($submission->path) }}';
        console.log('b:', filePath);

        // Fetch the file from the server
        fetch(filePath)
            .then(response => {
                if (response.ok) {
                    // Check if the fetched file is a PDF
                    if (response.headers.get('Content-Type') !== 'application/pdf') {
                        alert('Please upload a PDF file.');
                        throw new Error('File is not a PDF');
                    }
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

    document.getElementById('upload').addEventListener('change', function(e) {
        file = e.target.files[0];
        if (file.type !== 'application/pdf') {
            alert('Please upload a PDF file.');
            return;
        }

        let reader = new FileReader();
        reader.onload = function() {
            let arrayBuffer = reader.result;
            pdfjsLib.getDocument(new Uint8Array(arrayBuffer)).promise.then(function(pdf) {
                pdfDoc = pdf;
                renderPage(pageNum);
            });
        };
        reader.readAsArrayBuffer(file);
    });

    function renderPage(num) {

        pdfDoc.getPage(num).then(function(page) {
            let viewport = page.getViewport({
                scale: 1
            });
            pdfCanvas.height = viewport.height;
            pdfCanvas.width = viewport.width;
            fabricCanvasElement.height = viewport.height;
            fabricCanvasElement.width = viewport.width;
            // textLayer.style.height = viewport.height + 'px';
            // textLayer.style.width = viewport.width + 'px';

            let context = pdfCanvas.getContext('2d');
            let renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext).promise.then(function() {
                fabricCanvas.setWidth(viewport.width);
                fabricCanvas.setHeight(viewport.height);
                fabricCanvas.clear();
                fabricCanvas.renderAll();

                // Load annotations for the current page
                if (annotations[pageNum]) {
                    fabricCanvas.loadFromJSON(annotations[pageNum], fabricCanvas.renderAll.bind(fabricCanvas));
                }
            })

            // Update current page number display
            document.getElementById('page-num').textContent = pageNum;
            // // Clear previous text layer content
            // textLayer.innerHTML = '';

            // // Extract text content from the PDF page
            // return page.getTextContent().then(function(textContent) {
            //     textContent.items.forEach(function(item) {
            //         let span = document.createElement('span');
            //         span.textContent = item.str;
            //         span.style.position = 'absolute';
            //         span.style.left = item.transform[4] + 'px';
            //         span.style.top = (viewport.height - item.transform[5]) + 'px';
            //         span.style.fontSize = item.height + 'px';
            //         span.style.fontFamily = 'sans-serif';
            //         span.classList.add('text-layer-item');
            //         textLayer.appendChild(span);
            //     });
            // });
        });
    }

    function handleTextClick(span) {
        if (span.classList.contains('highlight')) {
            span.classList.remove('highlight');
        } else {
            span.classList.add('highlight');
        }
    }

    function prevPage() {
        if (pageNum <= 1) {
            return;
        }
        stopLineDraw();
        saveCurrentPageAnnotations(); // Save annotations for the current page
        pageNum--;
        renderPage(pageNum);
    }

    function nextPage() {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        stopLineDraw();
        saveCurrentPageAnnotations(); // Save annotations for the current page
        pageNum++;
        renderPage(pageNum);
    }

    function goToFirstPage() {
        if (!pdfDoc) {
            return
        }
        stopLineDraw();
        saveCurrentPageAnnotations();
        pageNum = 1;
        renderPage(pageNum);
    }

    function goToLastPage() {
        if (!pdfDoc) {
            return
        }
        stopLineDraw();
        saveCurrentPageAnnotations();
        pageNum = pdfDoc.numPages;
        renderPage(pageNum);
    }

    function addText() {
        stopLineDraw();
        let color = document.getElementById('text-color').value;
        let text = new fabric.IText('Sample Text', {
            left: 50,
            top: 50,
            fill: color
        });
        text.set({
            selectable: true
        });
        fabricCanvas.add(text);
    }

    function addRect() {
        stopLineDraw();
        let color = document.getElementById('object-color').value;
        let rect = new fabric.Rect({
            left: 100,
            top: 100,
            width: 100,
            height: 100,
            fill: 'rgba(0,0,0,0)',
            stroke: color,
            strokeWidth: 2
        });
        rect.set({
            selectable: true
        });
        fabricCanvas.add(rect);
    }

    function drawLine() {
        isDrawing = true;
        fabricCanvas.isDrawingMode = false; // Disable drawing mode in Fabric.js

        fabricCanvas.on('mouse:down', function(o) {
            if (!isDrawing) return;
            let pointer = fabricCanvas.getPointer(o.e);
            let startX = pointer.x;
            let startY = pointer.y;

            let points = [startX, startY, startX, startY];
            let color = document.getElementById('object-color').value;

            line = new fabric.Line(points, {
                strokeWidth: 2.85,
                // fill: color,
                stroke: color,
                // opacity: 0.5,
                originX: 'center',
                originY: 'center',
                selectable: true,
            });

            fabricCanvas.add(line);

            fabricCanvas.on('mouse:move', function(o) {
                if (!isDrawing) return;
                let pointer = fabricCanvas.getPointer(o.e);
                line.set({
                    x2: pointer.x,
                    y2: pointer.y
                });
                fabricCanvas.renderAll();
            });

            fabricCanvas.on('mouse:up', function(o) {
                if (!isDrawing) return;
                isDrawing = true;
                lines.push(line); // Store the line object in the array
                fabricCanvas.off('mouse:move');
            });
        });
    }

    function stopLineDraw() {
        if (!isDrawing) return;
        fabricCanvas.isDrawingMode = false;
        isDrawing = false;
        // lines.push(line); // Store the line object in the array
        fabricCanvas.off('mouse:move');
        // console.log(lines)
    }

    // Function to handle line click
    function handleLineClick(e) {
        if (e.target && e.target.type === 'line') {
            if (selectedLine) {
                selectedLine.set('stroke', document.getElementById('object-color').value); // Reset previous selected line color
            }
            selectedLine = e.target;
            selectedLine.set('stroke', 'red'); // Highlight selected line
            fabricCanvas.renderAll();
        }
    }


    function deleteLines() {
        // if (selectedLine) {
        //     fabricCanvas.remove(selectedLine);
        //     selectedLine = null;
        //     fabricCanvas.renderAll();
        // }
        // Iterate over all objects on the fabric canvas
        stopLineDraw();
        fabricCanvas.getObjects().forEach(function(obj) {
            // Check if the object is a line
            if (obj.type === 'line') {
                // Remove the line from the canvas
                fabricCanvas.remove(obj);
            }
        });
        // Render the canvas to reflect the changes
        fabricCanvas.renderAll();
    }

    function deleteAnnotation() {
        stopLineDraw();
        let activeObject = fabricCanvas.getActiveObject();
        if (activeObject) {
            fabricCanvas.remove(activeObject);
        }
    }

    function increaseFontSize() {
        stopLineDraw();
        let activeObject = fabricCanvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            activeObject.set('fontSize', activeObject.fontSize + 2);
            fabricCanvas.renderAll();
        }
    }

    function decreaseFontSize() {
        stopLineDraw();
        let activeObject = fabricCanvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            activeObject.set('fontSize', activeObject.fontSize - 2);
            fabricCanvas.renderAll();
        }
    }

    // Example of converting Fabric.js canvas to PDF using jsPDF
    function saveAsPDF() {
        let pdf = new jsPDF('p', 'mm', 'a4'); // Create new PDF document (landscape, millimeters, A4 size)

        // Convert canvas to image data URL
        let canvasDataURL = fabricCanvas.toDataURL({
            format: 'jpeg',
            quality: 0.8
        });

        // Add image to PDF
        pdf.addImage(canvasDataURL, 'JPEG', 0, 0, 210, 297); // Assuming A4 size dimensions

        // Save PDF
        pdf.save('canvas.pdf');
    }

    function selectText() {
        textLayer.addEventListener('mouseup', function(event) {
            let selectedText = window.getSelection().toString();
            if (selectedText) {
                let range = window.getSelection().getRangeAt(0);
                let highlightSpan = document.createElement('span');
                highlightSpan.textContent = selectedText;
                highlightSpan.classList.add('highlight');
                range.deleteContents();
                range.insertNode(highlightSpan);
            }
        });
    }

    function strikeThroughText() {
        let activeObject = fabricCanvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            let currentText = activeObject.text;
            let strikeThroughText = '';
            for (let i = 0; i < currentText.length; i++) {
                strikeThroughText += currentText[i] + '\u0336'; // Add strike-through character
            }
            activeObject.set('text', strikeThroughText);
            fabricCanvas.renderAll();
        }
    }

    // Attach keydown event to handle delete key press
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Delete') {
            deleteAnnotation(); // Call deleteAnnotation function on delete/backspace key press
        }
    });


    function saveAnnotations() {
        stopLineDraw();
        saveCurrentPageAnnotations(); // Save annotations for the current page
        let data = JSON.stringify(annotations);
        console.log(data);
        // Prepare form data to send both PDF file and annotations
        let formData = new FormData();
        formData.append('pdfFile', file); // Append the original PDF file
        formData.append('annotations', data);
        var id = "{{ $submission->id }}";
        var type = "{{ $type }}";
        var url = "{{ route('admin.submission.pdfsave', ['id' => ':id', 'type' => ':type']) }}";
        url = url.replace(':id', id);
        url = url.replace(':type', type);
        console.log(url);

        // Make AJAX request to save annotations and PDF file
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,

            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Set content type to false for FormData
            dataType: 'json',
            success: function(response) {
                alert('Annotations and PDF file saved successfully!');
                // Optionally handle success (e.g., reload page)
                var redirect_url = "{{ route('admin.submission.pdf', ['id' => ':id', 'type' => 'graded']) }}";
                redirect_url = redirect_url.replace(':id', id);
                window.location.replace(redirect_url);
            },
            error: function(xhr, status, error) {
                alert('Failed to save annotations and PDF file: ' + error);
            }
        });
    }

    function saveCurrentPageAnnotations() {
        // Save annotations for the current page
        // annotations[pageNum] = fabricCanvas.toJSON();
        // annotations[pageNum].objects = annotations[pageNum].objects.concat(lines.map(line => line.toObject()));
        annotations[pageNum] = {
            objects: fabricCanvas.toJSON().objects.slice() // Make a copy of current page objects
        };
    }
</script>
@endsection
