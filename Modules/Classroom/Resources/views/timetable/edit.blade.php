@extends('user::layouts.master')
@section('title', 'Admin | Edit Classroom Time Table')

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
                <h1>{{ $time->classroom->name }}'s Time Table</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.classroom.index') }}">Classrooms</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.classroom.time.index', $time->classroom_id) }}">Time Table</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $time->classroom->name }}'s Time Table</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addintakecoursetime" action="{{ route('admin.classroom.time.update', $time->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="day">Day</label> <span class="required">*</span>
                                <select name="day" class="form-control" id="day">
                                    <option value="" selected disabled>-- Select Day --</option>
                                    <option @if($time->day == 'Monday')selected @endif value="Monday">Monday</option>
                                    <option @if($time->day == 'Tuesday')selected @endif value="Tuesday">Tuesday</option>
                                    <option @if($time->day == 'Wednesday')selected @endif value="Wednesday">Wednesday</option>
                                    <option @if($time->day == 'Thursday')selected @endif value="Thursday">Thursday</option>
                                    <option @if($time->day == 'Friday')selected @endif value="Friday">Friday</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="from">From</label> <span class="required">*</span>
                                <input type="time" name="from" class="form-control" id="from" placeholder="Enter From Time" value="{{ $time->from }}">
                            </div>
                            <div class="form-group">
                                <label for="to">To</label> <span class="required">*</span>
                                <input type="time" name="to" class="form-control" id="to" placeholder="Enter To Time" value="{{ $time->to }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($time->status == '1')selected @endif value="1">Active</option>
                                    <option @if($time->status == '0')selected @endif value="0">Inactive</option>
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
        // $.validator.setDefaults({
        //     submitHandler: function() {
        //         alert("Form successful submitted!");
        //     }
        // });
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
