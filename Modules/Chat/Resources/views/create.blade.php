@extends('user::layouts.master')
@section('title', 'Admin | Create Chat')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Chat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.chat.index') }}">Chats</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="chat" action="{{ route('admin.chat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create Chat</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Title</label> <span class="required">*</span>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Please enter title">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label> <span class="required">*</span>
                                <textarea name="description" id="description" class="form-control" placeholder="Please enter description"></textarea>
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
                                <img src="{{ asset('files/chat-icon.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="intake_id">Intake</label> <span class="required">*</span>
                                    <select id="intake_id" name="intake_id" class="form-control">
                                        <option value="" selected disabled>-- Select Intake --</option>
                                        @foreach($intakes as $key => $value)
                                        <option value="{{$key}}"> {{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="intake_course_id">Course</label> <span class="required">*</span>
                                    <select id="intake_course_id" name="intake_course_id" class="form-control">
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="intake_semester_id">Semester</label> <span class="required">*</span>
                                    <select name="intake_semester_id" id="intake_semester_id" class="form-control">
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="intake_subject_id">Subject</label> <span class="required">*</span>
                                    <select name="intake_subject_id" id="intake_subject_id" class="form-control" onchange="fetchStudentsAndTeacher(this.value)">
                                    </select>
                                </div>
                                @if (getSettingValue('teaching_system') == 'Unit')
                                <div class="form-group col-md-3">
                                    <label for="intake_unit_id">Unit</label>
                                    <select name="intake_unit_id" id="intake_unit_id" class="form-control">
                                    </select>
                                </div>
                                @endif
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" disabled>-- Select Status --</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div id="students-teacher-container" style="display: none;">
                                <label>Select Students:</label>
                                <div id="students-list">
                                    <!-- Student checkboxes will be dynamically populated via JavaScript -->
                                </div>

                                <label>Select Teacher:</label>
                                <div id="teacher-checkbox">
                                    <!-- Teacher checkbox will be dynamically populated via JavaScript -->
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

    $('#intake_id').change(function() {
        var intakeID = $(this).val();
        if (intakeID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/chat/getIntakeCourse')}}?intake_id=" + intakeID,
                success: function(res) {
                    if (res) {
                        $("#intake_course_id").empty();
                        $("#intake_course_id").append('<option value="">-- Select Course --</option>');
                        $.each(res, function(key, value) {
                            $("#intake_course_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#intake_course_id").empty();
                    }
                }
            });
        } else {
            $("#intake_course_id").empty();
        }
    });

    $('#intake_course_id').change(function() {
        var courseID = $(this).val();
        if (courseID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/chat/getIntakeSemester')}}?intake_course_id=" + courseID,
                success: function(res) {
                    if (res) {
                        $("#intake_semester_id").empty();
                        $("#intake_semester_id").append('<option value="">-- Select Semester --</option>');
                        $.each(res, function(key, value) {
                            $("#intake_semester_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#intake_semester_id").empty();
                    }
                }
            });
        } else {
            $("#intake_semester_id").empty();
        }
    });

    $('#intake_semester_id').change(function() {
        var semesterID = $(this).val();
        if (semesterID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/chat/getIntakeSubject')}}?intake_semester_id=" + semesterID,
                success: function(res) {
                    if (res) {
                        $("#intake_subject_id").empty();
                        $("#intake_subject_id").append('<option value="">-- Select Subject --</option>');
                        $.each(res, function(key, value) {
                            $("#intake_subject_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#intake_subject_id").empty();
                    }
                }
            });
        } else {
            $("#intake_subject_id").empty();
        }
    });

    $('#intake_subject_id').change(function() {
        var subjectID = $(this).val();
        if (subjectID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/chat/getIntakeUnit')}}?intake_subject_id=" + subjectID,
                success: function(res) {
                    if (res) {
                        $("#intake_unit_id").empty();
                        $("#intake_unit_id").append('<option value="">-- Select Unit --</option>');
                        $.each(res, function(key, value) {
                            $("#intake_unit_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#intake_unit_id").empty();
                    }
                }
            });
        } else {
            $("#intake_unit_id").empty();
        }
    });

    function fetchStudentsAndTeacher(subjectId) {
        if (subjectId) {
            fetch(`/admin/chat/getStudentTeacherFromIntakeSubject?intake_subject_id=${subjectId}`)
                .then(response => response.json())
                .then(data => {
                    let studentCheckboxes = '';
                    let teacherCheckbox = '';

                    if (data.students.length > 0) {
                        data.students.forEach(student => {
                            studentCheckboxes += `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="students[]" value="${student.id}" id="student_${student.id}">
                                <label class="form-check-label" for="student_${student.id}">
                                    ${student.name}
                                </label>
                            </div>
                        `;
                        });
                    }

                    if (data.teacher) {
                        teacherCheckbox = `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="teacher_id" value="${data.teacher.id}" id="teacher_${data.teacher.id}">
                            <label class="form-check-label" for="teacher_${data.teacher.id}">
                                ${data.teacher.name}
                            </label>
                        </div>
                    `;
                    } else {
                        teacherCheckbox = '<p>No teacher found for this subject.</p>';
                    }

                    document.getElementById('students-list').innerHTML = studentCheckboxes;
                    document.getElementById('teacher-checkbox').innerHTML = teacherCheckbox;

                    document.getElementById('students-teacher-container').style.display = 'block';
                })
                .catch(error => console.error('Error fetching data:', error));
        } else {
            document.getElementById('students-teacher-container').style.display = 'none';
        }
    }
</script>
@endsection
