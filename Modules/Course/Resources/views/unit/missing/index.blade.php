@extends('user::layouts.master')
@section('title', 'Admin | Add Missing Unit')

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
                <h1 Course Unit>Add Missing Unit</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($unit->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($unit->course->registered == 1) href="{{ route('admin.course.unit.index', $unit->course_id) }}" @else href="{{ route('admin.unregistered.unit.index', $unit->course_id) }}" @endif>Units</a></li>
                    <li class="breadcrumb-item active">Add Missing</li>
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
                        <h3 class="card-title">Add <small>Missing Unit</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="missingunit" action="{{ route('admin.course.unit.missing.update', $unit->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="intake_course_id">Intake Course</label> <span class="required">*</span>
                                <select id="intake_course_id" name="intake_course_id" class="form-control">
                                    <option value="" selected disabled>-- Select Intake Course --</option>
                                    @foreach($intakeCourses as $key => $value)
                                    <option value="{{ $value->id }}"> {{ $value->intake->name}} ({{ $value->course->course_name }})</option>
                                    @endforeach
                                </select>
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
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
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
        $('#missingunit').validate({
            rules: {
                intake_course_id: {
                    required: true,
                },
            },
            messages: {
                intake_course_id: "Please choose one intake course",
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

    $(document).ready(function() {
        $(".btn-success").click(function() {
            var lsthmtl = $(".clone").html();
            $(".increment").after(lsthmtl);
        });
        $("body").on("click", ".btn-danger", function() {
            $(this).parents(".hdtuto").remove();
        });
    });
</script>
@endsection