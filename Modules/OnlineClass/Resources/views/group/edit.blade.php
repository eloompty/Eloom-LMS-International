@extends('user::layouts.master')
@section('title', 'Admin | Edit Student Group')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Student Group</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.onlineclass.group.index') }}">Group Online Class</a></li>
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
                        <h3 class="card-title">Edit <small>Group Student Group</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addonlinegroupclass" action="{{ route('admin.onlineclass.group.update', $onlineGroup->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $onlineGroup->name }}">
                            </div>
                            <div class="form-group">
                                <label for="trainer_id">Teacher</label> <span class="required">*</span>
                                <select id="trainer_id" name="trainer_id" class="form-control">
                                    <option value="" selected disabled>-- Select Teacher --</option>
                                    @foreach($trainers as $key => $value)
                                    <option @if($trainerGroup->trainer_id == $value->id)selected @endif value="{{ $value->id }}">{{ userName('Trainer', $value->id) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($onlineGroup->status == '1')selected @endif value="1">Active</option>
                                    <option @if($onlineGroup->status == '0')selected @endif value="0">Inactive</option>
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
                                                                        @foreach($course->studentIntake as $index => $value)
                                                                        <div class="row">
                                                                            <div class="icheck-primary d-inline">
                                                                                <input type="checkbox" class="intake{{ $intake->id }}course{{ $course->course_id }}checkbox" id="intake{{ $intake->id }}course{{ $course->course_id }}student{{ $value->student_id }}" name="student_id[]" @if($value->intake_course_student_id != 0)checked @endif  value="{{ $value->student_id }}">
                                                                                <label for="intake{{ $intake->id }}course{{ $course->course_id }}student{{ $value->student_id }}" class="check view">{{ userName('Student', $value->student_id) }}</label>
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
        $('#addonlinegroupclass').validate({
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
@endsection