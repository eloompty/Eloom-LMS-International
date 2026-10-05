@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Create Student Group')

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
                <h1>Create Student Group</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.onlineclass.group.index') }}">Group Online Classes</a></li>
                    <li class="breadcrumb-item active">Create Student Group</li>
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
                        <h3 class="card-title">Create <small>Student Group</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addonlineclass" action="{{ route('trainer.onlineclass.group.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="student_id">Choose Students</label> <span class="required">*</span>
                                @foreach($trainerIntakes as $key => $trainerIntake)
                                <div class="row">
                                    <div class="col-12" id="accordion">
                                        <div class="card card-primary card-outline">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapseIntake{{$key}}">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        {{ $trainerIntake->intakeCourse->course->course_name }} ({{ $trainerIntake->intakeCourse->intake->name }})
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="collapseIntake{{$key}}" class="collapseIntake show" data-parent="#accordion">
                                                <div class="card-body">
                                                    @if ($trainerIntake->student_intake_course->count() > 0)
                                                    <div class="row">
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" class="form-controll trainerIntake{{$trainerIntake->intake_course_id}}" id="trainerIntake{{$trainerIntake->intake_course_id}}">
                                                            <label for="trainerIntake{{$trainerIntake->intake_course_id}}" class="check view">Select All</label>
                                                        </div>
                                                    </div>
                                                    @foreach($trainerIntake->student_intake_course as $index => $value)
                                                    <div class="row">
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" class="trainerIntake{{$trainerIntake->intake_course_id}}checkbox" id="trainerIntake{{$trainerIntake->intake_course_id}}student{{$value->student_id}}" name="student_id[]" value="{{ $value->student_id }}">
                                                            <label for="trainerIntake{{$trainerIntake->intake_course_id}}student{{$value->student_id}}" class="check view">{{ userName('Student', $value->student_id) }}</label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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
        $('#addonlineclass').validate({
            rules: {
                name: {
                    required: true,
                },
                "student_id[]": {
                    required: true,
                }
            },
            messages: {
                name: "Please enter name",
                "student_id[]": "Please select students",
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
</script>
@foreach($trainerIntakes as $key => $trainerIntake)
<script>
    $('.trainerIntake{{$trainerIntake->intake_course_id}}').on('change', function() {
        $('.trainerIntake{{$trainerIntake->intake_course_id}}checkbox').prop('checked', $(this).prop("checked"));
    });
    //deselect "checked all", if one of the listed checkbox category is unchecked amd select "checked all" if all of the listed checkbox category is checked
    $('.checkbox').change(function() { //".checkbox" change 
        if ($('.trainerIntake{{$trainerIntake->intake_course_id}}checkbox:checked').length == $('.trainerIntake{{$trainerIntake->intake_course_id}}checkbox').length) {
            $('.trainerIntake{{$trainerIntake->intake_course_id}}').prop('checked', true);
        } else {
            $('.trainerIntake{{$trainerIntake->intake_course_id}}').prop('checked', false);
        }
    });
</script>
@endforeach
@endsection