@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Edit Resource')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $resource->unit->name }}'s Resource</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.resource.index', $trainerIntake->id) }}">Resources</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $resource->unit->name }}'s Resource</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editresource" action="{{ route('trainer.subject.resource.update', $resource->id) }}" method="POST" enctype="multipart/form-data">
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
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($resource->status == '1')selected @endif value="1">Active</option>
                                    <option @if($resource->status == '0')selected @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="trainer_intake_id" class="form-control" value="{{ $trainerIntake->id }}">
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
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter category name",
                resource_type: "Please one resource type",
                resource_category_id: "Please choose one category",
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
