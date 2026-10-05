@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Edit Chat')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Chat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.chat.index', $trainerIntake->intake_subject_id) }}">Chats</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="chat" action="{{ route('trainer.subject.chat.update', $chat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Chat</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Title</label> <span class="required">*</span>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Please enter title" value="{{ $chat->title }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label> <span class="required">*</span>
                                <textarea name="description" id="description" class="form-control" placeholder="Please enter description">{{ $chat->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Upload File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <img src="{{ asset($chat->image) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                            </div>
                            <div class="form-group">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($chat->status == '1')selected @endif value="1">Active</option>
                                        <option @if($chat->status == '0')selected @endif value="0">Inactive</option>
                                    </select>
                                </div>
                            <div id="students-teacher-container">
                                <label>Select Students:</label>
                                <div id="students-list">
                                    @foreach ($students as $student)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="students[]" value="{{ $student['id'] }}" id="student_id" @if ($student['is_attendee']==true) checked @endif>
                                        <label class="form-check-label" for="student_id">
                                            {{ $student['name'] }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div> 
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" onclick="return confirmation();">Submit</button>
            </div>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#chat').validate({
            rules: {
                title: {
                    required: true
                },
                description: {
                    required: true
                },
                intake_id: {
                    required: true
                },
                intake_course_id: {
                    required: true
                },
                intake_semester_id: {
                    required: true
                },
                intake_subject_id: {
                    required: true
                },
                "students[]": {
                    required: true
                },
                teacher_id: {
                    required: true
                }
            },
            messages: {
                title: "Please enter title",
                description: "Please enter description",
                intake_id: "Please choose one intake",
                intake_course_id: "Please choose one course",
                intake_semester_id: "Please choose one semester",
                intake_subject_id: "Please choose one subject",
                "students[]": "Please choose atleast ome student",
                teacher_id: "Please choose one teacher",
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

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('chat').addEventListener('submit', function(event) {
        var fileInput = document.getElementById('image');
        var file = fileInput.files[0];

        if (file && file.size > 10 * 1024 * 1024) { // 10 MB
            alert('File size exceeds 10 MB');
            event.preventDefault();
        }
    });
</script>
@endsection
