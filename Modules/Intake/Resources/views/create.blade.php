@extends('user::layouts.master')
@section('title', 'Admin | Add Intake')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Intake</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item active">Add Intake</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addintake" action="{{ route('admin.intake.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Intake</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="reference_name">Reference Name</label> <span class="required">*</span>
                                <input type="text" name="reference_name" class="form-control" id="reference_name" placeholder="Enter Reference Name">
                            </div>
                            <div class="form-group">
                                <label for="orientation_date">Orientation Date</label> <span class="required">*</span>
                                <input type="date" name="orientation_date" class="form-control" id="orientation_date" placeholder="Enter Orientation Date">
                            </div>
                            <div class="form-group">
                                <label for="starting_date">Starting Date</label> <span class="required">*</span>
                                <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date">
                            </div>
                            <div class="form-group">
                                <label for="allow_submission_after_due_date">Allow Assignment Submission After Due Date</label>
                                <select name="allow_submission_after_due_date" class="form-control" id="allow_submission_after_due_date">
                                    <option value="" selected disabled>-- Select Allow Assignment Submission After Due Date --</option>
                                    <option value="on">On</option>
                                    <option value="off">Off</option>
                                </select>
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
        $('#addintake').validate({
            rules: {
                name: {
                    required: true,
                },
                reference_name: {
                    required: true,
                },
                orientation_date: {
                    required: true,
                },
                starting_date: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter name",
                reference_name: "Please enter reference name",
                orientation_date: "Please select orientation date",
                staring_date: "Please enter starting date",
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