@extends('user::layouts.master')
@section('title', 'Admin | Edit Student Fee')

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
                <h1>Edit Student Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.fee.index', $studentIntakeCourseFee->student_id) }}">Fees</a></li>
                    <li class="breadcrumb-item active">Edit Student Fee</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="editstudentfee" action="{{ route('admin.student.intake.course.fee.update', $studentIntakeCourseFee->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit Fee</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $studentIntakeCourseFee->name }}" disabled>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enrollment_fee">Enrollment Fee</label> <span class="required">*</span>
                                        <input type="number" step="0.01" name="enrollment_fee" class="form-control" id="enrollment_fee" placeholder="Enter Enrollment Fee" value="{{ $studentIntakeCourseFee->enrollment_fee }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="icheck-info d-inline">
                                            <input type="checkbox" class="checkbox" id="enrollment_fee_wavier" name="enrollment_fee_wavier" value="1" @if ($studentIntakeCourseFee->enrollment_fee_wavier == 1) checked @endif disabled>
                                            <label for="enrollment_fee_wavier" class="check enrollment_fee_wavier">Enrollment Fee Wavier</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="material_fee">Material Fee</label> <span class="required">*</span>
                                        <input type="number" step="0.01" name="material_fee" class="form-control" id="material_fee" placeholder="Enter Material Fee" value="{{ $studentIntakeCourseFee->material_fee }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="icheck-info d-inline">
                                            <input type="checkbox" class="checkbox" id="material_fee_wavier" name="material_fee_wavier" value="1" @if ($studentIntakeCourseFee->material_fee_wavier == 1) checked @endif disabled>
                                            <label for="material_fee_wavier" class="check material_fee_wavier">Material Fee Wavier</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="initial_fee">Initial Fee</label> <span class="required">*</span>
                                        <input type="number" step="0.01" name="initial_fee" class="form-control" id="fee" placeholder="Enter Initial Fee" value="{{ $studentIntakeCourseFee->initial_fee }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fee">Fee</label> <span class="required">*</span>
                                        <input type="number" step="0.01" name="fee" class="form-control" id="fee" placeholder="Enter Fee" value="{{ $studentIntakeCourseFee->fee }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="due_date">Due Date</label> <span class="required">*</span>
                                        <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $studentIntakeCourseFee->due_date }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="type">Type</label> <span class="required">*</span>
                                        <select name="type" class="form-control" id="type" disabled>
                                            <option value="" selected disabled>-- Select Type --</option>
                                            <option @if($studentIntakeCourseFee->type == 'Onshore')selected @endif value="Onshore">Onshore</option>
                                            <option @if($studentIntakeCourseFee->type == 'Offshore')selected @endif value="Offshore">Offshore</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($studentIntakeCourseFee->status == '1')selected @endif value="1">Active</option>
                                    <option @if($studentIntakeCourseFee->status == '0')selected @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
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
        $('#editstudentfee').validate({
            rules: {
                due_date: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                due_date: "Please enter due_date",
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