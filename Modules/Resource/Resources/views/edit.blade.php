@extends('user::layouts.master')
@section('title', 'Admin | Edit Resource')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Resource</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.resource.index') }}">Resource</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit <small>Resource</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editresource" action="{{ route('admin.resource.update', $resource->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Name</label> <span class="required">*</span>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $resource->name }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="resource_type">Type</label> <span class="required">*</span>
                                    <select name="resource_type" class="form-control" id="resource_type">
                                        <option value="" selected disabled>-- Select Type --</option>
                                        <option @if($resource->resource_type == 'PDF')selected @endif value="PDF">PDF</option>
                                        <option @if($resource->resource_type == 'Image')selected @endif value="Image">Image</option>
                                        <option @if($resource->resource_type == 'Word')selected @endif value="Word">Word</option>
                                        <option @if($resource->resource_type == 'Powerpoint')selected @endif value="Powerpoint">Powerpoint</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="resource_category_id">Resource Categories</label> <span class="required">*</span>
                                    <select id="resource_category_id" name="resource_category_id" class="form-control">
                                        <option value="" selected disabled>-- Select Resource Categories --</option>
                                        @foreach($categories as $key => $value)
                                        <option value="{{ $key }}" @if ($key==$resource->resource_category_id) selected @endif> {{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="file">Upload File</label> <span class="required">*</span>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input" id="file" onchange="readURL(this);" value="{{$resource->path}}">
                                        <label class="custom-file-label" for="file">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <img src="{{ asset($resource->path) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="course_id">Course</label> <span class="required">*</span>
                                    <select id="course_id" class="form-control">
                                        <option value="" selected disabled>-- Select Course --</option>
                                        @foreach($courses as $key => $value)
                                        <option value="{{ $key }}" @if ($key==$resource->course_id) selected @endif> {{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="semester_id">Semester</label> <span class="required">*</span>
                                    <select id="semester_id" class="form-control">
                                        <option value="" selected disabled>-- Select Semester --</option>
                                        @foreach($semesters as $index => $semester)
                                        <option value="{{ $index }}" @if ($index==$resource->semester_id) selected @endif> {{$semester}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="subject_id">Subject</label> <span class="required">*</span>
                                    <select id="subject_id" class="form-control">
                                        <option value="" selected disabled>-- Select Subject --</option>
                                        @foreach($subjects as $number => $subject)
                                        <option value="{{ $number }}" @if ($number==$resource->subject_id) selected @endif> {{$subject}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if (getSettingValue('teaching_system') == 'Unit')
                                <div class="form-group col-md-3">
                                    <label for="unit_id">Unit</label>
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option value="" selected disabled>-- Select Unit --</option>
                                        @foreach($units as $digit => $unit)
                                        <option value="{{ $digit }}" @if ($digit==$resource->unit_id) selected @endif> {{$unit}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($resource->status == '1')selected @endif value="1">Active</option>
                                        <option @if($resource->status == '0')selected @endif value="0">Inactive</option>
                                    </select>
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
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
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
        $('#editresource').validate({
            rules: {
                name: {
                    required: true,
                },
                resource_type: {
                    required: true,
                },
                resource_category_id: {
                    required: true,
                },
                course_id: {
                    required: true,
                },
                semester_id: {
                    required: true,
                },
                subject_id: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter category name",
                resource_type: "Please one resource type",
                resource_category_id: "Please choose one category",
                course_id: "Please choose one course",
                semester_id: "Please choose one semester",
                subject_id: "Please choose one subject",
                status: "Please select one status",
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

    $('#course_id').change(function() {
        var courseID = $(this).val();
        if (courseID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/student/semester')}}?course_id=" + courseID,
                success: function(res) {
                    if (res) {
                        $("#semester_id").empty();
                        $("#semester_id").append('<option value="">-- Select Semester --</option>');
                        $.each(res, function(key, value) {
                            $("#semester_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#semester_id").empty();
                    }
                }
            });
        } else {
            $("#semester_id").empty();
        }
    });

    $('#semester_id').change(function() {
        var semesterID = $(this).val();
        if (semesterID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/student/subject')}}?semester_id=" + semesterID,
                success: function(res) {
                    if (res) {
                        $("#subject_id").empty();
                        $("#subject_id").append('<option value="">-- Select Subject --</option>');
                        $.each(res, function(key, value) {
                            $("#subject_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#subject_id").empty();
                    }
                }
            });
        } else {
            $("#subject_id").empty();
        }
    });

    $('#subject_id').change(function() {
        var subjectID = $(this).val();
        if (subjectID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/student/unit')}}?subject_id=" + subjectID,
                success: function(res) {
                    if (res) {
                        $("#unit_id").empty();
                        $("#unit_id").append('<option value="">-- Select Unit --</option>');
                        $.each(res, function(key, value) {
                            $("#unit_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#unit_id").empty();
                    }
                }
            });
        } else {
            $("#unit_id").empty();
        }
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

    document.getElementById('editresource').addEventListener('submit', function(event) {
        var fileInput = document.getElementById('file');
        var file = fileInput.files[0];

        if (file && file.size > 10 * 1024 * 1024) { // 10 MB
            alert('File size exceeds 10 MB');
            event.preventDefault();
        }
    });
</script>
@endsection