@extends('user::layouts.master')
@section('title', 'Admin | Add Classroom Time Table')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $classroom->name }}'s Time Table</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.classroom.index') }}">Classroom</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.classroom.time.index', $classroom->id) }}">Time Tables</a></li>
                    <li class="breadcrumb-item active">Add</li>
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
                        <h3 class="card-title">Add <small>{{ $classroom->name }}'s Time Table</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="timetable" action="{{ route('admin.classroom.time.store', $classroom->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="from">From</label> <span class="required">*</span>
                                    <input type="time" name="from" class="form-control" id="from" placeholder="Enter From Time">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="to">To</label> <span class="required">*</span>
                                    <input type="time" name="to" class="form-control" id="to" placeholder="Enter To Time">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="monday" name="days[]" value="Monday">
                                        <label for="monday" class="check">Monday</label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="tuesday" name="days[]" value="Tuesday">
                                        <label for="tuesday" class="check">Tuesday</label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="wednesday" name="days[]" value="Wednesday">
                                        <label for="wednesday" class="check">Wednesday</label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="thursday" name="days[]" value="Thursday">
                                        <label for="thursday" class="check">Thursday</label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="friday" name="days[]" value="Friday">
                                        <label for="friday" class="check">Friday</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
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
        $('#timetable').validate({
            rules: {
                "days[]": {
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
