@extends('user::layouts.master')
@section('title', 'Admin | Add Classroom')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Classroom</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.classroom.index') }}">Classrooms</a></li>
                    <li class="breadcrumb-item active">Create</li>
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
                        <h3 class="card-title">Create <small>Classroom</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addclassroom" action="{{ route('admin.classroom.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="trainer_id">Teacher</label> <span class="required">*</span>
                                <select id="trainer_id" name="trainer_id" class="form-control">
                                    <option value="" selected disabled>-- Select Teacher --</option>
                                    @foreach($trainers as $key => $value)
                                    <option value="{{ $value->id }}">{{ userName('Trainer', $value->id) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="student_id">Choose Students</label> <span class="required">*</span>
                                @foreach($intakes as $key => $intake)
                                <div class="row">
                                    <div class="col-12" id="accordion">
                                        <div class="card card-primary card-outline">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapseIntake{{$key}}">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        {{ $intake->name }} ({{ $intake->starting_date }})
                                                    </h4>
                                                </div>
                                            </a>
                                            <div id="collapseIntake{{$key}}" class="collapseIntake show" data-parent="#accordion">
                                                <div class="card-body">
                                                    @foreach($intake->course as $num => $course)
                                                    <div class="row">
                                                        <div class="col-12" id="childaccordion">
                                                            <div class="card card-primary card-outline">
                                                                <a class="d-block w-100" data-toggle="collapse" href="#collapseCourse{{$num}}">
                                                                    <div class="card-header">
                                                                        <h4 class="card-title w-100">
                                                                            {{ $course->course->course_name }}
                                                                        </h4>
                                                                    </div>
                                                                </a>
                                                                <div id="collapseCourse{{$num}}" class="collapseCourse show" data-parent="#childaccordion">
                                                                    <div class="card-body">
                                                                        @if ($course->intakeUnit->where('status', 1)->count() > 0)
                                                                        <div class="row">
                                                                            <div class="icheck-primary d-inline">
                                                                                <input type="checkbox" class="form-controll intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}" id="intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}">
                                                                                <label for="intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}" class="check view">Select All Intake Unit</label>
                                                                            </div>
                                                                        </div>
                                                                        @foreach($course->intakeUnit->where('status', 1) as $key => $unit)
                                                                        <div class="row">
                                                                            <div class="icheck-primary d-inline">
                                                                                <input type="checkbox" class="intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}checkbox" id="intake{{ $intake->id }}intakecourse{{ $course->course_id }}unit{{ $unit->id }}" name="intake_unit_id[]" value="{{ $unit->id }}">
                                                                                <label for="intake{{ $intake->id }}intakecourse{{ $course->course_id }}unit{{ $unit->id }}" class="check view">{{ $unit->unit->name }}</label>
                                                                            </div>
                                                                        </div>
                                                                        @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div id="collapseCourse{{$num}}" class="collapseCourse show" data-parent="#childaccordion">
                                                                    <div class="card-body">
                                                                        @if ($course->studentIntake->where('status', 1)->count() > 0)
                                                                        <div class="row">
                                                                            <div class="icheck-primary d-inline">
                                                                                <input type="checkbox" class="form-controll intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}" id="intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}">
                                                                                <label for="intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}" class="check view">Select All Student</label>
                                                                            </div>
                                                                        </div>
                                                                        @foreach($course->studentIntake->where('status', 1) as $index => $value)
                                                                        <div class="row">
                                                                            <div class="icheck-primary d-inline">
                                                                                <input type="checkbox" class="intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}checkbox" id="intake{{ $intake->id }}course{{ $course->course_id }}student{{ $value->student_id }}" name="student_id[]" value="{{ $value->student_id }}">
                                                                                <label for="intake{{ $intake->id }}course{{ $course->course_id }}student{{ $value->student_id }}" class="check view">{{ userName('Student', $value->student_id) }}</label>
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
        $('#addclassroom').validate({
            rules: {
                name: {
                    required: true,
                },
                trainer_id: {
                    required: true,
                },
                "student_id[]": {
                    required: true,
                }
            },
            messages: {
                name: "Please enter name",
                trainer_id: "Please select trainer",
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
@foreach($intakes as $key => $intake)
@foreach($intake->course as $num => $course)
<script>
    $('.intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}').on('change', function() {
        $('.intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}checkbox').prop('checked', $(this).prop("checked"));
    });
    //deselect "checked all", if one of the listed checkbox category is unchecked amd select "checked all" if all of the listed checkbox category is checked
    $('.checkbox').change(function() { //".checkbox" change 
        if ($('.intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}checkbox:checked').length == $('.intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}checkbox').length) {
            $('.intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}').prop('checked', true);
        } else {
            $('.intake{{ $intake->id }}course{{ $course->course_id }}{{ $num }}').prop('checked', false);
        }
    });

    $('.intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}').on('change', function() {
        $('.intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}checkbox').prop('checked', $(this).prop("checked"));
    });
    //deselect "checked all", if one of the listed checkbox category is unchecked amd select "checked all" if all of the listed checkbox category is checked
    $('.checkbox').change(function() { //".checkbox" change 
        if ($('.intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}checkbox:checked').length == $('.intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}checkbox').length) {
            $('.intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}').prop('checked', true);
        } else {
            $('.intake{{ $intake->id }}intakecourse{{ $course->course_id }}{{ $num }}').prop('checked', false);
        }
    });
</script>
@endforeach
@endforeach
@endsection