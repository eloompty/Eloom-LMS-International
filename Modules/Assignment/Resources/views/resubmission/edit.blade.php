@extends('user::layouts.master')
@section('title', 'Admin | Edit Assignment Resubmission Request')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Assignment Resubmission Request</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Assignment Resubmission Request</li>
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
                        <h3 class="card-title">Edit <small>{{ $resubmission->assignment->name }} Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editresubmission" action="{{ route('admin.resubmission.update', $resubmission->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="student_id">Student Name</label> <span class="required">*</span>
                                <input type="text" name="student_id" class="form-control" id="student_id" value="{{ userName('Student', $resubmission->student_id) }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="path">Assignment</label> <span class="required">*</span>
                            </div>
                            <div class="form-group">
                                @if ($resubmission->assignment->type == 'file')
                                <img src="{{ asset($resubmission->assignment->path) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                @elseif ($resubmission->assignment->type == 'mcq')
                                <a href="{{ route('admin.assignment.mcq', $resubmission->assignment->id) }}"><img src="{{ asset('files/mcq.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
                                @else
                                <a href="{{ route('admin.assignment.question', $resubmission->assignment->id) }}"><img src="{{ asset('files/qa.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="due_date">New Assignment Due Date</label>
                                <input type="date" name="due_date" class="form-control" id="due_date" value="{{ $resubmission->due_date }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($resubmission->status == '1')selected @endif value="1">Approve</option>
                                    <option @if($resubmission->status == '2')selected @endif value="2">Reject</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label> <span class="required">*</span>
                                <textarea class="form-control" name="remarks">{{ $resubmission->remarks }}</textarea>
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
        $('#editresubmission').validate({
            rules: {
                status: {
                    required: true,
                },
                remarks: {
                    required: true,
                },
            },
            messages: {
                status: "Please choose one status",
                remarks: "Please enter remarks",
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
