@extends('user::layouts.master')
@section('title', 'Admin | Edit Intake Course Time')

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
                <h1>{{ $intakeCourseTime->intakeCourse->intake->name }} ({{ $intakeCourseTime->intakeCourse->course->course_name }})'s Time Table</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeCourseTime->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.time.index', $intakeCourseTime->intake_course_id) }}">Time Table</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $intakeCourseTime->intakeCourse->intake->name }} ({{ $intakeCourseTime->intakeCourse->course->course_name }})'s Time Table</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addintakecoursetime" action="{{ route('admin.intake.course.time.update', $intakeCourseTime->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="day">Day</label> <span class="required">*</span>
                                <select name="day" class="form-control" id="day">
                                    <option value="" selected disabled>-- Select Day --</option>
                                    <option @if($intakeCourseTime->day == 'Monday')selected @endif value="Monday">Monday</option>
                                    <option @if($intakeCourseTime->day == 'Tuesday')selected @endif value="Tuesday">Tuesday</option>
                                    <option @if($intakeCourseTime->day == 'Wednesday')selected @endif value="Wednesday">Wednesday</option>
                                    <option @if($intakeCourseTime->day == 'Thursday')selected @endif value="Thursday">Thursday</option>
                                    <option @if($intakeCourseTime->day == 'Friday')selected @endif value="Friday">Friday</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="from">From</label> <span class="required">*</span>
                                <input type="time" name="from" class="form-control" id="from" placeholder="Enter From Time" value="{{ $intakeCourseTime->from }}">
                            </div>
                            <div class="form-group">
                                <label for="to">To</label> <span class="required">*</span>
                                <input type="time" name="to" class="form-control" id="to" placeholder="Enter To Time" value="{{ $intakeCourseTime->to }}">
                            </div>
                            <div class="form-group">
                                <label for="classroom">Classroom</label>
                                <input type="text" name="classroom" class="form-control" id="classroom" placeholder="Enter Classroom" value="{{ $intakeCourseTime->classroom }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($intakeCourseTime->status == '1')selected @endif value="1">Active</option>
                                    <option @if($intakeCourseTime->status == '0')selected @endif value="0">Inactive</option>
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
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit <small>Unit Time Table</small></h3>
                    </div>
                    <div class="card-body">
                        @if(count($intakeUnitTimes) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Day</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Classroom</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($intakeUnitTimes as $index => $value)
                                <tr>
                                    <form id="edititnakeunit" action="{{ route('admin.intake.unit.time.update', $value->id) }}" method="POST">
                                        @csrf
                                        <td>{{ $value->intakeUnit->unit->name }}</td>
                                        <td>
                                            <select name="day" class="form-control" id="day">
                                                <option value="" selected disabled>-- Select Day --</option>
                                                <option @if($value->day == 'Monday')selected @endif value="Monday">Monday</option>
                                                <option @if($value->day == 'Tuesday')selected @endif value="Tuesday">Tuesday</option>
                                                <option @if($value->day == 'Wednesday')selected @endif value="Wednesday">Wednesday</option>
                                                <option @if($value->day == 'Thursday')selected @endif value="Thursday">Thursday</option>
                                                <option @if($value->day == 'Friday')selected @endif value="Friday">Friday</option>
                                            </select>
                                        <td><input type="time" name="from" class="form-control" id="from" placeholder="Enter From" value="{{ $value->from }}" required></td>
                                        <td><input type="time" name="to" class="form-control" id="to" placeholder="Enter Day" value="{{ $value->to }}" required></td>
                                        <td><input type="text" name="classroom" class="form-control" id="classroom" placeholder="Enter Classroom" value="{{ $value->classroom }}"></td>
                                        <td>
                                            <select name="status" class="form-control" id="status">
                                                <option value="" selected disabled>-- Select Status --</option>
                                                <option @if($value->status == '1')selected @endif value="1">Active</option>
                                                <option @if($value->status == '0')selected @endif value="0">Inactive</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Update</button>
                                            @if ($value->status != 2)
                                            <a href="{{ route('admin.intake.unit.time.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Unit</th>
                                    <th>Day</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Classroom</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <h3>No Data Found</h3>
                        @endif
                    </div>
                    <!-- /.card -->
                </div>
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
        $('#addintakecoursetime').validate({
            rules: {
                day: {
                    required: true,
                },
                from: {
                    required: true,
                },
                to: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                day: "Please choose one day",
                from: "Please enter from time",
                to: "Please enter to time",
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
</script>
@endsection
